<?php 
namespace App\Services;

use App\Models\LinkGroup;

class LinkGroupService
{
    public function create($requestObject){
        $newRow = new LinkGroup();
        $newRow->title = trim(ucwords($requestObject->title));
        $newRow->app_id = APP_ID;
        $newRow->ip = $requestObject->ip();
        $newRow->user_agent = $requestObject->headers->get('user-agent');
        $newRow->save();
        return $newRow;
    }
    
    public function linkGroupListByAppId($appId, $columnsToReturn){
        // no pagination needed here
        return LinkGroup::where('app_id', $appId)->orderBy('id', 'DESC')->get($columnsToReturn);
    }



}
