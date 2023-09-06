<?php

namespace App\Http\Controllers\User\Auth;

use App\User;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

// use Bitfumes\ApiAuth\Helpers\ImageCrop;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function block(User $user)
    {
        $user->update(['active' => false]);
        return response('blocked', Response::HTTP_ACCEPTED);
    }

    public function unblock(User $user)
    {
        $user->update(['active' => true]);
        return response('unblocked', Response::HTTP_ACCEPTED);
    }
}
