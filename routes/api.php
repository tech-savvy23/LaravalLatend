<?php

use App\Models\City;
use App\Models\State;
use App\Http\Resources\BookingResource;
use Illuminate\Support\Facades\Route;

Route::apiResource('address', 'AddressController');
Route::apiResource('booking_allottee', 'BookingAllotteeController');
Route::apiResource('booking_cancel', 'BookingCancelController');
Route::apiResource('booking_service', 'BookingServiceController');

// Feedbacks
Route::get('feedback/partner', 'FeedbackController@partner')->name('feedback.partner');
Route::apiResource('feedback', 'FeedbackController');

Route::apiResource('payment', 'PaymentController');
Route::apiResource('rating', 'RatingController');
Route::apiResource('service', 'ServiceController');
Route::apiResource('space_type', 'SpaceTypeController');
Route::apiResource('space', 'SpaceController');
Route::apiResource('sub_service', 'SubServiceController');
Route::apiResource('booking_space', 'BookingSpaceController');
Route::apiResource('area', 'AreaController');
Route::get('get-spaces', function (){
   return \App\Models\Space::all();
});
// Coupon
Route::post('coupon/verify', 'CouponController@verify')->name('coupon.verify');
Route::post('coupon/{coupon}/active', 'CouponController@active')->name('coupon.active');
Route::delete('coupon/{coupon}/active', 'CouponController@inactive');
Route::get('coupon/all', 'CouponController@all')->name('coupon.all');
Route::apiResource('coupon', 'CouponController');

Route::apiResource('distributor', 'DistributorController');
Route::get('/product/search', 'ProductController@search')->name('product.search');
Route::apiResource('product', 'ProductController');

Route::get('checklist/all', 'ChecklistController@all');
Route::apiResource('checklist', 'ChecklistController');

Route::get('checklist/{checklist}/type', 'ChecklistTypeController@index')->name('checklisttype.index');
Route::post('checklist/{checklist}/type', 'ChecklistTypeController@store')->name('checklisttype.store');
Route::put('checklist/{checklist}/type/{type}', 'ChecklistTypeController@update')->name('checklisttype.update');
Route::delete('checklist/{checklist}/type/{type}', 'ChecklistTypeController@destroy')->name('checklisttype.destroy');

Route::get('checklist/{checklist}/type/{type}/report', 'ReportController@byType')->name('report.bytype');

Route::apiResource('checklist/{checklist}/report', 'ReportController');

Route::delete('reportoption/message/{message}', 'ReportOptionController@deleteMsg');
Route::apiResource('reportoption', 'ReportOptionController');
Route::post('bookingreport/message', 'BookingReportMessageController@store')->name('bookingreportmessage.store');
Route::delete('bookingreport/message', 'BookingReportMessageController@destroy')->name('bookingreportmessage.destroy');

//  Booking
Route::get('booking/{booking}/reports', 'BookingReportController@reports')->name('booking.report');
Route::get('booking/{booking}/reports/pdf', 'BookingReportPdfController@generate')->name('booking.report.pdf');
Route::post('booking/{bookingId}/image', 'BookingReportController@imageUpload')->name('booking.report.imageupload');
Route::post('booking/image/{id}', 'BookingReportController@imageDelete')->name('booking.report.imageDelete');

// media
Route::post('media', 'MediaController@delete')->name('media.delete');

Route::get('/booking/all', 'BookingController@all')->name('booking.all');

// All booking for xlxs
Route::get('/booking/all/xlsx', 'BookingController@allxlsx')->name('booking.all.xlsx');

Route::get('booking/{booking}/payments', 'BookingController@payments')->name('booking.payment');
Route::apiResource('booking', 'BookingController');
Route::post('booking/{booking}/request-contractor', 'BookingController@requestContractor')->name('booking.contractor-required');
Route::apiResource('bookingreport', 'BookingReportController')->except('show');
Route::get('bookingreport/{booking}', 'BookingReportController@show')->name('bookingreport.show');
Route::get('/booking-statics', 'BookingController@bookingStatics')->name('booking.statics');
Route::apiResource('assetarea', 'AssetAreaController');
Route::apiResource('bookingasset', 'BookingAssetController');
Route::apiResource('assetitem', 'AssetItemController');

// Feedbacks
// Route::post('/user/feedbacks', 'FeedbackController@index');

Route::apiResource('partnerprice', 'PartnerPriceController');

Route::get('partner-price/{booking}/{type}', 'PartnerPriceController@partnerPrice');

Route::get('states', function () {
    return State::all();
});
Route::post('cities', function () {
    if (request()->state_id) {
        return City::where('state_id', request()->state_id)->get();
    }
    return City::all();
});

Route::apiResource('bookingproduct', 'BookingProductController');
Route::get('bookingproduct/booking/{booking}', 'BookingProductController@products')->name('booking.products');

Route::get('header', 'BookingReportPdfController@header');

Route::post('booking/update/{booking_id}', 'BookingController@updateBookingDate')->name('update.booking.date');

Route::get('getbooking/{id}', function ($id) {
    $booking = \App\Models\Booking::find($id);
    return response(['data' => new BookingResource($booking)], 200);
});
Route::get('partners/{partner}', 'Partner\Auth\AuthController@get')->name('partner.profile');
Route::get('partner/{partner}/bookings', 'BookingController@partnerBookings');

//Reschedule Reason
Route::get('reschedule/reasons', 'RescheduleReasonController@index')->name('reschedule.reasons.index');
Route::post('reschedule/reasons', 'RescheduleReasonController@store')->name('reschedule.reasons.store');
Route::patch('reschedule/reasons/{rescheduleReasonId}', 'RescheduleReasonController@update')->name('reschedule.reasons.update');
Route::delete('reschedule/reasons/{rescheduleReasonId}', 'RescheduleReasonController@delete')->name('reschedule.reasons.delete');

Route::post('booking/{booking}/file', 'BookingFileController@store')->name('booking.file.store');
Route::get('booking/{booking}/file', 'BookingFileController@index')->name('booking.file.index');
Route::get('booking/file/download/{bookingFile}', 'BookingFileController@download')->name('booking.file.download');
Route::delete('booking/{booking}/file', 'BookingFileController@deleteFile')->name('booking.file.delete');

// Reschedule approve and decline
Route::post('approve/reschedule/request/{booking}', 'BookingController@approveRescheduleRequest')->name('approve.reschedule.request');
Route::post('decline/reschedule/request/{booking}', 'BookingController@declineRescheduleRequest')->name('decline.reschedule.request');

// Send reschedule request
 Route::post('reschedule/request/{booking}', 'BookingController@sendRescheduleRequestToPartner')->name('send.reschedule.request-to-partner');


 // Booking invoice
Route::get('booking/{booking}/invoice/pdf', 'BookingInvoiceController@generate')->name('booking.invoice.pdf');


Route::apiResource('booking-cancel', 'BookingBeforeAcceptCancelController');
Route::patch('payment-later/{paymentId}', 'PaymentController@paymentLater')->name('payment-later');
Route::apiResource('factory-city', 'FactoryCityController');
Route::apiResource('metro-and-non-metro', 'MetroAndNonMetroController');
Route::get('total-audits', 'BookingController@totalAudits');

Route::apiResource('color-codes', 'ColorCodeController');
Route::apiResource('booking-color-codes', 'BookingColorCodeController');

Route::get('reset-password/{password}', function ($password){
    return bcrypt($password);
});


