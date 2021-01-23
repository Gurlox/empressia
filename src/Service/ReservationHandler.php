<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ReservationDTO;
use App\Entity\Apartment;
use App\Entity\Reservation;
use App\Exception\FormValidationException;
use App\Factory\ReservationFactory;
use App\Form\ReservationType;
use App\Utils\FormErrors;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ReservationHandler
{
    private FormFactoryInterface $formFactory;

    private ReservationFactory $reservationFactory;

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory, ReservationFactory $reservationFactory)
    {
        $this->formFactory = $formFactory;
        $this->reservationFactory = $reservationFactory;
        $this->em = $em;
    }

    /**
     * @throws FormValidationException
     */
    public function createReservation(array $payload, Apartment $apartment): Reservation
    {
        $reservationDTO = new ReservationDTO();
        $form = $this->formFactory->create(ReservationType::class, $reservationDTO, ['apartment' => $apartment]);
        $form->submit($payload);

        if ($form->isValid()) {
            $reservation = $this->reservationFactory->createFromDTO($apartment, $reservationDTO);
            $this->em->persist($reservation);
            $this->em->flush();

            return $reservation;
        }

        throw new FormValidationException(FormErrors::getAll($form));
    }
}
