<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Models\Partner;
use App\Models\PartnerDevice;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Partner\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AuthController
{
    public function __construct()
    {
        $this->user= app()['config']['api-auth.models.user'];
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $request->validate([
            'device_id' => 'required',
            'device_type' => 'required',
            'token' => 'required'
        ]);
        return $this->performLogin($request);
    }

    /**
     * @param $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    protected function performLogin($request)
    {

        $token = auth('partner')->attempt(['email'=>$request->email, 'password'=> $request->password]);

        if (!$token) {

            return $this->noTokenResponse();
        }

        if (!$this->checkActive($request)) {
            auth('partner')->logout();
            return response()->json([
                'errors' => [
                    'error' => 'Admin has not activated your account',
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

//         if (!$this->checkEmailVerify()) {
//             auth('partner')->logout();
//             return $this->emailNotVerifiedResponse();
//         }
        $this->storePartnerDevice($request);
        return $this->respondWithToken($token);
    }

    public function checkActive($request)
    {
        return auth('partner')->user()->active;
    }

    /**
     * @return JsonResponse
     */
    protected function noTokenResponse(): JsonResponse
    {
        return response()->json([
            'errors' => [
                'error' => 'Credentials does\'t match our record',
            ],
        ], 401);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    protected function emailNotVerifiedResponse()
    {
        return response(['errors' => ['verify' => 'Please verify your email first.']], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @return mixed
     */
    protected function checkEmailVerify()
    {
        $user = Partner::whereEmail(request('email'))->first();
        return $user->email_verified_at;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth('partner')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    private function storePartnerDevice($request)
    {
        $partner_device = PartnerDevice::where('token',$request->token)->first();

        if (!$partner_device) {

            return PartnerDevice::create([
                'partner_id' => auth('partner')->user()->id,
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
                'token' => $request->token,
            ]);
        }
        return $partner_device;

    }
}
