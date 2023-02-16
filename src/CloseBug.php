<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:close-bug',
    description: 'Close bug'
)]
class CloseBug extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('theBugId', InputArgument::REQUIRED, 'The `id` of bug');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $theBugId = (int)$input->getArgument('theBugId');

        $bug = $this->entityManager->find(Bug::class, $theBugId);
        $bug->close();

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}