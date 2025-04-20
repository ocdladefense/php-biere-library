<?php
use Http\Http;
use \DateTime as DateTime;
use GoogleCalendar\QueryRequest;
use GoogleCalendar\EventList;
use Http\HttpRequest;
// use Http\HttpResponse;
use Http\Url;





function getEvents($calId, $params = array())
{
    //need a number variable as a string from the onclick event to capture
    //desired stature for parsing
    $url = new Url("https://appdev.ocdla.org/calendar/${calId}/events");
    if(count($params) > 0) {
        $url->setParams($params);
    }

    //configuring the request 
    $config = array(
        "returntransfer"        => true,
        "useragent"             => "Mozilla/5.0",
        "followlocation"        => true,
        "ssl_verifyhost"        => false,
        "ssl_verifypeer"        => false
    );

    // $config = null;
    $http = new Http($config);

    $req = new HttpRequest($url->__toString());

    $resp = $http->send($req, false);


    $body = $resp->getBody();

    return json_decode($body);
}




function getEventInfo($name = null, $date = null, $tpl = null) {

    // $name = $req->path;
    $name = $name ?? "Flight";

    $calId = getSitePrimaryCalendarId();
    $maxResults = 1; // only get a single event

    $client = new Http();
    // var_dump($name,$date);exit;

    $req = new QueryRequest(CALENDAR_API_ENDPOINT);
    $req->setCalendarId($calId);
    $req->addParam("maxResults", $maxResults);
    $req->query($name);
    $req->setDate($date);


    // var_dump($req->getUrl());exit;
    $resp = $client->send($req);
    $body = $resp->getBodyAsJson();
  
    // var_dump($body);exit;
    $list = new EventList($body);

    $events = $list->getEvents();
    // var_dump($events);exit;
    return $events;
}


function getEventList($name = null, $date = null, $tpl = null) {

    // $name = $req->path;
    $name = $name ?? "Flight";

    $calId = getSitePrimaryCalendarId();
    $maxResults = 5;

    $client = new Http();
    // var_dump($name,$date);exit;

    $req = new QueryRequest(CALENDAR_API_ENDPOINT);
    $req->setCalendarId($calId);
    $req->setRange(90);
    $req->addParam("maxResults", $maxResults);
    $req->query($name);

    $resp = $client->send($req);
    $body = $resp->getBodyAsJson();
  
    // var_dump($body);exit;
    $list = new EventList($body);

    $events = $list->getEvents();
    
    return $events;
}








function getFeaturedEvents() {

    $calId = getSitePrimaryCalendarId();
    $maxResults = 5;

    $client = new Http();


    $req = new QueryRequest(CALENDAR_API_ENDPOINT);
    $req->setCalendarId($calId);
    $req->setRange(90);
    $req->addParam("maxResults", $maxResults);
    $req->query("Featured");

    $resp = $client->send($req);
    $body = $resp->getBodyAsJson();
  

    $list = new EventList($body);
    $filtered = $list->filterOut("summary","happy hour");
    $filtered = $filtered->filterOut("location","Unpublished");

    return $filtered->getEvents($maxResults);
}


function getUpcomingEvents() {

    $calId = getSitePrimaryCalendarId();
    $maxResults = 30;

    $client = new Http();


    $req = new QueryRequest(CALENDAR_API_ENDPOINT);
    $req->setCalendarId($calId);
    $req->setRange(15);
    $req->addParam("maxResults", $maxResults);
    

    $resp = $client->send($req);
    $body = $resp->getBodyAsJson();
  

    $list = new EventList($body);
    $filtered = $list->filterOut("summary","happy hour");

    return $filtered->getEvents($maxResults);
}



function getUpcomingEventsHomepage() {

    $calId = getSitePrimaryCalendarId();
    $maxResults = 30;

    $client = new Http();


    $req = new QueryRequest(CALENDAR_API_ENDPOINT);
    $req->setCalendarId($calId);
    $req->setRange(15);
    $req->addParam("maxResults", $maxResults);
    

    $resp = $client->send($req);
    $body = $resp->getBodyAsJson();
  

    $list = new EventList($body);
    $filtered = $list->filterOut("summary","happy hour");
    $filtered = $filtered->filterOut("location","Featured");

    return $filtered->getEvents(3);
}


