<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Partner\ResetPassword;
use App\Models\Partner;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordController extends Controller
{
    /**
     * @param $email
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $partner = Partner::where('email', $request->email)->first();
        if (!$partner) {
            return response('No Partner found.', Response::HTTP_NOT_FOUND);
        }
        if ($this->createSignature($partner)) {

            $token = cache("partner-password-{$partner->id}");

            Mail::to($partner->email)->send(new ResetPassword($partner, $token));
            
            return response()->json([
                
                'data' => 'We have e-mailed your password reset link!'
            ],Response::HTTP_OK);

        }
        return response('Request is not send please try again..', Response::HTTP_NOT_ACCEPTABLE);

    }

    /**
     * @param Request $request
     */
    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required | email',
        ]);
    }

    /**
     * @param $partner
     * @return bool
     * @throws \Exception
     */
    protected function createSignature($partner): bool
    {
        return cache(["partner-password-{$partner->id}" => Str::random(10)], 3000);
    }

    /**
     * @param $email
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword($email, $token)
    {
        $partner = Partner::where('email', $email)->first();

        if ($partner) {
            if (cache()->has("partner-password-{$partner->id}")) {

                if ($token == cache("partner-password-{$partner->id}")) {
                    return view('partner_change_password', compact('email', 'token'));

                }
            }
        }



    }

    /**
     * @param $email
     * @param $token
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function resetPassword($email, $token, Request $request)
    {

        $this->resetPasswordValidate($request);
        $partner = Partner::where('email', $email)->first();

        if (cache()->has("partner-password-{$partner->id}")) {

            if ($token == cache("partner-password-{$partner->id}")) {
                $this->updatePassword($partner, $request);
                Cache::forget("partner-password-{$partner->id}");
                return redirect()->route('successful.message')->with('success','Aww yeah, you have successfully changed the password of '.$partner->email);

            }
        }

        return response('Password is not updated please try again..', Response::HTTP_NOT_ACCEPTABLE);

    }

    /**
     * @param Request $request
     */
    protected function resetPasswordValidate(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required | same:password'
        ]);

    }

    /**
     * @param User $user
     * @param Request $request
     * @return bool
     */
    protected function updatePassword(Partner $partner, Request $request)
    {
        return $partner->update(['password' => $request->password]);
    }

    /**
     * @param $email
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function successfullyChanged()
    {
        return view('successfully_changed');
    }
}
