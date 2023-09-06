<?php

namespace App\Models\Common;
use Aws\Laravel\AwsFacade as AWS;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'otp', 'model_id', 'model_type', 'ip', 'for_type', 'for_id',
    ];

    protected $casts = ['model_id' => 'integer', 'otp'=>'integer'];

    public const BOOKING = 'booking';

    /**
     * Generating OTP
    */
    public static function generate($model, $type)
    {

        $otp   = rand(1000, 9999);
        $otpUser = self::where('otp', $otp)
            ->where('model_id', $model->id)
            ->where('model_type', get_class($model))
            ->where('for_type',get_class($type))
            ->where( 'for_id',$type->id)
            ->first();
        if ($otpUser) {
            $otpUser->update([
                'otp' => $otp,
            ]);
        }
        else {
            self::create([
                'model_id'   => $model->id,
                'model_type' => get_class($model),
                'for_type'   => get_class($type),
                'for_id'     => $type->id,
                'otp'        => $otp,
            ]);

        }
        return $otp;
    }

    public static function send($msg, $mob)
    {
        if (env('MESSAGE_SEND')) {
            $aws = AWS::createClient('sns');
            $aws->publish([
                'Message' => $msg,
                'PhoneNumber' => '+91'.$mob,
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType'  => [
                        'DataType'    => 'String',
                        'StringValue' => 'Transactional',
                    ]
                ],
            ]);
            // $msg        = "Your OTP for perfect House is $otp";
//            $url        ='http://sms.kmrinfotech.com/index.php/smsapi/httpapi/?uname=shammiommur&password=ommur2017&sender=OmmurM&receiver=' . $mob . '&route=TA&msgtype=1&sms=' . urlencode($msg);
//            $ch         = curl_init($url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_exec($ch);
//            curl_close($ch);
            return true;
        }

    }

    public function OTPExists($otp)
    {
        return $this
            ->where('otp', $otp)->exists();
    }
}
