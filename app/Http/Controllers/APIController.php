<?php

namespace App\Http\Controllers;

use App\Services\LinkGroupService;
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
            500);
        }
    }
    public function linkGroupCreate(Request $request){
        try{
            // validations - not laravel default, all custom
            // check title is set and not empty
            if(!$request->filled('title')){
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => "Title is required.",
                    ], 
                400);
            }
            // check title string length - max 252
            if(strlen($request->title) > 252){
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => "Title is too big. Max limit is 252 characters.",
                    ], 
                400);
            }

            // now everything is okay, call the service
            $newEntry = (new LinkGroupService())->create($request);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => "Link Group Created Successfully.",
                    'data' => [
                        'id' => $newEntry->id,
                        'title' => $newEntry->title
                    ]
                ], 
            200);

        }
        catch(\Exception $e){
            dd($e);
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
