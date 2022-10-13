<?php

namespace App\Http\Middleware;

use App\Models\App;
use Closure;
use Illuminate\Http\Request;

class APIAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // get http referer
        $requestFromApp = $request->headers->get('referer');
        if(config('app.env') == 'local'){
            $requestFromApp = 'Localhost'; // This is pre seeded data
        }
        $apiKey = $request->headers->get('auth-key');
        if(!$requestFromApp){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Invalid App.'
                ], 
            400);
        }
        // check database has app information
        $app = App::where('base_url', $requestFromApp)->where('api_key', $apiKey)->first();

        if(!$app){
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Login Failed.'
                ], 
            401);
        }

        // check for binded ips
        if(count($app->binded_ips) == 0){
            // ip bind needs not to be checked, add app info to request for future use and return next
            $request->add(['app_id' => $app->id]);
            return $next($request);
        }
        
        // as binded ips are in array, check the current ip is in the list or not
        if(in_array($request->ip(), $app->binded_ips)){
            $request->add(['app_id' => $app->id]);
            return $next($request);
        }
        else{
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Login Failed.'
                ], 
            422);
        }





        
    }
}
