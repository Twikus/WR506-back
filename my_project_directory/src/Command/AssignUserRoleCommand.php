<?php

// src/Command/AssignUserRoleCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User; // Assurez-vous que c'est le bon chemin vers votre entité User

class AssignUserRoleCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:assign-user-role')
            ->setDescription('Assign ROLE_USER to a specific user.')
            ->addArgument('user_id', InputArgument::REQUIRED, 'The user ID to assign ROLE_USER.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Récupérez l'ID d'utilisateur depuis les arguments de la commande
        $userId = $input->getArgument('user_id');

        // Récupérez l'utilisateur depuis la base de données
        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        // Vérifiez si l'utilisateur existe
        if (!$user) {
            $output->writeln("User with ID $userId not found.");

            return Command::FAILURE;
        }

        // Vérifiez si l'utilisateur a déjà le rôle ROLE_USER
        if (!in_array('ROLE_USER', $user->getRoles(), true)) {
            // Ajoutez le rôle ROLE_USER
            $user->addRole('ROLE_USER');

            // Enregistrez les modifications
            $this->entityManager->flush();

            $output->writeln("ROLE_USER has been assigned to user with ID $userId.");
        } else {
            $output->writeln("User with ID $userId already has ROLE_USER.");
        }

        return Command::SUCCESS;
    }
}
