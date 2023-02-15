<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-product',
    description: 'Create product'
)]
class CreateProduct extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('newProductName', InputArgument::REQUIRED, 'The name of new product');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $newProductName = (string)$input->getArgument('newProductName');

        $product = new Product();
        $product->setName($newProductName);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $output->writeln("Created Product with ID " . $product->getId());

        return Command::SUCCESS;
    }
}


