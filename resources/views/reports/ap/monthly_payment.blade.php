@php($monthlyPaymentController = new \App\Http\Controllers\Ap\MonthlyPaymentController())

<html>

<body>
<div style="page-break-inside: avoid;">
    <table cellpadding="5" width="100%" border="1">
        <tr>
            <th>Supplier Information</th>
            <th>Remarks</th>
            <th>Due Date</th>
            <th>Remaining Days</th>
        </tr>

        @foreach($monthlyPayments as $monthlyPayment)

            <tr style="vertical-align: top">
                <td width="45%">{!! $monthlyPaymentController->supplierInformation($monthlyPayment) !!}</td>
                <td width="25%">{{ $monthlyPayment->remarks }}</td>
                <td width="20%">{!! date('F d, Y', strtotime($monthlyPayment->date)) . '<br>' . date('l', strtotime($monthlyPayment->date)) !!}</td>
                <td width="10%">{{ $monthlyPayment->remaining_days }}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>
