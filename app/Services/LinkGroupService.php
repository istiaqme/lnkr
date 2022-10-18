<?php 
namespace App\Services;

use App\Models\Link;
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

    public function linkGroupById($id){
        return LinkGroup::where('id', $id)->first();
    }

    public function appIdPermissionToLinkGroup($id, $appId){
        $linkGroup = $this->linkGroupById($id);
        if(!$linkGroup){
            return false;
        }

        if($linkGroup->app_id == $appId){
            return true;
        }

        return false;

    }


    public function linksByGroupIdAndAppId($linkGroupId, $appId, $as = 'object'){
        $paginatedRows = Link::where('link_group_id', $linkGroupId)
                        ->where('app_id', $appId)
                        ->orderBy('id', 'DESC')
                        ->paginate(50);

        if($as == 'array'){
            return $paginatedRows->toArray();
        }

        return $paginatedRows;
    }


}
