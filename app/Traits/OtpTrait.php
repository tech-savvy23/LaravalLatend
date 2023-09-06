<?php

namespace App\Traits;

use App\Models\Common\Otp;
use App\Models\User\UserLogin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait OtpTrait
{
    use VariablesTrait;
    /**
     * Generate OTP
     *
     * @return object
     *
     * @param Request $request
     *
     * @param string $type
     */
    public function generateOtp(Request $request, $type)
    {
        $otp = Otp::where('type',$type)->where('mobile',$request->mobile)->where('ip',$request->ip)->first();
        $otp_number = $this->generateRandomOTP();
        if ($otp) {
            $otp->otp = $otp_number;
            $otp->date_time = $request->date_time;
            $otp->timezone = $request->timezone;
            $otp->save();
            $otp->send(
                $request->all(),
                $otp_number,
                $this->getOtpChannel()
            );
            return $otp;
        }
        $otp =  new Otp();
        return $otp->store(
            $request->all(),
            $type,
            $otp_number,
            $this->getOtpChannel()
            );
    }

    /**
     * Generate Random OTP
     *
     * @return string
     *
     * @param
     */
    public function generateRandomOTP()
    {
        return rand(100000,999999);
    }


    /**
     * verification of OTP
     *
     * @return object
     *
     * @param Request $request
     *
     * @param string $type
     */
    public function verificationOTP(array $data, $type)
    {
        $otps = Otp::where('type',$type)->where('mobile',$data['mobile'])->where('ip',$data['ip'])->get();

        $now = Carbon::now()->timezone($data['timezone']);
        if ($otps->count() > 0) {

            foreach ($otps as $otp) {

                if ($otp->otp == $data['otp']) {
                    $otp_time = Carbon::parse($otp->date_time)->addMinutes(5);

                    if ($now->format('Y-m-d H:i:s') > $otp_time->format('Y-m-d H:i:s')) {

                        return response([
                            'status' => 422,
                            'response' => 'single_error',
                            'data' => [
                                'error' =>[
                                    'otp' => ['OTP has been expired'],
                                ],

                            ]
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $otp->delete();
                    if ($type == 'U')
                    {
                       UserLogin::where('mobile',$data['mobile'])->update(['register_status' => 1]);
                    }
                    return response([
                        'status' => 200,
                        'response' => 'success',

                    ], Response::HTTP_OK);
                }
            }
        }
        return response([
            'status' => 422,
            'response' => 'single_error',
            'data' => [
                'otp' => ['OTP is invalid'],

            ]
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}
