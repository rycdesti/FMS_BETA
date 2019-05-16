<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Description</th>
        <th>Currency Code</th>
        <th>Status</th>
    </tr>

    @foreach($currencies as $currency)
        @php(extract($currency->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $description }}</td>
            <td>{{ $currency_code }}</td>
            <td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
