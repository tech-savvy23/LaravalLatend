<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function show()
    {
       return view('.privacy_policy');
    }
    public function how()
    {
        return view('.howitwork');
    }

    public function terms()
    {
        return view('.terms');
    }

    public function faq()
    {
        return view('.faq');
    }

    public function help()
    {
        return view('.help');
    }

    public function home()
    {
        return view('.home');
    }

    public function about()
    {
        return view('.home');
    }
}
