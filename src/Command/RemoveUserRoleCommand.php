<?php

// src/Command/RemoveUserRoleCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class RemoveUserRoleCommand extends Command
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
            ->setName('app:remove-user-role')
            ->setDescription('Remove a role from a specific user.')
            ->addArgument('user_id', InputArgument::REQUIRED, 'The user ID to remove a role.');
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

        // Get the roles assigned to the user
        $userRoles = $user->getRoles();

        // Check if the user has any roles to remove
        if (empty($userRoles)) {
            $output->writeln("User with ID $userId has no roles to remove.");

            return Command::FAILURE;
        }

        // Ask the user to choose a role from the roles assigned to the user
        $selectedRole = $this->askForRoleToRemove($input, $output, $userRoles);

        // Check if the user has the selected role
        if (in_array($selectedRole, $userRoles, true)) {
            // Remove the selected role
            $user->removeRole($selectedRole);

            // Save the changes
            $this->entityManager->flush();

            $output->writeln("$selectedRole has been removed from user with ID $userId.");
        } else {
            $output->writeln("User with ID $userId does not have the role $selectedRole.");
        }

        return Command::SUCCESS;
    }

    private function askForRoleToRemove(InputInterface $input, OutputInterface $output, array $userRoles): string
    {
        $helper = new QuestionHelper();
        $question = new ChoiceQuestion('Choose a role to remove:', $userRoles);

        return $helper->ask($input, $output, $question);
    }
}
