<?php

namespace App\Http\Middleware;

use App\Api;
use Closure;
use Auth;
use Illuminate\Routing\Middleware\ThrottleRequests;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, string $action)
    {
        switch (strtolower($action)) {
            case 'auth':
                if($request->has('apiKey'))
                    $this->handleAPIAuthentication($request);
                break;

            case 'throttle':
                $config = config('api.throttle');
                return app(ThrottleRequests::class)->handle($request, $next, $config['maxAttempts'], $config['decay']);
                break;
        }

        return $next($request);
    }

    protected function handleAPIAuthentication($request, Closure $next) {
      $apiKey = $request->get('apiKey');
      if(!$apiKey)
        return response()->status(403)->json(['message'  =>  'Please append your API key.']);

      $api = Api::whereApiKey($apiKey)->firstOrFail();
      if($api)
      {
        Auth::login($api->user);
        return $next($request);
      } else {
        return response()->status(401)->json(['message'  =>  'invalid API key.']);
      }
    }
}
