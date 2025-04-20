<?php



global $config, $meta, $eventDb, $events, $hours, $holidays, $ga_property_id, $ga_debug;

include("secret/secrets.php");

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

define("CALENDAR_API_ENDPOINT","https://appdev.ocdla.org/calendar/%s/events");



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

        return BIERE_LIBRARY_CALENDAR_ID;
}



function getFilter($threshold) {

    return function($event) {
        $today = new DateTime();
        
        $when = $event["datetime"];

        return $today <= $when && $when < $threshold;
    };
}


