<?php

namespace App\Http\Middleware\Other;

use App\Exceptions\UnAuthorized;
use App\Models\Common\ApiKey;
use Closure;

class VerifyAPIKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param  \Closure  $next
     *
     * @return mixed
     *
     * @throws UnAuthorized
     */
    public function handle($request, Closure $next,$apiKey)
    {
        $this->authenticateApiKey($apiKey);

        return $next($request);
    }
    /**
     * Determine if the user is logged in to any of the given guards.
     *
     * @param string $apiKey
     *
     * @return void
     *
     * @throws UnAuthorized
     */
    protected function authenticateApiKey($apiKey)
    {
        if ($apiKey === '') {

            throw new UnAuthorized();
        }

        $apikey = explode('-', $apiKey);

        $verify_apikey = ApiKey::where('device', [$apikey[0]])->first();

        if (strcmp($apikey[1], $verify_apikey->key) !== 0) {

            throw new UnAuthorized();
        }
    }
}
