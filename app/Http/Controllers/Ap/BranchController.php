<?php

namespace App\Http\Controllers\Ap;

use App\Models\System\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
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
            $branches = Branch::get();

            if ($status_filter) {
                $branches = $branches->where('disabled', $status_filter);
            }

            return DataTables::of($branches)
                ->editColumn('status', function (Branch $branch) {
                    if ($branch->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $branch->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $branch->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (Branch $branch) {
                    return '<div>' . $branch->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $branch->created_at->diffForHumans() . '</div><br>' .
                        ($branch->last_modified ? '<div>' . $branch->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $branch->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (Branch $branch) {
                    $actions = '';
                    $actions .= '<button id="btn-edit" data-id="' . $branch->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                                    <button id="btn-delete" data-id="' . $branch->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>';

                    $actions .= '<button id="btn-update-status" data-id="' . $branch->id . '" type="button" class="btn btn-link">' . ($branch->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                    return $actions;
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
            'branch_name' => 'required',
        ]);

        $filtered_words = array("OF", "THE", "AND");
        $special_char = array('(', ')', '!', '@', '#', '$', '%', '^', '&', '*');

        $branch_code = '';
        $branch_name = str_replace($special_char, '', request('branch_name'));
        $array_branch_name = explode(" ", strtoupper($branch_name));

        foreach ($array_branch_name as $key => $value) {
            if ($value) {
                if (in_array($value, $filtered_words)) {
                    unset($branch_code[$key]);
                } else {
                    if (strlen($branch_code) < 5) {
                        $branch_code .= $array_branch_name[$key][0];
                    }
                }
            }
        }

        $request['branch_name'] = ucwords(request('branch_name'));
        $request['branch_code'] = $branch_code;
        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = Branch::create($data);
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
        $data = Branch::find($id);

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
     * @param Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'branch_name' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $branch->update($data);
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
     * @param Branch $branch
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Branch $branch)
    {
        $result = $branch->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param Branch $branch
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(Branch $branch)
    {
        $status = $branch->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $branch->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $branch->update([
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
}
