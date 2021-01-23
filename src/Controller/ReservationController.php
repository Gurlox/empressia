<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Apartment;
use App\Exception\FormValidationException;
use App\Service\ReservationHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractFOSRestController
{
    private ReservationHandler $reservationHandler;

    public function __construct(ReservationHandler $reservationHandler)
    {
        $this->reservationHandler = $reservationHandler;
    }

    /**
     * @Rest\Post("/apartments/{id}/reservations")
     */
    public function postReservationAction(Request $request, Apartment $apartment): JsonResponse
    {
        try {
            $reservation = $this->reservationHandler->createReservation($request->request->all(), $apartment);
            return new JsonResponse(['reservation' => $reservation], Response::HTTP_OK);
        } catch (FormValidationException $exception) {
            return new JsonResponse(['messages' => $exception->getErrorMessages()], Response::HTTP_BAD_REQUEST);
        }
    }
}
