<?php

// namespace
namespace Nettools\Core\Formatters;



// abstract for an output formatter strategy
abstract class FormatterOutputStrategy
{
    // output data
    abstract function output($data);
}

?>