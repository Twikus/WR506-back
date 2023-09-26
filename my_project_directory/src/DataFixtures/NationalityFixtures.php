<?php

namespace App\DataFixtures;

use App\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NationalityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $title = ['Français', 'Anglais', 'Américain', 'Allemand', 'Italien', 'Espagnol', 'Portugais', 'Belge', 'Suisse', 'Canadien'];
        // Génere les données pour 10 nationalités avec un title réaliste

        foreach (range(1, 10) as $i) {
            $nationality = new Nationality();
            $nationality->setTitle($title[rand(0, 9)]);
            $manager->persist($nationality);
            $this->addReference('nationality_' . $i, $nationality); // "expose" l'objet à l'extérieur de la classe pour les liaisons avec Movie
        }

        $manager->flush();
    }
}
