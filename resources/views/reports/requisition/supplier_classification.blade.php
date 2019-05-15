<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Description</th>
        <th>Classification Code</th>
        <th>Status</th>
    </tr>

    @foreach($supplierClassifications as $supplierClassification)
        @php(extract($supplierClassification->toArray()))

        <tr style="vertical-align: top">
            <td>{{ $description }}</td>
            <td>{{ $classification_code }}</td>
            <td width="20%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
