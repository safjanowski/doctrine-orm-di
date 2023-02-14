<?php
// create_bug.php
require_once "bootstrap.php";

$theReporterId = $argv[1];
$theDefaultEngineerId = (isset($argv[2])) ? $argv[2] : 1;
$productIds = explode(",", $argv[3]);

$reporter = $entityManager->find(App\User::class, $theReporterId);
$engineer = $entityManager->find(App\User::class, $theDefaultEngineerId);
if (!$reporter || !$engineer) {
    echo "No reporter and/or engineer found for the input.\n";
    exit(1);
}

$bug = new App\Bug();
$bug->setDescription("Something does not work!");
$bug->setCreated(new DateTime("now"));
$bug->setStatus("OPEN");

foreach ($productIds as $productId) {
    $product = $entityManager->find(App\Product::class, $productId);
    if (!$product) {
        echo "No product found for the input.\n";
        exit(1);
    }
    $bug->assignToProduct($product);
}

$bug->setReporter($reporter);
$bug->setEngineer($engineer);

$entityManager->persist($bug);
$entityManager->flush();

echo "Your new Bug Id: " . $bug->getId() . "\n";
