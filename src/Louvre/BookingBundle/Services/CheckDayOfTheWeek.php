<?php

namespace Louvre\BookingBundle\Services;

class CheckDayOfTheWeek
{
    public function check($day) {
        if ($day->format('D') == 'Tue' || $day->format('D') == 'Sun') {
            return true;
        } else {
            return false;
        }
    }
}