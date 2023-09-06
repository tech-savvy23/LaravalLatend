<?php

namespace App\Traits;

trait VariablesTrait
{
    /**
     * Profile image link
     * @var
     */
    private $profile = 'https://lorempixel.com/200/200/?39638';

    /**
     * Get profile link
     */

     public function getProfile()
     {
         return $this->profile;
     }

     /**
     * Set profile link
     */

    public function setProfile($profile)
    {
        $this->profile = $profile ;
        return $this->profile;
    }


     /**
     * otp channel
     * @var
     */
    private $otp_channel = 'slack';

    /**
     * Get profile link
     */

     public function getOtpChannel()
     {
         return $this->otp_channel;
     }

     /**
     * Set profile link
     */

    public function setOtpChannel($channel)
    {
        $this->otp_channel = $channel ;
        return $this->otp_channel;
    }

}