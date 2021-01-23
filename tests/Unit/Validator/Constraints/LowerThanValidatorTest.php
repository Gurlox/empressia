<?php

namespace App\Tests\Unit\Validator\Constraints;

use App\DTO\ReservationDTO;
use App\Validator\Constraints\LowerThan;
use App\Validator\Constraints\LowerThanValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

class LowerThanValidatorTest extends TestCase
{
    private $constraint;

    public function setUp(): void
    {
        $this->constraint = $this->getConstraint();
    }

    public function getContext($expectedMessage = null)
    {
        $builder = $this->createMock(ConstraintViolationBuilder::class);
        $context = $this->createMock(ExecutionContext::class);

        if ($expectedMessage) {
            $builder->expects($this->once())->method('addViolation');

            $context->expects($this->once())
                ->method('buildViolation')
                ->with($this->equalTo('message'))
                ->will($this->returnValue($builder));
        } else {
            $context->expects($this->never())->method('buildViolation');
        }

        return $context;
    }

    public function testValidate__with_invalid_dates__will_add_violation(): void
    {
        $reservationDTO = new ReservationDTO();
        $reservationDTO->startDate = (new \DateTime())->modify('+2 days');
        $reservationDTO->endDate = new \DateTime();

        $uniqueValidator = new LowerThanValidator();
        $uniqueValidator->initialize($this->getContext($this->constraint->getMessage()));
        $uniqueValidator->validate($reservationDTO, $this->constraint);
    }

    private function getConstraint(): LowerThan
    {
        return new LowerThan([
            'firstField' => 'startDate',
            'secondField' => 'endDate',
            'message' => 'message',
        ]);
    }
}
