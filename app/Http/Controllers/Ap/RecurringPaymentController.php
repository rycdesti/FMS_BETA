<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\RecurringPayment;
use App\Models\Requisition\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class RecurringPaymentController extends Controller
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
            $recurringPayments = RecurringPayment::all();
            return DataTables::of($recurringPayments)
                ->editColumn('supplier_name', function (RecurringPayment $recurringPayment) {
                    return $recurringPayment->supplier->name;
                })
                ->editColumn('supplier_info', function (RecurringPayment $recurringPayment) {
                })
                ->editColumn('frequency', function (RecurringPayment $recurringPayment) {
                })
                ->editColumn('status', function (RecurringPayment $recurringPayment) {
                    if ($recurringPayment->disabled == 'N') {
                        return '<div><span class="badge-primary p-1">Enabled</span></div>';
                    } else {
                        return '<div><span class="badge-danger p-1">Disabled</span></div><br>
                                <div>' . $recurringPayment->disabled_by . '</div>
                                <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPayment->date_disabled->diffForHumans() . '</div>';
                    }
                })
                ->editColumn('logs', function (RecurringPayment $recurringPayment) {
                    return '<div>' . $recurringPayment->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPayment->created_at->diffForHumans() . '</div><br>' .
                        ($recurringPayment->last_modified ? '<div>' . $recurringPayment->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $recurringPayment->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (RecurringPayment $recurringPayment) {
                    return '<button id="btn-edit" data-id="' . $recurringPayment->id . '" title="Edit Record" type="button" class="btn btn-outline-secondary"><i class="fa fa-edit"></i></button>
                            <button id="btn-delete" data-id="' . $recurringPayment->id . '" title="Delete Record" type="button" class="btn btn-outline-danger"><i class="fa fa-trash-o"></i></button><hr>
                            <button id="btn-RecurringPayment-account" data-id="' . $recurringPayment->id . '" type="button" class="btn btn-link">Manage RecurringPayment Accounts</button><br>
                            <button id="btn-update-status" data-id="' . $recurringPayment->id . '" type="button" class="btn btn-link">' . ($recurringPayment->disabled == 'N' ? 'Disable' : 'Enable') . '</button>';
                })
                ->rawColumns(['supplier_name', 'supplier_info', 'frequency', 'status', 'logs', 'actions'])
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
        $validate = [
            'supplier_id' => 'required',
            'remarks' => 'required',
            'amount' => 'required',
            'frequency' => 'required',
        ];

        if ($request['is_duration'] == 'Y') {
            $validate = array_merge($validate, [
                'duration_from' => 'required',
                'duration_to' => 'required',
            ]);
        }

        $request->validate($validate);
//
//        $request['logs'] = 'Created by: Test';
//        $data = $request->all();

//        $result = RecurringPayment::create($data);
//        if ($result) {
//            /**
//             * check for failure of event tag when insert try to rollback (DB rollback)
//             * try to check if there is other way to insert multiple record
//             */
//            return response()->json(['success' => true, 'message' => 'The record was added successfully!']);
//        }
//        return response()->json(['success' => false, 'message' => 'Something went wrong, Please try again.'], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get all suppliers from storage
     */
    public function get_supplier()
    {
        $suppliers = Supplier::where('disabled', '=', 'N')
            ->orderBy('name')
            ->get([
                'id as value',
                'name as text'
            ]);

        return $suppliers;
    }

    /**
     * Get frequency
     *
     * @param null $value
     * @return array|mixed
     */
    public function get_frequency($value = null)
    {
        $days = array();
        for ($count_days = 1; $count_days < 32; $count_days++) {
            $days[$count_days] = $count_days;
        }
        $frequency = array('frequency' => array('W' => 'Weekly', 'M' => 'Monthly', 'Q' => 'Quarterly', 'S' => 'Semestral', 'A' => 'Annual'),
            'week' => array('1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday'),
            'quarter' => array('Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'),
            'semester' => array('SEM1' => '1st Semester', 'SEM2' => '2nd Semester', 'SUMMER' => 'Summer'),
            'month' => array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'),
            'days' => $days);
        if ($value) {
            return $frequency['frequency'][$value];
        } else {
            return $frequency;
        }
    }
}
