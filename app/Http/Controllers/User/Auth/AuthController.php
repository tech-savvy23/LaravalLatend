<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use Carbon\Carbon;
use App\Models\Common\Otp;
// use Bitfumes\ApiAuth\Helpers\ImageCrop;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\RegisterRequest;
use App\Notifications\WelcomeCustomerByAdmin;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only('getUser');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create($request->all());
        $user->update(['email_verified_at'=> Carbon::now()]);
        $otp   = Otp::generate($user, $user);
        try {
            Otp::send("Your OTP for perfect House is $otp", $user->mobile);
            return response($user, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response(['errors' => 'SMS not sent, please try again']);
        }
    }

    public function registerByAdmin(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:100',
            'email'      => 'required|email|unique:users,email|unique:partners,email',
            'mobile'     => 'required|max:10|unique:users,mobile',
        ]);
        $request['password'] = request('email');
        $user                = User::create($request->all());
        $user->update(['email_verified_at'=> Carbon::now()]);
        $user->update(['mobile_verified_at'=> true]);
        $user->notify(new WelcomeCustomerByAdmin());
        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function update(UpdateRequest $request)
    {
        $user = auth()->user();
        $this->checkForAvatar($request, $user);
        $user->update($request->except('image'));
        return response([
            'data'=> new UserResource($user),
        ], Response::HTTP_ACCEPTED);
    }

    public function checkForAvatar($request, $user)
    {
        if ($request->has('image')) {
            $user->uploadProfilePic($request->image);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function getUser()
    {
        $user = auth()->user();
        return response([
            'data'=> new UserResource($user),
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'user'         => new UserResource(auth()->user()),
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('partner')->refresh());
    }

    public function all()
    {
        $users = User::latest()->paginate(50);
        return UserResource::collection($users);
    }
}
