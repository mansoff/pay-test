<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

$container->register('console', Symfony\Component\Console\Application::class)
    ->addMethodCall('add', [new Reference('example.command')]);
$container->register('finder', \Symfony\Component\Finder\Finder::class);
$container->register(
    'example.command',
    \ExampleBundle\Command\ExampleCommand::class
)
    ->addArgument(new Reference('service_container'));

$container->register(
    'csv.formatter',
    \ExampleBundle\Service\CsvFormatter::class
);
$container->register(
    'operations.gateway',
    \ExampleBundle\Service\OperationsGateway::class
)
    ->addArgument(new Reference('csv.formatter'));

$container->register(
    'operation_builder',
    \ExampleBundle\Service\OperationBuilder::class
);
$container->register(
    'operations.repository',
    \ExampleBundle\Service\OperationsRepository::class
)
    ->addArgument(new Reference('operations.gateway'))
    ->addArgument(new Reference('operation_builder'));

$container->register(
    'fees.config',
    \ExampleBundle\Service\Fees\FeesConfig::class
)
    ->addArgument([
        'cash_in' => [
            'legal' => [
                'percent' => '0.0003',
                'max' => '5.00',
                'currency' => 'EUR',
            ],
            'natural' => [
                'percent' => '0.0003',
                'max' => '5.00',
                'currency' => 'EUR',
            ],
        ],
        'cash_out' => [
            'legal' => [
                'percent' => '0.003',
                'min' => '0.50',
                'currency' => 'EUR',
            ],
            'natural' => [
                'percent' => '0.003',
                'week_sum' => '1000.00',
                'currency' => 'EUR',
                'free_operations' => '3',
            ],
        ],
    ]);
$container->register(
    'fee.calculator',
    \ExampleBundle\Service\Fees\FeeCalculator::class
)
    ->addArgument(new Reference('fees.config'))
    ->addArgument(new Reference('exchange.service'))
    ->addArgument([
        'natural_cash_in' => new Reference('natural_in.fee'),
        'natural_cash_out' => new Reference('natural_out.fee'),
        'legal_cash_in' => new Reference('legal_in.fee'),
        'legal_cash_out' => new Reference('legal_out.fee'),
    ]);

$container->register('exchange.service', \ExampleBundle\Service\Exchange::class)
    ->addArgument([
        'USD' => '1.1497',
        'JPY' => '129.53',
    ]);

$container->register(
    'natural_in.fee',
    \ExampleBundle\Service\Fees\NaturalInFee::class
)
    ->addArgument(new Reference('exchange.service'))
    ->addArgument(new Reference('fees.config'));
$container->register(
    'natural_out.fee',
    \ExampleBundle\Service\Fees\NaturalOutFee::class
)
    ->addArgument(new Reference('exchange.service'))
    ->addArgument(new Reference('fees.config'))
    ->addArgument(new Reference('week.gateway'));
$container->register(
    'legal_in.fee',
    \ExampleBundle\Service\Fees\LegalInFee::class
)
    ->addArgument(new Reference('exchange.service'))
    ->addArgument(new Reference('fees.config'));
$container->register(
    'legal_out.fee',
    \ExampleBundle\Service\Fees\LegalOutFee::class
)
    ->addArgument(new Reference('exchange.service'))
    ->addArgument(new Reference('fees.config'));

$container->register('week.gateway', \ExampleBundle\Service\WeekGateway::class);

return $container;
