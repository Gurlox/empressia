<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ApartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=ApartmentRepository::class)
 */
class Apartment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $address;

    /**
     * @ORM\Column(type="integer")
     */
    private int $availableSlots;

    /**
     * @ORM\Column(type="float")
     */
    private float $priceForBed;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="apartment", orphanRemoval=true)
     * @var Reservation[]|Collection
     */
    private Collection $reservations;

    public function __construct(string $address, int $availableSlots, float $priceForBed)
    {
        $this->reservations = new ArrayCollection();
        $this->address = $address;
        $this->availableSlots = $availableSlots;
        $this->priceForBed = $priceForBed;
    }

    public function getSlotsLeft(\DateTimeInterface $reservationStartDate, \DateTimeInterface $reservationEndDate): int
    {
        $slotsLeft = $this->getAvailableSlots();
        foreach ($this->getReservations() as $reservation) {
            if (
                ($reservation->getStartDate() <= $reservationStartDate && $reservationStartDate <= $reservation->getEndDate()) ||
                ($reservation->getStartDate() <= $reservationEndDate && $reservationEndDate <= $reservation->getEndDate())
            ) {
                $slotsLeft -= $reservation->getSlots();
            }
        }

        return $slotsLeft;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getAvailableSlots(): int
    {
        return $this->availableSlots;
    }

    public function setAvailableSlots(int $availableSlots): self
    {
        $this->availableSlots = $availableSlots;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setApartment($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getApartment() === $this) {
                $reservation->setApartment(null);
            }
        }

        return $this;
    }

    public function getPriceForBed(): float
    {
        return $this->priceForBed;
    }

    public function setPriceForBed($priceForBed): self
    {
        $this->priceForBed = $priceForBed;

        return $this;
    }
}
