<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\Models\Ap\BankDeposit;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
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

//            var_dump($period_from_filter, $period_to_filter); die();

            $bankDeposits = $bankDeposits->whereBetween('date_deposit', [$period_from_filter, $period_to_filter]);

            return DataTables::of($bankDeposits)
                ->editColumn('bank_account_id', function(BankDeposit $bankDeposit) {
                    return $bankDeposit->bankAccount->bank->bank_name;
                })
                ->editColumn('logs', function (BankDeposit $bankDeposit) {
                    return '<div>' . $bankDeposit->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bankDeposit->created_at->diffForHumans() . '</div><br>' .
                        ($bankDeposit->last_modified ? '<div>' . $bankDeposit->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bankDeposit->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (BankDeposit $bankDeposit) {
                    $actions = '';
                    if (!$bankDeposit->bankAccounts->count()) {
                        $actions .= '<button id="btn-edit" data-id="' . $bankDeposit->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                                    <button id="btn-delete" data-id="' . $bankDeposit->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>';
                    } else {
                        $actions .= '<button id="btn-edit" data-id="' . $bankDeposit->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button><hr>';
                    }
                    $actions .= '<button id="btn-bank-account" data-id="' . $bankDeposit->id . '" type="button" class="btn btn-link">Manage BankDeposit Accounts</button><br>
                            <button id="btn-update-status" data-id="' . $bankDeposit->id . '" type="button" class="btn btn-link">' . ($bankDeposit->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                    return $actions;
                })
                ->rawColumns(['logs', 'actions'])
                ->make(true);
        } else {

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
        $request->validate([
            'bank_name' => 'required',
        ]);

        $request['bank_code'] = $bank_code;
        $request['bank_prefix'] = $bank_prefix;
        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = BankDeposit::create($data);
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
        $data = BankDeposit::find($id);

        return $data;
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
     * @param BankDeposit $bankDeposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankDeposit $bankDeposit)
    {
        $request->validate([
            'bank_name' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $bankDeposit->update($data);
        if ($result) {
            /**
             * check for failure of event tag when insert try to rollback (DB rollback)
             * try to check if there is other way to insert multiple record
             */
            return response()->json(['success' => true, 'message' => 'The record was updated successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param BankDeposit $bankDeposit
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(BankDeposit $bankDeposit)
    {
        $result = $bankDeposit->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param BankDeposit $bankDeposit
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(BankDeposit $bankDeposit)
    {
        $status = $bankDeposit->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $bankDeposit->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $bankDeposit->update([
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

    public function generatePDFReport()
    {
        $bankDeposits = BankDeposit::all();

        try {
            $pdf = SnappyPdf::loadView('reports.ap.bank_deposit', compact('bankDeposits'));
            return $pdf->stream('report_req_bank_deposit_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
