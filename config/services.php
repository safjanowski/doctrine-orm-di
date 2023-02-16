<?php

use App\CloseBug;
use App\CreateBug;
use App\CreateProduct;
use App\CreateUser;
use App\Dashboard;
use App\ListBugsArray;
use App\ListBugsRepository;
use App\ListProducts;
use App\Products;
use App\ShowBug;
use App\ShowProduct;
use App\UpdateProduct;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();
    $inflector = InflectorFactory::create()->build();
    foreach ([CloseBug::class, CreateBug::class, CreateUser::class, CreateProduct::class, Dashboard::class, ListBugsArray::class, ListBugsRepository::class, ListProducts::class, Products::class, ShowBug::class, ShowProduct::class, UpdateProduct::class] as $class) {
        $services->set(str_replace('\\', '.', $inflector->tableize($class)), $class)
            ->args([service('doctrine.orm.entity_manager')])
            ->tag('console.command');
    }
};