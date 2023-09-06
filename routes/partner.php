<?php

Route::group(['namespace' => 'Partner'], function () {
    // Mulitiple checklist for booking

    Route::get('lead/multiple/checklist/{id}/{checklistId}', 'LeadsController@getMultipleChecklist')->name('get-multiple-checklist');
    Route::post('lead/multiple/checklist', 'LeadsController@storeMultipleChecklist')->name('lead-multiple-checklist');
    Route::delete('lead/multiple/checklist/{id}', 'LeadsController@destoryMultipleChecklist')->name('delete-multiple-checklist');
    Route::patch('lead/multiple/checklist/{id}', 'LeadsController@updateMultipleChecklist')->name('update-multiple-checklist');

    // Booking device

    Route::get('lead/booking-device/{id}/{checklisttypeid}', 'LeadsController@getBookingDevice')->name('get-booking-devices');
    Route::post('lead/booking-device/', 'LeadsController@storeBookingDevice')->name('lead-booking-device');
    Route::delete('lead/booking-device/{id}', 'LeadsController@destoryBookingDevice')->name('delete-booking-device');
    Route::patch('lead/booking-device/{id}', 'LeadsController@updateBookingDevice')->name('update-booking-device');
    
    //Add booking device images
    Route::post('lead/booking-device/{id}/image', 'LeadsController@addBookingDeviceImage')->name('add-booking-device-image');
    Route::delete('lead/booking-device/image/{mediaId}', 'LeadsController@deleteBookingDeviceImage')->name('delete-booking-device-image');

    // Leads
    Route::get('lead/new', 'LeadsController@new')->name('partner.lead.new');
    Route::get('lead/accepted', 'LeadsController@accepted')->name('partner.lead.accepted');
    Route::get('lead/cancelled', 'LeadsController@cancelled')->name('partner.lead.cancelled');
    Route::get('lead/completed', 'LeadsController@completed')->name('partner.lead.completed');
    Route::get('lead/{lead}', 'LeadsController@index')->name('partner.lead.details');
    Route::delete('lead/{lead}/cancel', 'LeadsController@cancel')->name('partner.lead.cancel');
    Route::post('lead/{lead}/accept', 'LeadsController@accept')->name('partner.lead.accept');
    Route::post('lead/{lead}/submit', 'LeadsController@submit')->name('partner.lead.submit');
    Route::post('lead/{lead}/cod-submit', 'LeadsController@codSubmit')->name('partner.lead.cod-submit');
    // Verify OTP
    Route::post('lead/{lead}/verify-otp', 'LeadsController@verifyOtp')->name('partner.otp.verify');

    //Reschedule
    Route::post('reschedule/request/{booking}', 'LeadsController@sendRescheduleRequest')->name('send.reschedule.request');

    // Reschedule approve and decline
    Route::post('approve/reschedule/request/{booking}', 'LeadsController@approveRescheduleRequest')->name('approve.reschedule.request-by-partner');
    Route::post('decline/reschedule/request/{booking}', 'LeadsController@declineRescheduleRequest')->name('decline.reschedule.request-by-partner');
});

Route::group(['namespace' => 'Partner\Auth'], function () {
    Route::post('register', 'AuthController@register')->name('partner.register');
    Route::post('login', 'LoginController@login')->name('partner.login');
    Route::post('logout', 'LoginController@logout')->name('partner.logout');
    Route::get('user', 'AuthController@getUser')->name('partner.get');
    Route::patch('user', 'AuthController@update')->name('partner.update');

    Route::get('documents', 'AuthController@getDocument')->name('partner.documents');
    Route::patch('documents', 'AuthController@storeUpdateDocuments')->name('partner.update.documents');

    Route::get('earning', 'AuthController@getEarning')->name('partner.earning');

    Route::get('/{type}/all', 'AuthController@all')->name('partner.all');

    // Block unblock
    Route::post('/{partner}/block', 'StatusController@block')->name('partner.block');
    Route::post('/{partner}/unblock', 'StatusController@unblock')->name('partner.unblock');

    // email verification
    Route::post('/email/verify/resend', 'VerifyEmailController@resend')->name('partner.verification.resend');
    Route::post('/email/verify/{id}', 'VerifyEmailController@verifyEmail')->name('partner.verification.verify');

    // Password Resets
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('partner.password.email');
    Route::post('/password/reset', 'ResetPasswordController@reset')->name('partner.password.request');
    Route::post('/password/update', 'ResetPasswordController@updatePassword')->name('partner.password.update');

    Route::post('email/verify', 'EmailVerificationController@sendEmailVerification');
    Route::post('forgot/password/email', 'ChangePasswordController@sendResetLinkEmail');

    /** Police Verification */
    Route::post('/{partner}/police-verify', 'StatusController@policeVerification')->name('partner.police.verification');
});

Route::get('notification', 'NotificationController@partnerNotification')->name('partner.notification');
