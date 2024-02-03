<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Nationality;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Xylis\FakerCinema\Provider\Person;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $rewards = [
            'Cesar', "Palme or", 'Oscar'
        ];

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Person($faker));

        $nationalities = $manager->getRepository(Nationality::class)->findAll();

        foreach (range(1, 30) as $i) {

            $fullname = $faker->unique()->actor;
            $randomNationality = $nationalities[array_rand($nationalities)];
            $actor = (new Actor())
            ->setFirstName(substr($fullname, 0, strpos($fullname, ' ')))
            ->setLastName(substr($fullname, strpos($fullname, ' ') + 1))
            ->setNationality($randomNationality)
            ->setReward($rewards[rand(0, 2)]);
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
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
