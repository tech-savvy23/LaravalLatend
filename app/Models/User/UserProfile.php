<?php

namespace App\Models\User;

use App\Traits\VariablesTrait;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use VariablesTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_id','f_name','l_name','image'
    ];

   /**
     * Create a new user profile instance after a valid registration
     * and user login.
     *
     * @param  array  $data
     *
     * @param UserLogin $userLogin
     *
     * @return UserProfile
     */

    public function store(array $data,$userLogin)
    {
        return $this->create([
            'login_id' => $userLogin->id,
            'f_name' => $data['first_name'],
            'l_name' => $data['last_name'],
            'image' => $this->getProfile(),
        ]);
    }
}
