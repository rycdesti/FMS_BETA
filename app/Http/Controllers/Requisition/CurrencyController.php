<?php

namespace App\Http\Controllers\Requisition;

use App\Models\Requisition\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->isRequestTypeDatatable(request())) {
            $currencies = Currency::all();
            return DataTables::of($currencies)
                ->editColumn('status', function (Currency $currency) {
                    if ($currency->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $currency->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $currency->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (Currency $currency) {
                    return '<div>' . $currency->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $currency->created_at->diffForHumans() . '</div><br>' .
                        ($currency->last_modified ? '<div>' . $currency->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $currency->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (Currency $currency) {
                    return '<button id="btn-edit" data-id="' . $currency->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>' .
                        ($currency->suppliers->count() ? '<hr>' : '<button id="btn-delete" data-id="' . $currency->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>') .
                        '<button id="btn-update-status" data-id="' . $currency->id . '" type="button" class="btn btn-link">' . ($currency->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
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
        $request->validate([
            'description' => 'required',
            'currency_code' => 'required',
            'symbol' => 'required',
        ]);

        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = Currency::create($data);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Currency::find($id);

        return $data;
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
     * @param  \Illuminate\Http\Request $request
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'description' => 'required',
            'currency_code' => 'required',
            'symbol' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $currency->update($data);
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
     * @param Currency $currency
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Currency $currency)
    {
        $result = $currency->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param Currency $currency
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(Currency $currency)
    {
        $status = $currency->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $currency->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $currency->update([
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
