<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Partner\EmailVerification;
use App\Models\Partner;
use App\User;
use Carbon\Carbon;
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
        $partner = Partner::where('email', $inputs["email"])->first();

        if (!$partner) {

            return response()->json([
                'data' => 'No user found.'
            ],Response::HTTP_NOT_FOUND);
        }
        if ($this->createSignature($partner)) {

            $token = cache("verify-partner-{$partner->id}");
            Mail::to($partner->email)->send(new EmailVerification($partner, $token));

            return response()->json([
                'data' => 'Verification email is successfully send.',
            ],Response::HTTP_ACCEPTED);

        }
        return response()->json([
            'data' => 'Verification email is not send please try again..',
        ],Response::HTTP_NOT_ACCEPTABLE);

    }

    /**
     * @param $partner
     * @return bool
     * @throws \Exception
     */
    protected function createSignature($partner): bool
    {
        return cache(["verify-partner-{$partner->id}" => Str::random(10)], 3000);
    }

    /**
     * @param $email
     * @param $token
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verifyEmail($email, $token)
    {
        $partner = Partner::where('email', $email)->first();

        if (cache()->has("verify-partner-{$partner->id}")) {
            if ($token == cache("verify-partner-{$partner->id}")) {
                $this->markEmailAsVerified($partner);
                Cache::forget("verify-partner-{$partner->id}");
                return redirect()->route('successful.message')->with('success',$partner->email .' is successfully verified.');

            }
        }

        return response('Email is not verified please try again..', Response::HTTP_NOT_ACCEPTABLE);

    }

    public function markEmailAsVerified(Partner $partner)
    {
        $partner->email_verified_at = Carbon::now();
        $partner->save();
        return;
    }
}
