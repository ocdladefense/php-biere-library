<?php



global $config, $meta, $eventDb, $events, $hours, $holidays, $ga_property_id, $ga_debug;

// We aren't currently using the draft list.
// include("config/draft-list.php");

// Contains the business regular hours.
include("config/hours.php");

// Contains special holiday hours.
include("config/holidays.php");

// Meta data like keywords and titles for paths.
include("config/meta.php");

$ga_property_id = "G-1BNTM584DQ";
# $ga_DEV_property_id = "G-GT8NZ6HNJK";
$ga_debug = false;
$online_ordering_enabled = false;

// https://ocdla.app/calendar/c_07c922398faccc5695d450d685cccf3a2463815c4ea56ad99d709e741e9e1363/events
define("CALENDAR_API_ENDPOINT","https://ocdla.app/calendar/%s/events");



function isGaDebug() {
    global $ga_debug;
    
    return true === $ga_debug;
}






$config = array(
    "phone" => "541-286-4193",
    "email" => "info@thebierelibrary.com",
    "event-email" => "events@thebierelibrary.com"
);









function config($name, $default = null) {
    global $online_ordering_enabled;

    return !isset($online_ordering_enabled) ? $default : $online_ordering_enabled;
}






function getSitePrimaryCalendarId() {
        $biereLibraryCalendarId = "c_07c922398faccc5695d450d685cccf3a2463815c4ea56ad99d709e741e9e1363";
        
        $josesLibraryCalendarId = "54b9c815f343ae63a171ff6afe361206457cf4c59e97a22b53165c981f98f0ab";

        return $biereLibraryCalendarId;
}



function getFilter($threshold) {

    return function($event) {
        $today = new DateTime();
        
        $when = $event["datetime"];

        return $today <= $when && $when < $threshold;
    };
}


