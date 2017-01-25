<?php
namespace ExampleBundle\Service;

class CsvFormatter
{
    /**
     * @param string $input
     *
     * @return array
     */
    public function formatContent($input)
    {
        return $this->getArray($this->replaceNewLine($input));
    }

    /**
     * @param string $input
     *
     * @return string
     */
    protected function replaceNewLine($input)
    {
        $input = str_replace("\r\n", "\n", $input);
        $input = str_replace("\n\r", "\n", $input);
        return str_replace("\r", "\n", $input);
    }

    /**
     * @param string $input
     *
     * @return array
     */
    protected function getArray($input)
    {
        return explode("\n", $input);
    }
}
