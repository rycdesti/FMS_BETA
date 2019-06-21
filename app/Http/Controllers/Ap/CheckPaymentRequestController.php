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
        if($this->isRequestTypeDatatable(request())) {
            $checkPaymentRequests = CheckPaymentRequest::get();

            return DataTables::of($checkPaymentRequests)
                ->editColumn('payment_request_details', function(CheckPaymentRequest $checkPaymentRequest) {
                    return '';
                })
                ->editColumn('supplier_info', function(CheckPaymentRequest $checkPaymentRequest) {
                    return '';
                })
                ->editColumn('particulars', function(CheckPaymentRequest $checkPaymentRequest) {
                    return '';
                })
                ->editColumn('logs', function (CheckPaymentRequest $checkPaymentRequest) {
                    return '<div>' . $checkPaymentRequest->logs . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $checkPaymentRequest->created_at->diffForHumans() . '</div><br>' .
                        ($checkPaymentRequest->last_modified ? '<div>' . $checkPaymentRequest->last_modified . '</div>
                            <div><i class="fa fa-clock-o pr-1"></i>' . $checkPaymentRequest->updated_at->diffForHumans() . '</div>' : '');
                })
                ->editColumn('actions', function (CheckPaymentRequest $checkPaymentRequest) {
                    return '';
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        var_dump($request->all());
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
     * Get all get payment terms from resource storage
     *
     * @return mixed
     */
    public function get_payment_terms() {
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