function renderEvents($events, $tpl = "event") {

    $templatePath = getThemePath() . "/{$tpl}.tpl.php";


    if(null == $events || count($events) == 0) {
        return "No events";
    }

    $index = 0;
    
    foreach($events as $event) {

        
        
        /*
        // The unique event id for this event.
        $event->id;

        // public 'kind' => string 'calendar#event
        $event->kind;

        // The location for the event.
        $event->location;

        // Not sure what this is.
        $event->recurringEventId;

        // Name of the event.
        $event->summary;
        */

        // if(++$index > $limit) break;
        // Extended description of the event.  Can include HTML.
        // $event->description;

        // Google\Service\Calendar\EventDateTime type.
        $start = $event->start;
        $startDate = $start->date; // for all-day event
        $startTime = $start->dateTime;
        // $startTimezone = $start->timezone;

        // Google\Service\Calendar\EventDateTime
        $end = $event->end;
        $endDate = $end->date; // for all-day event
        $endTime = $end->dateTime;
        // $endTimezone = $end->timezone;

        
        

        // Link to the Google Calendar application for this event.
        // $event->htmlLink;
        $start = $event->start->date ?? $event->start->dateTime;
        $end = $event->end->date ?? $event->end->dateTime;
        $start = new DateTime($start);
        $end = new DateTime($end);

        $link = strtolower($event->summary);
        $link = trim($link, $characters = " \n\r\t\v\x00()");
        $link = preg_replace("/[\s\:\(\)\-]+/mis", "-", $link);
        $link = preg_replace("/\'s/mis", "", $link);
        $link = preg_replace("/\&\-/mis", "", $link);
        $link = preg_replace("/[\!\']+/mis", "", $link);
        $link = "/event/" . $start->format('Y-m-d') ."/".$link;

        
        $hasImage = false;

        // $attachmentUrls = array(); should now be deprecated.
        // Need to convert this to:
        // https://drive.google.com/uc?export=view&id=1DRWUbvNjby0yQTBW08U1LabNnnEE4AV9
        // https://stackoverflow.com/questions/59148718/google-drive-api-publish-document-and-get-published-link
        // https://stackoverflow.com/questions/15557392/how-do-i-display-images-from-google-drive-on-a-website


        if(isset($event->attachments) && count($event->attachments) > 0) {
            $attachments = array_map(function($attachment) {
                $attachment->embedUrl = getExternalUrl($attachment, $attachment->mimeType);
                $attachment->isImage = $attachment->mimeType != "application/vnd.google-apps.document";
                return $attachment;
            }, $event->attachments);
        } else {
            $attachments = array();
        }

        
        $event->teaser = parseTeaser($event->description, strpos($event->location, "WebPage"));
        // var_dump($attachments);exit;
        $hasAttachments = count($attachments) > 0;
        $hasLink = strpos($event->location, "WebPage") !== false || null != $event->teaser;

        // var_dump($attachmentUrl);
        include $templatePath;
     }
}



// Only generate teasers for standalone events that can be linked to;
// i.e., for a full description.
function parseTeaser($str, $standalone = false) {

    $TEASER_LENGTH = 512;

    if($standalone === false) {
        return null;
    }

    $paragraphs = preg_split("/\<br\>/mis",$str);
    $paragraphs = array_filter($paragraphs, function($item) { return !empty($item); });
    // var_dump($setup);exit;

    if(count($paragraphs) > 0) {
        return strlen($paragraphs[0]) > $TEASER_LENGTH ? substr($paragraphs[0], 0, $TEASER_LENGTH) . "..." : $paragraphs[0];
    }
    else if(strlen($str) > $TEASER_LENGTH) {
        return substr($str, 0, $TEASER_LENGTH) . "...";
    }
    else return null;
}

/**
 * For now the external URL can toggle between images and a Google Doc.
 */
function getExternalUrl($attachment, $mimeType) {

    $format1 = "https://drive.google.com/uc?export=view&id=%s";
    $format2 = "https://drive.usercontent.google.com/download?id=%s&export=view&authuser=0";
    $format3 = "https://drive.usercontent.google.com/download?id=%s&export=view";
    $format4 = "/themes/biere-library/assets/images/events/%s";
    $format = "https://drive.google.com/thumbnail?id=%s&sz=w%s-h%s";
    $urlFormat = $mimeType == "application/vnd.google-apps.document" ? "https://docs.google.com/document/d/%s/pub?embedded=true" : $format;

    return $mimeType == "application/vnd.google-apps.document" ? sprintf($urlFormat, $attachment->fileId) : sprintf($urlFormat, $attachment->fileId, "400", "600");
}



function renderEventComponent($func, $args = array()) {

    $function = "get".$func;

    
    $events = call_user_func_array($function, $args);
    $tpl = $args["tpl"] ?? "event";
    ob_start();

        renderEvents($events, $tpl);

    $out = ob_get_contents();
    ob_end_clean();

    // end output buffering.
    // return the contents of the output buffer.
    return $out;
}