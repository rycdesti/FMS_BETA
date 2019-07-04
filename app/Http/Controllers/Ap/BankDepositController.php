<?php

namespace App\Http\Controllers\Ap;

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
            $period_from_filter = request()->period_from_filter;
            $period_to_filter = request()->period_to_filter;

            $bankDeposits = BankDeposit::get();
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
                ->editColumn('bank_details', function(BankDeposit $bankDeposit) {
                    $b_details = '<div>Bank Name: '. $bankDeposit->bankAccount->bank->bank_name .'</div>';
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
