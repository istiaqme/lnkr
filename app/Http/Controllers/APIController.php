<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function index(){
        try{
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Start Making Something Great.',
                    'data' => [
                        'name' => config('app.name'),
                        'base_url' => config('app.url'),
                        'base_api_url' => config('app.url').'/api'
                    ]
                ], 
            200);
        }
        catch(\Exception $e){
            $message = config('app.debug') ? $e->getMessage()  : 'System Error: Contact to the service provider.';
            return response()->json(
                [
                    'status' => 'success',
                    'message' => $message,
                ], 
            200);
        }
    }
}
