<?php


namespace App\Models\Common;

use App\Models\User\UserDevice;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\Exceptions\InvalidOptionsException;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


class FireBaseMessaging
{
    /**
     * @param $title
     * @param $body
     * @param $token
     * @return mixed
     * @throws \LaravelFCM\Message\Exceptions\InvalidOptionsException
     */
    public static function send($title,$body,$token)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)
            ->setSound('default');
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $downstreamResponse = FCM::sendTo($token, $option, $notification);

        return $downstreamResponse;
    }

    /**
     * Send Notification to users
     *
     * @param $title
     * @param $body
     * @param $tokens
     * @param $userDevice
     *
     * @return bool
     */
    public static function sendNotification($title,$body,$tokens,$userDevice,$user)
    {
        if (count($tokens)) {
            try {
                self::setEnvironment($user);
                $downstreamResponse = self::send($title, $body, $tokens);
                if ($downstreamResponse->numberFailure())
                    self::tokenFailureActions($userDevice, $downstreamResponse);

                if ($downstreamResponse->numberModification())
                    self::tokenModificationActions($userDevice, $downstreamResponse);

                return $downstreamResponse->numberSuccess();


            } catch (InvalidOptionsException $e) {
            }

        }

    }

    /**
     * Token Failure Actions
     * @param $userDevice
     * @param $downstreamResponse
     * @return bool
     */
    public static function tokenFailureActions($userDevice, $downstreamResponse): bool
    {
        /** On Delete */
        if (!empty($downstreamResponse->tokensToDelete())) {
            foreach ($downstreamResponse->tokensToDelete() as $deleteToken) {
                $user_devices = $userDevice->where('token', $deleteToken);
                if (count($user_devices) > 0) {
                    foreach ($user_devices as $user_device) {
                        $user_device->delete();
                    }
                }
            }
        }
        return $downstreamResponse->numberFailure();
    }

    /**
     * Token modification actions
     * @param $userDevice
     * @param $downstreamResponse
     * @return bool
     */
    public static function tokenModificationActions($userDevice, $downstreamResponse): bool
    {
        /** On modifications */
        if (!empty($downstreamResponse->tokensToModify())) {
            foreach ($downstreamResponse->tokensToModify() as $modifyKey => $modifyValue) {
                $user_devices = $userDevice->where('token', $modifyKey);
                if (count($user_devices) > 0) {
                    foreach ($user_devices as $user_device) {
                        $user_device->update(['token' => $modifyValue]);
                    }
                }
            }
        }
       return $downstreamResponse->numberModification();
    }

    public static function setEnvironment($user)
    {
        config(['fcm.http.server_key' => env('FCM_ANDROID_IOS_'.$user.'_SERVER_KEY')]);
        config(['fcm.http.sender_id' => env('FCM_ANDROID_IOS_'.$user.'_SENDER_ID')]);

    }
}
