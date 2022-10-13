<?php

namespace App\Services;

use App\Models\Link;

// use App\Models\LinkGroup;

class LinkService
{


    public function linkCreate($linkGroupId, $title, $redirect_to, $appId, $data, $clientInfo)
    {
        $newLinkRow = new Link();
        $newLinkRow->link_group_id = $linkGroupId;
        $newLinkRow->title = $title;
        $newLinkRow->redirect_to = $redirect_to;
        $newLinkRow->app_id = $appId;
        
        $newLinkRow->short_key = $data['shortKey'];
        $newLinkRow->note = $data['note'];

        $newLinkRow->ip = $clientInfo['ip'];
        $newLinkRow->user_agent = $clientInfo['user_agent'];
        $newLinkRow->status = true;

        $response = $newLinkRow->save();
        dd($response);

    }
}
