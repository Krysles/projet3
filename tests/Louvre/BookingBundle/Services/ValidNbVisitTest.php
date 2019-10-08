<?php

namespace Tests\Louvre\BookingBundle\Services;

use Louvre\BookingBundle\Services\ValidNbVisit;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ValidNbVisitTest extends KernelTestCase
{
    private $em;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null;
    }

    public function testCheck() {
        $validNbVisit = new ValidNbVisit($this->em);
        $day = new \DateTime();
        $nbTickets = 1000;
        $result = $validNbVisit->check($day, $nbTickets);
        $this->assertFalse($result, 'Plus de 1000 tickets');
    }
}