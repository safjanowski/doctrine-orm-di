<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create user'
)]
class CreateUser extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('newUsername', InputArgument::REQUIRED, 'The name of new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newUsername = (string)$input->getArgument('newUsername');

        $user = new User();
        $user->setName($newUsername);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("Created User with ID " . $user->getId());

        return Command::SUCCESS;
    }
}


