<?php

namespace App\Models\User;

use Carbon\Carbon;
use App\Traits\OtpTrait;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserLogin extends Authenticatable
{
    use HasApiTokens,Notifiable,OtpTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile', 'status', 'login_type', 'email', 'password', 'register_status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relation with user profile
     *
     * Relation hasOne
     *
     * @return object
     */
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'login_id', 'id');
    }

    /**
     * Relation with user login activity
     *
     * Relation hasMany
     *
     * @return object
     */
    public function userLoginActivities()
    {
        return $this->hasMany(UserLoginActivity::class, 'login_id', 'id');
    }

    /**
     * Relation with user login activity
     *
     * Relation hasOne
     *
     * @return object
     */
    public function userLoginActivity()
    {
        return $this->hasOne(UserLoginActivity::class, 'login_id', 'id');
    }

    /**
     * Get email verified time and date formate
     *
     * @return string
     */
    public function getEmailVerifiedAt()
    {
        return Carbon::parse($this->email_verified_at);
    }

    /**
     *
     * -------------------------------------------------------------
     * User login process start
     * -------------------------------------------------------------
     */

    /**
     * Create a new user login instance after a valid registration.
     *
     * @param  array  $data
     * @return UserLogin
     */
    public function store(array $data)
    {
        return $this->create([
            'email'           => $data['email'],
            'mobile'          => $data['mobile'],
            'password'        => Hash::make($data['password']),
            'status'          => 1,
            'login_type'      => $data['type'],
            'register_status' => 0,
        ]);
    }

    /**
    * Validation of credential.
    *
    * @param $request
    *
    * @return object
    */
    public function validation($request)
    {
        return Validator::make($request->all(), [
            'user_id'    => 'required',
            'password'   => 'required|min:6|max:25',
            'ip'         => 'required|ip',
            'timezone'   => 'required',
            'date_time'  => 'required',
            'user_agent' => 'required',
        ]);
    }

    /**
     * Validation of registration.
     *
     * @param $request
     *
     * @return object
     */
    public function registration_validation($request)
    {
        return Validator::make($request->all(), [
            'first_name'       => 'required|max:100',
            'last_name'        => 'required|max:100',
            'email'            => 'required|max:255|email|unique:user_logins',
            'password'         => 'required|min:6|max:25|same:confirm_password',
            'confirm_password' => 'required|min:6|max:25|same:password',
            'mobile'           => 'required|min:10|max:10|unique:user_logins',
            'type'             => 'required',
            'ip'               => 'required|ip',
            'date_time'        => 'required',
            'timezone'         => 'required',
        ]);
    }

    /**
     * Get the needed authorization credential.
     *
     * @param $request
     *
     * @param string $credentialUserId
     *
     * @param string $credentialPassword
     *
     * @return object
     */
    public function credentials($request, $userId, $password)
    {
        if ($this->authGuard()->attempt([
            $userId => $request->user_id,
            $password => $request->password,
        ], true)) {
            return $this->userActiveOrInactive($request);
        }

        return response([
            'status'   => 422,
            'response' => 'single_error',
            'data'     => [
                'error' => ['Invalid (email id or mobile number) and password '],
            ],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
    * User is active or inactive if active then generate
    * the access token or not then give the error
    *
    * @return object
    *
    * @param $request
    */
    public function userActiveOrInactive($request)
    {
        $user_login    = $this->authGuard()->user();
        $user_register = $this->getMobileStatus($request, $user_login);
        if ($user_register['status']) {
            //User is Active or Inactive
            if ($this->getUserStatus($user_login)) {
                //Last login activity
                $this->createLoginActivity($request, $user_login);
                return response([
                    'status'   => 200,
                    'response' => 'success',
                    'data'     => new UserResource($user_login, $this->generateAccessToken($request)),
                    // creating user details and generating token
                ], Response::HTTP_OK);
            }

            return response([
                'status'   => 422,
                'response' => 'single_error',
                'data'     => [
                    'error' => [
                        'other' => ['User is not active '],
                    ],
                ],
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        return response([
            'status'   => 422,
            'response' => 'single_error',
            'data'     => [
                'error' => [
                    'other' => ['redirect'],
                ],
            ],
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Add login activity
     *
     * @param $request
     *
     * @param Auth $userLogin
     */
    public function createLoginActivity($request, $userLogin)
    {
        $user_login_activity = new UserLoginActivity();
        return $user_login_activity->store($request, $userLogin);
    }

    /**
     * Get the status of user
     *
     * @param Auth $userLogin
     *
     * @return bool
     **/
    public function getUserStatus(UserLogin $userLogin)
    {
        if ($userLogin->status === 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the mobile status of user
     *
     * @param Auth $userLogin
     **/
    public function getMobileStatus($request, UserLogin $userLogin)
    {
        if ($userLogin->register_status === 0) {
            $request->merge(['mobile' => $userLogin->mobile]);
            return ['status' => false, 'data'=>  $this->getOTP($request)];
        }
        return ['status' => true];
    }

    /**
     * Get OTP
     *
     * @param Request $request
     *
     **/
    public function getOTP($request)
    {
        return $this->generateOtp($request, 'U');
    }

    /**
     * Verification OTP
     *
     * @param Request $request
     *
     **/
    public function verifyOTP(Request $request)
    {
        return $this->verificationOTP($request->all(), 'U');
    }

    /**

     * Generate access token
     * @return string
     * @param Request $request
     * */
    protected function generateAccessToken($request)
    {
        $user_login = $this->authGuard()->user();
        $token_name = $request->user_id . $user_login->id;
        return 'Bearer ' . $user_login->createToken($token_name)->accessToken;
    }

    /**
     * Logout
     *
     * @param $request
     */
    public function logout()
    {
        Auth::user()->token()->revoke();
        $this->authGuard()->logout();
        session()->invalidate();

        return response([
            'status'   => 200,
            'response' => 'success',
            'data'     => [
                'logout' => [
                    'Successfully logout',
                ],
            ],
        ], Response::HTTP_OK);
    }

    /**
    * Get the guard to be used during authentication.
    *
    */
    public function authGuard()
    {
        return Auth::guard('user_logins');
    }

    /**
     * -------------------------------------------------------------
     * User login process end
     * -------------------------------------------------------------
     */
}
