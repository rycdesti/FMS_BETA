<?php

namespace App\Http\Controllers\Requisition;

use App\Http\Controllers\Controller;
use App\Models\Requisition\SupplierContact;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierContactController extends Controller
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
            'contact_person' => 'required',
            'phone_number1' => 'required',
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = SupplierContact::create($data);
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
            $supplierContacts = SupplierContact::where('supplier_id', '=', $id);
            return DataTables::of($supplierContacts)
                ->editColumn('contact_info', function (SupplierContact $supplierContact) {
                    return '<div>Phone number 1: ' . $supplierContact->phone_number1 . '</div>' .
                        ($supplierContact->phone_number2 ? '<div>Phone number 2: ' . $supplierContact->phone_number2 . '</div>' : '') .
                        ($supplierContact->phone_number3 ? '<div>Phone number 3: ' . $supplierContact->phone_number3 . '</div>' : '') .
                        ($supplierContact->fax_number ? '<div>Fax number: ' . $supplierContact->fax_number . '</div>' : '');
                })
                ->editColumn('logs', function (SupplierContact $supplierContact) {
                    return '<div>' . $supplierContact->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $supplierContact->created_at->diffForHumans() . '</div><br>' .
                        ($supplierContact->last_modified ? '<div>' . $supplierContact->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $supplierContact->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (SupplierContact $supplierContact) {
                    return '<button id="btn-edit" data-id="' . $supplierContact->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                            <button id="btn-delete" data-id="' . $supplierContact->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button>';
                })
                ->rawColumns(['contact_info', 'status', 'logs', 'actions'])
                ->make(true);
        } else {
            $data = SupplierContact::find($id);

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
     * @param SupplierContact $supplierContact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupplierContact $supplierContact)
    {
        $request->validate([
            'contact_person' => 'required',
            'phone_number1' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $supplierContact->update($data);
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
     * @param SupplierContact $supplierContact
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(SupplierContact $supplierContact)
    {
        $result = $supplierContact->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    public function generatePDFReport($id)
    {
        $supplierContacts = SupplierContact::where('supplier_id', '=', $id)->get();

        try {
            $pdf = SnappyPdf::loadView('reports.requisition.supplier_contact', compact('supplierContacts'));
            return $pdf->stream('report_req_supplier_contact_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
