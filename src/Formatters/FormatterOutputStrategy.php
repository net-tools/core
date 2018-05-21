<?php
/**
 * FormatterOutputStrategy
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Formatters;



/**
 * Abstract for an output formatter strategy
 */
abstract class FormatterOutputStrategy
{
    /** 
     * Output data
     * 
     * @param string $data Data to output
     */
    abstract function output($data);
}

?>