<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\BankAccount;
use App\Models\Ap\BankDeposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BankDepositController extends Controller
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
            $bank_filter = request()->bank_filter;
            $bank_account_filter = request()->bank_account_filter;
            $period_from_filter = request()->period_from_filter;
            $period_to_filter = request()->period_to_filter;

            $bankDeposits = BankDeposit::get();
            if ($bank_filter) {
                if ($bank_account_filter) {
                    $bankDeposits = $bankDeposits->where('bank_account_id', $bank_account_filter);
                } else {
                    $bankAccounts = BankAccount::where('bank_id', $bank_filter)->pluck('id');
                    $bankDeposits = $bankDeposits->whereIn('bank_account_id', $bankAccounts);
                }
            }
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

            $bankDeposits = $bankDeposits->whereBetween('date_deposit', [$period_from_filter, $period_to_filter]);
            return DataTables::of($bankDeposits)
                ->editColumn('bank_details', function (BankDeposit $bankDeposit) {
                    $b_details = '<div>Bank Name: ' . $bankDeposit->bankAccount->bank->bank_name . '</div>';
                    $b_details .= '<div>Bank Account No: ' . $bankDeposit->bankAccount->acct_no . '</div>';
                    return $b_details;
                })
                ->editColumn('date_deposit', function (BankDeposit $bankDeposit) {
                    return date('F d, Y', strtotime($bankDeposit->date_deposit));
                })
                ->editColumn('time_deposit', function (BankDeposit $bankDeposit) {
                    return date('H:i:s', strtotime($bankDeposit->time_deposit));
                })
                ->editColumn('cash_deposit', function (BankDeposit $bankDeposit) {
                    return number_format($bankDeposit->cash_deposit, 2);
                })
                ->editColumn('logs', function (BankDeposit $bankDeposit) {
                    return '<div>' . $bankDeposit->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bankDeposit->created_at->diffForHumans() . '</div><br>' .
                        ($bankDeposit->last_modified ? '<div>' . $bankDeposit->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bankDeposit->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (BankDeposit $bankDeposit) {
                    $actions = '';
                    return $actions;
                })
                ->rawColumns(['bank_details', 'date_deposit', 'time_deposit', 'cash_deposit', 'logs', 'actions'])
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
            'bank_id' => 'required',
            'bank_account_id' => 'required',
            'date_deposit' => 'required',
            'time_deposit' => 'required',
            'ref_no' => 'required',
            'total_deposit' => 'not_in:0,0.0,0.00',
        ];

        $custom = [
            'bank_id.required' => 'Bank is required',
            'bank_account_id.required' => 'Account number is required.',
            'date_deposit.required' => 'Date deposit is required.',
            'time_deposit.required' => 'Time deposit is required.',
            'ref_no.required' => 'Reference number is required',
            'total_deposit.not_in' => 'Total deposit should not be equal to zero (0).',
        ];

        $request->validate($validate, $custom);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = BankDeposit::create($data)->id;
        if ($result) {
            /**
             * check for failure of event tag when insert try to rollback (DB rollback)
             * try to check if there is other way to insert multiple record
             */
            return response()->json(['success' => true, 'message' => 'The record was added successfully!']);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
