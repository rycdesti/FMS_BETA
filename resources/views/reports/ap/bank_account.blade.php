<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Bank Address</th>
        <th>Account Information</th>
        <th>Status</th>
    </tr>

    @foreach($bankAccounts as $bankAccount)
        @php(extract($bankAccount->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $bank_address }}</td>
            <td>
                <span>Account Code: {{ $acct_code }}</span><br>
                <span>Account Number: {{ $acct_no }}</span><br>
                <span>Account Type: {{ array('S' => 'Savings', 'C' => 'Cheque In')[$acct_type] }}</span><br>
                <span>Currency: {{ $bankAccount->currency->description }}</span><br>
                <span>Beginning Balance: {{ number_format($beginning_balance, 2) }}</span>
            </td>
            <td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
