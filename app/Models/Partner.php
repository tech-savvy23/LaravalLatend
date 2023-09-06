<?php

namespace App\Models;

use Carbon\Carbon;
use App\PartnerDocument;
use Illuminate\Support\Str;
use App\Helpers\Images\Upload;
use Illuminate\Support\Facades\DB;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Partner extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public const TYPE_AUDITOR    = 'Auditor';
    public const TYPE_CONTRACTOR = 'Contractor';

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
        'name', 'email', 'password', 'type', 'phone', 'city', 'state', 'pin', 'latitude', 'longitude', 'active', 'police_verified_at',
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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->email_verified_at != null) {
            $this->notify(new ResetPassword($token));
        }
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function partnerDevices()
    {
        return $this->hasMany(PartnerDevice::class, 'partner_id', 'id');
    }

    public function partnerDevicesToken()
    {
        return $this->hasMany(PartnerDevice::class, 'partner_id', 'id')->pluck('token')->toArray();
    }

    public function media()
    {
        return $this->morphOne(Media::class, 'model');
    }

    public function uploadProfilePic($image)
    {
        $disk     = env('DISK', 'public');
        $filename = 'partner/' . Str::random() . '.jpg';
        if ($this->media()->exists()) {
            $this->media->delete();
            Storage::disk($disk)->delete('images/' . $this->media->name);
        }
        $image    = Upload::resize($image, 400);
        Storage::disk($disk)->put('images/' . $filename, $image);
        $this->media()->create(['name' =>  $filename]);
    }

    /**
     * Document
     *
     * @return relationship
     */
    public function document()
    {
        return $this->hasOne(PartnerDocument::class, 'partner_id', 'id');
    }

    public function bookingAllottees()
    {
        return $this->hasMany(BookingAllottee::class, 'allottee_id');
    }

    public function totalEarning()
    {
        if ($this->type === self::TYPE_AUDITOR) {

            $payments = DB::table('payments')
                        ->leftJoin('bookings', 'bookings.id', '=', 'payments.booking_id')
                        ->leftJoin('booking_allottees', 'booking_allottees.booking_id', '=', 'payments.booking_id')
                        ->where('booking_allottees.allottee_id', $this->id)
                        ->where('booking_allottees.allottee_type', self::TYPE_AUDITOR)
                        ->where('booking_allottees.status', 1)
                        ->orderByDesc('bookings.booking_time')
                        ->select('bookings.booking_time as time', 'payments.partner_price as amount')
                        ->get();

        } elseif ($this->type === self::TYPE_CONTRACTOR) {

            $payments = DB::table('payments')
                        ->leftJoin('bookings', 'bookings.id', '=', 'payments.booking_id')
                        ->leftJoin('booking_allottees', 'booking_allottees.booking_id', '=', 'payments.booking_id')
                        ->where('booking_allottees.allottee_id', $this->id)
                        ->where('booking_allottees.allottee_type', self::TYPE_CONTRACTOR)
                        ->where('booking_allottees.status', 1)
                        ->orderByDesc('bookings.booking_time')
                        ->select('bookings.booking_time as time', 'payments.amount as amount')
                        ->get();
        }

        $payment_array = [];

        foreach ($payments as $payment) {

            if (array_key_exists(Carbon::parse($payment->time)->format('M Y'), $payment_array)) {
                $payment_array[Carbon::parse($payment->time)->format('M Y')] = (int)$payment_array[Carbon::parse($payment->time)->format('M Y')] + (int)$payment->amount;
            } else {
                $payment_array[Carbon::parse($payment->time)->format('M Y')] = (int)$payment->amount;
            }
        }

        return $payment_array;
    }
}
