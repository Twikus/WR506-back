<?php

// src/Command/AssignUserRoleCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

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
            ->setDescription('Assign a role to a specific user.')
            ->addArgument('user_id', InputArgument::REQUIRED, 'The user ID to assign a role.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('user_id');

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if (!$user) {
            $output->writeln("User with ID $userId not found.");

            return Command::FAILURE;
        }

        // Define available roles
        $availableRoles = ['ROLE_USER', 'ROLE_ADMIN']; // Add any additional roles as needed

        // Ask the user to choose a role from the available options
        $selectedRole = $this->askForRole($input, $output, $availableRoles);

        // Check if the user already has the selected role
        if (!in_array($selectedRole, $user->getRoles(), true)) {
            // Add the selected role
            $user->addRole($selectedRole);

            // Save the changes
            $this->entityManager->flush();

            $output->writeln("$selectedRole has been assigned to user with ID $userId.");
        } else {
            $output->writeln("User with ID $userId already has $selectedRole.");
        }

        return Command::SUCCESS;
    }

    private function askForRole(InputInterface $input, OutputInterface $output, array $availableRoles): string
    {
        $helper = new QuestionHelper();
        $question = new ChoiceQuestion('Choose a role:', $availableRoles);

        return $helper->ask($input, $output, $question);
    }
}
