<?php

declare(strict_types=1);

namespace App\Utils;

use Symfony\Component\Form\FormInterface;

class FormErrors
{
    public static function getAll(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}
