<?php

namespace App\Http\Controllers\Requisition;

use App\Models\Requisition\Currency;
use App\Models\Requisition\Supplier;
use App\Models\Requisition\SupplierClassification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
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
            $suppliers = Supplier::all();
            return DataTables::of($suppliers)
                ->editColumn('supplier_information', function (Supplier $supplier) {
                    return '<div>Supplier Code: ' . $supplier->supplier_code . '</div>
                            <div>Classification: ' . $supplier->supplierClassification->description . '</div>
                            <div>Address: ' . $supplier->address . ' ' . $supplier->city . ', ' . $supplier->state . ', ' . $supplier->country . '</div>
                            <div>Currency: ' . $supplier->currency->description . '</div>';
                })
                ->editColumn('status', function (Supplier $supplier) {
                    if ($supplier->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $supplier->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $supplier->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (Supplier $supplier) {
                    return '<div>' . $supplier->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $supplier->created_at->diffForHumans() . '</div><br>' .
                        ($supplier->last_modified ? '<div>' . $supplier->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $supplier->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (Supplier $supplier) {
                    return '<button id="btn-edit" data-id="' . $supplier->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>' .
                        ($supplier->recurringPayments()->count() ? '<hr>' : '<button id="btn-delete" data-id="' . $supplier->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>') .
                        '<button id="btn-contact" data-id="' . $supplier->id . '" type="button" class="btn btn-link">Manage Contacts</button><br>
                         <button id="btn-update-status" data-id="' . $supplier->id . '" type="button" class="btn btn-link">' . ($supplier->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                })
                ->rawColumns(['supplier_information', 'status', 'logs', 'actions'])
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
            'name' => 'required',
            'check_name' => 'required',
            'supplier_classification_id' => 'required',
            'currency_id' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
        ]);

        $supplier_name = $request['name'];
        $supplier_initials = '';
        $array_supplier_name = explode(' ', trim(strtoupper($supplier_name)));

        if (count($array_supplier_name) > 1) {
            foreach ($array_supplier_name as $key => $value) {
                if (strlen(trim($value)) >= 1) {
                    $supplier_initials .= $value[0];
                }
            }
        } else {
            $supplier_initials = trim(strtoupper($supplier_name));
        }
        $supplier_code = substr($supplier_initials, 0, 3) . date('ymdHis');

        $request['supplier_code'] = $supplier_code;
        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = Supplier::create($data);
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
        $data = Supplier::find($id);

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
     * @param Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required',
            'check_name' => 'required',
            'supplier_classification_id' => 'required',
            'currency_id' => 'required',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $supplier->update($data);
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
     * @param Supplier $supplier
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Supplier $supplier)
    {
        $result = $supplier->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param Supplier $supplier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(Supplier $supplier)
    {
        $status = $supplier->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $supplier->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $supplier->update([
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
     * Get all supplier classifications from storage
     *
     * @return mixed
     */
    public function get_supplier_classification()
    {
        $supplierClassifications = SupplierClassification::where('disabled', '=', 'N')
            ->orderBy('description')
            ->get([
                'id as value',
                'description as text'
            ]);

        return $supplierClassifications;
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
