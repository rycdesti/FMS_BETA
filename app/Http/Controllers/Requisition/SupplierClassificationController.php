<?php

namespace App\Http\Controllers\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Requisition\SupplierClassification;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierClassificationController extends Controller
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
            $supplierClassifications = SupplierClassification::get();

            if ($status_filter) {
                $supplierClassifications = $supplierClassifications->where('disabled', $status_filter);
            }

            return DataTables::of($supplierClassifications)
                ->editColumn('status', function (SupplierClassification $supplierClassification) {
                    if ($supplierClassification->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $supplierClassification->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $supplierClassification->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (SupplierClassification $supplierClassification) {
                    return '<div>' . $supplierClassification->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $supplierClassification->created_at->diffForHumans() . '</div><br>' .
                        ($supplierClassification->last_modified ? '<div>' . $supplierClassification->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $supplierClassification->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (SupplierClassification $supplierClassification) {
                    return '<button id="btn-edit" data-id="' . $supplierClassification->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>' .
                        ($supplierClassification->suppliers->count() ? '<hr>' : '<button id="btn-delete" data-id="' . $supplierClassification->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>') .
                        '<button id="btn-update-status" data-id="' . $supplierClassification->id . '" type="button" class="btn btn-link">' . ($supplierClassification->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
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
            'description' => 'required'
        ]);

        $description = $request['description'];
        $supplier_initials = '';
        $array_supplier_name = explode(' ', trim(strtoupper($description)));

        if (count($array_supplier_name) > 1) {
            foreach ($array_supplier_name as $key => $value) {
                if (strlen(trim($value)) >= 1) {
                    $supplier_initials .= $value[0];
                }
            }
        } else {
            $supplier_initials = trim(strtoupper($description));
        }
        $code = substr($supplier_initials, 0, 3) . date('ymdHis');

        $request['classification_code'] = $code;
        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = SupplierClassification::create($data);
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
        $data = SupplierClassification::find($id);

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
     * @param SupplierClassification $supplierClassification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierClassification $supplierClassification)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $supplierClassification->update($data);
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
     * @param SupplierClassification $supplierClassification
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(SupplierClassification $supplierClassification)
    {
        $result = $supplierClassification->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param SupplierClassification $supplierClassification
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(SupplierClassification $supplierClassification)
    {
        $status = $supplierClassification->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $supplierClassification->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $supplierClassification->update([
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
        $supplierClassifications = SupplierClassification::all();

        try {
            $pdf = PDF::loadView('reports.requisition.supplier_classification', compact('supplierClassifications'));
            return $pdf->stream('report_req_supplier_classification_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
