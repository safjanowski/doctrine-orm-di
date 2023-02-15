<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-product',
    description: 'Update product'
)]
class UpdateProduct extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'The `id` of product');
        $this->addArgument('newName', InputArgument::REQUIRED, 'The `new name` of product');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = (int)$input->getArgument('id');
        $newName = (string)$input->getArgument('newName');

        $product = $this->entityManager->find(Product::class, $id);

        if ($product === null) {
            $output->writeln("<error>Product $id does not exist.</error>");
            return Command::FAILURE;
        }

        $product->setName($newName);

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}


