<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:list-bugs-repository',
    description: 'List bugs repository'
)]
class ListBugsRepository extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bugs = $this->entityManager->getRepository(Bug::class)->getRecentBugs();

        foreach ($bugs as $bug) {
            $output->writeln([
                $bug->getDescription() . " - " . $bug->getCreated()->format('d.m.Y'),
                "    Reported by: " . $bug->getReporter()->getName(),
                "    Assigned to: " . $bug->getEngineer()->getName()
            ]);
            foreach ($bug->getProducts() as $product) {
                $output->writeln("    Platform: " . $product->getName());
            }
        }

        return Command::SUCCESS;
    }
}


