<?php

Route::group(['namespace' => 'User\Auth'], function () {
    Route::get('/', 'AuthController@getUser')->name('user.get');
    Route::patch('/', 'AuthController@update')->name('user.update');
    Route::post('register', 'AuthController@register')->name('user.register');
    Route::post('register/by-admin', 'AuthController@registerByAdmin')->name('user.register.byAdmin');
    Route::post('login', 'LoginController@login')->name('user.login');
    Route::post('logout', 'LoginController@logout')->name('user.logout');
    Route::get('all', 'AuthController@all')->name('user.all');

    // Block unblock
    Route::post('/{user}/block', 'StatusController@block')->name('user.block');
    Route::post('/{user}/unblock', 'StatusController@unblock')->name('user.unblock');

    // email verification
    Route::post('/email/verify/resend', 'VerifyEmailController@resend')->name('user.verification.resend');
    Route::post('/email/verify/{id}', 'VerifyEmailController@verifyEmail')->name('user.verification.verify');

    // mobile otp verification
    Route::post('/mobile/resend/{mobile}', 'VerifyMobileController@resend')->name('user.mobile.resend');
    Route::post('/mobile/verify/{mobile}', 'VerifyMobileController@verifyEmail')->name('user.mobile.verify');

    // Password Resets
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
    Route::post('/password/reset', 'ResetPasswordController@reset')->name('user.password.request');
    Route::post('/password/update', 'ResetPasswordController@updatePassword')->name('user.password.update');

    Route::post('email/verify', 'EmailVerificationController@sendEmailVerification');
    Route::post('forgot/password/email', 'ChangePasswordController@sendResetLinkEmail');
});
Route::get('address', 'User\AddressController@index')->name('user.address.index');
Route::get('notification', 'NotificationController@userNotification')->name('user.notification');
