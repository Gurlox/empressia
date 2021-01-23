<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\ReservationDTO;
use App\Entity\Apartment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Apartment $apartment */
        $apartment = $options['apartment'];
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => false,
            ])
            ->add('slots', IntegerType::class)
            ->add('bookingPerson', TextType::class)
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($apartment) {
            /** @var ReservationDTO $formData */
            $formData = $event->getForm()->getData();
            if ($formData->slots > $apartment->getSlotsLeft($formData->startDate, $formData->endDate)) {
                $event->getForm()->addError(new FormError("This apartment doesn't have that many slots left."));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReservationDTO::class,
            'csrf_protection' => false,
        ]);
        $resolver->setRequired('apartment');
    }
}