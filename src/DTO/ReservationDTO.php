<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;

/**
 * @CustomAssert\LowerThan(firstField="startDate", secondField="endDate", message="Start date should be lower than end date")
 */
class ReservationDTO
{
    public \DateTimeInterface $startDate;

    public \DateTimeInterface $endDate;

    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=1)
    */
    public int $slots;

    /**
     * @Assert\NotBlank()
    */
    public string $bookingPerson;
}
