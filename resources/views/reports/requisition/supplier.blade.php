<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Supplier Name</th>
        <th>Supplier Information</th>
        <th>Status</th>
    </tr>

    @foreach($suppliers as $supplier)
        @php(extract($supplier->toArray()))

        <tr style="vertical-align: top">
            <td width="35%">{{ $name }}</td>
            <td width="50%">
                <span>Supplier Code: {{ $supplier_code }}</span><br>
                <span>Classification: {{ $supplier->supplierClassification->description }}</span><br>
                <span>Address: {{ $address }}</span><br>
                <span>Currency: {{ $supplier->currency->description }}</span>
            </td>
            <td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
