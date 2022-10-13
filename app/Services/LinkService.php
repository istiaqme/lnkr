<?php

namespace App\Services;

use App\Models\Link;
use Illuminate\Support\Str;

// use App\Models\LinkGroup;

class LinkService
{


    public function linkCreate($linkGroupId, $title, $redirectTo, $appId, $optionalParams, $clientInfo)
    {

        $newLinkRow = new Link();
        $newLinkRow->link_group_id = $linkGroupId;
        $newLinkRow->title = trim(ucwords($title));
        $newLinkRow->redirect_to = $redirectTo;
        $newLinkRow->app_id = $appId;
        
        $newLinkRow->short_key = strtolower($optionalParams['shortKey']);
        $newLinkRow->note = $optionalParams['note'];

        $newLinkRow->ip = $clientInfo['ip'];
        $newLinkRow->user_agent = $clientInfo['userAgent'];
        $newLinkRow->status = true;

        $newLinkRow->save();

        return $newLinkRow;

    }

    
    public function linkByShortKey($shortKey){
        return Link::where('short_key', $shortKey)->first();
    }

    public function shortKeyExistsInApp($shortKey){
        return Link::where('short_key', $shortKey)->first();
    }


    public function generateShortKey($length=8){
        $shortKey =  Str::random($length);
        $link = $this->linkByShortKey($shortKey);
        if($link){
            $this->generateShortKey($length);
        }else{
           return $shortKey;
        }
    }

    public function verifyAppDefinedShortKey($shortKey){
        if($this->shortKeyExistsInApp($shortKey)){
            $shortKey = $this->generateShortKey(8);
            return $shortKey;
        }
        return $shortKey;
    }




}
