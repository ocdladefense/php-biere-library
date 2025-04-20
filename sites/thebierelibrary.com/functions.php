<?php
use Http\Http;
use Http\HttpRequest;
use Http\HttpResponse;

require "extra/functions-event.php";





function getTodaysHoursMessage() {
    global $hours, $holidays;
    $results = is_array($holidays) ? array_merge($hours,$holidays) : $hours;

    $today = new DateTimeImmutable();
    $today = $today->setTime(0,0,0,0);
    $key = isHoliday($today) ? date("n/j/Y") : date("l");

    return $results[$key]["message"];
}



function getTodaysHours() {
    global $hours, $holidays;
    $day = date("l");
    $date = date("n/j/Y");

    // return $day;
    return isset($holidays[$date]) ? $holidays[$date]["message"] : $hours[$day]["hours"];
}


/**
 * Return true if the given day is a weekend.
 * Should return true for Friday, Saturday or Sunday.
 */
function isWeekend($date) {
    return true;
}


function preprocess(&$vars = array()) {
    
    // var_dump($vars);exit;
}





/**
 * @function date_matches
 * 
 * Test if the date/time $test is any of $dates.  Returns boolean true if so,
 *  otherwise returns false.
 */
function date_matches(\DateTimeImmutable $test, array $dates) {

    return is_array($dates) ? in_array($test, $dates) : $test === $dates;
}



/**
 * @function isOpen
 * 
 * Given a day of week, especially a regular business day, return true if the 
 * the business is open, otherwise return false if it is closed.
 */
function isOpen($dayOfWeek) {

    global $hours;

    return $hours[$dayOfWeek]["status"] == "open";
}




function isOpenToday() {
    $today = new DateTimeImmutable();
    $today = $today->setTime(0,0,0,0);
    // var_dump($today,$closed);exit;

    // Check for regular hours; make sure it's not a holiday.
    return isOpen(date("l")) && !isHolidayClosure($today);
}




function isHolidayClosure(DateTimeImmutable $date) {

    // An array of holidays, keyed by date.
    global $holidays, $timezone;


    $format = "n/j/Y";

    $key = $date->format($format);
    $entry = $holidays[$key] ?? null;

    // Get the dates.
    $closed = array_keys($holidays);
    $closed = array_map(function($d) use($format) {
        return (DateTimeImmutable::createFromFormat($format, $d))->setTime(0,0,0,0);
    }, $closed);
    $closed = array_filter($closed, function($d) use($entry) {
        return null != $entry && "closed" == $entry["status"];
    });

    return date_matches($date, $closed);

    /*
    if(null != $entry) {
        return "closed" == $entry["status"];// || date_matches($date, $closed);
    }
    else return date_matches($date, $closed);
    */
}


function isHoliday(DateTimeImmutable $date) {

    // An array of holidays, keyed by date.
    global $holidays, $timezone;


    $format = "n/j/Y";

    $key = $date->format($format);
    // $entry = $holidays[$key];

    // Get the dates.
    $closed = array_keys($holidays);
    $closed = array_map(function($d) use($format) {
        return (DateTimeImmutable::createFromFormat($format, $d))->setTime(0,0,0,0);
    }, $closed);

    return date_matches($date, $closed);
}




function getHours($dayofWeek) {

    global $hours;

    return $hours[$dayofWeek]["hours"];
}




function getHoursData($dayofWeek) {

    global $hours;

    return $hours[$dayofWeek];
}




function getAgendaDay($date) {
    $formatted = $date->format("Y-m-d");
    
    
    $today = (new DateTime())->format("Y-m-d");
    $tomorrow = (new DateTime())->modify("+1 day")->format("Y-m-d");

    // var_dump($formatted,$today,$tomorrow);
    // exit;

    if($today == $formatted) return "Today";
    if($tomorrow == $formatted) return "Tomorrow";
    return $date->format('l');
}




function getNextOpenDate($date = null) {

    global $hours;

   
    // Increment by one day at a time to find the next business day.
    $step = new DateInterval("P1D");

    // Shouldn't need to increment more than a week, except for vacations.
    // @TODO handle vacations.
    $limit = new DateInterval("P7D");

    for( $datetime = (new DateTimeImmutable($date))->add($step); $datetime < $datetime->add($limit); $datetime = $datetime->add($step)) {

        $dayOfWeek = $datetime->format("l");

        // $today = new DateTimeImmutable();
        $nextDate = $datetime->setTime(0,0,0,0);

        if(isOpen($dayOfWeek) && !isHolidayClosure($nextDate)) {
            return $datetime;
        }
    }

    return null;
}


