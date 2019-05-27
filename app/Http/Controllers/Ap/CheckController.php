<?php

namespace App\Http\Controllers\Ap;

use App\Http\Controllers\Controller;
use App\Models\Ap\BankAccount;
use App\Models\Ap\Check;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CheckController extends Controller
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
            $bank_account_filter = request()->bank_account_filter;

            $checks = Check::select('bank_account_id', 'check_from', 'check_to', 'logs', 'created_at')
                ->where('bank_account_id', '=', $bank_account_filter)
                ->groupCheck()
                ->get();
            return DataTables::of($checks)
                ->editColumn('acct_no', function (Check $check) {
                    return '<div>' . $check->bankAccount->acct_no . '</div>';
                })
                ->editColumn('logs', function (Check $check) {
                    return '<div>' . $check->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $check->created_at->diffForHumans() . '</div><br>' .
                        ($check->last_modified ? '<div>' . $check->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $check->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (Check $check) use ($bank_account_filter) {
                    $actions = '';
                    $validate_check = Check::whereHas('vouchers')->where([
                        ['bank_account_id', '=', $bank_account_filter],
                        ['check_from', '=', $check->check_from],
                        ['check_to', '=', $check->check_to],
                    ])->first();

                    if (!isset($validate_check)) {
                        $actions .= '<button id="btn-delete" data-id="' . $check->check_from . '-' . $check->check_to . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>';
                    }
                    $actions .= '<button id="btn-view-check" data-id="' . $check->check_from . '-' . $check->check_to . '" type="button" class="btn btn-link">View Check Booklet</button><br>';
                    return $actions;
                })
                ->rawColumns(['acct_no', 'logs', 'actions'])
                ->make(true);
        } else {
            $data = Check::find(request()->bank_account_filter);

            return $data;
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
            'bank_account_id' => 'required',
            'check_from' => 'required',
            'check_to' => 'required'
        ]);

        $bank_account_id = $request['bank_account_id'];
        $check_from = $request['check_from'];
        $check_to = $request['check_to'];
        $created_at = now();
        $updated_at = now();

        $collection = array();
        $data = array();

        for ($check_no = $check_from; $check_no <= $check_to; $check_no++) {
            array_push($data, array(
                'bank_account_id' => $bank_account_id,
                'check_from' => $check_from,
                'check_to' => $check_to,
                'check_no' => $check_no,
                'logs' => 'Created by: Test',
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ));

            if ($check_no % 100 == 0 || $check_no == $check_to) {
                $collection[] = $data;
                $data = array();
            }

            if (Check::where(['bank_account_id' => $bank_account_id, 'check_no' => $check_no])->exists()) {
                return response()->json(['success' => false, 'message' => 'There was a conflict between the check number sequence. Please try again.'], 500);
            }
        }

        foreach ($collection as $item) {
            $result = Check::insert($item);
            if (!$result) {
                Check::where(['bank_account_id' => $bank_account_id, 'check_from' => $check_from, 'check_to' => $check_to])->delete();
                return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
            }
        }
        return response()->json(['success' => true, 'message' => 'The record was added successfully!']);
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
       //
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
     * @param Check $check
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Check $check)
    {
        $request->validate([
            'remarks' => 'required',
        ]);

        $request['voided'] = 'Y';
        $request['voided_by'] = 'Voided by: Test';
        $request['date_voided'] = now();
        $request['last_modified'] = 'Last modified by: Test';
        $data = $request->all();

        $result = $check->update($data);
        if ($result) {
            /**
             * check for failure of event tag when insert try to rollback (DB rollback)
             * try to check if there is other way to insert multiple record
             */
            return response()->json(['success' => true, 'message' => 'The check was voided successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $sequence
     * @return \Illuminate\Http\Response
     */
    public function destroy($sequence)
    {
        $sequence_ = explode('-', $sequence);
        $result = Check::where(['check_from' => $sequence_[0], 'check_to' => $sequence_[1]])->delete();
        if ($result) {
            return response()->json(['success' => true, 'message' => 'The record was deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Get bank account from storage
     *
     * @param $id
     * @return mixed
     */
    public function get_bank_account($id)
    {
        $bank = BankAccount::find($id);

        return $bank;
    }

    /**
     * Get list of checks
     *
     * @param $sequence
     * @return mixed
     * @throws \Exception
     */
    public function get_check_list($sequence)
    {
        $sequence_ = explode('-', $sequence);

        if ($this->isRequestTypeDatatable(request())) {
            $status_filter = request()->status_filter;
            $checks = Check::where(['bank_account_id' => $sequence_[0], 'check_from' => $sequence_[1], 'check_to' => $sequence_[2]]);

            if($status_filter == 'I') {
                $checks = $checks->whereNotNull('voucher_no')->get();;
            } else if($status_filter == 'N') {
                $checks = $checks->where('voucher_no', null)
                    ->where('voided', $status_filter)->get();;
            } else if($status_filter == 'Y'){
                $checks = $checks->where('voided', $status_filter)->get();;
            } else {
                $checks = $checks->get();
            }

            return DataTables::of($checks)
                ->editColumn('status', function (Check $check) {
                    $status = $check->voided == 'N' && $check->voucher_no == null ? '<span>Blank Check</span>' :
                        $status = $check->voucher_no ? '<span>Issued Check</span>' : '<span>Voided Check</span>';
                    return $status;
                })
                ->editColumn('actions', function (Check $check) {
                    return $check->voided == 'N' && $check->voucher_no == null ? '<button id="btn-void-check" data-id="' . $check->id . '" type="button" class="btn btn-link">Void Check</button>' : '';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
    }

    public function generatePDFReport($id)
    {
        $checks = Check::select('bank_account_id', 'check_from', 'check_to')
            ->where('bank_account_id', '=', $id)
            ->groupCheck()
            ->get();

        try {
            $pdf = PDF::loadView('reports.ap.check', compact('checks'));
            return $pdf->stream('report_req_check_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
