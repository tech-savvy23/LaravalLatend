<?php

namespace App\Http\Controllers\Partner\Auth;

use App\Models\Partner;
use App\PartnerDocument;
use Illuminate\Routing\Controller;
// use Bitfumes\ApiAuth\Helpers\ImageCrop;
use App\Http\Resources\PartnerResource;
use App\Http\Requests\Partner\UpdateRequest;
use App\Http\Requests\Partner\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:partner')->only('getUser');
    }

    public function register(RegisterRequest $request)
    {
        // $customer = User::where('email',$request->email)->first();
        // if ($customer) {
        //     return response()->json([
        //         'errors' => [
        //             'email' => 'The email id is already exited'
        //         ]
        //     ], 422);
        // }
        $user = Partner::create($request->all());
        // $user->sendEmailVerificationNotification();
        return response(['message' => 'Account created successfully'], Response::HTTP_CREATED);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function update(UpdateRequest $request)
    {
        $user = auth('partner')->user();
        $this->checkForAvatar($request, $user);
        $user->update($request->except('image'));
        return response([
            'data'=> new PartnerResource($user),
        ], Response::HTTP_ACCEPTED);
    }

    public function checkForAvatar($request, $user)
    {
        if ($request->has('image')) {
            $user->uploadProfilePic($request->image);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function getUser()
    {
        $user = auth('partner')->user();
        return response([
            'data'=> new PartnerResource($user),
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('partner')->factory()->getTTL() * 60,
            'user'         => new PartnerResource(auth('partner')->user()),
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('partner')->refresh());
    }

    public function all($type)
    {
        $auditors = Partner::where('type', $type)->latest()->paginate(50);
        return PartnerResource::collection($auditors);
    }

    /**
     * @param Partner $partner
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function get(Partner $partner)
    {
        return response([
            'data'=> new PartnerResource($partner),
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * store documents
     *
     * @return boolean
     */
    public function storeDocuments($partner, $request)
    {
        //     $partner_document =

    //    return $partner->document;
    }

    /**
    * update documents
    *
    * @return JSON
    */
    public function storeUpdateDocuments()
    {
        $partner = auth('partner')->user();

        if ($partner->document) {
            $partner->document->update([
                'pan'  => request()->pan,
                'gst'  => request()->gst,
                'bank' => request()->bank,
            ]);
        } else {
            PartnerDocument::create([
                'partner_id' => $partner->id,
                'pan'        => request()->pan,
                'gst'        => request()->gst,
                'bank'       => request()->bank,
            ]);
        }

        return response([
            'data'=> $partner->fresh()->document,
        ], Response::HTTP_CREATED);
    }

    public function getDocument()
    {
        $partner = auth('partner')->user();
        return response([
            'data'=> $partner->fresh()->document,
        ], Response::HTTP_OK);
    }

    public function getEarning()
    {
        $partner = auth('partner')->user();
        return response()->json([
            'data' => [$partner->totalEarning()],
        ], 200);
    }
}
