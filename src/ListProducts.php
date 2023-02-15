<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:list-products',
    description: 'List products'
)]
class ListProducts extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        /** @var $products Product[] */
        $products = $productRepository->findAll();

        foreach ($products as $product) {
            $output->writeln(sprintf("-%s\n", $product->getName()));
        }

        return Command::SUCCESS;
    }
}


