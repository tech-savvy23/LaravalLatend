@component('mail::message')
# Welcome to {{ env('APP_NAME') }}

Hii {{$user->name}},

We are very happy to see you here.

@component('mail::button', ['url' => 'http://twitter.com/intent/tweet?text=Just joined @'.env('APP_NAME').'to start my
learning journey.'])
Tweet About it
@endcomponent

# Setting
You can change password, go to profile->password<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent