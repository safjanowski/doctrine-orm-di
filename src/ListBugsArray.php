<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:list-bugs-array',
    description: 'List bugs array'
)]
class ListBugsArray extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bugs = $this->entityManager->getRepository(Bug::class)->getRecentBugsArray();

        foreach ($bugs as $bug) {
            $output->writeln($bug['description'] . " - " . $bug['created']->format('d.m.Y'));
            $output->writeln("    Reported by: " . $bug['reporter']['name']);
            $output->writeln("    Assigned to: " . $bug['engineer']['name']);
            foreach ($bug['products'] as $product) {
                $output->writeln("    Platform: " . $product['name']);
            }
        }

        return Command::SUCCESS;
    }
}


