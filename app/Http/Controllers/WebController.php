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
            // log a visit
            $visitLog = $linkService->logAVisit($request->shortKey, $link->app_id, $request);
            // set visit token
            $visitToken = $visitLog->token? $visitLog->token : 'VT_'.mt_rand(10000, 999999);
            // check redirect to url already has query string or not
            if(strpos($urlToRedirect, "?")){
                $urlToRedirect = $urlToRedirect.'&'.config('app.qstrackparam').'='.$visitToken;
            }
            else{
                $urlToRedirect = $urlToRedirect.'?'.config('app.qstrackparam').'='.$visitToken;
            }

            
            


            if($request->query->count() > 0){
                foreach($request->query as $key => $singleQueryStringValue){
                    $linkService->logQueryStringItem(
                        $visitLog->id? $visitLog->id : 0, // 0 is default if any visit log is not recorded
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
            abort(500);
        }
        
    }
}
