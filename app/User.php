<?php

namespace App;

use App\Models\Address;
use App\Models\Booking;
use App\Models\Common\Otp;
use Illuminate\Support\Str;
use App\Helpers\Images\Upload;
use App\Models\User\UserDevice;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'mobile', 'email_verified_at', 'mobile_verified', 'image', 'active', 'gst', 'pan',
    ];

    protected $casts = ['mobile_verified' => 'boolean'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function address()
    {
        return $this->hasMany(Address::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function otp()
    {
        return $this->morphMany(Otp::class, 'for');
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucfirst($value);
    }

    public function userDevices()
    {
        return $this->hasMany(UserDevice::class, 'user_id', 'id');
    }

    public function userDevicesToken()
    {
        return $this->hasMany(UserDevice::class, 'user_id', 'id')->pluck('token')->toArray();
    }

    public function uploadProfilePic($image)
    {
        $disk     = env('DISK', 'public');
        $filename = 'customer/' . Str::random() . '.jpg';
        if ($this->image) {
            Storage::disk($disk)->delete('images/' . $this->image);
        }
        $image    = Upload::resize($image, 400);
        Storage::disk($disk)->put('images/' . $filename, $image);
        $this->update(['image' => $filename]);
    }
}
