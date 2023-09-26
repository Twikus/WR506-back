<?php

namespace App\DataFixtures;

use App\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NationalityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Génère un tableau dans la variable pays qui contient dix origines
        $pays = ['France', 'Allemagne', 'Italie', 'Espagne', 'Portugal', 'Belgique', 'Suisse', 'Angleterre', 'Pays-Bas', 'Autriche'];
        foreach (range(0, 9) as $i) {
            $nationality = new Nationality();
            $nationality->setTitle($pays[$i]);
            $manager->persist($nationality);
            $this->addReference('nationality_' . $i, $nationality);
        }

        $manager->flush();
    }
}