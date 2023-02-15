<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:show-product',
    description: 'Show product'
)]
class ShowProduct extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'The `id` of product');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = (int)$input->getArgument('id');
        $product = $this->entityManager->find(Product::class, $id);

        if ($product === null) {
            $output->writeln("<error>No product found.</error>");
            return Command::FAILURE;
        }

        $output->writeln(sprintf("-%s", $product->getName()));

        return Command::SUCCESS;
    }
}


