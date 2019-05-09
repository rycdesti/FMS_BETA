<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\RecurringPaymentDistribution;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RecurringPaymentDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $request->validate([
            'recurring_payment_id' => 'required',
            'chart_of_account_id' => 'required',
            'typical_balance' => 'required',
            'amount' => 'required',
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = RecurringPaymentDistribution::create($data);
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
     * @throws \Exception
     */
    public function show($id)
    {
        if ($this->isRequestTypeDatatable(request())) {
            $recurringPaymentDistributions = RecurringPaymentDistribution::where('recurring_payment_id', '=', $id);
            return DataTables::of($recurringPaymentDistributions)
                ->editColumn('acct_code', function (RecurringPaymentDistribution $recurringPaymentDistribution) {
                    return $recurringPaymentDistribution->chartOfAccount->acct_code;
                })
                ->editColumn('acct_desc', function (RecurringPaymentDistribution $recurringPaymentDistribution) {
                    return $recurringPaymentDistribution->chartOfAccount->description;
                })
                ->editColumn('debit', function (RecurringPaymentDistribution $recurringPaymentDistribution) {
                    return $recurringPaymentDistribution->typical_balance == "D" ? number_format($recurringPaymentDistribution->amount,2) : 0.00;
                })
                ->editColumn('credit', function (RecurringPaymentDistribution $recurringPaymentDistribution) {
                    return $recurringPaymentDistribution->typical_balance == "C" ? number_format($recurringPaymentDistribution->amount,2) : 0.00;
                })
                ->editColumn('logs', function (RecurringPaymentDistribution $recurringPaymentDistribution) {
                    return '<div>' . $recurringPaymentDistribution->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPaymentDistribution->created_at->diffForHumans() . '</div><br>' .
                        ($recurringPaymentDistribution->last_modified ? '<div>' . $recurringPaymentDistribution->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPaymentDistribution->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (RecurringPaymentDistribution $recurringPaymentDistribution) {
                    return '<button id="btn-delete" data-id="' . $recurringPaymentDistribution->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button>';
                })
                ->rawColumns(['acct_info', 'debit', 'credit', 'logs', 'actions'])
                ->make(true);
        } else {
            $data = RecurringPaymentDistribution::find($id);

            return $data;
        }
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
     * @param RecurringPaymentDistribution $recurringPaymentDistribution
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(RecurringPaymentDistribution $recurringPaymentDistribution)
    {
        $result = $recurringPaymentDistribution->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }
}
