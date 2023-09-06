<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Partner;
use App\Notifications\SendNotifications;
use Bitfumes\Multiauth\Model\Admin;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationEveryEvening extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evening:sendNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'his will send notification every evening';

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
        $today_complete_audit = Booking::todayAuditedBookings();
        $title = 'Today complete audits';
        $message = 'Today '. count($today_complete_audit) .' audits are done.';
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
                ->where('bookings.status', Booking::AUDITED)
                ->whereDate('booking_time', Carbon::now())
                ->get();

            if ($leads) {
                $message = 'Today your  '. count($leads) .' audits are done.';
//                Notification::send($partner, new SendNotifications($title, $message));

            }

        }
    }
}
