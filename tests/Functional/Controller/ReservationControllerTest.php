<?php

namespace App\Tests\Functional\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ReservationControllerTest extends WebTestCase
{
    public function testPostReservation__take_all_slots__should_succeed(): void
    {
        $response = $this->getResponseForApartmentReservation([
            'startDate' => '2021-01-20',
            'endDate' => '2021-01-29',
            'slots' => 10,
            'bookingPerson' => 'Jan Kowalski',
        ], 1);
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(10, $body['reservation']['slots']);
        $this->assertEquals(800, $body['reservation']['price']);
    }

    /**
     * @depends testPostReservation__take_all_slots__should_succeed
     */
    public function testPostReservation__reserve_during_reserved_date__should_return_bad_request(): void
    {
        $response = $this->getResponseForApartmentReservation([
            'startDate' => '2021-01-22',
            'endDate' => '2021-01-29',
            'slots' => 1,
            'bookingPerson' => 'Jan Kowalski',
        ], 1);
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertTrue(!empty($body['messages']));
    }

    /**
     * @depends testPostReservation__take_all_slots__should_succeed
     */
    public function testPostReservation__reserve_after_reserved_date__should_succeed(): void
    {
        $response = $this->getResponseForApartmentReservation([
            'startDate' => '2021-01-30',
            'endDate' => '2021-02-02',
            'slots' => 1,
            'bookingPerson' => 'Jan Kowalski',
        ], 1);
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $body['reservation']['slots']);
    }

    public function testPostReservation__with_startDate_lower_than_endDate__should_return_bad_request(): void
    {
        $response = $this->getResponseForApartmentReservation([
            'startDate' => '2021-01-23',
            'endDate' => '2021-01-20',
            'slots' => 2,
            'bookingPerson' => 'Jan Kowalski',
        ], 1);
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertTrue(!empty($body['messages']));
    }

    public function testPostReservation__with_too_many_slots__should_return_bad_request(): void
    {
        $response = $this->getResponseForApartmentReservation([
            'startDate' => '2021-01-23',
            'endDate' => '2021-01-20',
            'slots' => 1000,
            'bookingPerson' => 'Jan Kowalski',
        ], 1);
        $body = json_decode($response->getContent(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertTrue(!empty($body['messages']));
    }

    private function getResponseForApartmentReservation(array $payload, int $apartmentId): Response
    {
        $client = static::createClient();
        $client->request(
            'POST',
            "/apartments/$apartmentId/reservations",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        return $client->getResponse();
    }
}
