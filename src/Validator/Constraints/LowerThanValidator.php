<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LowerThanValidator extends ConstraintValidator
{
    /**
     * @throws UnexpectedTypeException
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof LowerThan) {
            throw new UnexpectedTypeException($constraint, LowerThan::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!property_exists(get_class($value), $constraint->getFirstField()) || !property_exists(get_class($value), $constraint->getSecondField())) {
            throw new \Exception('This object doesn\'t have fields like this');
        }

        if ($value->{$constraint->getFirstField()} > $value->{$constraint->getSecondField()}) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
