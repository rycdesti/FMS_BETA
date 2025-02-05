<?php

namespace App\Http\Controllers\Financial;

use App\Models\Financial\AccountCategory;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class AccountCategoryController extends Controller
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
            $accountCategories = AccountCategory::get();

            if ($status_filter) {
                $accountCategories = $accountCategories->where('disabled', $status_filter);
            }

            return DataTables::of($accountCategories)
                ->editColumn('status', function (AccountCategory $accountCategory) {
                    if ($accountCategory->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $accountCategory->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $accountCategory->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (AccountCategory $accountCategory) {
                    return '<div>' . $accountCategory->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $accountCategory->created_at->diffForHumans() . '</div><br>' .
                        ($accountCategory->last_modified ? '<div>' . $accountCategory->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $accountCategory->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (AccountCategory $accountCategory) {
                    return '<button id="btn-edit" data-id="' . $accountCategory->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>' .
                        ($accountCategory->chartOfAccounts->count() ? '<hr>' : '<button id="btn-delete" data-id="' . $accountCategory->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>') .
                        '<button id="btn-update-status" data-id="' . $accountCategory->id . '" type="button" class="btn btn-link">' . ($accountCategory->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                })
                ->rawColumns(['status', 'logs', 'actions'])
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
            'description' => 'required',
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = AccountCategory::create($data);
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
        $data = AccountCategory::find($id);

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
     * @param AccountCategory $accountCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountCategory $accountCategory)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $accountCategory->update($data);
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
     * @param AccountCategory $accountCategory
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(AccountCategory $accountCategory)
    {
        $result = $accountCategory->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param AccountCategory $accountCategory
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(AccountCategory $accountCategory)
    {
        $status = $accountCategory->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $accountCategory->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $accountCategory->update([
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
        $accountCategories = AccountCategory::all();

        try {
            $pdf = SnappyPdf::loadView('reports.financial.account_category', compact('accountCategories'));
            return $pdf->stream('report_req_account_categories_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
