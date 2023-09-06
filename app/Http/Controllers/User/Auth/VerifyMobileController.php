<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use App\Models\Common\Otp;
use Symfony\Component\HttpFoundation\Response;

class VerifyMobileController extends AuthController
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function resend($mobile)
    {
        $user  = User::whereMobile($mobile)->first();
        $otp   = !$user->otp->first() ? Otp::generate($user, $user) : $user->otp->first()->otp;
        Otp::send("Your OTP for perfect house is $otp", $mobile);
        return response('done', 202);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function verifyEmail($mobile)
    {
        $user  = User::whereMobile($mobile)->first();
        if (!$user) {
            return response('No user found. Please click on button to verify.', Response::HTTP_NOT_FOUND);
        }

        if ($this->matchOTP($user)) {
            $user->update(['mobile_verified' => true]);
            $user->otp->each->delete();
            return $this->respondWithToken(auth()->login($user));
        }

        return response('Credential not found or please try to login & resend sms.', Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
    protected function matchOTP($user): bool
    {
        return (int )$user->otp->first()->otp === (int) request('otp');
    }
}
