<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\Bank;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
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
            $banks = Bank::all();
            return DataTables::of($banks)
                ->setRowId('row-{{ $id }}')
                ->editColumn('status', function (Bank $bank) {
                    if ($bank->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $bank->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $bank->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (Bank $bank) {
                    return '<div>' . $bank->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bank->created_at->diffForHumans() . '</div><br>' .
                        ($bank->last_modified ? '<div>' . $bank->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $bank->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (Bank $bank) {
                    $actions = '';
                    if (!$bank->bankAccounts->count()) {
                        $actions .= '<button id="btn-edit" data-id="' . $bank->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                                    <button id="btn-delete" data-id="' . $bank->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>';
                    } else {
                        $actions .= '<button id="btn-edit" data-id="' . $bank->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button><hr>';
                    }
                    $actions .= '<button id="btn-bank-account" data-id="' . $bank->id . '" type="button" class="btn btn-link">Manage Bank Accounts</button><br>
                            <button id="btn-update-status" data-id="' . $bank->id . '" type="button" class="btn btn-link">' . ($bank->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
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
            'bank_name' => 'required',
        ]);

        $filtered_words = array("OF", "THE", "AND");
        $special_char = array('(', ')', '!', '@', '#', '$', '%', '^', '&', '*');

        $bank_initials = '';
        $bank_name = str_replace($special_char, '', request('bank_name'));
        $array_bank_name = explode(" ", strtoupper($bank_name));

        foreach ($array_bank_name as $key => $value) {
            if ($value) {
                if (in_array($value, $filtered_words)) {
                    unset($array_bank_name[$key]);
                } else {
                    if (strlen($bank_initials) < 5) {
                        $bank_initials .= $array_bank_name[$key][0];
                    }
                }
            }
        }
        $bank_prefix = str_pad($bank_initials, 3, $bank_initials[0], STR_PAD_RIGHT);
        $bank_code = substr($bank_prefix, 0, 3) . date('ymdHis');

        $request['bank_code'] = $bank_code;
        $request['bank_prefix'] = $bank_prefix;
        $request['logs'] = 'Created by: Test';
        $data = $request->all();

        $result = Bank::create($data);
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
        $data = Bank::find($id);

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
     * @param Bank $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'bank_name' => 'required',
        ]);

        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $bank->update($data);
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
     * @param Bank $bank
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Bank $bank)
    {
        $result = $bank->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Update the status of specified resource from storage
     *
     * @param Bank $bank
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update_status(Bank $bank)
    {
        $status = $bank->disabled == 'N' ? 'Y' : 'N';

        if ($status == 'Y') {
            $result = $bank->update([
                'disabled' => $status,
                'date_disabled' => now(),
                'disabled_by' => 'Disabled by: Test',
                'last_modified' => 'Last modified by: Test',
            ]);
        } else {
            $result = $bank->update([
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
        $banks = Bank::all();

        try {
            $pdf = PDF::loadView('reports.ap.bank', compact('banks'));
            return $pdf->stream('report_req_bank_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
