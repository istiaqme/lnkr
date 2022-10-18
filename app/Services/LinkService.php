<?php

namespace App\Services;

use App\Models\Link;
use App\Models\LinkVisit;
use App\Models\LinkVisitQueryLog;
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


    public function linkByShortKey($shortKey)
    {
        return Link::where('short_key', $shortKey)->first();
    }

    public function shortKeyExistsInApp($shortKey)
    {
        return Link::where('short_key', $shortKey)->first();
    }


    public function generateShortKey($length = 8)
    {
        $shortKey =  Str::random($length);
        $link = $this->linkByShortKey($shortKey);
        if ($link) {
            $this->generateShortKey($length);
        } else {
            return $shortKey;
        }
    }

    /**
     * @param string shortKey - this would be a random string
     * @return string
     * 
     */
    public function verifyAppDefinedShortKey($shortKey)
    {
        if ($this->shortKeyExistsInApp($shortKey)) {
            $shortKey = $this->generateShortKey(8);
            return $shortKey;
        }
        return $shortKey;
    }

    public function logAVisit($linkShortKey, $appId, $request){
        $newRow = new LinkVisit();
        $newRow->link_short_key = $linkShortKey;
        $newRow->token = str_replace('-', '', Str::uuid());
        $newRow->app_id = $appId;
        $newRow->http_referer = $request->headers->get('referer');
        $newRow->ip = $request->ip();
        $newRow->user_agent = $request->headers->get('user-agent');
        $newRow->save();
        return $newRow;
    }

    public function logQueryStringItem($linkVisitId, $linkShortKey, $appId, $query, $value, $request){
        $newRow = new LinkVisitQueryLog();
        $newRow->link_visit_id = $linkVisitId;
        $newRow->link_short_key = $linkShortKey;
        $newRow->app_id = $appId;
        $newRow->query = $query;
        $newRow->data = $value;
        $newRow->http_referer = $request->headers->get('referer');
        $newRow->ip = $request->ip();
        $newRow->user_agent = $request->headers->get('user-agent');
        $newRow->save();
        return $newRow;
    }




}
