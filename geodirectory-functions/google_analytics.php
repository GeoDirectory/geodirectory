<?php
/**
 * Google analystics related functions.
 *
 * @since 1.0.0
 * @package GeoDirectory
 */

/**
 * Formats seconds into to h:m:s.
 *
 * @since 1.0.0
 *
 * @param int  $sec The number of seconds.
 * @param bool $padHours Whether add leading zero for less than 10 hours. Default false.
 * @return string h:m:s format.
 */
function geodir_sec2hms($sec, $padHours = false)
{
    // holds formatted string
    $hms = "";
    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600);

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ':' : $hours . ':';

    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in
    // minutes past the hour: to get that, we need to
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60);

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60);

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    // done!
    return $hms;
}

/**
 * Get the google analytics via api.
 *
 * @since 1.0.0
 *
 * @param string $page Page url to use in analytics filters.
 * @param bool   $ga_start The start date of the data to include in YYYY-MM-DD format.
 * @param bool   $ga_end The end date of the data to include in YYYY-MM-DD format.
 * @return string Html text content.
 */
function geodir_getGoogleAnalytics($page, $ga_start, $ga_end)
{


    // NOTE: the id is in the form ga:12345 and not just 12345
    // if you do e.g. 12345 then no data will be returned
    // read http://www.electrictoolbox.com/get-id-for-google-analytics-api/ for info about how to get this id from the GA web interface
    // or load the accounts (see below) and get it from there
    // if you don't specify an id here, then you'll get the "Badly formatted request to the Google Analytics API..." error message
    $id = trim(get_option('geodir_ga_id'));


    $at = geodir_ga_get_token();

    $start_date = '';
    $end_date = '';
    if(isset($_REQUEST['ga_type']) && $_REQUEST['ga_type']=='thisweek'){
        if(!$ga_start){$ga_start = date('Y-m-d', strtotime("-6 day"));}
        if(!$ga_end){$ga_end = date('Y-m-d');}
        $dimensions = "&dimensions=ga:date,ga:nthDay";
    }elseif(isset($_REQUEST['ga_type']) && $_REQUEST['ga_type']=='lastweek'){
        if(!$ga_start){$ga_start = date('Y-m-d', strtotime("-13 day"));}
        if(!$ga_end){$ga_end = date('Y-m-d', strtotime("-7 day"));}
        $dimensions = "&dimensions=ga:date,ga:nthDay";
    }
    elseif(isset($_REQUEST['ga_type']) && $_REQUEST['ga_type']=='thisyear'){
        if(!$ga_start){$ga_start = date('Y')."-01-01";}
        if(!$ga_end){$ga_end = date('Y-m-d');}
        $dimensions = "&dimensions=ga:month,ga:nthMonth";
    }
    elseif(isset($_REQUEST['ga_type']) && $_REQUEST['ga_type']=='lastyear'){
        if(!$ga_start){$ga_start = date('Y', strtotime("-1 year"))."-01-01";}
        if(!$ga_end){$ga_end = date('Y', strtotime("-1 year"))."-12-31";}
        $dimensions = "&dimensions=ga:month,ga:nthMonth";
    }
    elseif(isset($_REQUEST['ga_type']) && $_REQUEST['ga_type']=='country'){
        if(!$ga_start){$ga_start = "14daysAgo";}
        if(!$ga_end){$ga_end = "yesterday";}
        $dimensions = "&dimensions=ga:country&sort=-ga:sessions&max-results=5";
    }


    $APIURL = "https://www.googleapis.com/analytics/v3/data/ga?";
    $ids = "ids=".$id;
    if(!$start_date){$start_date = "&start-date=".$ga_start;}
    if(!$end_date){$end_date = "&end-date=".$ga_end;}
    $metrics = "&metrics=ga:sessions";
    $filters = "&filters=ga:pagePath==".$page;
    $access_token = "&access_token=".$at;

    $use_url = $APIURL.$ids.$start_date.$end_date.$dimensions.$metrics.$filters.$access_token;

    if(isset($_REQUEST['ga_type']) && $_REQUEST['ga_type']=='realtime'){
        $metrics = "&metrics=rt:activeUsers";
        $dimensions = "&filters=ga:pagePath==".$page;

        $use_url = "https://www.googleapis.com/analytics/v3/data/realtime?".$ids.$access_token.$metrics.$dimensions;
    }

    $response =  wp_remote_get($use_url,array('timeout' => 15));

    echo $response['body'];
    exit;

}// end GA function


function geodir_ga_get_token(){
    $at = get_option('gd_ga_access_token');
    $use_url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=".$at;
    $response =  wp_remote_get($use_url,array('timeout' => 15));

    if(!empty($response['response']['code']) && $response['response']['code']==200) {//access token is valid

    return $at;
    }else{//else get new access token

        $refresh_at = get_option('gd_ga_refresh_token');
        if(!$refresh_at){
            echo json_encode(array('error'=>__('Not authorized, please click authorized in GD > Google analytic settings.', GEODIRECTORY_TEXTDOMAIN)));exit;
        }

        $rat_url = "https://www.googleapis.com/oauth2/v3/token?";
        $client_id = "client_id=".get_option('geodir_ga_client_id');
        $client_secret = "&client_secret=".get_option('geodir_ga_client_secret');
        $refresh_token = "&refresh_token=".$refresh_at;
        $grant_type = "&grant_type=refresh_token";

        $rat_url_use = $rat_url.$client_id.$client_secret.$refresh_token.$grant_type;

        $rat_response =  wp_remote_post($rat_url_use,array('timeout' => 15));
        if(!empty($rat_response['response']['code']) && $rat_response['response']['code']==200) {
            $parts = json_decode($rat_response['body']);


            update_option('gd_ga_access_token', $parts->access_token);
            return $parts->access_token;

        }else{
            echo json_encode(array('error'=>__('Login failed', GEODIRECTORY_TEXTDOMAIN)));exit;
        }


    }

}