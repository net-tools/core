<?php
/**
 * Init ; called automatically at Composer autoloading
 *
 * Standard initializations :
 * - display errors on standard output (by default)
 * - timezone init (none by default, using the timezone from PHP config file)
 * - internal encoding for multibyte functions (defaults to UTF-8)
 * - locale (none by default)
 * To provide values for those initializations, define the following constants :
 * - K_NETTOOLS_DISPLAY_ERRORS (to stderr or stdout)
 * - K_NETTOOLS_INIT_TIMEZONE (to the appropriate timezone)
 * - K_NETTOOLS_INIT_MB_INTERNAL_ENCODING (to the appropriate charset)
 * - K_NETTOOLS_INIT_LOCALE (to the locale to use)
 * - K_NETTOOLS_POSTMASTER (email to send exception stack trace to ; defaults to $_SERVER['SERVER_ADMIN'])
 *
 * @author Pierre - dev@nettools.ovh
 * @license MIT
 */


// namespace
namespace Nettools\Core\Includes;



// display errors on screen (stdout) rather than on the error log (stderr)
// As the error log may not be easily accessible on some hosts, errors
// could be printed on default display to help debug (if a system is well
// designed, errors should be catched with exceptions and throwables handling).
if ( !defined('K_NETTOOLS_DISPLAY_ERRORS') )
	// by default, errors are displayed in the standard output, unless the user
	// defines a K_NETTOOLS_DISPLAY_ERRORS constant to 'stderr' value.
	define('K_NETTOOLS_DISPLAY_ERRORS', 'stdout');
Initializer::initDisplayErrors(K_NETTOOLS_DISPLAY_ERRORS);


// set default timezone
if ( defined('K_NETTOOLS_INIT_TIMEZONE') )
    Initializer::initTimeZone(K_NETTOOLS_INIT_TIMEZONE);


// set default encoding for mb_* functions ; since PHP 5.6, it's utf-8 by default. 
// However, if the php config file on your host is wrong, it may set another charset
// which should be avoided, as UTF-8 is more global. It's a good pratice to ensure
// the right charset is defined, whichever is the php config file
if ( !defined('K_NETTOOLS_INIT_MB_INTERNAL_ENCODING') )
	define('K_NETTOOLS_INIT_MB_INTERNAL_ENCODING', 'utf-8');
Initializer::initInternalEncoding(K_NETTOOLS_INIT_MB_INTERNAL_ENCODING);


// set default locale, if not defined by user before including the vendor autoload.php
if ( defined('K_NETTOOLS_INIT_LOCALE') )
    Initializer::initLocale(K_NETTOOLS_INIT_LOCALE);


// defines the email address to send exceptions details to
if ( !defined('K_NETTOOLS_POSTMASTER') )
	define('K_NETTOOLS_POSTMASTER', array_key_exists('SERVER_ADMIN', $_SERVER) ? $_SERVER['SERVER_ADMIN'] : null);


?>