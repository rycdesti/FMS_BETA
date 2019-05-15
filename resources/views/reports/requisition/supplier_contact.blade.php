<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Contact Name</th>
        <th>Contact Information</th>
    </tr>

    @foreach($supplierContacts as $supplierContact)
        @php(extract($supplierContact->toArray()))
        <tr style="vertical-align: top">
            <td>{{ $supplierContact->contact_person }}</td>
            <td>
                @if($phone_number1)<span>Phone Number 1: {{ $phone_number1 }}</span>@endif
                @if($phone_number2)<br><span>Phone Number 2: {{ $phone_number2 }}</span>@endif
                @if($phone_number3)<br><span>Phone Number 3: {{ $phone_number3 }}</span>@endif
                @if($fax_number)<br><span>Fax Number: {{ $fax_number }}</span>@endif
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
