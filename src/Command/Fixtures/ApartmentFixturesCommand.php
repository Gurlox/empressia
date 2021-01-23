<?php

namespace App\Command\Fixtures;

use App\Entity\Apartment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApartmentFixturesCommand extends Command
{
    protected static $defaultName = 'app:fixtures:apartments';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Persist apartment entities to database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getApartments() as $apartment) {
            $apartmentEntity = new Apartment($apartment['address'], $apartment['availableSlots'], $apartment['priceForBed']);
            $this->em->persist($apartmentEntity);
        }

        $this->em->flush();

        return 0;
    }

    private function getApartments(): array
    {
        return [
            [
                'address' => 'Poznań Dąbrowskiego 42a/2',
                'availableSlots' => 10,
                'priceForBed' => 100,
            ],
            [
                'address' => 'Poznań Przybyszewskiego 50/3',
                'availableSlots' => 7,
                'priceForBed' => 200,
            ],
        ];
    }
}
