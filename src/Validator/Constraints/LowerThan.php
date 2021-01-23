<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class LowerThan extends Constraint
{
    public ?string $message;

    public string $firstField;

    public string $secondField;

    public function __construct(array $options)
    {
        if (!isset($options['firstField']) || !isset($options['secondField'])) {
            throw new MissingOptionsException('Some mandatory options for this validator are missing', $options);
        }
        parent::__construct($options);
        $this->message = $options['message'];
        $this->firstField = $options['firstField'];
        $this->secondField = $options['secondField'];
    }

    public function validatedBy()
    {
        return \get_class($this) . 'Validator';
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getFirstField(): string
    {
        return $this->firstField;
    }

    public function getSecondField(): string
    {
        return $this->secondField;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
