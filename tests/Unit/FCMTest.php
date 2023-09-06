<?php

namespace Tests\Unit;

use App\Models\Common\FireBaseMessaging;
use App\Models\PartnerDevice;
use App\Models\User\UserDevice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;


class FCMTest extends TestCase
{

    use RefreshDatabase;
    /**
     * @test
     */
    public function on_delete_token_error()
    {

        $numberSuccess = 1;
        $mockResponse = new \LaravelFCM\Mocks\MockDownstreamResponse($numberSuccess);
        $mockResponse->addTokenToDelete('token_to_delete');

        $sender = Mockery::mock(FireBaseMessaging::class);
        $sender->shouldReceive('send')->once()->andReturn($mockResponse);
        $title = '';
        $body = 'body';
        $token = 'token';
        $data = $sender->send($title,$body,$token);
        $this->assertEquals(1,$data->numberFailure());
        $this->assertContains('token_to_delete',$data->tokensToDelete());
    }

    /**
     * @test
     */
    public function on_delete_with_error()
    {

        $numberSuccess = 1;
        $mockResponse = new \LaravelFCM\Mocks\MockDownstreamResponse($numberSuccess);
        $mockResponse->addTokenWithError('token_to_delete', 'something went wrong');

        $sender = Mockery::mock(FireBaseMessaging::class);
        $sender->shouldReceive('send')->once()->andReturn($mockResponse);
        $title = '';
        $body = 'body';
        $token = 'token';
        $data = $sender->send($title,$body,$token);
        $this->assertEquals(1,$data->numberFailure());
        $this->assertArrayHasKey('token_to_delete',$data->tokensWithError());

    }

    /**
     * @test
     */
    public function success_of_fcm_notification()
    {
        $numberSuccess = 1;
        $mockResponse = new \LaravelFCM\Mocks\MockDownstreamResponse($numberSuccess);

        $sender = Mockery::mock(FireBaseMessaging::class);
        $sender->shouldReceive('send')->once()->andReturn($mockResponse);
        $title = '';
        $body = 'body';
        $token = 'token';
        $data = $sender->send($title,$body,$token);
        $this->assertEquals(1,$data->numberSuccess());
    }

    /**
     * @test
     */
    public function on_modification_of_fcm()
    {

        $numberSuccess = 1;
        $mockResponse = new \LaravelFCM\Mocks\MockDownstreamResponse($numberSuccess);
        $mockResponse->addTokenToModify('token_to_modify', 'token_modified');

        $sender = Mockery::mock(FireBaseMessaging::class);
        $sender->shouldReceive('send')->once()->andReturn($mockResponse);
        $title = '';
        $body = 'body';
        $token = 'token';
        $data = $sender->send($title,$body,$token);
        $this->assertEquals(1,$data->numberModification());
        $this->assertArrayHasKey('token_to_modify',$data->tokensToModify());
    }

    /** @test */
    public function set_android_client_environment_variables()
    {
        FireBaseMessaging::setEnvironment('CLIENT');
        $this->assertEquals(env('FCM_ANDROID_IOS_CLIENT_SERVER_KEY'),config('fcm.http.server_key'));
        $this->assertEquals(env('FCM_ANDROID_IOS_CLIENT_SENDER_ID'),config('fcm.http.sender_id'));
    }

}
