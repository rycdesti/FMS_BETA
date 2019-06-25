@php($checkPaymentController = new \App\Http\Controllers\Ap\CheckPaymentRequestController())

<head>
    <title>Check Payment Request</title>
</head>

<html>
<body style="border: 1px solid black">

<style>
    .label {
        vertical-align: top;
    }

    table {
        font-size: 10pt;
    }

    textarea {
        width: 100%;
        text-align: center;
    }
</style>

<table width="100%" cellpadding="0" border="0">
    <tr>
        <td colspan="3" style="text-align: right; padding-bottom: 7px">TIPQ-FIN-1008</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center">
            <span style="font-size: 1em;">TECHNOLOGICAL INSTITUTE OF THE PHILIPPINES</span>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="text-align: center">
            <span style="font-size: .83em;">QUEZON CITY</span>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="text-align: center; padding-bottom: 15px">
            <span style="text-decoration: underline; font-size: 1.17em; font-weight: bold;">REQUEST FOR CHECK PREPARATION FORM</span>
        </td>
    </tr>

    <tr>
        <td width="20%"></td>
        <td width="45%" style="text-align: right">Date</td>
        <td width="40%">
            <textarea>{{ $checkPaymentRequest->request_date }}</textarea>
        </td>
    </tr>
    <tr>
        <td class="label" width="27%">PAYEE</td>
        <td colspan="2">
            <textarea>{{ $checkPaymentRequest->supplier_id ? $checkPaymentRequest->supplier->check_name : $checkPaymentRequest->supplier_name }}</textarea>
        </td>
    </tr>
    <tr>
        <td class="label">PARTICULARS</td>
        <td colspan="2" style="height: 120px; border: 1px solid; padding: 5px; vertical-align: top;">{!! $checkPaymentRequest->particulars !!} </td>
    </tr>
    <tr>
        <td class="label">Amount</td>
        <td colspan="2">
            <textarea>PHP {{ number_format($checkPaymentRequest->amount, 2) }}</textarea>
        </td>
    </tr>
    <tr>
        <td class="label">Amount in words</td>
        <td colspan="2">
            <textarea>{{ $checkPaymentController->convertNumberToWords($checkPaymentRequest->amount) }}</textarea>
        </td>
    </tr>
    <tr>
        <td class="label" style="padding-top: 20px;">Requested by</td>
        <td colspan="2" width="100%" style="padding-top: 20px;">
            <div style="border-bottom: 1px solid black; width: 100%; text-align: center">{{ $checkPaymentRequest->requested_by }}</div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center">Name in Print./Signature</td>
    </tr>
    <tr>
        <td class="label" style="padding-top: 20px;">Adm. Officer/s</td>
        <td colspan="2" width="100%" style="padding-top: 20px;">
            <div style="border-bottom: 1px solid black; width: 100%; text-align: center">{{ $checkPaymentRequest->requested_by }}</div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center">Signature/s</td>
    </tr>
    <tr>
        <td class="label" style="padding-top: 20px">Approved</td>
        <td colspan="2" style="padding-top: 20px; border-bottom: 1px solid black"></td>
    </tr>
</table>

<div style="border: 1px solid black; float: right; position: absolute; bottom: 20px; right: 12px;">
<span style="padding: 5px;">Revision Status/ Date:1/19 Feb: 2003</span>
</div>
</body>
</html>
