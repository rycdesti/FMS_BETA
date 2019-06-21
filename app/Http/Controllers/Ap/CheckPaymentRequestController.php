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
                        $s_info .= '<div>Payee: ' . $checkPaymentRequest->supplier->check_name . '</div>';
                    } else {
                        $s_info .= '<div>Payee: ' . $checkPaymentRequest->supplier_name . '</div>';
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

    public function generateCheckPaymentPDF($id) {
        $checkPaymentRequest = CheckPaymentRequest::find($id);

        try {
            $pdf = SnappyPdf::loadView('reports.ap.check_preparation_request', compact('checkPaymentRequest'));
            return $pdf->setPaper('A6')
                ->setOption('margin-bottom', 0)
                ->setOption('margin-right', 2)
                ->setOption('margin-left', 2)
                ->setOption('margin-top', 4)
                ->stream('report_check_payment_request_' . date('Y_m_d_h_i_s', strtotime(now())) . '.pdf');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public static function convertNumberToWords($number)
    {

        $hyphen = ' ';
        $conjunction = ' ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion',
            1000000000000 => 'Trillion',
            1000000000000000 => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . (new self)->ConvertNumberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

                if ($remainder) {
                    $string .= $conjunction . (new self)->convertNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = (new self)->convertNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= (new self)->convertNumberToWords($remainder);
                }
                break;
        }

        return $string;
    }
}
