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

            // check if linkGroupId is send or not
            if (!$request->linkGroupId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Link Group is mandatory for this request.'
                ], 400);
            }

            // check link group id is valid or not
            $linkGroupPermission = (new LinkGroupService())->appIdPermissionToLinkGroup($request->linkGroupId, APP_ID);
            // retrun invalid linkGroup id response
            if (!$linkGroupPermission) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Link Group not found.'
                ], 401);
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
                    'message' => 'Title length can not be more than 252 characters.'
                ], 400);
            }

            
            // validate redirect_to
            if (!$request->filled('redirectTo')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Redirect URL can not be empty.'
                ], 400);
            }
            
            // validate url structure
            if(!filter_var($request->redirectTo, FILTER_VALIDATE_URL))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Redirect URL is not in valid format.'
                ], 400);
            }

            $shortKey = $request->filled('shortKey') ? (new LinkService())->verifyAppDefinedShortKey($request->shortKey) : (new LinkService())->generateShortKey(6);
            $note = $request->filled('note') ? $request->note : null;



            
            // if all request valid, create new link 

            $newLink = (new LinkService)->linkCreate(
                $request->linkGroupId,
                $request->title,
                $request->redirectTo,
                APP_ID,
                [
                    'shortKey' => $shortKey,
                    'note'  => $note
                ],
                [
                    'ip' => $request->ip(),
                    'userAgent' => $request->header('user-agent')
                ]
            );

            // return response
            return response()->json([
                'status' => 'success',
                'message' => 'Link created succesfully.',
                'data' => [
                    'short_key' => $newLink->short_key,
                    'title' => $newLink->title,
                    'redirect_to' => $newLink->redirect_to,
                    'note' => $newLink->note,
                    'id' => $newLink->id,
                    'link_group_id' => $newLink->link_group_id
                ]
            ], 200);


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
