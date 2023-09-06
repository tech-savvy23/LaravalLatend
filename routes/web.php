<?php

use Aws\Laravel\AwsFacade as AWS;

Route::group(['prefix' => 'customer', 'namespace' => 'User\Auth'], function () {
    Route::get('change/password/{email}/{token}', 'ChangePasswordController@changePassword')->name('change.password');
    Route::post('reset/password/{email}/{token}', 'ChangePasswordController@resetPassword')->name('reset.password');
    Route::get('successfully', 'ChangePasswordController@successfullyChanged')->name('successful.message');
    Route::get('verify/{email}/{token}', 'EmailVerificationController@verifyEmail')->name('verify.email');
});

Route::group(['prefix' => 'Partner', 'namespace' => 'Partner\Auth'], function () {
    Route::get('change/password/{email}/{token}', 'ChangePasswordController@changePassword')->name('partner.change.password');
    Route::post('reset/password/{email}/{token}', 'ChangePasswordController@resetPassword')->name('partner.reset.password');
    Route::get('successfully', 'ChangePasswordController@successfullyChanged')->name('partner.successful.message');
    Route::get('verify/{email}/{token}', 'EmailVerificationController@verifyEmail')->name('partner.verify.email');
});

Route::get('privacy-policy', 'PolicyController@show');
Route::get('about-us', 'PolicyController@about');
Route::get('how-it-work', 'PolicyController@how');
Route::get('terms','PolicyController@terms');
Route::get('faq','PolicyController@faq');
Route::get('help','PolicyController@help');
Route::get('/','PolicyController@home');
Route::get('otp/{mob}', function($mob)
{
    $aws = AWS::createClient('sns');
            $response = $aws->publish([
                'Message' => 'hello world',
                'PhoneNumber' => '+91'.$mob,
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SMSType'  => [
                        'DataType'    => 'String',
                        'StringValue' => 'Transactional',
                    ]
                ],
            ]);
            return $response;
});

Route::get('bcrypt', function ()
{
    return bcrypt('Secret123');
});

Route::get('feedback/{booking}/{bookingAllottee}','FeedbackController@feedback');
Route::post('feedback','FeedbackController@add')->name('feedback.add');
