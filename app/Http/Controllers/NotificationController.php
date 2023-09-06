<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NotificationController extends Controller
{
    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $partner;

    /**
     * NotificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only('userNotification');
        $this->user = auth()->user();
        $this->middleware('auth:partner')->only('partnerNotification');
        $this->partner = auth('partner')->user();
    }

    /**
     * @return mixed
     */
    public function userNotification()
    {
        return  response()->json($this->getNotifications($this->user->notifications));
    }

    /**
     * @return mixed
     */
    public function partnerNotification()
    {
        return response()->json($this->getNotifications($this->partner->notifications));
    }

    /**
     * @param $notifications
     * @return array
     */
    private function getNotifications($notifications)
    {
        $notification_list = [];
        foreach ($notifications as $notification) {
            foreach ($notification['data'] as $key => $value) {

                if ($key == 'message') {

                    $date = Carbon::parse($notification->created_at);
                    array_push($notification_list,[
                        'message' => $value,
                        'date' => $date->format('d M, Y'),
                        'time' => $date->format('H:i A')
                    ]);
                }
            }
        }
        return $notification_list;

    }
}
