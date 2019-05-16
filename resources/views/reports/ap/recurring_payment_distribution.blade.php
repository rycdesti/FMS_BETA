<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Account Code</th>
        <th>Description</th>
        <th>Debit</th>
        <th>Credit</th>
    </tr>

    @foreach($recurringPaymentDistributions as $recurringPaymentDistribution)
        @php(extract($recurringPaymentDistribution->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $recurringPaymentDistribution->chartOfAccount->acct_code }}</td>
            <td>{{ $recurringPaymentDistribution->chartOfAccount->description }}</td>

            @if($typical_balance == 'D')
                <td>{{ number_format($amount, 2) }}</td>
                <td>0</td>
            @else
                <td>0</td>
                <td>{{ number_format($amount, 2) }}</td>
            @endif

        </tr>
    @endforeach
</table>
</body>
</html>
