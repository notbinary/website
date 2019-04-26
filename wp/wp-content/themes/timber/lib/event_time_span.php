<?php
/**
 * Takes two times and returns a time span formatted nicely
 * e.g.: 10-5pm   11:30-12pm  4:45-5:15pm
 * @param  string|datetime $start_time The first time
 * @param  string|datetime $end_time   The second time
 * @return strinf                      The resulting string
 */
function event_time_span ($start_time, $end_time) {

    if (is_string($start_time)) {
        $start_time = strtotime($start_time);
    }
    if (is_string($end_time)) {
        $end_time = strtotime($end_time);
    }

    $shours = date('g', $start_time);
    $smins = date('i', $start_time);
    if ($smins == '00') {
        $smins = '';
    } else {
        $smins = ":$smins";
    }

    $ehours = date('g', $end_time);
    $emins = date('i', $end_time);
    if ($emins == '00') {
        $emins = '';
    } else {
        $emins = ":$emins";
    }

    $startperiod = date('a', $start_time);
    $endperiod = date('a', $end_time);

    return "$shours$smins$startperiod"."–"."$ehours$emins$endperiod";
}
