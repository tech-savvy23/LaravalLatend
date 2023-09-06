<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
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

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response('No user found.', Response::HTTP_NOT_FOUND);
        }
        
        if ($this->createSignature($user)) {

            $token = cache("user-password-{$user->id}");

            Mail::to($user->email)->send(new ResetPassword($user, $token));
            
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
     * @param $user
     * @return bool
     * @throws \Exception
     */
    protected function createSignature($user): bool
    {
        return cache(["user-password-{$user->id}" => Str::random(10)], 3000);
    }

    /**
     * @param $email
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePassword($email, $token)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            if (cache()->has("user-password-{$user->id}")) {

                if ($token == cache("user-password-{$user->id}")) {
                    return view('change_password', compact('email', 'token'));

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

        $user = User::where('email', $email)->first();

        if (cache()->has("user-password-{$user->id}")) {

            if ($token == cache("user-password-{$user->id}")) {
                
                $this->updatePassword($user, $request);
                Cache::forget("user-password-{$user->id}");
                return redirect()->route('successful.message')->with('success','Aww yeah, you have successfully changed the password of '.$user->email);

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
    protected function updatePassword(User $user, Request $request)
    {
        return $user->update(['password' => $request->password]);
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
