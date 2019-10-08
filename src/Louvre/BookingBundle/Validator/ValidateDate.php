<?php

namespace Louvre\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidateDate extends Constraint
{
    public $msgDaysHoliday = 'message.daysholiday';
    public $msgDayOfTheWeek = 'message.dayoftheweek';
    public $msgPastDays = 'message.pastdays';
    public $msgVisits = 'message.visits';
    public $msgHour = 'message.hour';

    public function validatedBy()
    {
        return 'louvre_booking_validatedate';
    }
}