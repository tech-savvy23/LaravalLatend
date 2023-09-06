<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <style>

        table {
            width: 100%;
            overflow: visible;
        }

        table,
        td,
        th,
        tr {
            border-collapse: collapse;
            padding: 15px;
            border: 1px solid grey;
            text-align: center;
            font-size: 20px;
        }

        td p, th p {
            word-wrap: break-word;
        }

        .first-page {
            text-align: center;
            padding: 0 3rem;
            page-break-after: always
        }

        .service-header,
        .space-type-header,
        .address-header,
        .report-header,
        .slogan-header,
        .contact-header {
            font-size: 3rem;
            font-weight: 700;
        }

        .address-header,
        .report-header {
            font-size: 1.5rem;
        }

        .service-header {
            color: #f44718;
        }

        .space-type-header {
            color: #548DD4;
        }

        .contact-header {
            font-size: 1.5rem !important;
        }

        .slogan-header,
        .contact-header {
            color: #E36C0A
        }

        .second-page {
            padding-top: 1rem;
            page-break-after: always
        }

        .second-page .about {
            font-size: 1.3rem;
        }

        .second-page .about strong {
            font-size: 1.5rem;
        }

        .last-page {
            display: -webkit-flex;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding-top: 1rem;
            page-break-before: always;

        }

        .last-page .about {


        }

        .last-page .about ul li {
            font-size: 1.3rem;
        }

        .last-page .about strong {
            font-size: 1.5rem;
        }

        .last-page .hereby {
            bottom: 10px;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .border-none {
            border: none;
        }

        .front-table {
            text-align: left;
            font-size: 18px;
        }

        .about {
            text-align: justify;
            text-justify: inter-word;
            font-size: 18px;
        }

        .text-justify {
            font-size: 18px;
        }


        thead {
            display: table-header-group
        }

        tfoot {
            display: table-row-group
        }

        tr {
            page-break-inside: avoid
        }

    </style>
</head>

<body>
<div>
    <div class="first-page">
        <p class="service-header uppercase">
            {{"{$booking->booking_service->service->name} Audit Report"}}
        </p>
        <p class="space-type-header">
            @php
                $booking_space_type_name = $booking->booking_space->spaceType ? $booking->booking_space->spaceType->name
                : "";
            @endphp
            {{"{$booking_space_type_name} of {$user}"}}
        </p>
        <p class="address-header">
            {{"{$booking->address->body}, {$booking->address->landmark}, {$booking->address->city},
            {$booking->address->state}, {$booking->address->pin} of {$user}"}}
        </p>
        <p class="report-header"> {{"Report ID: {$report_id}-{$booking->id}"}}</p>
        <p>
            <img style="display:inline;height: 250px; width:350px"
                 src="{{ public_path("/images/safetifyme-front.jpg") }}"
                 height="auto"/>
        </p>
        <p class="slogan-header">
            Safety Experts At Your Doorstep
        </p>
        <div style="padding:0px 3.5rem;">
            <table class="border-none ">
                <tbody>
                <tr class="border-none">
                    <td class="front-table border-none">
                        <strong>Date of Booking:</strong> {{$booking->created_at->format('d M,Y H:i A')}} <br>

                    </td>
                    <td class="front-table border-none">
                        <strong>Booking Id:</strong> {{$booking_id.'-'.$booking->id}}
                    </td>
                </tr>

                <tr class="border-none">
                    <td class="front-table border-none">
                        <strong>Date of Schedule Audit:</strong> {{$booking->booking_time->format('d M,Y H:i A')}}
                    </td>
                    <td class="front-table border-none">
                        <strong>Date of Audit:</strong>
                        @if($booking->reports->count() > 0)
                            {{$booking->reports->last()->created_at->format('d M,Y H:i A')}}

                        @elseif($booking->booking_devices->count() > 0)
                            {{$booking->booking_devices->last()->created_at->format('d M,Y H:i A')}}
                        @endif


                    </td>

                </tr>
                <tr class="border-none">
                    <td class="front-table border-none">
                        <strong>Audit Start Time:</strong>
                        @if($booking->reports->count() > 0)
                            {{$booking->reports->first()->created_at->format('H:i A')}}

                        @elseif($booking->booking_devices->count() > 0)
                            {{$booking->booking_devices->first()->created_at->format('H:i A')}}
                        @endif

                    </td>
                    <td class="front-table border-none">
                        <strong>Audit End Time: : </strong>
                        @if($booking->reports->count() > 0)
                            {{$booking->reports->last()->created_at->format('H:i A')}}

                        @elseif($booking->booking_devices->count() > 0)
                            {{$booking->booking_devices->last()->created_at->format('H:i A')}}
                        @endif
                    </td>
                </tr>
                <tr class="border-none">
                    <td class="front-table border-none">
                        <strong> Audit and Report By:</strong> Mr.
                        {{$booking->booking_allottee->where('allottee_type', $partner_type)->where('status', 1)->first()->partner->name}}
                    </td>
                    <td class="front-table border-none">
                        <strong>No. of Observation
                            : </strong> {{$booking->reports->count() + $booking->booking_devices->count()}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <p class="contact-header">
            Toll Free No. 1800 570 0780
        </p>
        <p class="contact-header">
            www.safetifyme.com
        </p>

    </div>
    <div class="second-page">

        <p class="about">
            <strong>&#8226; About SafetifyMe</strong>
            <br>
            SafetifyMe is a location based mobile app that combines the power of technology with real experts at
            your doorstep to find out the defects & flaws in your electrical and fire prone assets at your homes,
            shops, offices, events and make you and your loved ones safe and live well.
        </p>
        @if($booking->booking_service->service_id === 1)
            <p class="about">
                <strong>&#8226; About Fire Safety Audit</strong>
                <br>
                A Fire Safety Audit is a structured and a systematic examination of the workplace and household to
                identify the hazards from fire. This involves an in-depth examination of an organisation and household’s
                fire safety management system(s) and associated arrangements. The audit focuses on the key aspects of
                managing fire safety within the workplace & home and offers a structured path for continual improvement
                towards best practice status.
            </p>
        @endif
        @if($booking->booking_service->service_id === 3)
            <p class="about">
                <strong>&#8226; About Electrical Safety Audit</strong>
                <br>
                An Electrical Safety Audit is a structured and a systematic examination of the workplace and household
                to identify the hazards from electricity. The process involves physical inspection to identify
                electrical hazards (shock, fire, short circuit, overloading) and to suggest electrical safety solutions.
                The audit focuses on the key aspects of managing electrical safety within the workplace & home and
                offers a structured path for continual improvement towards best practice status.
            </p>
        @endif
        @if($booking->booking_service->service_id === 2)
            <p class="about">
                <strong>&#8226; About Electrical & Fire Safety Audit</strong>
                <br>
                An Electrical & Fire Safety Audit is a structured and a systematic examination of the workplace and
                household to identify the hazards from electricity & fire. This involves an in-depth examination of an
                organization & household’s fire safety management and physical inspection to identify electrical hazards
                (shock, fire, short circuit, overloading) and to suggest electrical safety solution. The audit focuses
                on the key aspects of managing electrical & fire safety within the workplace & home and offers a
                structured path for continual improvement towards best practice status.
            </p>
        @endif
    </div>
</div>

<div style="margin-top:50px;">
    @if($data->booking_multiple_checklist->count() > 0)
        <table>
            <thead style="margin:10px 0px;">
            <tr>
                <th style=" font-size:16px;">S.No.</th>
                <th style=" font-size:16px;">Checklist</th>
                <th style=" font-size:16px;">Floor</th>
                <th style=" font-size:16px;">Images</th>
                <th style=" font-size:16px;">Report</th>
                <th style=" font-size:16px;"> Description & (Observation)</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 0;
            @endphp
            @foreach($data->booking_multiple_checklist as $booking_multiple_checklist)

                @php
                    $phase1 = 0;
                    $phase3 = 0;
                    $phase1value = 1;
                    $phase3value = 1.732;
                @endphp
                @foreach($booking_multiple_checklist->bookingReports as $report)
                    <tr>
                        <td style=" font-size:16px;">{{++$count}}</td>
                        <td style=" font-size:16px;">{{$report->checklist->title}}</td>
                        <td style=" font-size:16px;">{{$booking_multiple_checklist->title}}</td>
                        <td>
                            @foreach($report->media as $image)
                                <div style="margin: 15% 0px;">
                                    <img src="{{ env('AWS_URL').'/'.$image->name }}" height="200" width="300"/>
                                </div>
                            @endforeach

                        </td>
                        <td style=" font-size:16px;">
                            {{$report->report->title}}
                        </td>

                        <td style="text-align:start; font-size: 16px;" class="text-justify">
                            @if($report->selected_option)
                                <p style="width: 200px !important; ">
                                    {{$report->selected_option->title}}
                                </p>
                                <br>
                            @endif
                            @if($report->messages->count() > 0)
                                <p class="text-justify">
                                    @foreach($report->messages as $message)
                                        {{$message->message}} <br/>
                                    @endforeach
                                </p>
                            @endif
                            @if($report->observation)
                                <p class="text-justify">
                                    <b>Visual Description:</b> {{$report->observation}}
                                </p>
                                <br/>
                            @endif

                            @if($report->result)
                                <p class="text-justify">
                                    <b>Value:</b> {{$report->result}}
                                </p>
                                <br>
                            @endif

                            @if($report->bookingColorCode)
                                <div class="text-justify">
                                    <b>Status:</b>
                                    <span style="background: {{$report->bookingColorCode->colorCode->code}}; width: 100%; padding:5px 10px;">{{$report->bookingColorCode->colorCode->name}}</span>
                                </div>
                                <br>
                            @endif
                        </td>
                    </tr>

                    @php
                        if ($report->report->title === '3-Ⴔ PHASE VOLTAGE (VOLTS)' || $report->report->title === 'R' ||
                        $report->report->title === 'Y' ||
                        $report->report->title === 'B' ||
                        $report->report->title === 'Running Power Factor') {

                            $phase3++;
                            $phase3value *=(float)$report->result;
                        }

                        if ($report->report->title === 'SINGLE PHASE VOLTAGE (VOLTS)' || $report->report->title === 'Phase Current' ||
                        $report->report->title === 'Running Power Factor') {

                            $phase1++;
                            $phase1value *=(float)$report->result;
                        }
                    @endphp
                    @if ($phase1 === 3)
                        <tr>
                            <td colspan="5">Single Phase power</td>
                            <td>{{$phase1value/1000}}</td>
                        </tr>
                    @endif
                    @if ($phase3 === 5)
                        <tr>
                            <td colspan="5">3-Phase Power</td>
                            <td>{{$phase3value/1000}}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach()
            </tbody>
        </table>
    @endif
    <hr>
    @if(count($data->booking_devices) > 0)
        <p style="margin-top:50px; font-size:2rem; page-break-before: always;">Electrical Equipments List</p>
        <table>
            <thead>
            <tr>
                <th style=" font-size:16px;">S.No.</th>
                <th style=" font-size:16px;">Type</th>
                <th style=" font-size:16px;">Equipment</th>
                <th style=" font-size:16px;">Images</th>
                <th style=" font-size:16px;">Values</th>
                <th style=" font-size:16px;"> Description & (Observation)</th>
            </tr>
            </thead>
            <tbody>

            @php
                $device_index = 0;
                $col_span_count = 1;
                //booking device group
                $booking_devices_group = $data->booking_devices->groupBy('checklist_type_id');
            @endphp


            @foreach($booking_devices_group as $booking_devices)
                @php
                    $booking_device_list = '';
                    $device_images_count = 0;
                    $total_power = 0;
                @endphp

                @foreach ($booking_devices as $booking_device)

                    @php
                        $device_images = '';
                        $watt = '';
                        $alert = '';
                        foreach($booking_device->media as $image) {

                            $device_images .= '<div><img src="'.env('AWS_URL').'/'.$image->name.'" height="200" width="300" /></div>';
                            $device_images_count++;
                        }
                        $values = json_decode(json_encode($booking_device->value), true);

                        if ($booking_device->bookingColorCode) {
                            $alert = ' <div class="text-justify">
                                    <b>Status:</b>
                                    <span style="background:'.$booking_device->bookingColorCode->colorCode->code.'; width: 100%; padding:5px 10px;">
                                    '.$booking_device->bookingColorCode->colorCode->name.'</span>
                                </div>';
                        }
                        $booking_device_list .=
                        '<tr>
                            <td style=" font-size:16px;">'.$col_span_count.'</td>
                            <td style=" font-size:16px;">'.$booking_device->type->title.'</td>
                            <td style=" font-size:16px;">'.$booking_device->title.'</td>
                            <td style= font-size:16px;">'.$device_images.'</td>
                            <td style="text-align:start; font-size:16px;">voltage: '.$values['voltage'].' Volts<br>
                                current: '.$values['current'].' Ampere</br>
                                pf: '.$values['pf'].' </br>
                                '.$watt.'
                                power: '.$values['power'].' KW </br>
                                phase: '.$values['phase'].' Φ </br>
                            </td>
                            <td style="text-align:start;">
                                <p style="width: 200px !important;" class="text-justify"> '.$booking_device->result.'</p>
                                '.$alert.'
                            </td>
                        </tr>';

                        $total_power = $total_power + $values['power'];

                        ++$col_span_count;
                    @endphp
                @endforeach

                {!!$booking_device_list !!}
                <tr>
                    <td colspan="4" style="font-size:16px; text-align: end;">
                        <b>Total Power</b>
                    </td>
                    <td style="font-size:16px; text-align: start;">
                        <b> {{$total_power}} KW</b>
                    </td>
                    <td></td>
                </tr>

            @endforeach()
            </tbody>
        </table>
    @endif
</div>
@if(count($booking_products) > 0)
    <div style="margin-top:50px;">
        <h3>Product Prices</h3>
        <table>
            <thead>
            <tr>
                <th>S.No.</th>
                <th>Type</th>
                <th>Maker</th>
                <th>price</th>
                <th>Qty</th>
                <th> Total Price</th>
            </tr>
            </thead>
            <tbody>
            @foreach($booking_products as $booking_product)
                <tr>
                    <td>{{$loop->index + 1}}</td>
                    <td>{{$booking_product->description}}</td>
                    <td>{{$booking_product->maker}}</td>
                    <td>{{$booking_product->price}}</td>
                    <td>{{$booking_product->pivot->quantity}}</td>
                    <td>{{$booking_product->pivot->quantity * $booking_product->price}}</td>
                    @php $total_price += $booking_product->pivot->quantity * $booking_product->price @endphp
                </tr>
            @endforeach
            <tr>
                <th colspan="5" style="text-align: right">Tax ({{$gst}}%)</th>
                <td colspan="5">{{number_format((float)$total_price * $gst/100, 2, '.', '')}}</td>
            </tr>
            <tr>
                <th colspan="5" style="text-align: right">Grand Price</th>
                <td colspan="5">{{($total_price + (number_format((float)$total_price * $gst/100, 2, '.', '')))}}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
@endif

<div class="last-page">
    <div class="about">
        <strong>Summary</strong>
        <ul>
            <li style="font-size:1.3rem;">
                Opening meeting conducted with the client at the property.
            </li>
            <li style="font-size:1.3rem;">
                Review of all relevant documents like bills made available.
            </li>
            <li style="font-size:1.3rem;">
                Property walkthrough and in-depth inspection in presence of customer.
            </li>
            <li style="font-size:1.3rem;">
                Discussions and interactions with the customer.
            </li>
            <li style="font-size:1.3rem;">
                Preparing and issuance of draft report with observation & suggestions.
            </li>
            <li style="font-size:1.3rem;">
                Issuance of final report
            </li>
            <li style="font-size:1.3rem;">
                Closing meeting to apprise main finding and suggest their respective solutions.
            </li>
        </ul>
    </div>
</div>

</body>

</html>
