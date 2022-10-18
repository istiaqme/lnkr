<?php

namespace App\Http\Controllers;

use App\Services\LinkService;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Exception;
use Illuminate\Support\Facades\Redirect;

class WebController extends Controller
{
    // log visits
    public function linkVisit(Request $request)
    {
        try {
            $linkService = new LinkService();
            // get available shortkey in DB
            $link = $linkService->linkByShortKey($request->shortKey);
            if(!$link){
                abort(404);
            }

            $urlToRedirect = $link->redirect_to;
            config('app.qstrackparam');
            // log a visit
            $visitLog = $linkService->logAVisit($request->shortKey, $link->app_id, $request);
            if($visitLog){
                $urlToRedirect = $urlToRedirect.'?'.config('app.qstrackparam').'='.$visitLog->token;
            }

            if($request->query->count() > 0){
                foreach($request->query as $key => $singleQueryStringValue){
                    $linkService->logQueryStringItem(
                        $visitLog->id? $visitLog->id : 0,
                        $request->shortKey,
                        $link->app_id,
                        $key,
                        $singleQueryStringValue,
                        $request
                    );
                    $urlToRedirect = $urlToRedirect.'&'.$key.'='.$singleQueryStringValue;
                }
            }

            return Redirect::to($urlToRedirect, 302);

        } catch (\Exception $e) {
            dd($e);
        }
        
    }
}
