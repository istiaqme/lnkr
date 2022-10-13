<?php

namespace App\Http\Controllers;

use App\Models\LinkGroup;
use App\Services\LinkGroupService;
use App\Services\LinkService;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function index()
    {
        try {
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Start Making Something Great.',
                    'data' => [
                        'name' => config('app.name'),
                        'base_url' => config('app.url'),
                        'base_api_url' => config('app.url') . '/api'
                    ]
                ],
                200
            );
        } catch (\Exception $e) {
            $message = config('app.debug') ? $e->getMessage()  : 'System Error: Contact to the service provider.';
            return response()->json(
                [
                    'status' => 'success',
                    'message' => $message,
                ],
                500
            );
        }
    }
    public function linkGroupCreate(Request $request)
    {
        try {
            // validations - not laravel default, all custom
            // check title is set and not empty
            if (!$request->filled('title')) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => "Title is required.",
                    ],
                    400
                );
            }
            // check title string length - max 252
            if (strlen($request->title) > 252) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => "Title is too big. Max limit is 252 characters.",
                    ],
                    400
                );
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
                200
            );
        } catch (\Exception $e) {
            dd($e);
            $message = config('app.debug') ? $e->getMessage()  : 'System Error: Contact to the service provider.';
            return response()->json(
                [
                    'status' => 'success',
                    'message' => $message,
                ],
                200
            );
        }
    }
    public function linkGroupList(Request $request)
    {
        try {


            // everything is okay, call the service
            $list = (new LinkGroupService())->linkGroupListByAppId(APP_ID, ['id', 'title', 'created_at']);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => "List Returned Successfully.",
                    'data' => [
                        'pagination' => false,
                        'count' => count($list),
                        'items' => $list
                    ]
                ],
                200
            );
        } catch (\Exception $e) {
            dd($e);
            $message = config('app.debug') ? $e->getMessage()  : 'System Error: Contact to the service provider.';
            return response()->json(
                [
                    'status' => 'success',
                    'message' => $message,
                ],
                200
            );
        }
    }


    // create a short key associated with link group

    public function createLink(Request $request)
    {
        try {

            // dd($request->all());

            // check if linkGroupId is send or not
            if (!$request->filled('linkGroupId')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Link group is not found'
                ], 400);
            }

            // check title filled 

            if (!$request->filled('title')) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => "Title is required.",
                    ],
                    400
                );
            }

            // check title length

            if (strlen($request->title > 252)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Title lenght can not be more than 252 characters'
                ], 400);
            }

            // check for short_key
            if (!$request->filled('short_key')) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => "Short Key is required",
                    ],
                    400
                );
            }

            // validate redirect_to
            if (!$request->filled('redirect_to')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Redirect URL can not be empty'
                ]);
            }
            //  validate notes
            if (!$request->filled('note')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please a note'
                ]);
            }

            // check link group id is valid or not

            $linkGroup = new LinkGroup();
            $linkGroup::where('id', $request->linkGroupId)->first();

            // retrun invalid linkGroup id response

            if (!$linkGroup) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Group not found'
                ], 400);
            }

            // if all request valid, create new link 

            $newLink = (new LinkService)->linkCreate($request->linkGroupId, $request->title, $request->redirect_to, APP_ID, [
                'shortKey' => $request->short_key,
                'note'  => $request->note
            ], [
                'ip' => $request->ip(),
                'user_agent' => $request->header('user-agent')
            ]);

            // return response
            
        } catch (\Exception $e) {
            dd($e);
            $message = config('app.debug') ? $e->getMessage()  : 'System Error: Contact to the service provider.';
            return response()->json([
                'status' => 'error',
                'message' => $message,
            ], 500);
        }
    }
}
