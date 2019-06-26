<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\Bank;
use App\Models\Ap\Check;
use App\Models\Ap\RecurringPayment;
use App\Models\Ap\Voucher;
use App\Models\Ap\VoucherDistribution;
use App\Models\Requisition\Supplier;
use Barryvdh\Snappy\Facades\SnappyPdf;
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
     * @return array
     * @throws \Exception
     */
    public function index()
    {
        if ($this->isRequestTypeDatatable(request())) {
            $frequency_filter = request()->frequency_filter;
            $status_filter = request()->status_filter;
            $period_from_filter = request()->period_from_filter;
            $period_to_filter = request()->period_to_filter;
            if ($period_from_filter) {
                $period_from_filter = date('Y-m-d', strtotime($period_from_filter));
            } else {
                $period_from_filter = date('Y-m-01');
            }

            if ($period_to_filter) {
                $period_to_filter = date('Y-m-d', strtotime($period_to_filter));
            } else {
                $period_to_filter = date('Y-m-t');
            }
            $monthlyPayments = $this->monthlyPayments($period_from_filter, $period_to_filter, $status_filter, $frequency_filter);

            return DataTables::of($monthlyPayments)
                ->editColumn('supplier_info', function ($monthlyPayment) {
                    return $this->supplierInformation($monthlyPayment);
                })
                ->editColumn('voucher_info', function ($monthlyPayment) {
                    $v_info = '';
                    if ($monthlyPayment->voucher) {
                        $check_info = Check::find($monthlyPayment->voucher->check_id);
                        $v_info .= '<div class="mb-3">Voucher Number: ' . $monthlyPayment->voucher->voucher_no . '</div>';
                        $v_info .= '<div>Bank Account: ' . $check_info->bankAccount->bank->bank_name . ' (' . $check_info->bankAccount->acct_no . ')' . '</div>';
                        $v_info .= '<div>Check Number: ' . $check_info->check_no . '</div>';
                        $v_info .= '<div class="mb-3">Check Date: ' . $monthlyPayment->voucher->check_date . '</div>';
                        $v_info .= '<div>Explanation: ' . $monthlyPayment->voucher->explanation . '</div>';
                    } else {
                        $v_info .= 'N/A';
                    }
                    return $v_info;
                })
                ->editColumn('due_date', function ($monthlyPayment) {
                    $status = '';
                    if ($monthlyPayment->voucher) {
                        if ($monthlyPayment->voucher->status == 'O') {
                            $status .= '<div class="mt-4">Status: <span class="text-primary font-weight-bold">For Review</span></div>';
                        } else if ($monthlyPayment->voucher->status == 'R') {
                            $status .= '<div class="mt-4">Status: <span class="text-purple font-weight-bold">For Recommendation</span></div>';
                        } else if ($monthlyPayment->voucher->status == 'F') {
                            $status .= '<div class="mt-4">Status: <span class="text-warning font-weight-bold">For Approval</span></div>';
                        } else if ($monthlyPayment->voucher->status == 'A') {
                            $status .= '<div class="mt-4">Status: <span class="text-success font-weight-bold">Approved</span></div>';
                        }
                    } else {
                        $status .= '<div class="mt-4">Status: <span class="text-danger font-weight-bold">Not Requested</span></div>';
                    }
                    return date('F d, Y', strtotime($monthlyPayment->date)) . '<br>' . date('l', strtotime($monthlyPayment->date)) . $status;
                })
                ->editColumn('remaining_days', function ($monthlyPayment) {
                    if ($monthlyPayment->voucher && $monthlyPayment->voucher->status == 'A') {
                        $monthlyPayment->remaining_days = 0;
                    }
                    return '<div class="text-center">' . $monthlyPayment->remaining_days . '</div>';
                })
                ->editColumn('actions', function ($monthlyPayment) {
                    $actions = '';
                    $voucher_label = 'Create Check Voucher';
                    if ($monthlyPayment->voucher) {
                        if ($monthlyPayment->voucher->status == 'O') {
                            $voucher_label = 'Review Check Voucher';
                        } else if ($monthlyPayment->voucher->status == 'R') {
                            $voucher_label = 'Recommend Check Voucher';
                        }

                        if ($monthlyPayment->voucher->status != 'F') {
                            $actions .= '<button id="btn-check-voucher" data-id="' . $monthlyPayment->recurring_payment_id . '&' . $monthlyPayment->date . '" type="button" class="btn btn-link">' . $voucher_label . '</button><br>';
//                            $actions .= '<button id="btn-delete-check-voucher" data-id="' . $monthlyPayment->recurring_payment_id . '&' . $monthlyPayment->date . '&' . $monthlyPayment->voucher->check_id . '" type="button" class="btn btn-link">Delete Check Voucher</button><br>';
                        }
                        $actions .= '<button id="btn-print-check-voucher" data-id="' . $monthlyPayment->recurring_payment_id . '&' . $monthlyPayment->date . '" type="button" class="btn btn-link">Print Check Voucher</button>';
                    } else {
                        $actions .= '<button id="btn-check-voucher" data-id="' . $monthlyPayment->recurring_payment_id . '&' . $monthlyPayment->date . '" type="button" class="btn btn-link">' . $voucher_label . '</button>';
                    }
                    return $actions;
                })
                ->rawColumns(['supplier_info', 'voucher_info', 'due_date', 'remaining_days', 'actions'])
                ->make(true);
        } else {
            $frequency_filter = request()->frequency_filter;
            $status_filter = request()->status_filter;
            $period_from_filter = request()->period_from_filter;
            $period_to_filter = request()->period_to_filter;
            if ($period_from_filter) {
                $period_from_filter = date('Y-m-d', strtotime($period_from_filter));
            } else {
                $period_from_filter = date('Y-m-01');
            }

            if ($period_to_filter) {
                $period_to_filter = date('Y-m-d', strtotime($period_to_filter));
            } else {
                $period_to_filter = date('Y-m-t');
            }
            $monthlyPayments = $this->monthlyPayments($period_from_filter, $period_to_filter, $status_filter, $frequency_filter);


            return $monthlyPayments;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     * @throws \Exception
     */
    public function index_batch()
    {
        if ($this->isRequestTypeDatatable(request())) {
            $frequency_filter = request()->frequency_filter;
            $status_filter = request()->status_filter;
            $period_from_filter = request()->period_from_filter;
            $period_to_filter = request()->period_to_filter;
            if ($period_from_filter) {
                $period_from_filter = date('Y-m-d', strtotime($period_from_filter));
            } else {
                $period_from_filter = date('Y-m-01');
            }

            if ($period_to_filter) {
                $period_to_filter = date('Y-m-d', strtotime($period_to_filter));
            } else {
                $period_to_filter = date('Y-m-t');
            }
            $monthlyPayments = $this->monthlyPayments($period_from_filter, $period_to_filter, $status_filter, $frequency_filter);

            return DataTables::of($monthlyPayments)
                ->editColumn('actions', function ($monthlyPayment) {
                    $actions = '<div class="text-center"><input id="checkbox-item" data-id="' . $monthlyPayment->voucher->id . '" type="checkbox" style="transform: scale(1.5);"/></div>';
                    return $actions;
                })
                ->editColumn('supplier_info', function ($monthlyPayment) {
                    $supplier = Supplier::find($monthlyPayment->supplier_id);

                    $s_info = '<div>Payee: ' . $supplier->name . '</div>';
                    $s_info .= '<div class="mb-3">TIN: ' . $supplier->tin . '</div>';
                    $s_info .= '<div>Remarks: ' . $monthlyPayment->remarks . '</div>';

                    return $s_info;
                })
                ->editColumn('amount_date', function ($monthlyPayment) {
                    $amount_date = '<div>Amount Due: ' . $monthlyPayment->amount . '</div>';
                    $amount_date .= '<div>Date: ' . date('F d, Y', strtotime($monthlyPayment->date)) . '<br>' . date('l', strtotime($monthlyPayment->date)) . '</div>';
                    return $amount_date;
                })
                ->editColumn('voucher_info', function ($monthlyPayment) {
                    $v_info = '';
                    if ($monthlyPayment->voucher) {
                        $check_info = Check::find($monthlyPayment->voucher->check_id);
                        $v_info .= '<div class="mb-3">Voucher Number: ' . $monthlyPayment->voucher->voucher_no . '</div>';
                        $v_info .= '<div>Bank Account: ' . $check_info->bankAccount->bank->bank_name . ' (' . $check_info->bankAccount->acct_no . ')' . '</div>';
                        $v_info .= '<div>Check Number: ' . $check_info->check_no . '</div>';
                        $v_info .= '<div class="mb-3">Check Date: ' . $monthlyPayment->voucher->check_date . '</div>';
                        $v_info .= '<div>Explanation: ' . $monthlyPayment->voucher->explanation . '</div>';
                    } else {
                        $v_info .= 'N/A';
                    }
                    return $v_info;
                })
                ->editColumn('distribution_info', function ($monthlyPayment) {
                    $distribution_info = '';
                    $debit_total = 0;
                    $credit_total = 0;

                    $distribution_info .= '<table border="1">';
                    $distribution_info .= '    <tr>';
                    $distribution_info .= '        <th class="bg-primary text-white">Particulars</th>';
                    $distribution_info .= '        <th class="bg-primary text-white">Debit</th>';
                    $distribution_info .= '        <th class="bg-primary text-white">Credit</th>';
                    $distribution_info .= '    </tr>';
                    foreach ($monthlyPayment->voucher->voucherDistributions as $voucherDistribution) {
                        $distribution_info .= '    <tr>';
                        $distribution_info .= '        <td>' . $voucherDistribution->chartOfAccount->description . '</td>';
                        if ($voucherDistribution->typical_balance == 'D') {
                            $distribution_info .= '        <td>' . number_format($voucherDistribution->amount, 2) . '</td>';
                            $distribution_info .= '        <td></td>';
                            $debit_total += $voucherDistribution->amount;
                        } else {
                            $distribution_info .= '        <td></td>';
                            $distribution_info .= '        <td>' . number_format($voucherDistribution->amount, 2) . '</td>';
                            $credit_total += $voucherDistribution->amount;
                        }
                        $distribution_info .= '    </tr>';
                    }
                    $distribution_info .= '    <tr>';
                    $distribution_info .= '        <td class="bg-gray-100 font-weight-bold">Total</td>';
                    $distribution_info .= '        <td class="bg-gray-100 font-weight-bold">' . number_format($debit_total, 2) . '</td>';
                    $distribution_info .= '        <td class="bg-gray-100 font-weight-bold">' . number_format($credit_total, 2) . '</td>';
                    $distribution_info .= '    </tr>';

                    $distribution_info .= '</table>';

                    return $distribution_info;
                })
                ->rawColumns(['actions', 'supplier_info', 'amount_date', 'voucher_info', 'distribution_info'])
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
            'voucher.bank_account_id' => 'required',
            'voucher.check_id' => 'required',
            'voucher.check_date' => 'required',
            'voucher.document_type' => 'required',
            'voucher.document_no' => 'required',
            'voucher.explanation' => 'required',
            'debit_total' => 'in:' . $request['credit_total'],
            'voucher.amount' => 'lte:' . $request['debit_total'],
            'recurring_payment_distributions.*.chart_of_account_id' => 'required_with:recurring_payment_distributions.*.typical_balance, recurring_payment_distributions.*.amount',
            'recurring_payment_distributions.*.amount' => 'required_with:recurring_payment_distributions.*.chart_of_account_id'
        ];

        $custom = [
            'voucher.bank_account_id.required' => 'Bank is required.',
            'voucher.check_id.required' => 'Check number is required.',
            'voucher.check_date.required' => 'Check date is required.',
            'voucher.document_type.required' => 'Document type is required.',
            'voucher.document_no.required' => 'Document number is required.',
            'voucher.explanation.required' => 'Explanation is required.',
            'debit_total.in' => 'Total debit should tally with total credit.',
            'voucher.amount.lte' => 'Total distribution amount does not satisfy the amount needed to be paid.',
            'recurring_payment_distributions.*.chart_of_account_id.required_with' => 'Account number is required.',
            'recurring_payment_distributions.*.amount.required_with' => 'Debit/Credit is required.',
        ];

        $curr_date = date('Y-m', time());
        $count_vouchers = Voucher::where('voucher_no', 'like', '%' . $curr_date . '%')->get()->count();
        $voucher_no = $curr_date . '-' . sprintf("%04d", $count_vouchers + 1);

        $request->validate($validate, $custom);

        $voucher_array = $request['voucher'];
        $voucher_array = array_merge($voucher_array, [
            'voucher_no' => $voucher_no,
            'status' => 'O',
            'prepared_by' => 'Test',
            'logs' => 'Created by: Test'
        ]);
        $request->request->set('voucher', $voucher_array);
        $voucher_data = $request['voucher'];
        $recurring_payment_distribution_data = $request['recurring_payment_distributions'];

        $validate_check = Check::find($voucher_data['check_id'])->voucher_no;
        if ($validate_check) {
            return response()->json(['success' => false, 'message' => 'Check number already used. Please update and try again.'], 500);
        } else {
            $result = Voucher::create($voucher_data)->id;
            if ($result) {
                $created_at = now();
                $updated_at = now();

                $voucher_distribution_data = array();
                foreach ($recurring_payment_distribution_data as $distribution_datum) {
                    if ($distribution_datum['chart_of_account_id'] &&
                        $distribution_datum['typical_balance'] &&
                        $distribution_datum['amount']) {
                        $voucher_distribution_data[] = [
                            'voucher_id' => $result,
                            'chart_of_account_id' => $distribution_datum['chart_of_account_id'],
                            'typical_balance' => $distribution_datum['typical_balance'],
                            'amount' => $distribution_datum['amount'],
                            'logs' => 'Created by: Test',
                            'created_at' => $created_at,
                            'updated_at' => $updated_at
                        ];
                    }
                }

                $check_result = Check::find($voucher_data['check_id'])->update(['voucher_no' => $voucher_no]);
                $voucher_distribution_result = VoucherDistribution::insert($voucher_distribution_data);

                if ($check_result && $voucher_distribution_result) {
                    /**
                     * check for failure of event tag when insert try to rollback (DB rollback)
                     * try to check if there is other way to insert multiple record
                     */
                    return response()->json(['success' => true, 'message' => 'The record was added successfully!']);
                } else {
                    Check::find($voucher_data['check_id'])->update('voucher_no', null);
                    VoucherDistribution::where(['voucher_id' => $result])->delete();

                    return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
                }
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
        $recurring_payment_id = explode('&', $id)[0];
        $recurring_payment_date = explode('&', $id)[1];

        $recurringPayment = RecurringPayment::where('id', $recurring_payment_id)
            ->with([
                'supplier',
                'recurringPaymentDistributions' => function ($query) {
                    $query->select('recurring_payment_id', 'chart_of_account_id', 'typical_balance', 'amount');
                },
                'voucher' => function ($query) use ($recurring_payment_date) {
                    $query->where('date', $recurring_payment_date);
                    $query->where('status', '!=', 'V');
                    $query->first();
                },
                'voucher.voucherDistributions',
                'voucher.voucherDistributions.chartOfAccount',
                'voucher.bankAccount',
                'voucher.check'
            ])->first();

//        $recurringPayment = RecurringPayment::where('id', $recurring_payment_id)
//            ->with('supplier')
//            ->with(['recurringPaymentDistributions' => function ($query) {
//                $query->select('recurring_payment_id', 'chart_of_account_id', 'typical_balance', 'amount');
//            }])->with(['voucher' => function ($query) use ($recurring_payment_date) {
//                $query->where('date', $recurring_payment_date);
//                $query->first();
//                $query->with(['voucherDistributions' => function ($query) {
//                    $query->with('chartOfAccount');
//                }]);
//                $query->with('bankAccount');
//                $query->with('check');
//            }])->first();

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
        $validate = [
            'voucher.bank_account_id' => 'required',
            'voucher.check_id' => 'required',
            'voucher.check_date' => 'required',
            'voucher.document_type' => 'required',
            'voucher.document_no' => 'required',
            'voucher.explanation' => 'required',
            'debit_total' => 'in:' . $request['credit_total'],
            'voucher.amount' => 'lte:' . $request['debit_total'],
            'recurring_payment_distributions.*.chart_of_account_id' => 'required_with:recurring_payment_distributions.*.typical_balance, recurring_payment_distributions.*.amount',
            'recurring_payment_distributions.*.amount' => 'required_with:recurring_payment_distributions.*.chart_of_account_id'
        ];

        $custom = [
            'voucher.bank_account_id.required' => 'Bank is required.',
            'voucher.check_id.required' => 'Check number is required.',
            'voucher.check_date.required' => 'Check date is required.',
            'voucher.document_type.required' => 'Document type is required.',
            'voucher.document_no.required' => 'Document number is required.',
            'voucher.explanation.required' => 'Explanation is required.',
            'debit_total.in' => 'Total debit should tally with total credit.',
            'voucher.amount.lte' => 'Total distribution amount does not satisfy the amount needed to be paid.',
            'recurring_payment_distributions.*.chart_of_account_id.required_with' => 'Account number is required.',
            'recurring_payment_distributions.*.amount.required_with' => 'Debit/Credit is required.',
        ];

        $request->validate($validate, $custom);

        $voucher_array = $request['voucher'];
        $voucher_array = array_merge($voucher_array, [
            'last_modified' => 'Last modified by: Test'
        ]);

        $voucher_status = $voucher_array['status'];
        if ($voucher_status == 'O') {
            $voucher_array['status'] = 'R';
            $voucher_array['checked_by'] = 'Test';
        } else if ($voucher_status == 'R') {
            $voucher_array['status'] = 'F';
            $voucher_array['recommended_by'] = 'Test';
        }

        $request->request->set('voucher', $voucher_array);
        $voucher_data = $request['voucher'];
        $recurring_payment_distribution_data = $request['recurring_payment_distributions'];

        $result = Voucher::find($voucher_data['id'])->update($voucher_data);
        if ($result) {
            $created_at = now();
            $updated_at = now();

            $voucher_distribution_data = array();
            foreach ($recurring_payment_distribution_data as $distribution_datum) {
                if ($distribution_datum['chart_of_account_id'] &&
                    $distribution_datum['typical_balance'] &&
                    $distribution_datum['amount']) {
                    $voucher_distribution_data[] = [
                        'voucher_id' => $voucher_data['id'],
                        'chart_of_account_id' => $distribution_datum['chart_of_account_id'],
                        'typical_balance' => $distribution_datum['typical_balance'],
                        'amount' => $distribution_datum['amount'],
                        'logs' => 'Created by: Test',
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    ];
                }
            }

            VoucherDistribution::where(['voucher_id' => $voucher_data['id']])->delete();
            if ($voucher_data['old_check_id'] != $voucher_data['check_id']) {
                Check::find($voucher_data['old_check_id'])->update(['voucher_no' => null]);
                Check::find($voucher_data['check_id'])->update(['voucher_no' => $voucher_data['voucher_no']]);
            }

            $voucher_distribution_result = VoucherDistribution::insert($voucher_distribution_data);
            if ($voucher_distribution_result) {
                /**
                 * check for failure of event tag when insert try to rollback (DB rollback)
                 * try to check if there is other way to insert multiple record
                 */
                return response()->json(['success' => true, 'message' => 'The record was updated successfully!']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $batch_id
     * @param $status_filter
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_batch($batch_id, $status_filter)
    {
        $vouchers = explode(',', $batch_id);

        $voucher_data = [
            'last_modified' => 'Last modified by: Test'
        ];
        if($status_filter == 'O') {
            $voucher_data = array_merge($voucher_data, [
                'status' => 'R',
                'checked_by' => 'Test'
            ]);
        } else if ($status_filter == 'R') {
            $voucher_data = array_merge($voucher_data, [
                'status' => 'F',
                'recommended_by' => 'Test'
            ]);
        }

        $result = Voucher::whereIn('id', $vouchers)->update($voucher_data);
        if ($result) {
            /**
             * check for failure of event tag when insert try to rollback (DB rollback)
             * try to check if there is other way to insert multiple record
             */
            return response()->json(['success' => true, 'message' => 'The record/s was updated successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recurring_payment_id = explode('&', $id)[0];
        $recurring_payment_date = explode('&', $id)[1];
        $voucher_check_id = explode('&', $id)[2];

        $result = Voucher::where('recurring_payment_id', $recurring_payment_id)
            ->where('date', $recurring_payment_date)
            ->delete();
        if ($result) {
            Check::find($voucher_check_id)->update(['voucher_no' => null]);
            return response()->json(['success' => true, 'message' => 'The check voucher was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
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
     * @param $period_from_filter
     * @param $period_to_filter
     * @param $status_filter
     * @param $frequency_filter
     * @return array
     * @throws \Exception
     */
    public function monthlyPayments($period_from_filter, $period_to_filter, $status_filter, $frequency_filter)
    {
        $date_start = $period_from_filter;
        $date_end = $period_to_filter;
        $date_end_object = new DateTime($date_end);
        $month_from = date('n', strtotime($period_from_filter));
        $month_to = date('n', strtotime($period_to_filter));

        $date = new DatePeriod(
            new DateTime($date_start), new DateInterval('P1D'), $date_end_object);

        $monthlyPayments = RecurringPayment::with(['recurringPaymentDates' => function ($child) use ($month_from, $month_to, $date_start, $date_end) {
            $child->where(function ($child_sub1) use ($month_from, $month_to) {
                $child_sub1->whereBetween('month', [$month_from, $month_to])
                    ->orWhere('month', 0);
            });
        }])->where('disabled', 'N')
            ->get();

        if ($frequency_filter) {
            $monthlyPayments = $monthlyPayments->where('frequency', $frequency_filter);
        }

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
                    $object->title = $monthlyPayment->supplier->name;
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

                    $voucher = Voucher::with([
                        'voucherDistributions',
                        'voucherDistributions.chartOfAccount'
                    ])->where('recurring_payment_id', $monthlyPayment->id)
                        ->where('status', '!=', 'V')
                        ->where('date', $date)
                        ->first();

                    $object->voucher = $voucher;

                    if ((strtotime($monthlyPayment->duration_from) <= strtotime($date)
                            && strtotime($monthlyPayment->duration_to) >= strtotime($date))
                        || ($monthlyPayment->duration_from == null
                            && $monthlyPayment->duration_to == null)) {

                        if ($monthlyPayment->frequency == 'W' && $day_of_week == $recurringPaymentDate->weekday) {
                            if ($this->checkStatusFilter($status_filter, $object->voucher['status'])) {
                                $monthlyPaymentsList[] = $object;
                            }

                        } else if (($monthlyPayment->frequency == 'Q'
                                || $monthlyPayment->frequency == 'S'
                                || $monthlyPayment->frequency == 'A')
                            && ($recurringPaymentDate->month == $month_only
                                && $recurringPaymentDate->day == $date_only)) {
                            if ($this->checkStatusFilter($status_filter, $object->voucher['status'])) {
                                $monthlyPaymentsList[] = $object;
                            }

                        } else if ($monthlyPayment->frequency == 'M' && $recurringPaymentDate->day == $date_only) {
                            if ($this->checkStatusFilter($status_filter, $object->voucher['status'])) {
                                $monthlyPaymentsList[] = $object;
                            }
                        }
                    }
                }
            }
        }

        return $monthlyPaymentsList;
    }

    /**
     * Check if object satisfied the filter
     *
     * @param $status_filter
     * @param $status
     * @return bool
     */
    public function checkStatusFilter($status_filter, $status)
    {
        if (!$status_filter) {
            return true;
        } else if ($status_filter == 'N') {
            if (!$status) {
                return true;
            }
        } else if ($status == $status_filter) {
            return true;
        }

        return false;
    }

    /**
     * Display supplier information
     *
     * @param $monthlyPayment
     * @return string
     */
    public static function supplierInformation($monthlyPayment)
    {
        $supplier = Supplier::find($monthlyPayment->supplier_id);

        $s_info = '<div>Payee: ' . $supplier->name . '</div>';
        $s_info .= '<div class="mb-3">TIN: ' . $supplier->tin . '</div>';

//        foreach (SupplierContact::where('supplier_id', $monthlyPayment->supplier_id)->get() as $value) {
//            $s_info .= '<div class="mb-3">';
//            $s_info .= '<div>Contact Person: ' . $value->contact_person . '</div>';
//            $s_info .= '<div>Phone Number 1: ' . $value->phone_number1 . '</div>';
//            $s_info .= $value->phone_number2 ? '<div>Phone Number 2: ' . $value->phone_number2 . '</div>' : '';
//            $s_info .= $value->phone_number3 ? '<div>Phone Number 3: ' . $value->phone_number3 . '</div>' : '';
//            $s_info .= $value->fax_number ? '<div>Fax Number: ' . $value->fax_number . '</div>' : '';
//            $s_info .= '</div>';
//        }

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
        $s_info .= '<br></fieldset>';

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
                $bankList[$bankAccount->id] = $bank->bank_name . ' (' . $bank->bank_prefix . ') - ' . $bankAccount->acct_no;
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
     * Get voucher checks from resource storage
     *
     * @param $id
     * @return mixed
     */
    public function get_voucher_checks($id)
    {
        $bank_account_id = explode('&', $id)[0];
        $check_id = explode('&', $id)[1];

        $checks = Check::where('bank_account_id', $bank_account_id)
            ->where(function ($query) use ($check_id) {
                $query->where('voucher_no', null)
                    ->orWhere('id', $check_id);
            })
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
        $monthlyPayments = $this->monthlyPayments(request()->period_from_filter, request()->period_to_filter, '', '');

        try {
            $pdf = SnappyPdf::loadView('reports.ap.monthly_payment', compact('monthlyPayments'));
            return $pdf->stream('report_req_monthly_payment_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function generateCheckVoucherPDF($id)
    {
        $monthlyPayment = $this->show($id);
        $voucherDistributions = $monthlyPayment->voucher->voucherDistributions->toArray();

        $debitDistribution = array_values(array_filter($voucherDistributions, function ($distribution) {
            $filtered_array = ($distribution['typical_balance'] == 'D');
            return $filtered_array;
        }));

        $creditDistribution = array_values(array_filter($voucherDistributions, function ($distribution) {
            $filtered_array = ($distribution['typical_balance'] == 'C');
            return $filtered_array;
        }));

        try {
            $pdf = SnappyPdf::loadView('reports.ap.check_voucher', compact(['monthlyPayment', 'debitDistribution', 'creditDistribution']));
            return $pdf->setPaper('letter')
                ->setOption('margin-bottom', 0)
                ->stream('report_req_check_voucher_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public static function convertNumberToWords($number)
    {

        $hyphen = ' ';
        $conjunction = ' ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion',
            1000000000000 => 'Trillion',
            1000000000000000 => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . (new self)->ConvertNumberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

                if ($remainder) {
                    $string .= $conjunction . (new self)->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = (new self)->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= (new self)->convertNumberToWords($remainder);
                }
                break;
        }

        return $string;
    }
}
