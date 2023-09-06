<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Common\Otp;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Support\Facades\Notification;
use App\Notifications\WelcomeCustomerByAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_provide_user_details()
    {
        $user      = $this->create_user();
        $this->actingAs($user);
        $user         = auth()->user();
        $res          = $this->getJson(route('user.get'));
        $this->assertEquals($user->first_name, json_decode($res->getContent())->data->first_name);
    }

    /** @test */
    public function user_can_login_and_then_logout()
    {
        $user = factory(User::class)->create();
        $this->postJson(route('user.login'), [
            'username'              => $user->email,
            'password'              => 'secret123',
            'device_id'             => 'e63df1c8341d3e8b',
            'device_type'           => 'android',
            'token'                 => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
        $this->assertTrue(auth()->check());
        $this->postJson(route('user.logout'));
        $this->assertFalse(auth()->check());
    }

    // /** @test */
    // public function a_user_can_register_and_get_email_confirmation()
    // {
    // Notification::fake();
    // $res = $this->postJson(route('user.register'), [
    //     'first_name'            => 'sarthak',
    //     'last_name'             => 'shrivastava',
    //     'email'                 => 'sarthak@bitfumes.com',
    //     'mobile'                => 9999999999,
    //     'password'              => 'secret123',
    //     'password_confirmation' => 'secret123',
    // ]);

    // $user = User::first();
    // Notification::assertSentTo($user, VerifyEmail::class);
    // dd($res->getContent()->message);
    // $this->assertDatabaseHas('users', ['email'=>'sarthak@bitfumes.com', 'email_verified_at'=>null]);
    // }

    /** @test */
    public function admin_can_get_all_users()
    {
        $this->create_user([], 10);
        $res = $this->getJson(route('user.all'))->json();
        $this->assertEquals(10, count($res['data']));
    }

    /** @test */
    public function a_user_can_register_and_get_sms_otp()
    {
        // Notification::fake();
        $res = $this->postJson(route('user.register'), [
            'first_name'            => 'sarthak',
            'last_name'             => 'shrivastava',
            'email'                 => 'sarthak@bitfumes.com',
            'mobile'                => 9999999999,
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $user = User::first();
        $this->assertDatabaseHas('otps', ['for_id' => $user->id]);
        // Notification::assertSentTo($user, VerifyEmail::class);
        // dd($res->getContent()->message);
        $user  = User::latest()->first();
        $this->assertDatabaseHas('users', ['email'=>'sarthak@bitfumes.com', 'email_verified_at'=>$user->email_verified_at]);
    }

    /** @test */
    public function an_admin_can_register_a_user()
    {
        Notification::fake();
        $this->create_admin();
        $res = $this->postJson(route('user.register.byAdmin'), [
            'first_name'            => 'sarthak',
            'last_name'             => 'shrivastava',
            'email'                 => 'sarthak@bitfumes.com',
            'mobile'                => 9999999999,
        ]);
        $user = User::first();
        Notification::assertSentTo($user, WelcomeCustomerByAdmin::class);
        $this->assertDatabaseHas('users', ['email'=>'sarthak@bitfumes.com', 'email_verified_at'=>Carbon::now()]);
    }

    /** @test */
    public function a_user_get_404_if_user_not_found_via_id_while_verifying_email()
    {
        $this->postJson(route('user.verification.verify', $notInDBUserId = 4000), ['signature'=>'random'])->assertStatus(404);
    }

    /** @test */
    public function user_can_resend_verify_email()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $this->postJson(route('user.verification.resend', $user->toArray()));
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function user_can_Verify_its_email()
    {
        $user = factory(User::class)->create();
        $this->postJson(route('user.verification.verify', $user->id));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function user_can_verify_its_otp()
    {
        $user = factory(User::class)->create(['mobile_verified' => false]);
        $otp  = factory(Otp::class)->create(['for_id' => $user->id, 'for_type'=>get_class($user)]);
        $this->postJson(route('user.mobile.verify', $user->mobile), ['otp' => $otp->otp]);
        $this->assertTrue($user->fresh()->mobile_verified);
        $this->assertDatabaseMissing('otps', ['for_id' => $user->id]);
    }

    /** @test */
    public function for_registration_email_password_firstname_last_name_mobile_is_required()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('user.register'));
        $res->assertSessionHasErrors(['email', 'password', 'first_name', 'last_name', 'mobile']);
    }

    /** @test */
    public function for_registration_email_must_be_real_email()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('user.register'), ['email'=>'sarthak.com']);
        $this->assertEquals(session('errors')->get('email')[0], 'The email must be a valid email address.');
    }

    /** @test */
    public function for_registration_password_must_be_min_of_8_chars()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('user.register'), ['password'=>'abcd', 'password_confirmation'=>'abcd']);
        $this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 8 characters.');
    }

    /** @test */
    public function a_user_can_login_with_email_and_password()
    {
        $user = factory(User::class)->create(['mobile_verified'=>true]);
        $res  = $this->postJson(route('user.login'), [
            'username'    => $user->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ])->json();
        $this->assertEquals($user->email, auth()->user()->email);
        $this->assertNotNull($res['access_token']);
    }

    /** @test */
    public function a_user_can_get_error_for_invalid_email()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create(['mobile_verified'=>true]);
        $res  = $this->post(route('user.login'), ['username' => 'invalidEmail', 'password'=>'secret123']);
        $res->assertSessionHasErrors('username');
    }

    /** @test */
    public function a_user_can_get_error_for_invalid_mobile_number()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create(['mobile_verified'=>true]);
        $res  = $this->post(route('user.login'), ['username' => 888, 'password'=>'secret123']);
        $res->assertSessionHasErrors('username');
    }

    /** @test */
    public function for_login_username_password_required()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('user.login'));
        $res->assertSessionHasErrors(['username', 'password']);
    }

    /** @test */
    public function for_login_password_must_be_min_of_8_chars()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('user.login'), ['password'=>'abcd', 'password_confirmation'=>'abcd']);
        $this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 8 characters.');
    }

    /**
    * @test
    */
    public function a_password_reset_link_email_can_be_sent()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $res  = $this->postJson(route('user.password.email'), ['email' => $user->email])->assertStatus(202);
        Notification::assertSentTo([$user], ResetPassword::class);
    }

    /** @test */
    public function a_user_can_change_its_password()
    {
        Notification::fake();
        $user = factory(User::class)->create();
        $this->post(route('user.password.email'), ['email' => $user->email]);
        Notification::assertSentTo([$user], ResetPassword::class, function ($notification) use ($user) {
            $token = $notification->token;
            $this->assertTrue(Hash::check('secret123', $user->password));

            $res = $this->postJson(route('user.password.request'), [
                'email'                 => $user->email,
                'password'              => 'newpassword',
                'password_confirmation' => 'newpassword',
                'token'                 => $token,
            ])->assertStatus(202);
            $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
            return true;
        });
    }

    /** @test */
    public function api_can_update_user_details()
    {
        Storage::fake();
        $image  = \Illuminate\Http\Testing\File::image('image.jpg');
        $image  = base64_encode(file_get_contents($image));

        $bookingreport['images'] = ["data:image/png;base64,{$image}", "data:image/png;base64,{$image}"];

        $user = $this->create_user(['image' => null]);
        $this->actingAs($user);
        $res  = $this->patch(route('user.update'), [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'mobile'     => $user->mobile,
            'image'      => $image,
            'email'      => 'abc@def.com', ]);

        $this->assertEquals('abc@def.com', json_decode($res->getContent())->data->email);
        $this->assertDatabaseHas('users', ['image' => $user->fresh()->image]);
        Storage::disk('public')->assertExists('images/' . $user->fresh()->image);
    }

    /** @test */
    public function device_id_is_required()
    {
        $this->withExceptionHandling();
        $user = $this->create_user();

        $response = $this->postJson(route('user.login'), [
            'username' => $user->email,
            'password' => 'secret123',
        ]);
        $response->assertJsonStructure(['errors'=>['device_id']]);
    }

    /** @test */
    public function device_type_is_required()
    {
        $this->withExceptionHandling();
        $user = $this->create_user();

        $response = $this->postJson(route('user.login'), [
            'username' => $user->email,
            'password' => 'secret123',
        ]);
        $response->assertJsonStructure(['errors'=>['device_type']]);
    }

    /** @test */
    public function token_is_required()
    {
        $this->withExceptionHandling();
        $user = $this->create_user();

        $response = $this->postJson(route('user.login'), [
            'username' => $user->email,
            'password' => 'secret123',
        ]);
        $response->assertJsonStructure(['errors'=>['token']]);
    }

    /** @test */
    public function store_device_id_and_device_type()
    {
        $this->withExceptionHandling();
        $user = $this->create_user();

        $response = $this->postJson(route('user.login'), [
            'username'    => $user->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_devices', [
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
    }

    /** @test */
    public function if_token_is_already_available_than_not_create_new_one()
    {
        $this->withExceptionHandling();
        $user = $this->create_user();

        $this->postJson(route('user.login'), [
            'username'    => $user->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);

        $this->postJson(route('user.login'), [
            'username'    => $user->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);

        $this->assertEquals(1, $user->userDevices->count());
    }

    /** @test */
    public function user_can_not_registered_if_he_already_registered_in_partner()
    {
        $this->withExceptionHandling();

        $this->create_partner([
            'email' => 'xyz@gmail.com',
        ]);

        $res = $this->postJson(route('user.register'), [
            'first_name'            => 'sarthak',
            'last_name'             => 'shrivastava',
            'email'                 => 'xyz@gmail.com',
            'mobile'                => 9999999999,
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $res->assertStatus(422);
        $res->assertJson([
            'errors' => [
                'email' => [
                    'The email has already been taken.',
                ],
            ],
        ]);
    }
}
