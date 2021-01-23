<?php

namespace App\Tests\Unit\Factory;

use App\DTO\ReservationDTO;
use App\Entity\Apartment;
use App\Factory\ReservationFactory;
use PHPUnit\Framework\TestCase;

class ReservationFactoryTest extends TestCase
{
    private ReservationFactory $reservationFactory;

    protected function setUp(): void
    {
        $this->reservationFactory = new ReservationFactory();
    }

    public function testCreateFromDTO__with_slots_over_7__should_return_discounted_price(): void
    {
        $apartment = $this->createMock(Apartment::class);
        $apartment->method('getPriceForBed')->willReturn(100.0);

        $reservationDTO = new ReservationDTO();
        $reservationDTO->startDate = new \DateTime();
        $reservationDTO->endDate = (new \DateTime())->modify('+8 days');
        $reservationDTO->slots = 10;
        $reservationDTO->bookingPerson = 'bookingPerson';

        $result = $this->reservationFactory->createFromDTO($apartment, $reservationDTO);

        $this->assertEquals(800, $result->getPrice());
        $this->assertEquals(10, $result->getSlots());
    }
}
