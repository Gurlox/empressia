<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\ReservationDTO;
use App\Entity\Apartment;
use App\Entity\Reservation;

class ReservationFactory
{
    public function createFromDTO(Apartment $apartment, ReservationDTO $reservationDTO): Reservation
    {
        $price = $apartment->getPriceForBed() * $reservationDTO->slots;
        if ($reservationDTO->startDate->diff($reservationDTO->endDate)->d > Reservation::DISCOUNTS_DAYS) {
            $price -= $price * Reservation::DISCOUNT;
        }
        $reservation = new Reservation(
            $reservationDTO->startDate,
            $reservationDTO->endDate,
            $reservationDTO->slots,
            $apartment,
            $reservationDTO->bookingPerson,
            $price
        );

        return $reservation;
    }
}
