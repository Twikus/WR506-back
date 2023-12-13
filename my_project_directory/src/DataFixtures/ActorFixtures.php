<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Nationality;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        // Génere les données pour 10 acteurs avec un firstName et un lastName réaliste
        // Ajouter les pays

        $firstNames = [
            'Jean', 'Pierre', 'Paul', 'Jacques', 'Marie', 'Julie', 'Julien', 'Jeanne', 'Pierre', 'Pauline'
        ];
        $lastNames = [
            'Dupont', 'Durand', 'Duchemin', 'Duchesse', 'Duc', 'Ducroc', 'Ducrocq', 'Ducroq', 'Ducroque', 'Ducroquefort'
        ];

        $nationalities = $manager->getRepository(Nationality::class)->findAll();

        foreach (range(1, 10) as $i) {
            $actor = new Actor();
            $actor->setFirstName($firstNames[rand(0, 9)]);
            $actor->setLastName($lastNames[rand(0, 9)]);
            $actor->setFullName($actor->getFirstName() . ' ' . $actor->getLastName());
            $randomNationality = $nationalities[array_rand($nationalities)];
            $actor->setNationality($randomNationality);
            $reward =['Oscar du meilleur acteur', 'Oscar du meilleur acteur dans un second rôle', 'Oscar de la meilleure actrice', 'Oscar de la meilleure actrice dans un second rôle', 'Oscar du meilleur film', 'Oscar du meilleur film d\'animation', 'Oscar du meilleur film documentaire', 'Oscar du meilleur film international', 'Oscar du meilleur scénario original', 'Oscar du meilleur scénario adapté'];
            $actor->setReward($reward[rand(0, 9)]);
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
            // "expose" l'objet à l'extérieur de la classe pour les liaisons avec Movie
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            NationalityFixtures::class,
        ];
    }
}
