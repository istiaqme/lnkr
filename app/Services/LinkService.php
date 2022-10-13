<?php

namespace App\Services;

use App\Models\Link;

// use App\Models\LinkGroup;

class LinkService
{


    public function linkCreate($linkGroupId, $title, $redirectTo, $appId, $data, $userInfo)
    {
        $newLinkRow = new Link();
        $newLinkRow->title = $title;
        $newLinkRow->redirect_to = $redirectTo;
        $newLinkRow->link_group_id = $linkGroupId;
        $newLinkRow->app_id = $appId;
        $newLinkRow->ip = $data['ip'];
        $newLinkRow->user_agent = $data['user_agent'];
        $newLinkRow->short_key = $data['shortKey'];
        $newLinkRow->note = $data['note'];
        $newLinkRow->status = true;
    }
}
