<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Description</th>
        <th>Status</th>
    </tr>

    @foreach($accountCategories as $accountCategory)
        @php(extract($accountCategory->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $description }}</td>
            <td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
