<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingXlsxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $bookingTime = $this->booking_time;
        $address = $this->address;
        $user = $this->user;
        $reports = $this->reports;
        $amount = $this->payment(Partner::TYPE_AUDITOR, 'amount');
        $gst = $this->payment(Partner::TYPE_AUDITOR, 'gst');
        $gstAmount = $this->paymentGst($amount, $gst);
        return [
            "Sno"               => $this->id,
            "Customer_Name"     => $user->first_name.' '.$user->last_name,
            "City"              => $address->city,  
            "State"             => $address->state,  
            "Date_Of_Booking"   => $bookingTime->format('m-d-Y'),  
            "Time_Of_Booking"   => $bookingTime->format('H:i A'),  
            "Auditor_Name"      => $this->auditor(),  
            "Date_Of_Audit"     => count($reports) > 0 ? $reports->last()->created_at->format('d M,Y') : '',  
            "Time_Of_Audit"     => count($reports) > 0 ? $reports->last()->created_at->format('H:i A') : '',  
            "Status"            => $this->bookingStatus(),  
            "Reschedule_Status" => $this->reschedule('status'),  
            "Reschedule_Reason" => $this->reschedule('reason'),  
            "Payment_Status"    => $this->payment(Partner::TYPE_AUDITOR, 'status'),  
            "GST_No"            => $this->user->gst,  
            "Service_Amount"    => number_format((float)$amount, 2, '.', ''),  
            "GST_Amount"        => number_format((float)$gstAmount, 2, '.', ''),  
            "Total_Amount"      => number_format((float)$amount + $gstAmount, 2, '.', ''),  
        ];
    }

    public function auditor()
    {
        $auditor = $this->booking_allottee->where('allottee_type', Partner::TYPE_AUDITOR)->where('status', 1)->first();
        return $auditor ? $auditor->partner->name : '';
    }

    public function bookingStatus()
    {
        switch($this->status) {
            case 0: 
                return 'STARTED';
                break;
            case 1: 
                return 'AUDITOR ACCEPTED';
                break;
            case 2: 
                return 'AUDITED';
                break;
            case 3: 
                return 'CONTRACTOR REQUIRED';
                break;
            case 4: 
                return 'CONTRACTOR ACCEPTED';
                break;
            case 5: 
                return 'COMPLETED';
                break;
        }
    }

    public function payment($partner, $column)
    {
        $payment = $this->payment->where('partner_type', $partner)->first();

        if($column == "status")
            return $payment ? ucfirst($payment->mode) : null;
        else if($column == "amount")
            return $payment ? ucfirst($payment->amount) : null;
        else if($column == "gst")
            return $payment ? ucfirst($payment->gst) : null;
    }

    public function paymentGst($amount, $gst)
    {
        return (float)$amount * $gst/100;
    }
    public function reschedule($column)
    {
        $reschedule = $this->rescheduleRequest->where('status', 1)->first();
        
        if($column == "reason")
            return $reschedule ? ucfirst($reschedule->reason) : null;
        
        else if($column == "status")
            return $reschedule ? 'Active' : '';
    }
}
