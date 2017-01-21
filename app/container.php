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

$container->register('fees.config', \ExampleBundle\Service\Fees\FeesConfig::class);
$container->register('fee.calculator', \ExampleBundle\Service\FeeCalculator::class)
    ->addArgument(new Reference('fees.config'))
    ->addArgument(new Reference('exchange.service'));

$container->register('exchange.service', \ExampleBundle\Service\Exchange::class)
    ->addArgument([
        'USD' => '1.1497',
        'JPY' => '129.53',
    ]);

return $container;
