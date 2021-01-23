<?php

namespace App\Tests\Unit\Service;

use App\Entity\Apartment;
use App\Entity\Reservation;
use App\Exception\FormValidationException;
use App\Factory\ReservationFactory;
use App\Service\ReservationHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\TypeTestCase;

class ReservationHandlerTest extends TypeTestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private MockObject $entityMangerInterface;

    private FormFactoryInterface $formFactory;

    /**
     * @var ReservationFactory|MockObject
     */
    private MockObject $reservationFactory;

    private ReservationHandler $reservationHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityMangerInterface = $this->createMock(EntityManagerInterface::class);
        $this->entityMangerInterface->method('persist')->willReturn(null);
        $this->entityMangerInterface->method('flush')->willReturn(null);
        $this->formFactory = $this->factory;
        $this->reservationFactory = $this->createMock(ReservationFactory::class);
        $this->reservationHandler = new ReservationHandler(
            $this->entityMangerInterface,
            $this->formFactory,
            $this->reservationFactory
        );
    }
    
    public function testCreateReservation__with_correct_data__should_return_reservation(): void
    {
        $reservation = $this->createMock(Reservation::class);
        $this->reservationFactory->method('createFromDTO')->willReturn($reservation);

        $apartment = $this->createMock(Apartment::class);
        $apartment->method('getSlotsLeft')->willReturn(1000);

        $result = $this->reservationHandler->createReservation([
            'startDate' => '2021-01-20',
            'endDate' => '2021-01-29',
            'slots' => 10,
            'bookingPerson' => 'Jan Kowalski',
        ], $apartment);

        $this->assertEquals($result, $reservation);
    }

    public function testCreateReservation__with_too_many_slots__should_throw_exception(): void
    {
        $apartment = $this->createMock(Apartment::class);
        $apartment->method('getSlotsLeft')->willReturn(1);

        $this->expectException(FormValidationException::class);
        $this->reservationHandler->createReservation([
            'startDate' => '2021-01-20',
            'endDate' => '2021-01-29',
            'slots' => 10,
            'bookingPerson' => 'Jan Kowalski',
        ], $apartment);
    }
}
