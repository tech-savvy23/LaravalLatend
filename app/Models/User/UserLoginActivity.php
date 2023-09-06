<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserLoginActivity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_id', 'ip', 'user_agent', 'last_activity', 'timezone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        'user_agent', 'ip',
    ];

    /**
     * Add login activity
     *
     * @param $request
     *
     * @param Auth $userLogin
     */

    public function store($request,$userLogin)
    {

        $this->create([
            'login_id' =>$userLogin->id,
            'ip' => $request->ip,
            'user_agent' => $request->user_agent,
            'timezone' => $request->timezone,
            'last_activity' => $request->date_time,
         ]);
    }


}
