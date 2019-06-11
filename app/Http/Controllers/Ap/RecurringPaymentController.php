<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\RecurringPayment;
use App\Models\Ap\RecurringPaymentDates;
use App\Models\Requisition\Supplier;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RecurringPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        if ($this->isRequestTypeDatatable(request())) {
            $status_filter = request()->status_filter;
            $recurringPayments = RecurringPayment::with('voucher')->get();

            if ($status_filter) {
                $recurringPayments = $recurringPayments->where('disabled', $status_filter);
            }

            return DataTables::of($recurringPayments)
                ->editColumn('supplier_name', function (RecurringPayment $recurringPayment) {
                    return $recurringPayment->supplier->name;
                })
                ->editColumn('supplier_info', function (RecurringPayment $recurringPayment) {
                    $s_contact = '<div class="mb-3">Amount: ' . $recurringPayment->amount . '</div>';

                    foreach ($recurringPayment->supplier->supplierContacts as $value) {
                        $s_contact .= '<div class="mb-3">';
                        $s_contact .= '<div>Contact Person: ' . $value['contact_person'] . '</div>';
                        $s_contact .= '<div>Phone Number 1: ' . $value['phone_number1'] . '</div>';
                        $s_contact .= $value['phone_number2'] ? '<div>Phone Number 2: ' . $value['phone_number2'] . '</div>' : '';
                        $s_contact .= $value['phone_number3'] ? '<div>Phone Number 3: ' . $value['phone_number3'] . '</div>' : '';
                        $s_contact .= $value['fax_number'] ? '<div>Fax Number: ' . $value['fax_number'] . '</div>' : '';
                        $s_contact .= '</div>';
                    }
                    $s_contact .= '<div class="mt-3">Bank Details: ' . $recurringPayment->bankAccount->bank->bank_name . ' (' . $recurringPayment->bankAccount->acct_no . ')' . '</div>';

                    return $s_contact;
                })
                ->editColumn('duration', function (RecurringPayment $recurringPayment) {
                    if ($recurringPayment->is_duration == 'N') {
                        return '<div>Continuous</div>';
                    } else {
                        return '<div>From: ' . date('F d, Y', strtotime($recurringPayment->duration_from)) . '</div>' .
                            '<div>To: ' . date('F d, Y', strtotime($recurringPayment->duration_to)) . '</div>';
                    }
                })
                ->editColumn('frequency', function (RecurringPayment $recurringPayment) {
                    $s_frequency = '<fieldset class="border p-2"><legend class="w-auto" style="font-size: 12pt"><span class="text-primary font-weight-bold">' . $this->get_frequency('frequency', $recurringPayment->frequency) . '</span></legend>';

                    foreach ($recurringPayment->recurringPaymentDates as $value) {
                        $month = $value['month'];
                        $day = $value['day'];
                        $weekday = $value['weekday'];

                        if ($recurringPayment->frequency == 'W') {
                            $s_frequency .= '<div class="text-center font-weight-bold">Every ' . $this->get_frequency('week', $weekday) . '</div><br>';
                        } else if ($recurringPayment->frequency == 'M') {
                            $s_frequency .= '<div class="text-center font-weight-bold">' . $this->ConvertToOrdinal($day) . ' of the Month</div><br>';
                        } else if ($recurringPayment->frequency == 'Q') {
                            $frequency_type_desc = $this->get_frequency('quarter', $value['frequency_type']);
                            $s_frequency .= '<div class="text-center">' . $frequency_type_desc . ':</div><div class="text-center font-weight-bold">' . $this->ConvertToOrdinal($day) . ' of ' . $this->get_frequency('month', $month) . '</div><br/>';
                        } else if ($recurringPayment->frequency == 'S') {
                            $frequency_type_desc = $this->get_frequency('semester', $value['frequency_type']);
                            $s_frequency .= '<div class="text-center">' . $frequency_type_desc . ':<br/></div><div class="text-center font-weight-bold">' . $this->ConvertToOrdinal($day) . ' of ' . $this->get_frequency('month', $month) . '</div><br/>';
                        } else if ($recurringPayment->frequency == 'A') {
                            $s_frequency .= '<div class="text-center font-weight-bold">' . $this->ConvertToOrdinal($day) . ' of ' . $this->get_frequency('month', $month) . '</div><br>';
                        }
                    }

                    $s_frequency .= "<br></fieldset>";
                    return $s_frequency;
                })
                ->editColumn('status', function (RecurringPayment $recurringPayment) {
                    if ($recurringPayment->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $recurringPayment->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPayment->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (RecurringPayment $recurringPayment) {
                    return '<div>' . $recurringPayment->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPayment->created_at->diffForHumans() . '</div><br>' .
                        ($recurringPayment->last_modified ? '<div>' . $recurringPayment->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPayment->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (RecurringPayment $recurringPayment) {
                    $actions = '';
                    if (!$recurringPayment->voucher) {
                        $actions .= '<button id="btn-delete" data-id="' . $recurringPayment->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>';
                    }
                    $actions .= '<button id="btn-recurring-payment-distribution" data-id="' . $recurringPayment->id . '" type="button" class="btn btn-link">Manage Distribution</button><br>
                            <button id="btn-update-status" data-id="' . $recurringPayment->id . '" type="button" class="btn btn-link">' . ($recurringPayment->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                    return $actions;
                })
                ->rawColumns(['supplier_name', 'supplier_info', 'duration', 'frequency', 'status', 'logs', 'actions'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'supplier_id' => 'required',
            'bank_account_id' => 'required',
            'remarks' => 'required',
            'amount' => 'required',
            'frequency' => 'required',
        ];

        if ($request['is_duration'] == 'Y') {
            $validate = array_merge($validate, [
                'duration_from' => 'required',
                'duration_to' => 'required',
            ]);
        }

        $custom = [];
        $frequency = $request['frequency'];
        $frequency_type = $request['frequency_type'];
        if ($frequency == 'W') {
            $validate = array_merge($validate, ['frequency_type.W.weekday' => 'required']);
            $custom = ['frequency_type.W.weekday.required' => 'The Day of the Week is required.'];
        } else if ($frequency == 'M') {
            $validate = array_merge($validate, ['frequency_type.M.day' => 'required']);
            $custom = ['frequency_type.M.day.required' => 'The Day of the Month field is required.'];
        } else if ($frequency == 'Q') {
            $validate = array_merge($validate, [
                'frequency_type.Q.Q1' => 'required',
                'frequency_type.Q.Q2' => 'required',
                'frequency_type.Q.Q3' => 'required',
                'frequency_type.Q.Q4' => 'required'
            ]);
            $custom = [
                'frequency_type.Q.Q1.required' => 'The 1st Quarter field is required.',
                'frequency_type.Q.Q2.required' => 'The 2nd Quarter field is required.',
                'frequency_type.Q.Q3.required' => 'The 3rd Quarter field is required.',
                'frequency_type.Q.Q4.required' => 'The 4th Quarter field is required.'
            ];
        } else if ($frequency == 'S') {
            $validate = array_merge($validate, [
                'frequency_type.S.SEM1' => 'required',
                'frequency_type.S.SEM2' => 'required',
                'frequency_type.S.SUMMER' => 'required'
            ]);
            $custom = [
                'frequency_type.S.SEM1.required' => 'The 1st Semester field is required.',
                'frequency_type.S.SEM2.required' => 'The 2nd Semester field is required.',
                'frequency_type.S.SUMMER.required' => 'The Summer field is required.'
            ];
        } else if ($frequency == 'A') {
            $validate = array_merge($validate, [
                'frequency_type.A.month' => 'required',
                'frequency_type.A.day' => 'required'
            ]);
            $custom = [
                'frequency_type.A.month.required' => 'The Month field is required.',
                'frequency_type.A.day.required' => 'The Day of the Month field is required.',
            ];
        }
        $request->validate($validate, $custom);

        $request['logs'] = 'Created by: Test';
        $data = $request->except('frequency_type');

        $temp = array();
        $dates_arr = array();
        $result = RecurringPayment::create($data)->id;
        if ($result) {
            $created_at = now();
            $updated_at = now();

            foreach ($frequency_type[$frequency] as $key => $value) {
                if ($frequency == 'Q' or $frequency == 'S') {
                    $temp[$key] = array(
                        'day' => date('d', strtotime($value)),
                        'month' => date('m', strtotime($value))
                    );
                } else {
                    $temp[$frequency][$key] = $value;
                }
            }

            foreach ($temp as $key => $value) {
                array_push($dates_arr, array(
                    'recurring_payment_id' => $result,
                    'month' => isset($value['month']) ? $value['month'] : 0,
                    'day' => isset($value['day']) ? $value['day'] : 0,
                    'weekday' => isset($value['weekday']) ? $value['weekday'] : 0,
                    'frequency_type' => $key,
                    'logs' => 'Created by: Test',
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ));
            }

            $dates_result = RecurringPaymentDates::insert($dates_arr);
            if ($dates_result) {
                /**
                 * check for failure of event tag when insert try to rollback (DB rollback)
                 * try to check if there is other way to insert multiple record
                 */
                return response()->json(['success' => true, 'message' => 'The record was added successfully!']);
            } else {
                RecurringPayment::where(['id' => $result])->delete();
                RecurringPaymentDates::where(['recurring_payment_id' => $result])->delete();

                return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RecurringPayment $recurringPayment
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(RecurringPayment $recurringPayment)
    {
        $result = $recurringPayment->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param RecurringPayment $recurringPayment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(RecurringPayment $recurringPayment)
    {
        $status = $recurringPayment->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $recurringPayment->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $recurringPayment->update([
                'disabled' => $status,
                'last_modified' => 'Last modified by: Test',
            ]);
        }

        if ($result) {
            $status = $status == 'N' ? 'enabled' : 'disabled';
            return response()->json(['success' => true, 'message' => 'The record was ' . $status . ' successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Get all suppliers from storage
     */
    public function get_supplier()
    {
        $suppliers = Supplier::where('disabled', '=', 'N')
            ->orderBy('name')
            ->get([
                'id as value',
                'name as text'
            ]);

        return $suppliers;
    }

    /**
     * Get frequency
     *
     * @param null $frequency_type
     * @param null $value
     * @return array|mixed
     */
    public static function get_frequency($frequency_type = null, $value = null)
    {
        $days = array();
        for ($count_days = 1; $count_days < 32; $count_days++) {
            $days[$count_days] = $count_days;
        }
        $frequency = array(
            'frequency' => array(
                'W' => 'Weekly',
                'M' => 'Monthly',
                'Q' => 'Quarterly',
                'S' => 'Semestral',
                'A' => 'Annual'
            ),
            'week' => array(
                '1' => 'Monday',
                '2' => 'Tuesday',
                '3' => 'Wednesday',
                '4' => 'Thursday',
                '5' => 'Friday',
                '6' => 'Saturday',
                '7' => 'Sunday'
            ),
            'quarter' => array(
                'Q1' => '1st Quarter',
                'Q2' => '2nd Quarter',
                'Q3' => '3rd Quarter',
                'Q4' => '4th Quarter'
            ),
            'semester' => array(
                'SEM1' => '1st Semester',
                'SEM2' => '2nd Semester',
                'SUMMER' => 'Summer'
            ),
            'month' => array(
                '1' => 'January',
                '2' => 'February',
                '3' => 'March',
                '4' => 'April',
                '5' => 'May',
                '6' => 'June',
                '7' => 'July',
                '8' => 'August',
                '9' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            ),
            'days' => $days
        );
        if ($value) {
            return $frequency[$frequency_type][$value];
        } else {
            return $frequency;
        }
    }

    /**
     * Convert number to its ordinal form
     *
     * @param $number
     * @return string
     */
    public static function ConvertToOrdinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        } else {
            return $number . $ends[$number % 10];
        }
    }

    public function generatePDFReport()
    {
        $recurringPayments = RecurringPayment::all();

        try {
            $pdf = SnappyPdf::loadView('reports.ap.recurring_payment', compact('recurringPayments'));
            return $pdf->stream('report_req_recurring_payment_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

}
