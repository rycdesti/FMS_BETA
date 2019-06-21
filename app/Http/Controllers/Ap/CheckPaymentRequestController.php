<?php

namespace App\Http\Controllers\Ap;

use App\Models\Ap\CheckPaymentRequest;
use App\Models\Requisition\PaymentTerm;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CheckPaymentRequestController extends Controller
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
            $checkPaymentRequests = CheckPaymentRequest::get();

            return DataTables::of($checkPaymentRequests)
                ->editColumn('payment_request_details', function (CheckPaymentRequest $checkPaymentRequest) {
                    $pr_details = '<div>Amount: ' . $checkPaymentRequest->amount . '</div>';
                    $pr_details .= '<div>Request Date: ' . $checkPaymentRequest->request_date . '</div>';
                    $pr_details .= '<div>Requested By: ' . $checkPaymentRequest->requested_by . '</div>';
                    return $pr_details;
                })
                ->editColumn('supplier_info', function (CheckPaymentRequest $checkPaymentRequest) {
                    $s_info = '';
                    if ($checkPaymentRequest->supplier_id) {
                        $s_info .= '<div>Pay To: ' . $checkPaymentRequest->supplier->check_name . '</div>';
                    } else {
                        $s_info .= '<div>Pay To: ' . $checkPaymentRequest->supplier_name . '</div>';
                    }
                    return $s_info;
                })
                ->editColumn('particulars', function (CheckPaymentRequest $checkPaymentRequest) {
                    return $checkPaymentRequest->particulars;
                })
                ->editColumn('logs', function (CheckPaymentRequest $checkPaymentRequest) {
                    return '<div>' . $checkPaymentRequest->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $checkPaymentRequest->created_at->diffForHumans() . '</div><br>' .
                        ($checkPaymentRequest->last_modified ? '<div>' . $checkPaymentRequest->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $checkPaymentRequest->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (CheckPaymentRequest $checkPaymentRequest) {
                    $actions = '<button id="btn-print-check-payment" data-id="' . $checkPaymentRequest->id . '" type="button" class="btn btn-link">Print Check Payment</button>';
                    return $actions;
                })
                ->rawColumns(['payment_request_details', 'supplier_info', 'particulars', 'logs', 'actions'])
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
            'pay_to' => 'required',
            'amount' => 'required',
            'particulars' => 'required',
        ];

        if ($request['pay_to'] == 'S') {
            $validate = array_merge($validate, [
                'supplier_id' => 'required',
            ]);
        } else if ($request['pay_to'] == 'O') {
            $validate = array_merge($validate, [
                'supplier_name' => 'required',
            ]);
        }

        $request->validate($validate);

        $request['request_date'] = now();
        $request['logs'] = 'Created by: Test';
        $request['requested_by'] = 'Test';
        $request['status'] = 'O';
        $data = $request->all();

        $result = CheckPaymentRequest::create($data);
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
     * Get all get payment terms from resource storage
     *
     * @return mixed
     */
    public function get_payment_terms()
    {
        $paymentTerms = PaymentTerm::where('disabled', '=', 'N')
            ->orderBy('payment_term_name')
            ->get([
                'id as value',
                'payment_term_name as text'
            ]);

        return $paymentTerms;
    }

    public function generatePDFReport(){
        try {
            $pdf = SnappyPdf::loadView('reports.ap.check_preparation_request', array());
            $pdf->setPaper('A6');
            return $pdf->stream('report_chk_payment_req_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
