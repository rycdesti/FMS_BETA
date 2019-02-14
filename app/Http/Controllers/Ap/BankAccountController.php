<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\Bank;
use App\Models\Ap\BankAccount;
use App\Models\Requisition\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BankAccountController extends Controller
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_address' => 'required',
            'acct_code' => 'required',
            'acct_no' => 'required',
            'acct_type' => 'required',
            'currency_id' => 'required',
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = BankAccount::create($data);
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
     * @param $bank_id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show($id)
    {
        if ($this->isRequestTypeDatatable(request())) {
            $bankAccounts = BankAccount::where('bank_id', '=', $id);
            return DataTables::of($bankAccounts)
                ->editColumn('acct_info', function (BankAccount $bankAccount) {
                    return '<div>Account Code: ' . $bankAccount->acct_code . '</div>
                            <div>Account Number: ' . $bankAccount->acct_no . '</div>
                            <div>Account Type: ' . $this->get_acct_type($bankAccount->acct_type) . '</div>
                            <div>Currency: ' . $bankAccount->currency->description . '</div>
                            <div>Beginning Balance: ' . ($bankAccount->beginning_balance ? number_format($bankAccount->beginning_balance, 2) : '0.00') . '</div>';
                })
                ->editColumn('status', function (BankAccount $bankAccount) {
                    if ($bankAccount->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $bankAccount->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $bankAccount->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (BankAccount $bankAccount) {
                    return '<div>' . $bankAccount->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bankAccount->created_at->diffForHumans() . '</div><br>' .
                        ($bankAccount->last_modified ? '<div>' . $bankAccount->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bankAccount->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (BankAccount $bankAccount) {
                    return '<button id="btn-edit" data-id="' . $bankAccount->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                            <button id="btn-delete" data-id="' . $bankAccount->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>
                            <button id="btn-beginning-bal" data-id="' . $bankAccount->id . '" type="button" class="btn btn-link">Beginning Balance</button><br>' .
                        ($bankAccount->acct_type == 'C' ? '<button id="btn-check-booklet" data-id="' . $bankAccount->id . '" type="button" class="btn btn-link">Manage Check Booklet</button><br>' : '') .
                        '<button id="btn-update-status" data-id="' . $bankAccount->id . '" type="button" class="btn btn-link">' . ($bankAccount->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                })
                ->rawColumns(['acct_info', 'status', 'logs', 'actions'])
                ->make(true);
        } else {
            $data = BankAccount::find($id);

            return $data;
        }
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
     * @param BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'bank_address' => 'required',
            'acct_code' => 'required',
            'acct_no' => 'required',
            'acct_type' => 'required',
            'currency_id' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $bankAccount->update($data);
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
     * @param BankAccount $bankAccount
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(BankAccount $bankAccount)
    {
        $result = $bankAccount->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param BankAccount $bankAccount
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(BankAccount $bankAccount)
    {
        $status = $bankAccount->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $bankAccount->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $bankAccount->update([
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
     * Update beginning balance of specified resource from storage
     *
     * @param Request $request
     * @param BankAccount $bankAccount
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_beginning_bal(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'beginning_balance' => 'required',
            'as_of' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $bankAccount->update($data);
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
     * Get bank from storage
     *
     * @param $id
     * @return mixed
     */
    public function get_bank($id)
    {
        $bank = Bank::find($id);

        return $bank;
    }

    /**
     * Get account types
     *
     * @param null $value
     * @return array|mixed
     */
    public function get_acct_type($value = null)
    {
        $acctType = array('S' => 'Savings', 'C' => 'Cheque In');
        if ($value) {
            return $acctType[$value];
        } else {
            return $acctType;
        }
    }

    /**
     * Get all currencies from storage
     *
     * @return mixed
     */
    public function get_currency()
    {
        $currencies = Currency::where('disabled', '=', 'N')
            ->orderBy('description')
            ->get([
                'id as value',
                'description as text'
            ]);

        return $currencies;
    }
}
