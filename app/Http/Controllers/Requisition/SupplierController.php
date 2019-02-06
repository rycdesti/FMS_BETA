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
     */
    public function index()
    {
        if ($this->isRequestTypeDatatable(request())) {
        $suppliers = Supplier::all();
        return DataTables::of($suppliers)
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
                    ($supplier->suppliers->count() ? '<hr>' : '<button id="btn-delete" data-id="' . $supplier->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>') .
                    '<button id="btn-update-status" data-id="' . $supplier->id . '" type="button" class="btn btn-link">' . ($supplier->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get all supplier classifications from storage
     *
     * @return mixed
     */
    public function get_supplier_classification() {
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
    public function get_currency() {
        $currencies = Currency::where('disabled', '=', 'N')
            ->orderBy('description')
            ->get([
                'id as value',
                'description as text'
            ]);

        return $currencies;
    }
}
