<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Account Code</th>
        <th>Information Code</th>
        <th>Status</th>
    </tr>

    @foreach($chartOfAccounts as $chartOfAccount)
        @php(extract($chartOfAccount->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $acct_code }}</td>
            <td>
                <span>Description: {{ $description }}</span><br>
                <span>Account Category: {{ $chartOfAccount->accountCategory->description }}</span><br>
                <span>Posting Type: {{ array('B' => 'Balance Sheet', 'P' => 'Profit And Loss')[$posting_type] }}</span><br>
                <span>Typical Balance: {{ array('C' => 'Credit', 'D' => 'Debit')[$typical_balance] }}</span>
            </td>
            <td width="20%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
