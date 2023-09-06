<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Partner;
use App\Notifications\SendNotifications;
use App\User;
use Bitfumes\Multiauth\Model\Admin;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationEveryMorning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'morning:sendNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send notification every morning.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $admins = Admin::all();
        $today_audits = Booking::todayAudits();
        $title = 'Today audits';
        $message = 'Today '. count($today_audits) .' audits are available.';
//        Notification::send($admins, new SendNotifications($title, $message));

       $this->partnerNotification();

    }

    /**
     * Send Notification to partners
     */
    private function partnerNotification()
    {
        $title = 'Today audits';
        $partners = Partner::all();
        foreach ($partners as $partner) {

            $leads = Booking::
            leftJoin('booking_allottees', 'booking_allottees.booking_id', '=', 'bookings.id')
                ->where('booking_allottees.allottee_id', $partner->id)
                ->whereDate('booking_time', Carbon::now())
                ->get();

            if ($leads) {
                $message = 'Today you have '. count($leads) .' audits.';

//                Notification::send($partner, new SendNotifications($title, $message));

            }

        }
    }


}
