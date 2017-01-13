<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

$container->register('console', Symfony\Component\Console\Application::class);

$container->register('console', Symfony\Component\Console\Application::class)
    ->addMethodCall('add', [new Reference('hello_world.command')]);

$container->register('finder', \Symfony\Component\Finder\Finder::class);

$container->register('hello_world.command', \ExampleBundle\Command\ExampleCommand::class)
    ->addArgument(new Reference('service_container'));

$container->register('csv.formatter', \ExampleBundle\Service\CsvFormatter::class);
$container->register('operations.gateway', \ExampleBundle\Service\OperationsGateway::class)
    ->addArgument(new Reference('csv.formatter'));

$container->register('operation_builder', \ExampleBundle\Service\OperationBuilder::class);
$container->register('operations.repository', \ExampleBundle\Service\OperationsRepository::class)
    ->addArgument(new Reference('operations.gateway'))
    ->addArgument(new Reference('operation_builder'));


return $container;