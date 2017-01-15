<?php
namespace ExampleBundle\Command;

use ExampleBundle\Service\FeeCalculator;
use ExampleBundle\Service\Operation;
use ExampleBundle\Service\OperationsRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExampleCommand extends Command
{
    const ARGUMENT_FILE_NAME = 'file_name';
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ExampleCommand constructor.
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('pay:run')
            ->addArgument(self::ARGUMENT_FILE_NAME, InputArgument::REQUIRED, 'Input file name')

            // the short description shown while running "php bin/console list"
            ->setDescription('Writes hello world')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to write hello world...")
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
                $output->writeln($feeCalculator->getFee($operation));
            }
        }
        $output->writeln('Hello world');
    }
}
