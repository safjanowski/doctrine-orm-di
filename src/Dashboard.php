<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:dashboard',
    description: 'Dashboard'
)]
class Dashboard extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('theUserId', InputArgument::REQUIRED, 'The `id` of user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Dashboard',
            '=========',
            ''
        ]);

        $theUserId = (int)$input->getArgument('theUserId');

        $myBugs = $this->entityManager->getRepository(Bug::class)->getUsersBugs($theUserId);

        $output->writeln(['You have created or assigned to ' . count($myBugs) . ' open bugs:', '']);

        foreach ($myBugs as $bug) {
            $output->writeln("{$bug->getId()} - {$bug->getDescription()}");
        }

        return Command::SUCCESS;
    }
}


