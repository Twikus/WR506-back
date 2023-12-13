<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (range(1, 40) as $i) {
            $movie = new Movie();
            $movie->setTitle('Movie ' . $i);
            $movie->setReleaseDate(new DateTime());
            $movie->setDuration(rand(60, 180));
            $movie->setDescription('Synopsis ' . $i);
            $movie->setCategory($this->getReference('category_' . rand(1, 5)));
            $movie->setEntries(rand(1000, 1000000));
            $movie->setNote(rand(0, 10));
            $movie->setBudget(rand(1000000, 100000000));
            
            $directors = ['Steven Spielberg', 'James Cameron', 'George Lucas', 'Peter Jackson', 'Christopher Nolan', 'Tim Burton', 'Quentin Tarantino', 'Martin Scorsese', 'Ridley Scott', 'David Fincher'];
            $movie->setDirector($directors[rand(0, 9)]);
            $movie->setWebsite('https://www.allocine.fr/film/fichefilm_gen_cfilm=' . rand(1000, 9999) . '.html');
            
            // Ajoute entre 2 et 6 acteurs dans le films, tous différents en se basant sur les fixtures
            $actors = [];
            foreach (range(1, rand(2, 6)) as $j) {
                $actor = $this->getReference('actor_' . rand(1, 10));
                if (!in_array($actor, $actors)) {
                    $actors[] = $actor;
                    $movie->addActor($actor);
                }
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            ActorFixtures::class,
        ];
    }
}
