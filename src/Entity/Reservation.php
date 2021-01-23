<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation implements \JsonSerializable
{
    const DISCOUNT = 0.2;
    const DISCOUNTS_DAYS = 7;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private int $slots;

    /**
     * @ORM\ManyToOne(targetEntity=Apartment::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private Apartment $apartment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $bookingPerson;

    /**
     * @ORM\Column(type="float")
     */
    private float $price;

    public function __construct(
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        int $slots,
        Apartment $apartment,
        string $bookingPerson,
        float $price
    )
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->setSlots($slots);
        $this->apartment = $apartment;
        $this->bookingPerson = $bookingPerson;
        $this->price = $price;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'startDate' => $this->getStartDate()->format('Y-m-d'),
            'endDate' => $this->getEndDate()->format('Y-m-d'),
            'apartment' => $this->getApartment()->getId(),
            'slots' => $this->getSlots(),
            'price' => $this->getPrice(),
            'bookingPerson' => $this->getBookingPerson(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getSlots(): int
    {
        return $this->slots;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function setSlots(int $slots): self
    {
        if ($slots < 1) {
            throw new \InvalidArgumentException();
        }
        $this->slots = $slots;

        return $this;
    }

    public function getApartment(): Apartment
    {
        return $this->apartment;
    }

    public function setApartment(Apartment $apartment): self
    {
        $this->apartment = $apartment;

        return $this;
    }

    public function getBookingPerson(): string
    {
        return $this->bookingPerson;
    }

    public function setBookingPerson(string $bookingPerson): self
    {
        $this->bookingPerson = $bookingPerson;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }
}
