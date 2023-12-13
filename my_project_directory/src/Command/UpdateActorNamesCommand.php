<?php

namespace App\Command;

use App\Entity\Actor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateActorNamesCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setFullName('app:update-actor-names')
            ->setDescription('Update names for existing actors.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actors = $this->entityManager->getRepository(Actor::class)->findAll();

        foreach ($actors as $actor) {
            $actor->setName($actor->getFirstName() . ' ' . $actor->getLastName());
        }

        $this->entityManager->flush();

        $output->writeln('Names updated for existing actors.');
    }
}
