<?php

/**
 * event_date_span
 *
 * if start and end date are the same (or no end date) only show start date
 * otherwise only show month and year for start/end where they're different
 *
 * @param string $start_date The start date of the range
 * @param string $end_date The end date of the range
 * @param bool $full_month Whether to use full month names or shorter ones
 * @param string $span_seperator The string to seperate the dates with, e.g. 1—2
 */
function event_date_span($start_date, $end_date = false, $full_month = true, $span_seperator = "&mdash;") {

    $result = '';
    $start_date = str_replace('/', '-', $start_date);
    $end_date = str_replace('/', '-', $end_date);

    $start_date = strtotime($start_date);
    $start_day_of_month = date('jS', $start_date);
    $start_month = $full_month ? date('F', $start_date) : date('M', $start_date);
    $start_year = date('Y', $start_date);

    // safety check on start_date
    if (false === $start_day_of_month || false === $start_month || false === $start_year) {
        return $result;
    }

    if ($end_date) {
        $end_date = strtotime($end_date);
        $end_day_of_month = date('jS', $end_date);
        $end_month = $full_month ? date('F', $end_date) : date('M', $end_date);
        $end_year = date('Y', $end_date);

        // safety check on end_date
        if (false === $end_day_of_month || false === $end_month || false === $end_year) {
            return $result;
        }

        if ($start_day_of_month == $end_day_of_month &&
            $start_month == $end_month &&
            $start_year == $end_year) {
            $result = $start_day_of_month . ' ' . $start_month . ' ' . $start_year;
        }
        elseif ($start_month == $end_month &&
            $start_year == $end_year) {
            $result = $start_day_of_month . $span_seperator . $end_day_of_month . ' ' . $start_month . ' ' . $start_year;
        }
        elseif ($start_year == $end_year) {
            $result = $start_day_of_month . ' ' . $start_month . $span_seperator .
                $end_day_of_month . ' ' . $end_month . ' ' .
                $start_year;
        }
        else {
            // assuming my logic is right, we shouldn't really be here
            $result = $start_day_of_month . ' ' . $start_month . ' ' . $start_year .
                $span_seperator .
                $end_day_of_month . ' ' . $end_month . $end_year;
        }
    }
    // no end date, just return the start date
    else {
        $result = $start_day_of_month . ' ' . $start_month . ' ' . $start_year;
    }

    return $result;
}
