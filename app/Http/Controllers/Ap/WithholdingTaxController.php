<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\WithholdingTax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WithholdingTaxController extends Controller
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
            $withholdingTaxes = WithholdingTax::get();

            if ($status_filter) {
                $withholdingTaxes = $withholdingTaxes->where('disabled', $status_filter);
            }

            return DataTables::of($withholdingTaxes)
                ->editColumn('tax', function (WithholdingTax $withholdingTax) {
                    return '<div>' . $withholdingTax->tax . '%</div>';
                })
                ->editColumn('status', function (WithholdingTax $withholdingTax) {
                    if ($withholdingTax->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $withholdingTax->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $withholdingTax->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (WithholdingTax $withholdingTax) {
                    return '<div>' . $withholdingTax->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $withholdingTax->created_at->diffForHumans() . '</div><br>' .
                        ($withholdingTax->last_modified ? '<div>' . $withholdingTax->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $withholdingTax->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (WithholdingTax $withholdingTax) {
                    $actions = '';
                    if (!$withholdingTax->vouchers->count()) {
                        $actions .= '<button id="btn-edit" data-id="' . $withholdingTax->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                                    <button id="btn-delete" data-id="' . $withholdingTax->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>';
                    } else {
                        $actions .= '<button id="btn-edit" data-id="' . $withholdingTax->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button><hr>';
                    }
                    $actions .= '<button id="btn-update-status" data-id="' . $withholdingTax->id . '" type="button" class="btn btn-link">' . ($withholdingTax->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                    return $actions;
                })
                ->rawColumns(['tax', 'status', 'logs', 'actions'])
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
            'tax' => 'required',
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = WithholdingTax::create($data);
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
        $data = WithholdingTax::find($id);

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
     * @param WithholdingTax $withholdingTax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WithholdingTax $withholdingTax)
    {
        $request->validate([
            'description' => 'required',
            'tax' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $withholdingTax->update($data);
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
     * @param WithholdingTax $withholdingTax
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(WithholdingTax $withholdingTax)
    {
        $result = $withholdingTax->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param WithholdingTax $withholdingTax
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(WithholdingTax $withholdingTax)
    {
        $status = $withholdingTax->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $withholdingTax->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $withholdingTax->update([
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
     * Get all withholding taxes from resource storage
     *
     * @return mixed
     */
    public function get_withholding_taxes()
    {
        $withholdingTaxes = WithholdingTax::where('disabled', '=', 'N')
            ->orderBy('tax')
            ->selectRaw('id as value, CONVERT(varchar(20), tax) + \'%\' as text')
            ->get();

        return $withholdingTaxes;
    }
}
