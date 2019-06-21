{{--<html>--}}

{{--<body>--}}
{{--<table cellpadding="5" width="100%" border="1">--}}
    {{--<tr>--}}
        {{--<th>Bank Name</th>--}}
        {{--<th>Bank Code</th>--}}
        {{--<th>Status</th>--}}
    {{--</tr>--}}

    {{--@foreach($banks as $bank)--}}
        {{--@php(extract($bank->toArray()))--}}

        {{--<tr style="vertical-align: top">--}}
            {{--<td>{{ $bank_name }}</td>--}}
            {{--<td>{{ $bank_code }}</td>--}}
            {{--<td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>--}}
        {{--</tr>--}}
    {{--@endforeach--}}
{{--</table>--}}
{{--</body>--}}
{{--</html>--}}

<html>
<body>

<style>
    table {
        font-size: 10pt;
    }

    th {
        text-align: center;
    }

    td {
        text-align: left;
    }
</style>

<table width="100%">
    <tr>
        <td></td>
    </tr>
    <tr>
        <th>
            <h4>TECHNOLOGICAL INSTITUTE OF THE PHILIPPINES</h4>
        </th>
    </tr>
    <tr>
        <th>
            <h5>QUEZON CITY</h5>
        </th>
    </tr>
    <tr>
        <th>
            <h3 STYLE="text-underline: black">REQUEST FOR CHECK PREPARATION FORM</h3>
        </th>
    </tr>
    <tr>
        <td width="50%"></td>
        <td width="10%">Date</td>
        <td width="40%">Date</td>
    </tr>
    <tr>
        <td>PAYEE</td>
        <td>
            <textarea>TESTTESTESTET</textarea>
        </td>
    </tr>
    <tr>
        <td>PARTICULARS</td>
        <td>
            <textarea>TESTTESTESTET</textarea>
        </td>
    </tr>
    <tr>
        <td>Amount</td>
        <td>
            <textarea>TESTTESTESTET</textarea>
        </td>
    </tr>
    <tr>
        <td>Amount in words</td>
        <td>
            <textarea>TESTTESTESTET</textarea>
        </td>
    </tr>
    <tr>
        <td width="25%">Requested by</td>
        <td width="75%">
            <textarea>TESTTESTESTET</textarea>
        </td>
    </tr>
    <tr>
        <td width="25%"></td>
        <td width="75%">Name in Print / Signature</td>
    </tr>
    <tr>
        <td width="25%">Adm. Officer/s</td>
        <td width="75%">
            <textarea>TESTTESTESTET</textarea>
        </td>
    </tr>
    <tr>
        <td width="25%"></td>
        <td width="75%">Name in Print / Signature</td>
    </tr>
    <tr>
        <td width="10%">Approved</td>
        <td width="50%"></td>
    </tr>
    <tr>
        <td width="65%" style="text-align: right">Revision status/ Date:1/19 Feb. 2003</td>
    </tr>
</table>

</body>
</html>
