<?php

namespace Tests\Louvre\BookingBundle\Services;

use Louvre\BookingBundle\Services\CheckDaysHoliday;

class CheckDaysHolidayTest extends \PHPUnit_Framework_TestCase
{
    public function testCheck() {
        $day = new \DateTime();
        $checkDaysHoliday = new CheckDaysHoliday();
        $result = $checkDaysHoliday->check($day);
        $this->assertFalse($result, 'C\'est un jour férié');
    }
}