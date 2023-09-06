<?php

namespace App\Http\Controllers\User\Auth;

use App\Models\User\UserDevice;
use App\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\User\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends AuthController
{
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

        if (!$this->checkMobileVerify()) {
        
            return $this->mobileNotVerifiedResponse();
        }

        if ($token = auth()->attempt($this->credentials($request))) {

            $this->storeUserDevice($request);
            return $this->respondWithToken($token);
        }

        return $this->noTokenResponse();
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
     * @return \Illuminate\Http\Response
     */
    protected function mobileNotVerifiedResponse()
    {
        return response(['errors' => ['mobile' => 'Please verify your mobile first.']], Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * @return mixed
     */
    protected function checkEmailVerify()
    {
        $user = User::whereEmail(request('email'))->first();
        return $user->email_verified_at;
    }

    /**
     * @return mixed
     */
    protected function checkMobileVerify()
    {
        $username = is_numeric(request('username')) ? 'mobile' : 'email';
        $user     = User::where($username, request('username'))->first();
        if (!$user) {
            return response(['errors' => ['error' => 'Credentials does not match our record.']], Response::HTTP_NOT_ACCEPTABLE);
        }
        return $user->mobile_verified;
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function credentials($request)
    {
        $username = is_numeric($request->username) ? 'mobile' : 'email';
        
        return [
            $username         => $request->username,
            'password'        => $request->password,
            'active'          => true,
            'mobile_verified' => true,
        ];
    }

    /**
     * @param $request
     * @return mixed
     */

    private function storeUserDevice($request)
    {
        $user_device = UserDevice::where('token',$request->token)->first();

        if (!$user_device) {

            return UserDevice::create([
                'user_id' => auth()->user()->id,
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
                'token' => $request->token,
            ]);
        }
        return $user_device;
    }
}
