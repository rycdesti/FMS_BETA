<?php
/**
 * Created by PhpStorm.
 * User: paolourielenriquez
 * Date: 16/05/2019
 * Time: 12:25 PM
 */

function get_frequency($frequency_type = null, $value = null)
{
    $days = array();
    for ($count_days = 1; $count_days < 32; $count_days++) {
        $days[$count_days] = $count_days;
    }
    $frequency = array('frequency' => array('W' => 'Weekly', 'M' => 'Monthly', 'Q' => 'Quarterly', 'S' => 'Semestral', 'A' => 'Annual'),
        'week' => array('1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday'),
        'quarter' => array('Q1' => '1st Quarter', 'Q2' => '2nd Quarter', 'Q3' => '3rd Quarter', 'Q4' => '4th Quarter'),
        'semester' => array('SEM1' => '1st Semester', 'SEM2' => '2nd Semester', 'SUMMER' => 'Summer'),
        'month' => array('1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'),
        'days' => $days);
    if ($value) {
        return $frequency[$frequency_type][$value];
    } else {
        return $frequency;
    }
}

/**
 * Convert number to its ordinal form
 *
 * @param $number
 * @return string
 */
function ConvertToOrdinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}
