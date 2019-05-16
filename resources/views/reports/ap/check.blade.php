<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Account Number</th>
        <th>Check Sequence From</th>
        <th>Check Sequence To</th>
    </tr>

    @foreach($checks as $check)
        @php(extract($check->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $check->bankAccount->acct_no }}</td>
            <td>{{ $check_from }}</td>
            <td>{{ $check_to }}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
