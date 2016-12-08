<?php
/**
 * Initializer
 *
 * @author Pierre - dev@net-tools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Includes;



/** 
 * Class to do some init stuff at autoloading
 */
final class Initializer
{
    /**
     * Set behavior for errors
     * 
     * @param string $std Set this parameter to 'stdout' to output errors on screen or 'stderr' to output errors to error log
     */
    function initDisplayErrors($std)
    {
        ini_set('display_errors', $std);        
    }
    

    /**
     * Set timezone
     *
     * @param string $tz Indicates the timezone to use for date/time calculations, e.g. 'Europe/Paris'
     */
    function initTimeZone($tz)
    {
        ini_set('date.timezone', $tz);
    }
    
    
    /**
     * Defines the encoding used for mb_xxx functions
     * 
     * @param string $charset Set this parameter to any valid charset, such as 'utf-8'
     */
    function initInternalEncoding($charset)
    {
        mb_internal_encoding($charset);
    }
    
    
    /** 
     * Set the locale (used to output dates in the correct language)
     * 
     * @param string $locale Set this parameter to any valid locale, such as 'fr_FR'
     */
    function initLocale($locale)
    {
        setlocale(LC_TIME, $locale);
    }
}
    
    
?>