<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:products',
    description: 'List number of new bugs for each product'
)]
class Products extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productBugs = $this->entityManager->getRepository(Bug::class)->getOpenBugsByProduct();

        foreach ($productBugs as $productBug) {
            $output->writeln($productBug['name'] . " has " . $productBug['openBugs'] . " open bugs!");
        }

        return Command::SUCCESS;
    }
}


