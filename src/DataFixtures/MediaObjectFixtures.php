<?php

namespace App\DataFixtures;

use App\Entity\MediaObject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaObjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (range(1, 5) as $i) {

            copy(
                __DIR__ . '/../../public/uploads/media/' . $i . '.jpeg',
                __DIR__ . '/../../public/uploads/media/' . $i . 'copy.jpeg'
            );
            $file = new UploadedFile(
                __DIR__ . '/../../public/uploads/media/' . $i . 'copy.jpeg',
                'film' . $i . '.jpeg',
                'image/jpeg',
                null,
                true
            );

            $mediaObject = (new MediaObject())
                ->setFile($file)
                ->setFilePath($file->getPathname());

            $manager->persist($mediaObject);
            $this->addReference('mediaObject_' . $i, $mediaObject);
        }

        $manager->flush();
    }
}