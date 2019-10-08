<?php

namespace Louvre\BookingBundle\Services;

class CheckTimeOfTheBooking
{
    const HEURE_MAX = '14';
    
    public function check($day, $halfDay)
    {
        if ($this->checkTimeOfTheBooking($day, $halfDay) == true) {
            return true;
        }
    }

    public function checkTimeOfTheBooking($day, $halfDay)
    {
        $now = new \DateTime();
        if (($day->format('Ymd') == $now->format('Ymd')) && ($now->format('H') >= self::HEURE_MAX) && ($halfDay == 0)) {
            return true;
        } else {
            return false;
        }
    }
}