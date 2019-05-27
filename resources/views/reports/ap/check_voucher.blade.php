@php($monthlyPaymentController = new \App\Http\Controllers\Ap\MonthlyPaymentController())

<head>
    <title>Check Voucher</title>
</head>

<html>

<body>
<table cellspacing="0" width="100%">
    <tr>
        <td rowspan="2" width="10%" style="padding-bottom: 35px;">
            <img src="{{ public_path() . '/images/tip_logo.png' }}" width="80" height="80"/>
        </td>
        <td rowspan="2" width="60%" style="text-align: center">
            <div style="font-size:15pt; font-weight: bold;">TECHNOLOGICAL INSTITUTE OF THE PHILIPPINES</div>
            <div style="font-size: 12pt;">MANILA</div>
            <div style="font-size: 14pt; font-weight: bold">CHECK VOUCHER</div>
        </td>
        <td style="padding-bottom: 25px;"></td>
    </tr>

    <tr>
        <td rowspan="2" width="100%">
            <table style="float: right;">
                <tr>
                    <td colspan="2" style="text-align: right;padding-bottom: 20px;">TIPM-FIN-001</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td style="text-align: right;"><u><b>{{ $monthlyPayment->voucher->check_date }}</b></u></td>
                </tr>
                <tr>
                    <td>Check No.</td>
                    <td style="text-align: right;"><u><b>{{ $monthlyPayment->voucher->check->check_no }}</b></u></td>
                </tr>
                <tr>
                    <td>Check Voucher No.</td>
                    <td style="text-align: right;"><u><b>{{ $monthlyPayment->voucher->voucher_no }}</b></u></td>
                </tr>
                <tr>
                    <td>Posted on Ledger No.</td>
                    <td style="text-align: right;"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>PAYEE:</td>
        <td><u><b>{{ $monthlyPayment->supplier->check_name }}</b></u></td>
    </tr>
</table>

<table cellpadding="5" cellspacing="0" width="100%" style="margin-top: 10px;">
    <tr>
        <td width="85%" colspan="3"
            style="text-align:center; border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid;">
            DESCRIPTION
        </td>
        <td width="15%" style="text-align:center; border-top: 1px solid; border-bottom: 1px solid;">AMOUNT</td>
    </tr>
    <tr>
        <td colspan="3" style="height: 150px; border-right: 1px solid;">{{ $monthlyPayment->voucher->explanation }}</td>
        <td style="text-align: center;">{{ $monthlyPayment->voucher->amount }}</td>
    </tr>
    <tr>
        <td width="35%"
            style="text-align:center; border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid;">ACCOUNT
            TITLE
        </td>
        <td width="15%"
            style="text-align:center; border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid;">DEBIT
        </td>
        <td width="35%"
            style="text-align:center; border-top: 1px solid; border-bottom: 1px solid; border-right: 1px solid;">ACCOUNT
            TITLE
        </td>
        <td width="15%" style="text-align:center; border-top: 1px solid; border-bottom: 1px solid;">CREDIT</td>
    </tr>

    @for($key = 0; $key <= 20; $key++)
        <tr>
            @if(isset($debitDistribution[$key]))
                @if($debitDistribution[$key]['typical_balance'] == 'D')
                    <td style="border-right: 1px solid;">{{ $debitDistribution[$key]['chart_of_account']['acct_code'] }}</td>
                    <td style="text-align:center; border-right: 1px solid;">{{ $debitDistribution[$key]['amount'] }}</td>
                @endif
            @else
                <td style="border-right: 1px solid; height: 28px"></td>
                <td style="border-right: 1px solid;"></td>
            @endif

            @if(isset($creditDistribution[$key]))
                @if($creditDistribution[$key]['typical_balance'] == 'C')
                    <td style="border-right: 1px solid;">{{ $creditDistribution[$key]['chart_of_account']['acct_code'] }}</td>
                    <td style="text-align:center;">{{ $creditDistribution[$key]['amount'] }}</td>
                @endif
            @else
                <td style="border-right: 1px solid; height: 28px"></td>
                <td></td>
            @endif
        </tr>
    @endfor
    <tr>
        <td colspan="4" style="border-top: 1px solid;"></Td>
    </tr>
</table>

<table cellspacing="0" width="100%" style="margin-top: 10px;">
    <tr>
        <td width="55%" style="border-top: 1px solid; padding-top: 10px; vertical-align: top;">
            RECEIVED from the TECHNOLOGICAL INSTITUTE OF THE
            <br>PHILIPPINES the sum of PESOS
            <u><b><i>{{ $monthlyPaymentController->convertNumberToWords($monthlyPayment->voucher->amount) }}</i></b></u>
            in payment to the above mentioned name.
        </td>

        <td width="45%" style="border-top: 1px solid; padding-top: 10px; padding-left: 10px;">
            <table cellspacing="0">
                <tr>
                    <td width="30%">Received by</td>
                    <td width="70%" style="text-align: center;padding-top: 15px;">
                        ______________________________________<br>Signature over Printed Name
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table cellspacing="0" width="100%" style="padding-top: 10px;">
                <tr>
                    <td width="70%">
                        <table cellpadding="10" cellspacing="0" width="100%" style="border: 1px solid">
                            <tr>
                                <td width="24%" style="border-right: 1px solid; vertical-align: top;">Prepared by<br>
                                    <br>
                                    <div style="text-align: center;font-weight: bold;">{{ $monthlyPayment->voucher->prepared_by }}</div>
                                </td>
                                <td width="24%" style="border-right: 1px solid; vertical-align: top;">Checked by<br>
                                    <br>
                                    <div style="text-align: center;font-weight: bold;">{{ $monthlyPayment->voucher->checked_by }}</div>
                                </td>
                                <td width="28%" style="border-right: 1px solid; vertical-align: top;">Recommended by<br>
                                    <br>
                                    <div style="text-align: center;font-weight: bold;">{{ $monthlyPayment->voucher->recommended_by }}</div>
                                </td>
                                <td width="24%" style="vertical-align: top;">Approved by<br>
                                    <br>
                                    <div style="text-align: center;font-weight: bold;">{{ $monthlyPayment->voucher->approved_by }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="30%">
                        <table cellspacing="0" style="float: right;">
                            <tr>
                                <td style="padding-left: 12px;">Date</td>
                                <td style="padding-left: 5px;">_______________</td>

                            </tr>
                            <tr>
                                <td style="padding-left: 12px;">Amount Receipted</td>
                                <td style="padding-left: 5px;">_______________</td>
                            </tr>
                            <tr>
                                <td style="padding-left: 12px;">TIP OR No.</td>
                                <td style="padding-left: 5px;">_______________</td>
                            </tr>
                            <tr>
                                <td style="padding-left: 12px;">Date of TIP OR</td>
                                <td style="padding-left: 5px;">_______________</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>

<footer style="text-align: right; margin-top: 10px;">
    <span style="border: 1px solid; padding: 5px;">Revision Status/Date: 2/2003 FEB 03</span>
</footer>

</html>