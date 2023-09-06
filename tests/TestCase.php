<?php

namespace Tests;

use App\User;
use App\Models\Area;
use App\Models\Space;
use App\Models\Report;
use App\Models\Address;
use App\Models\Booking;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use App\Models\AssetArea;
use App\Models\AssetItem;
use App\Models\Checklist;
use App\Models\SpaceType;
use App\Models\SubService;
use App\Models\BookingFile;
use App\Models\BookingAsset;
use App\Models\BookingSpace;
use App\Models\PartnerPrice;
use App\Models\ReportOption;
use App\Models\BookingDevice;
use App\Models\BookingReport;
use App\Models\ChecklistType;
use App\Models\BookingProduct;
use App\Models\BookingService;
use App\Models\RescheduleReason;
use App\Models\RescheduleRequest;
use App\Models\ReportOptionMessage;
use Bitfumes\Multiauth\Model\Admin;
use App\Models\BookingReportMessage;
use App\Models\BookingMultipleChecklist;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup(): void
    {
        parent::setUp();
        app()['config']->set('jwt.secret', 'abcdef');
        $this->withoutExceptionHandling();
    }

    /**
     * Create api key
     *
     * @param $args
     *
     * @param $num
     *
     */
    public function create_api_key($args = [], $num = null)
    {
        return factory(ApiKey::class, $num)->create($args);
    }

    public function create_space($args = [], $num = null)
    {
        return factory(Space::class, $num)->create($args);
    }

    public function create_space_type($args = [], $num = null)
    {
        return factory(SpaceType::class, $num)->create($args);
    }

    public function create_area($args = [], $num = null)
    {
        return factory(Area::class, $num)->create($args);
    }

    public function create_service($args = [], $num = null)
    {
        return factory(Service::class, $num)->create($args);
    }

    public function create_sub_service($args = [], $num = null)
    {
        return factory(SubService::class, $num)->create($args);
    }

    public function create_partner($args = [], $num = null)
    {
        return factory(Partner::class, $num)->create($args);
    }

    public function create_user($args = [], $num = null)
    {
        return factory(User::class, $num)->create($args);
    }

    public function create_booking($args = [], $num = null)
    {
        $address = $this->create_address(['longitude' => '23.123', 'latitude' => '23.123']);
        $args    = array_merge(['address_id' => $address->id], $args);
        return factory(Booking::class, $num)->create($args);
    }

    public function create_booking_service($args = [], $num = null)
    {
        return factory(BookingService::class, $num)->create($args);
    }

    public function create_address($args = [], $num = null)
    {
        return factory(Address::class, $num)->create($args);
    }

    public function create_reportoption($args = [], $num = null)
    {
        return factory(ReportOption::class, $num)->create($args);
    }

    public function create_checklist($args = [], $num = null)
    {
        return factory(Checklist::class, $num)->create($args);
    }

    public function create_checklisttype($args = [], $num = null)
    {
        return factory(ChecklistType::class, $num)->create($args);
    }

    public function create_report($args = [], $num = null)
    {
        return factory(Report::class, $num)->create($args);
    }

    public function create_report_message($args = [], $num = null)
    {
        return factory(ReportOptionMessage::class, $num)->create($args);
    }

    public function create_booking_space($args = [], $num = null)
    {
        return factory(BookingSpace::class, $num)->create($args);
    }

    public function create_assetarea($args = [], $num = null)
    {
        return factory(AssetArea::class, $num)->create($args);
    }

    public function create_assetitem($args = [], $num = null)
    {
        return factory(AssetItem::class, $num)->create($args);
    }

    public function create_bookingasset($args = [], $num = null)
    {
        return factory(BookingAsset::class, $num)->create($args);
    }

    public function create_bookingreport($args = [], $num = null)
    {
        return factory(BookingReport::class, $num)->create($args);
    }

    public function create_admin($args = [], $num = null)
    {
        $admin = factory(Admin::class, $num)->create($args);
        $this->actingAs($admin, 'admin');
    }

    public function create_payment($args = [], $num = null)
    {
        return factory(Payment::class, $num)->create($args);
    }

    public function create_partnerprice($args = [], $num = null)
    {
        return factory(PartnerPrice::class, $num)->create($args);
    }

    public function create_product($args = [], $num = null)
    {
        return factory(Product::class, $num)->create($args);
    }

    public function create_bookingproduct($args = [], $num = null)
    {
        return factory(BookingProduct::class, $num)->create($args);
    }

    public function authUser($args = [], $num = null)
    {
        $user =  factory(User::class, $num)->create($args);
        $this->actingAs($user);
        return $user;
    }

    public function create_reschedule_reason($args = [], $num = null)
    {
        return factory(RescheduleReason::class, $num)->create($args);
    }

    public function create_bookingreportmessage($args = [], $num = null)
    {
        return factory(BookingReportMessage::class, $num)->create($args);
    }

    public function create_booking_file($args = [], $num = null)
    {
        return factory(BookingFile::class, $num)->create($args);
    }

    public function create_reschedule_request($args = [], $num = null)
    {
        return factory(RescheduleRequest::class, $num)->create($args);
    }

    public function createMultipleChecklist($args = [], $num = null)
    {
        return factory(BookingMultipleChecklist::class, $num)->create($args);
    }

    public function createBookingDevice($args = [], $num = null)
    {
        return factory(BookingDevice::class, $num)->create($args);
    }
}
