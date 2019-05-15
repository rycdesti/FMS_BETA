<html>

<body>
<table cellpadding="5" width="100%" border="1">
    @php($keys = array_keys(array_diff_key($supplierContacts[0], array_flip(['logs', 'last_modified', 'created_at', 'updated_at']))))
    <tr>
        @foreach($keys as $key)
            <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
        @endforeach
    </tr>

    @foreach($supplierContacts as $supplierContact)
        <tr>
            @foreach($keys as $key)
                <td>{{ $supplierContact[$key] }}</td>
            @endforeach
        </tr>
    @endforeach
</table>
</body>
</html>
