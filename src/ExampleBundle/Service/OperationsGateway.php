<?php
namespace ExampleBundle\Service;

class OperationsGateway
{
    /**
     * @var CsvFormatter
     */
    private $formatter;

    /**
     * OperationsGateway constructor.
     * @param CsvFormatter $formatter
     */
    public function __construct(CsvFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    public function fetchAll($fileName)
    {
        return $this->formatter
            ->formatContent(
                $this->get($fileName)
            );
    }


    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function get($fileName)
    {
        return file_get_contents($fileName);
    }
}
