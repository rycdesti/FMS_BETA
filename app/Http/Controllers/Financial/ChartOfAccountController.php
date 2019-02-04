<?php

namespace App\Http\Controllers\Financial;

use App\Models\Financial\AccountCategory;
use App\Models\Financial\ChartOfAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ChartOfAccount[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function index()
    {
        if ($this->isRequestTypeDatatable(request())) {
            $chartOfAccounts = ChartOfAccount::all();
            return DataTables::of($chartOfAccounts)
                ->editColumn('information', function (ChartOfAccount $chartOfAccount) {
                    return '<div>Description: ' . $chartOfAccount->description . '</div>
                            <div>Account Category: ' . $chartOfAccount->accountCategory->description . '</div>
                            <div>Posting Type: ' . $this->get_posting_type($chartOfAccount->posting_type) . '</div>
                            <div>Typical Balance: ' . $this->get_typical_balance($chartOfAccount->typical_balance) . '</div>';
                })
                ->editColumn('status', function (ChartOfAccount $chartOfAccount) {
                    if ($chartOfAccount->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $chartOfAccount->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $chartOfAccount->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (ChartOfAccount $chartOfAccount) {
                    return '<div>' . $chartOfAccount->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $chartOfAccount->created_at->diffForHumans() . '</div><br>' .
                        ($chartOfAccount->last_modified ? '<div>' . $chartOfAccount->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $chartOfAccount->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (ChartOfAccount $chartOfAccount) {
                    return '<button id="btn-edit" data-id="' . $chartOfAccount->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                            <button id="btn-delete" data-id="' . $chartOfAccount->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>
                            <button id="btn-update-status" data-id="' . $chartOfAccount->id . '" type="button" class="btn btn-link">' . ($chartOfAccount->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                })
                ->rawColumns(['information', 'status', 'logs', 'actions'])
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
        $request->validate([
            'acct_code' => 'required',
            'description' => 'required',
            'account_category_id' => 'required',
            'posting_type' => 'required',
            'typical_balance' => 'required'
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = ChartOfAccount::create($data);
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
        $data = ChartOfAccount::find($id);

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $request->validate([
            'acct_code' => 'required',
            'description' => 'required',
            'account_category_id' => 'required',
            'posting_type' => 'required',
            'typical_balance' => 'required'
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $chartOfAccount->update($data);
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
     * @param ChartOfAccount $chartOfAccount
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $result = $chartOfAccount->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param ChartOfAccount $chartOfAccount
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(ChartOfAccount $chartOfAccount)
    {
        $status = $chartOfAccount->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $chartOfAccount->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $chartOfAccount->update([
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
     * Get all enabled account categories
     *
     * @return mixed
     */
    public function get_acct_category()
    {
        $acctCategories = AccountCategory::where('disabled', '=', 'N')
            ->orderBy('description')
            ->get([
                'id as value',
                'description as text'
            ]);

        return $acctCategories;
    }

    /**
     * Get posting type
     *
     * @param null $value
     * @return array|mixed
     */
    public function get_posting_type($value = null) {
        $postingType = array('B' => 'Balance Sheet', 'P' => 'Profit And Loss');
        if($value) {
            return $postingType[$value];
        } else {
            return $postingType;
        }
    }

    /**
     * Get typical balance
     *
     * @param null $value
     * @return array|mixed
     */
    public function get_typical_balance($value = null) {
        $typicalBalance = array('C' => 'Credit', 'D' => 'Debit');
        if($value) {
            return $typicalBalance[$value];
        } else {
            return $typicalBalance;
        }
    }
}
