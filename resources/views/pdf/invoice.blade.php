<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
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

        td p, th p{
            word-wrap: break-word;
        }
       
        thead { display: table-header-group }
        tfoot { display: table-row-group }
        tr { page-break-inside: avoid }
        .flex {
            display: flex;
            justify-content:space-between;
        }
    </style>
</head>
    <body>
        <div>
            <div>
                <div style="float:left;">
                    <div>
                        <div>
                            <img src="{{public_path("images/perfect-house-logo.jpeg")}}" height="50">
                        </div>
                        <div>
                            C 17 Industrial Estate RAMPUR<br>
                            <strong>GSTIN/UIN:</strong> 09AEJPG6218F1ZK<br>
                            <strong>State Name :</strong> Uttar Pradesh, Code : 09<br>
                            <strong>E-Mail :</strong> info@perfecttesthouse.com<br>
                            <strong>PAN/IT No:</strong> AEJPG6218F
                        </div>
                    </div>
                    <div>
                        <p> <strong>Customer Name:</strong> {{$user}}</p>
                        <p> <strong>GSTIN/UIN:</strong> {{$booking->user->gst ? $booking->user->gst : 'Not Available'}}</p>
                        <p> <strong>PAN/IT No:</strong> {{$booking->user->pan ? $booking->user->pan : 'Not Available'}}</p>
                        <p> <strong>Address:</strong> {{"{$booking->address->body}, {$booking->address->landmark}, {$booking->address->city}
                            "}}</p>
                        <p> <strong>State:</strong> {{$booking->address->state}}</p>
                        <p> <strong>Place of Supply:</strong> {{$booking->address->state}}</p>
                        <p> <strong>Zip:</strong> {{$booking->address->pin}}</p>
                    </div>
                </div>
                <div style="float:right; margin-top:130px;">
                    <div>
                        <p><strong>Invoice No:</strong> {{'PTH/'.$booking_id.$booking->id}}</p>
                        <p><strong>Invoice Date:</strong> {{$booking->reports->last()->created_at->format('d M,Y H:i A')}}</p> 
                        <p><strong>Booking No:</strong> {{$booking_id.'-'.$booking->id}}</p> 
                        <p><strong>Report No:</strong> {{$report_id.'-'.$booking->id}}</p> 
                        <p><strong>Booking Date:</strong> {{$booking->created_at->format('d M,Y H:i A')}}</p> 
                        <p><strong>Audit Date:</strong> {{$booking->reports->last()->created_at->format('d M,Y H:i A')}}</p> 
                    </div>
                </div>
            </div>
        </div>
        <div>
            <table>
                <thead>
                    <tr>
                        <th style="font-size:14px;">SI No.</th>
                        <th style="font-size:14px;">Description of Services</th>
                        <th style="font-size:14px;">HSN/SAC</th>
                        @if($booking->address->state==='Uttar Pradesh')
                            <th style="font-size:14px;">CGST </th> 
                            <th style="font-size:14px;">SGST </th>
                        @else
                            <th style="font-size:14px;">IGST </th>
                        @endif
                        <th style="font-size:14px;">Quantity</th>
                        <th style="font-size:14px;">Rate</th>
                        <th style="font-size:14px;">GST Rate</th>
                        <th style="font-size:14px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $gstAmount = $payment->amount * $gst /100;
                        $withoutGSTAmount = $payment->amount - $gstAmount;
                        $colspan = 7;
                    @endphp
                    <tr>
                        <td style="font-size:14px;">1</td>
                        <td style="font-size:14px;">{{$booking->booking_service->service->name}} Audit</td>
                        <td style="font-size:14px;">998346</td>
                        @if($booking->address->state==='Uttar Pradesh')
                            <td style="font-size:14px;">{{$gst/2}}%</td> 
                            <td style="font-size:14px;">{{$gst/2}}%</td>
                            @php
                                $colspan = 8   
                            @endphp
                        @else
                            <td style="font-size:14px;">{{$gst}}%</td>
                        @endif
                        
                        <td style="font-size:14px;">1</td>
                        <td style="font-size:14px;">{{$withoutGSTAmount}}</td>
                        <td style="font-size:14px;">{{$gstAmount}}</td>
                        <td style="font-size:14px;">{{$payment->amount}}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align:right; font-size:14px; font-weight:bold" colspan="{{$colspan}}">Total</td>
                        <td style="font-size:14px;">{{$payment->amount}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div>
            <div>
                <div>
                    <div>
                        <p> <strong>Amount Chargeable (in words):</strong>   {{ucwords($payment_words)}}</p>
                    </div>
                    <div>
                        <p> 
                            <strong> Declaration</strong>
                            <br>
                            We declare that this invoice shows the actual price of the
                            goods & services described and that all particulars are
                            true and correct.
                            <br>
                            <br>
                            <strong> Terms & Conditions </strong>
                            <br>
                            E. & O. E.
                            <br>
                            1. Goods & services once invoiced will not be revoked.
                            <br>
                            2. Interest @ 18% P.A. will be charged if the payment is
                            not made within 30 Day.
                        </p>
                    </div>
                    <div>
                        <p> <strong>Authorised Signatory</strong> (Finance)</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>