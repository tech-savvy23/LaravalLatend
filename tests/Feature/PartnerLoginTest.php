<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Media;
use App\Models\Partner;
use Illuminate\Support\Facades\Hash;
use App\Notifications\Auth\VerifyEmail;
use Illuminate\Support\Facades\Storage;
use App\Notifications\Auth\ResetPassword;
use App\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartnerLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_provide_user_details()
    {
        $partner      = $this->create_partner();
        $this->actingAs($partner, 'partner');
        $user         = auth()->user();
        $res          = $this->getJson(route('partner.get'));
        $this->assertEquals($user->name, json_decode($res->getContent())->data->name);
    }

    /** @test */
    public function user_can_login_and_then_logout()
    {
        $user = factory(Partner::class)->create(['active' => true]);
        // dd($user);
        $this->postJson(route('partner.login'), [
            'email'       => $user->email, 'password'=>'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
        $this->assertTrue(auth('partner')->check());
        $this->postJson(route('partner.logout'));
        $this->assertFalse(auth('partner')->check());
    }

    /** @test */
    public function only_active_partner_can_login()
    {
        $user     = factory(Partner::class)->create(['active' => true]);
        $response = $this->postJson(route('partner.login'), [
            'email'       => $user->email, 'password'=>'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
        $this->assertTrue(auth('partner')->check());
        $this->postJson(route('partner.logout'));

        $partner = factory(Partner::class)->create(['active' => false]);
        $this->postJson(route('partner.login'), [
            'email'       => $partner->email, 'password'=>'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
        $this->assertFalse(auth('partner')->check());
    }

    /** @test */
    public function inactive_partner_get_message()
    {
        $user = factory(Partner::class)->create(['active' =>false]);
        $res  = $this->postJson(route('partner.login'), [
            'email'       => $user->email, 'password'=>'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ])->json();
        $this->assertArrayHasKey('errors', $res);
        $this->assertFalse(auth('partner')->check());
    }

    /** @test */
    public function a_user_can_register_and_get_email_confirmation()
    {
        // Notification::fake();
        $res = $this->postJson(route('partner.register'), [
            'name'                  => 'sarthak',
            'email'                 => 'sarthak@bitfumes.com',
            'type'                  => Partner::TYPE_AUDITOR,
            'phone'                 => 9999999999,
            'city'                  => 'Delhi',
            'state'                 => 'Delhi',
            'pin'                   => 374744,
            'latitude'              => 'asdfasdfds',
            'longitude'             => 'asdf',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $user = Partner::first();
        // Notification::assertSentTo($user, VerifyEmail::class);
        // $this->assertNotNull(json_decode($res->getContent())->access_token);
        $this->assertDatabaseHas('partners', ['email'=>'sarthak@bitfumes.com', 'email_verified_at'=>null, 'name' => 'Sarthak']);
    }

    /** @test */
    public function a_user_get_404_if_user_not_found_via_id_while_verifying_email()
    {
        $this->postJson(route('partner.verification.verify', $notInDBUserId = 4000), ['signature'=>'random'])->assertStatus(404);
    }

    /** @test */
    public function user_can_resend_verify_email()
    {
        Notification::fake();
        $user = factory(Partner::class)->create();
        $this->postJson(route('partner.verification.resend', $user->toArray()));
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function user_can_Verify_its_email()
    {
        $user = factory(Partner::class)->create();
        $this->postJson(route('partner.verification.verify', $user->id));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function for_registration_email_password_and_name_is_required()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.register'));
        $res->assertSessionHasErrors(['email', 'password', 'name']);
    }

    /** @test */
    public function for_registration_email_must_be_real_email()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.register'), ['email'=>'sarthak.com']);
        $this->assertEquals(session('errors')->get('email')[0], 'The email must be a valid email address.');
    }

    /** @test */
    public function for_registration_password_must_be_min_of_8_chars()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.register'), ['password'=>'abcd', 'password_confirmation'=>'abcd']);
        $this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 8 characters.');
    }

    /** @test */
    public function for_registration_name_must_be_max_of_25_chars()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.register'), ['name'=>'ankur sarthak shrivastava savvy']);
        $this->assertEquals(session('errors')->get('name')[0], 'The name may not be greater than 25 characters.');
    }

    /** @test */
    public function a_user_can_login_with_email_and_password()
    {
        $user = factory(Partner::class)->create(['email_verified_at'=>Carbon::now()->subDay(), 'active' => true]);
        $res  = $this->postJson(route('partner.login'), [
            'email'       => $user->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ])->json();
        $this->assertEquals($user->email, auth('partner')->user()->email);
        $this->assertNotNull($res['access_token']);
    }

    /** @test */
    public function for_login_email_password_required()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.login'));
        $res->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function for_login_email_must_be_real_email()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.login'), ['email'=>'sarthak.com']);
        $this->assertEquals(session('errors')->get('email')[0], 'The email must be a valid email address.');
    }

    /** @test */
    public function for_login_password_must_be_min_of_8_chars()
    {
        $this->withExceptionHandling();
        $res = $this->post(route('partner.login'), ['password'=>'abcd', 'password_confirmation'=>'abcd']);
        $this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 8 characters.');
    }

    /**
    * @test
    */
    public function a_password_reset_link_email_can_be_sent()
    {
        Notification::fake();
        $user = factory(Partner::class)->create();
        $res  = $this->postJson(route('partner.password.email'), ['email' => $user->email])->assertStatus(202);
        Notification::assertSentTo([$user], ResetPassword::class);
    }

    /** @test */
    public function a_user_can_change_its_password()
    {
        Notification::fake();
        $user = factory(Partner::class)->create();
        $this->post(route('partner.password.email'), ['email' => $user->email]);
        Notification::assertSentTo([$user], ResetPassword::class, function ($notification) use ($user) {
            $token = $notification->token;
            $this->assertTrue(Hash::check('secret123', $user->password));

            $res = $this->post(route('partner.password.request'), [
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

        $this->authUser();
        $user = $this->create_partner();
        $this->actingAs($user, 'partner');
        $res  = $this->patch(route('partner.update'), [
            'name'       => $user->name,
            'image'      => $image,
            'email'      => 'abc@def.com', ]);

        $this->assertEquals($user->name, json_decode($res->getContent())->data->name);

        $this->assertInstanceOf(Media::class, $user->media);
        $this->assertDatabaseHas('media', ['name' => $user->fresh()->media->name]);
        Storage::disk('public')->assertExists('images/' . $user->fresh()->media->name);
    }

    /** @test */
    public function api_can_give_all_the_partner_according_to_type()
    {
        $this->create_partner(['type' => Partner::TYPE_AUDITOR], 2);
        $this->create_partner(['type' => 'contractor']);
        $res  = $this->getJson(route('partner.all', Partner::TYPE_AUDITOR))->json();
        $this->assertEquals(2, count($res['data']));
    }

    /** @test */
    public function device_id_is_required()
    {
        $this->withExceptionHandling();
        $partner = $this->create_partner();

        $response = $this->postJson(route('partner.login'), [
            'email'   => $partner->email,
            'password'=> 'secret123',
        ]);
        $response->assertJsonStructure(['errors'=>['device_id']]);
    }

    /** @test */
    public function device_type_is_required()
    {
        $this->withExceptionHandling();
        $partner = $this->create_partner();

        $response = $this->postJson(route('partner.login'), [
            'email'   => $partner->email,
            'password'=> 'secret123',
        ]);
        $response->assertJsonStructure(['errors'=>['device_type']]);
    }

    /** @test */
    public function token_is_required()
    {
        $this->withExceptionHandling();
        $partner = $this->create_partner();

        $response = $this->postJson(route('partner.login'), [
            'email'   => $partner->email,
            'password'=> 'secret123',
        ]);
        $response->assertJsonStructure(['errors'=>['token']]);
    }

    /** @test */
    public function store_device_id_and_device_type()
    {
        $this->withExceptionHandling();
        $partner = $this->create_partner();

        $response = $this->postJson(route('partner.login'), [
            'email'       => $partner->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('partner_devices', [
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
        ]);
    }

    /** @test */
    public function if_token_is_already_available_than_not_create_new_one()
    {
        $this->withExceptionHandling();
        $partner = $this->create_partner();

        $response = $this->postJson(route('partner.login'), [
            'email'       => $partner->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);
        $response = $this->postJson(route('partner.login'), [
            'email'       => $partner->email,
            'password'    => 'secret123',
            'device_id'   => 'e63df1c8341d3e8b',
            'device_type' => 'android',
            'token'       => 'fWiirCFSnB8:APA91bEM6CMIRIT_IynKBmV3M0y4f5enPX0Gjwp3a40GGfWa0N7YKVtSRv5OAIwne9A40CnWjBehimhA0B7hUGFJroiWgTJXbXqlNXu6yX41ZA5cIM-ito4JXng-KF8brVjFjM1FPGyM',
        ]);

        $this->assertEquals(1, $partner->partnerDevices->count());
    }

    /** @test */
    public function it_can_provide_partner_details()
    {
        $partner      = $this->create_partner();
        $res          = $this->getJson(route('partner.profile', $partner->id));
        $this->assertEquals($partner->name, json_decode($res->getContent())->data->name);
    }

    /** @test */
    public function partner_can_not_registered_if_he_already_registered_in_user()
    {
        $this->withExceptionHandling();

        factory(User::class)->create([
            'mobile_verified'=>true,
            'email'    => 'xyz@gmail.com',
            ]);
            
        $res = $this->postJson(route('partner.register'), [
            'name'                  => 'sarthak',
            'email'                 => 'xyz@gmail.com',
            'type'                  => Partner::TYPE_AUDITOR,
            'phone'                 => 9999999999,
            'city'                  => 'Delhi',
            'state'                 => 'Delhi',
            'pin'                   => 374744,
            'latitude'              => 'asdfasdfds',
            'longitude'             => 'asdf',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $res->assertStatus(422);
        $res->assertJson([
            'errors' => [
                'email' => [
                    'The email has already been taken.'
                ]
            ]
        ]);
    }
}
