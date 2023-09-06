<?php

namespace App\Http\Controllers\User;

use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth');
        $this->user = auth()->user();
    }


    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AddressResource::collection($this->user->address);
    }


}
