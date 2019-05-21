<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\Bank;
use App\Models\Ap\Check;
use App\Models\Ap\RecurringPayment;
use App\Models\Requisition\Supplier;
use App\Models\Requisition\SupplierContact;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use stdClass;
use Yajra\DataTables\Facades\DataTables;

class MonthlyPaymentController extends Controller
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
            $date_filter = request()->date_filter;
            $monthlyPayments = $this->monthlyPayments($date_filter);

            return DataTables::of($monthlyPayments)
                ->editColumn('supplier_info', function ($monthlyPayment) {
                    return $this->supplierInformation($monthlyPayment);
                })
                ->editColumn('due_date', function ($monthlyPayment) {
                    return date('F d, Y', strtotime($monthlyPayment->date)) . '<br>' . date('l', strtotime($monthlyPayment->date));
                })
                ->editColumn('remaining_days', function ($monthlyPayment) {
                    return $monthlyPayment->remaining_days;
                })
                ->editColumn('actions', function ($monthlyPayment) {
                    return '<button id="btn-create-check-voucher" data-id="' . $monthlyPayment->recurring_payment_id . '&' . $monthlyPayment->date . '" type="button" class="btn btn-link">Create Check Voucher</button>';
                })
                ->rawColumns(['supplier_name', 'supplier_info', 'due_date', 'remaining_days', 'actions'])
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
        var_dump($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recurring_payment_id = explode('&', $id)[0];
        $recurring_payment_date = explode('&', $id)[1];

        $recurringPayment = RecurringPayment::where('id', $recurring_payment_id)
            ->with('supplier')
            ->with('recurringPaymentDistributions')
            ->with(['voucher' => function ($query) use ($recurring_payment_date) {
                $query->where('date', $recurring_payment_date);
                $query->with('voucherDistributions');
            }])->first();

        return $recurringPayment;
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get frequency
     *
     * @param null $frequency_type
     * @param null $value
     * @return array|mixed
     */
    public function get_frequency($frequency_type = null, $value = null)
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
     * Retrieve list of monthly payments from resource storage
     *
     * @param $date_filter
     * @return array
     * @throws \Exception
     */
    public function monthlyPayments($date_filter)
    {
        $date_start = date('Y-m-01', strtotime($date_filter));
        $date_end = date('Y-m-t', strtotime($date_filter));
        $date_end_object = new DateTime($date_end);
        $date_end_object->modify('+1 days');
        $month = date('n', strtotime($date_filter));

        $date = new DatePeriod(
            new DateTime($date_start), new DateInterval('P1D'), $date_end_object);

        $monthlyPayments = RecurringPayment::with(['recurringPaymentDates' => function ($child) use ($month, $date_start, $date_end) {
            $child->where(function ($child_sub1) use ($month) {
                $child_sub1->where('month', 0)
                    ->orWhere('month', $month);
            });
        }])->where([
            ['duration_from', '=', null],
            ['duration_to', '=', null]
        ])->orWhere(function ($sub1) use ($date_start, $date_end) {
            $sub1->where([
                ['duration_from', '>=', $date_start],
                ['duration_to', '>=', $date_end]
            ])->orWhere([
                ['duration_from', '<=', $date_start],
                ['duration_to', '<=', $date_end]
            ])->orWhere([
                ['duration_from', '>=', $date_start],
                ['duration_to', '<=', $date_end]
            ])->orWhere([
                ['duration_from', '<=', $date_start],
                ['duration_to', '>=', $date_end]
            ]);
        })->where('disabled', 'N')
            ->get();

        $monthlyPaymentsList = array();
        foreach ($date as $date_row) {
            $day_of_week = $date_row->format("N");
            $month_only = $date_row->format("m");
            $date_only = $date_row->format("d");
            $date = $date_row->format("Y-m-d");

            foreach ($monthlyPayments as $monthlyPayment) {
                foreach ($monthlyPayment->recurringPaymentDates as $recurringPaymentDate) {
                    $object = new stdClass();
                    $object->date = $date;
                    $object->recurring_payment_id = $monthlyPayment->id;
                    $object->supplier_id = $monthlyPayment->supplier_id;
                    $object->document_no = $monthlyPayment->document_no;
                    $object->duration_from = $monthlyPayment->duration_from;
                    $object->duration_to = $monthlyPayment->duration_to;
                    $object->is_duration = $monthlyPayment->is_duration;
                    $object->frequency = $monthlyPayment->frequency;
                    $object->remarks = $monthlyPayment->remarks;
                    $object->amount = $monthlyPayment->amount;
                    $object->month = $recurringPaymentDate->month;
                    $object->day = $recurringPaymentDate->day;
                    $object->weekday = $recurringPaymentDate->weekday;
                    $object->frequency_type = $recurringPaymentDate->frequency_type;
                    $object->remaining_days = ceil((strtotime($date) - time()) / 86400);

                    if ((strtotime($monthlyPayment->duration_from) <= strtotime($date)
                            && strtotime($monthlyPayment->duration_to) >= strtotime($date))
                        || ($monthlyPayment->duration_from == null
                            && $monthlyPayment->duration_to == null)) {

                        if ($monthlyPayment->frequency == 'W' && $day_of_week == $recurringPaymentDate->weekday) {
                            $monthlyPaymentsList[] = $object;

                        } else if (($monthlyPayment->frequency == 'Q'
                                || $monthlyPayment->frequency == 'S'
                                || $monthlyPayment->frequency == 'A')
                            && ($recurringPaymentDate->month == $month_only
                                && $recurringPaymentDate->day == $date_only)) {
                            $monthlyPaymentsList[] = $object;

                        } else if ($monthlyPayment->frequency == 'M' && $recurringPaymentDate->day == $date_only) {
                            $monthlyPaymentsList[] = $object;
                        }
                    }
                }
            }
        }

        return $monthlyPaymentsList;
    }

    /**
     * Display supplier information
     *
     * @param $monthlyPayment
     * @return string
     */
    public static function supplierInformation($monthlyPayment)
    {
        $s_info = '<div class="mb-3"> ' . Supplier::find($monthlyPayment->supplier_id)->name . '</div>';

        foreach (SupplierContact::where('supplier_id', $monthlyPayment->supplier_id)->get() as $value) {
            $s_info .= '<div class="mb-3">';
            $s_info .= '<div>Contact Person: ' . $value->contact_person . '</div>';
            $s_info .= '<div>Phone Number 1: ' . $value->phone_number1 . '</div>';
            $s_info .= $value->phone_number2 ? '<div>Phone Number 2: ' . $value->phone_number2 . '</div>' : '';
            $s_info .= $value->phone_number3 ? '<div>Phone Number 3: ' . $value->phone_number3 . '</div>' : '';
            $s_info .= $value->fax_number ? '<div>Fax Number: ' . $value->fax_number . '</div>' : '';
            $s_info .= '</div>';
        }

        $s_info .= '<div>Frequency: ' . (new self)->get_frequency('frequency', $monthlyPayment->frequency);
        if ($monthlyPayment->frequency == 'Q') {
            $s_info .= ' (' . (new self)->get_frequency('quarter', $monthlyPayment->frequency_type) . ')</div>';
        } else if ($monthlyPayment->frequency == 'S') {
            $s_info .= ' (' . (new self)->get_frequency('semester', $monthlyPayment->frequency_type) . ')</div>';
        } else {
            $s_info .= '</div>';
        }
        $s_info .= '<div class="mb-3">Amount Due: ' . $monthlyPayment->amount . '</div>';

        $s_info .= '<fieldset class="border p-2"><legend class="w-auto" style="font-size: 12pt"><span class="text-primary font-weight-bold">Duration</span></legend>';
        $s_info .= $monthlyPayment->is_duration == 'Y' ?
            '<div>From: ' . date('F d, Y', strtotime($monthlyPayment->duration_from)) .
            '</div><div>To: ' . date('F d, Y', strtotime($monthlyPayment->duration_to)) . '</div>' :
            '<div>Continuous</div>';
        $s_info .= "<br></fieldset>";
        return $s_info;
    }

    /**
     * Get banks from resource storage
     *
     * @return array
     */
    public function get_banks()
    {
        $banks = Bank::with(['bankAccounts' => function ($child) {
            $child->where('disabled', 'N')
                ->where('acct_type', 'C');
        }])->where('disabled', 'N')
            ->get();

        $bankList = array();
        foreach ($banks as $bank) {
            foreach ($bank->bankAccounts as $bankAccount) {
                $bankList[$bankAccount->id] = $bank->bank_name . ' (' . $bank->bank_prefix . ') - ' . $bankAccount->acct_code;
            }
        }

        return $bankList;
    }

    /**
     * Get checks from resource storage
     *
     * @param $id
     * @return mixed
     */
    public function get_checks($id)
    {
        $checks = Check::where('bank_account_id', $id)
            ->where('voucher_no', null)
            ->where('voided', 'N')
            ->orderByRaw('len(check_no)')
            ->orderBy('check_no')
            ->get([
                'id as value',
                'check_no as text'
            ]);

        return $checks;
    }

    /**
     * Get document types
     *
     * @param null $value
     * @return array|mixed
     */
    public function get_document_type($value = null)
    {
        $documentType = array('I' => 'Invoice');
        if ($value) {
            return $documentType[$value];
        } else {
            return $documentType;
        }
    }

    public function generatePDFReport()
    {
        $monthlyPayments = $this->monthlyPayments(request()->date_filter);

        try {
            $pdf = PDF::loadView('reports.ap.monthly_payment', compact('monthlyPayments'));
            return $pdf->stream('report_req_monthly_payment_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

}
