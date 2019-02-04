<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\BankAccount;
use App\Models\Ap\Check;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CheckController extends Controller
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
        if ($this->isRequestTypeDatatable(request())) {
            $checks = Check::select('bank_account_id', 'check_from', 'check_to', 'logs', 'created_at')
                ->where('bank_account_id', '=', $id)
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
                ->editColumn('actions', function (Check $check) {
                    return '<button id="btn-delete" data-id="' . $check->check_from . '-' . $check->check_to . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>
                            <button id="btn-view-check" data-id="' . $check->check_from . '-' . $check->check_to . '" type="button" class="btn btn-link">View Check Booklet</button><br>';
                })
                ->rawColumns(['acct_no', 'logs', 'actions'])
                ->make(true);
        } else {
            $data = Check::find($id);

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
            $checks = Check::where(['bank_account_id' => $sequence_[0], 'check_from' => $sequence_[1], 'check_to' => $sequence_[2]])->get();
            return DataTables::of($checks)
                ->editColumn('status', function (Check $check) {
                    $status = $check->voided == 'N' ? '<span>Blank Check</span>' :
                        $status = $check->voucher_no ? '<span>Issued Check</span>' : '<span>Voided Check</span>';
                    return $status;
                })
                ->editColumn('actions', function (Check $check) {
                    return $check->voided == 'N' ? '<button id="btn-void-check" data-id="' . $check->id . '" type="button" class="btn btn-link">Void Check</button>' : '';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }
    }
}
