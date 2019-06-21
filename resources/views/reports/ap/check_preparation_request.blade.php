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
    .label {
        vertical-align: top;
    }

    table {
        font-size: 10pt;
    }

    textarea {
        width: 100%;
        text-align: center;
    }
</style>

<table width="100%" cellpadding="0" border="0">
    <tr>
        <td colspan="3" style="text-align: right; padding-bottom: 7px">TIPQ-FIN-1008</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center">
            <span style="font-size: 1em;">TECHNOLOGICAL INSTITUTE OF THE PHILIPPINES</span>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="text-align: center">
            <span style="font-size: .83em;">QUEZON CITY</span>
        </td>
    </tr>

    <tr>
        <td colspan="3" style="text-align: center; padding-bottom: 15px">
            <span style="text-decoration: underline; font-size: 1.17em; font-weight: bold;">REQUEST FOR CHECK PREPARATION FORM</span>
        </td>
    </tr>

    <tr>
        <td width="20%"></td>
        <td width="45%" style="text-align: right">Date</td>
        <td width="40%">
            <textarea>April 10, 2019</textarea>
        </td>
    </tr>
    <tr>
        <td class="label" width="27%">PAYEE</td>
        <td colspan="2">
            <textarea>Allan D. Go</textarea>
        </td>
    </tr>
    <tr>
        <td class="label">PARTICULARS</td>
        <td colspan="2">
            <textarea style="line-height: 50px;">Mar. 01, 2019 PayApp and TIP WebSite Hosting</textarea>
        </td>
    </tr>
    <tr>
        <td class="label">Amount</td>
        <td colspan="2">
            <textarea>PHP 11,029.04</textarea>
        </td>
    </tr>
    <tr>
        <td class="label">Amount in words</td>
        <td colspan="2">
            <textarea>Eleven Thousand Twenty Nine Pesos and Four Cents</textarea>
        </td>
    </tr>
    <tr>
        <td class="label" style="padding-top: 20px;">Requested by</td>
        <td colspan="2" width="100%" style="padding-top: 20px;">
            <div style="border-bottom: 1px solid black; width: 100%; text-align: center">Allan D. Go</div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center">Name in Print./Signature</td>
    </tr>
    <tr>
        <td class="label" style="padding-top: 20px;">Adm. Officer/s</td>
        <td colspan="2" width="100%" style="padding-top: 20px;">
            <div style="border-bottom: 1px solid black; width: 100%; text-align: center">Allan D. Go</div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="text-align: center">Signature/s</td>
    </tr>
    <tr>
        <td class="label" style="padding-top: 20px">Approved</td>
        <td colspan="2" style="padding-top: 20px; border-bottom: 1px solid black"></td>
    </tr>
</table>
</body>

<footer style="text-align: right; margin-top: 50px;">
    <span style="border: 1px solid; padding: 5px;">Revision Status/ Date:1/19 Feb: 2003</span>
</footer>
</html>
