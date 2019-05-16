<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Bank Name</th>
        <th>Bank Code</th>
        <th>Status</th>
    </tr>

    @foreach($banks as $bank)
        @php(extract($bank->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $bank_name }}</td>
            <td>{{ $bank_code }}</td>
            <td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
