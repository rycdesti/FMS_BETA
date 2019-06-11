@php($reccuringPaymentController = new \App\Http\Controllers\Ap\RecurringPaymentController())
<html>

<body>
<table cellpadding="5" width="100%" border="1">
    <tr>
        <th>Supplier Name</th>
        <th>Supplier Information</th>
        <th>Duration</th>
        <th>Frequency</th>
        <th>Status</th>
    </tr>

    @foreach($recurringPayments as $recurringPayment)
        @php(extract($recurringPayment->toArray()))

        <tr style="vertical-align: top">
            <td width="20%">{{ $recurringPayment->supplier->name }}</td>
            <td>
                @php($contactPersons = $recurringPayment->supplier->supplierContacts)
                @foreach($contactPersons as $contactPerson)
                    @php(extract($contactPerson->toArray()))

                    <span>Contact Person: {{ $contact_person }}</span><br>
                    @if($phone_number1)<span>Phone Number 1: {{$phone_number1}}</span><br>@endif
                    @if($phone_number2)<span>Phone Number 2: {{$phone_number2}}</span><br>@endif
                    @if($phone_number3)<span>Phone Number 3: {{$phone_number3}}</span><br>@endif
                    @if($fax_number)<span>Fax Number: {{$fax_number}}</span><br>@endif
                    <br><span>Amount: {{ $amount }}</span>
                @endforeach
            </td>
            <td>
                @if($is_duration == 'Y')
                    <span>From: {{ date('F d, Y', strtotime($duration_from)) }}</span><br>nm
                    <span>To: {{ date('F d, Y', strtotime($duration_from)) }}</span>
                @else
                    <span>Continuous</span>
                @endif
            </td>
            <td>
                @php($recurringPaymentDates = $recurringPayment->recurringPaymentDates)
                @foreach($recurringPaymentDates as $recurringPaymentDate)
                    @php(extract($recurringPaymentDate->toArray()))

                    @if($frequency == 'W')
                        <div class="text-center font-weight-bold">Every {{ $reccuringPaymentController->get_frequency('week', $weekday) }}</div><br>
                    @elseif ($frequency == 'M')
                        <div class="text-center font-weight-bold">{{ $reccuringPaymentController->ConvertToOrdinal($day) }} of the Month</div><br>
                    @elseif ($frequency == 'Q')
                        @php($frequency_type_desc = $reccuringPaymentController->get_frequency('quarter', $frequency_type))
                        <div class="text-center">{{ $frequency_type_desc }}</div>
                        <div class="text-center font-weight-bold">{{ $reccuringPaymentController->ConvertToOrdinal($day) }} of
                            {{ $reccuringPaymentController->get_frequency('month', $month) }}
                        </div><br/>
                    @elseif ($frequency == 'S')
                        @php($frequency_type_desc = $reccuringPaymentController->get_frequency('semester', $frequency_type))
                        <div class="text-center">{{ $frequency_type_desc }}<br/></div>
                        <div class="text-center font-weight-bold">{{ $reccuringPaymentController->ConvertToOrdinal($day) }} of
                            {{ $reccuringPaymentController->get_frequency('month', $month) }}
                        </div><br/>
                    @elseif ($frequency == 'A')
                        <div class="text-center font-weight-bold"> {{ $reccuringPaymentController->ConvertToOrdinal($day) }} of
                            {{ $reccuringPaymentController->get_frequency('month', $month) }}
                        </div><br>
                    @endif
                @endforeach

            </td>
            <td width="15%">{!! $disabled == 'N' ? 'Enabled' : 'Disabled<br>'.$disabled_by.'<br>'.date('F d, Y', strtotime($date_disabled)) !!}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
