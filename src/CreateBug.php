<?php

namespace App;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-bug',
    description: 'Create bug'
)]
class CreateBug extends Command
{
    public function __construct(private readonly ObjectManager $entityManager)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('theReporterId', InputArgument::REQUIRED, 'The `id` of reporter');
        $this->addArgument('productIds', InputArgument::REQUIRED, 'The list of products id, comma separated');
        $this->addArgument('theDefaultEngineerId', InputArgument::OPTIONAL, 'The `id` of engineer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $theReporterId = (int)$input->getArgument('theReporterId');
        $theDefaultEngineerId = (int)$input->getArgument('theDefaultEngineerId') ?? $theReporterId;
        $productIds = explode(",", $input->getArgument('productIds'));

        $reporter = $this->entityManager->find(User::class, $theReporterId);
        $engineer = $this->entityManager->find(User::class, $theDefaultEngineerId);
        if (!$reporter || !$engineer) {
            $output->writeln("<error>No reporter and/or engineer found for the input.</error>");
            return Command::FAILURE;
        }

        $bug = new Bug();
        $bug->setDescription("Something does not work!");
        $bug->setCreated(new \DateTime("now"));
        $bug->setStatus("OPEN");

        foreach ($productIds as $productId) {
            $product = $this->entityManager->find(Product::class, $productId);
            if (!$product) {
                $output->writeln("<error>No product found for the input.</error>");
                return Command::FAILURE;
            }
            $bug->assignToProduct($product);
        }

        $bug->setReporter($reporter);
        $bug->setEngineer($engineer);

        $this->entityManager->persist($bug);
        $this->entityManager->flush();

        $output->writeln("Your new Bug Id: " . $bug->getId());

        return Command::SUCCESS;
    }
}


