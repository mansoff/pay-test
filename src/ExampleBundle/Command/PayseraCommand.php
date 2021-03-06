<?php
namespace ExampleBundle\Command;

use ExampleBundle\Service\Fees\FeeCalculator;
use ExampleBundle\Service\Math;
use ExampleBundle\Service\Operation;
use ExampleBundle\Service\OperationsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PayseraCommand extends Command
{
    const ARGUMENT_FILE_NAME = 'file_name';
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ExampleCommand constructor.
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        parent::__construct();
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('pay:run')
            ->addArgument(self::ARGUMENT_FILE_NAME, InputArgument::REQUIRED, 'Input file name')
            ->setDescription('Pay operation fee calculator')
            ->setHelp("This command allows you to calculate operations fees")
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var OperationsRepository $operationsRepository */
        $operationsRepository = $this->container->get('operations.repository');
        /** @var FeeCalculator $feeCalculator */
        $feeCalculator = $this->container->get('fee.calculator');
        $operations = $operationsRepository->getOperations(
            $input->getArgument(self::ARGUMENT_FILE_NAME)
        );

        foreach ($operations as $operation) {
            if ($operation instanceof Operation) {
                $output->writeln(
                    Math::convertToOutput(
                        $feeCalculator->getFee($operation)
                    )
                );
            }
        }
    }
}
