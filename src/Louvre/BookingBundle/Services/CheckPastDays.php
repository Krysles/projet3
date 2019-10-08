<?php

namespace Louvre\BookingBundle\Services;


class CheckPastDays
{
    public function check($day) {
        $now = new \DateTime();
        if ($day->format('Y-m-d') < $now->format('Y-m-d')) {
            return true;
        } else {
            return false;
        }
    }
}