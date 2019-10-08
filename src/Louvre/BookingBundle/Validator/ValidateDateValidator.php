<?php

namespace Louvre\BookingBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class ValidateDateValidator extends ConstraintValidator
{
    private $checkDaysHoliday;
    private $checkDayOfTheWeek;
    private $checkPastDays;
    private $validNbVisit;
    private $checkTimeOfTheBooking;

    public function __construct($checkDaysHoliday, $checkDayOfTheWeek, $checkPastDays, $validNbVisit, $checkTimeOfTheBooking)
    {
        $this->checkDaysHoliday = $checkDaysHoliday;
        $this->checkDayOfTheWeek = $checkDayOfTheWeek;
        $this->checkPastDays = $checkPastDays;
        $this->validNbVisit = $validNbVisit;
        $this->checkTimeOfTheBooking = $checkTimeOfTheBooking;
    }

    public function validate($value, Constraint $constraint)
    {
        $nbTickets = $this->context->getRoot()->getData()->getNbTickets();
        $halfDay = $this->context->getRoot()->getData()->getHalfDay();

        if ($this->checkDaysHoliday->check($value) == true) {
            $this->context->addViolation($constraint->msgDaysHoliday);
        }
        if ($this->checkDayOfTheWeek->check($value) == true) {
            $this->context->addViolation($constraint->msgDayOfTheWeek);
        }
        if ($this->checkPastDays->check($value) == true) {
            $this->context->addViolation($constraint->msgPastDays);
        }
        if ($this->validNbVisit->check($value, $nbTickets) == true) {
            $this->context->addViolation($constraint->msgVisits);
        }
        if ($this->checkTimeOfTheBooking->check($value, $halfDay) == true) {
            $this->context->addViolation($constraint->msgHour);
        }
    }
}