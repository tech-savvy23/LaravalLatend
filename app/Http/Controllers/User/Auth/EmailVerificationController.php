<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationController extends AuthController
{

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function sendEmailVerification(Request $request)
    {
        $request->validate([
            'email' => 'required',
        ]);
        $inputs = request()->all();
        $user = User::where('email', $inputs["email"])->first();

        if (!$user) {

            return response()->json([
                'data' => 'No user found.'
            ],Response::HTTP_NOT_FOUND);
        }
        if ($this->createSignature($user)) {

            $token = cache("verify-user-{$user->id}");
            Mail::to($user->email)->send(new EmailVerification($user, $token));

            return response()->json([
                'data' => 'Verification email is successfully send.',
            ],Response::HTTP_ACCEPTED);

        }
        return response()->json([
            'data' => 'Verification email is not send please try again..',
        ],Response::HTTP_NOT_ACCEPTABLE);

    }

    /**
     * @param $user
     * @return bool
     * @throws \Exception
     */
    protected function createSignature($user): bool
    {
        return cache(["verify-user-{$user->id}" => Str::random(10)], 3000);
    }

    /**
     * @param $email
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verifyEmail($email, $token)
    {
        $user = User::where('email', $email)->first();

        if (cache()->has("verify-user-{$user->id}")) {
            if ($token == cache("verify-user-{$user->id}")) {
                $user->markEmailAsVerified();
                Cache::forget("verify-user-{$user->id}");
                return redirect()->route('successful.message')->with('success',$user->email .' is successfully verified.');

            }
        }

        return response('Email is not verified please try again..', Response::HTTP_NOT_ACCEPTABLE);

    }
}
