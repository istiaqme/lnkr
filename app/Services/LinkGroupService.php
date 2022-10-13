<?php 
namespace App\Services;

use App\Models\LinkGroup;

class LinkGroupService
{
    public function create($requestObject){
        $newRow = new LinkGroup();
        $newRow->title = $requestObject->title;
        $newRow->app_id = APP_ID;
        $newRow->ip = $requestObject->ip();
        $newRow->user_agent = $requestObject->headers->get('user-agent');
        $newRow->save();
        return $newRow;
    }



}
