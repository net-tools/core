# net-tools/core

## Composer library to provide PHP core functionalities

The package contains classes for :
- containers (cache, pool)
- formatters (export data to CSV of any other format - currently only CSV export is implemented)
- helpers (to help processing data in requests, to sanitize user data, to encode/decode data, etc.)


## Setup instructions

To install net-tools/core package, just require it through composer : `require net-tools/core:^1.0.0`


## How to use ?

The classes provided in the helpers are rather self-explanatory, each class and method dealing with only one purpose. All helper classes are not meant to be instantiated (all methods are static), **except PdoHelper which MUST be instantiated**. The Containers and Formatters class must also be instantiated.

Namespace | Class              |  Description
----------|--------------------|----------------
Helpers   | DataHelper         | Miscellaneous functions to deal with string, dates, and arrays
Helpers   | EncodingHelper     | Encode/decode accented characters to/from html entities or remove any accented character
Helpers   | FileHelper         | Guess the file type (image, video, etc...) or the Mime type of a file by looking at it's name
Helpers   | ImagingHelper      | Resize an image to a given with and/or height (aspect ratio preserved)
Helpers   | NetworkingHelper   | Send XMLHttp response headers (no cache allowed) ; add parameters to an url ; get relative folder (to webroot) for a file ; get feedback about an file upload error code
Helpers   | SecurityHelper     | Create tokens, token with an expiration delay, sanitize strings
Helpers   | PdoHelper          | Provide convenient functions to make queries with PDO. Deals with foreign keys (database schema with foreign keys must be supplied within an array). Can be used as any Pdo instance, as PdoHelper is a subclass of PHP Pdo class.
Containers| Pool               | Manage a pool of objects (refer to 'Pool' design pattern)
Containers| Cache              | Manage a cache of objects 
Containers| PersistentCache    | Manage a cache of objects which is meant to be serialized (to disk) ; provide one of the `CachePersistentProvider` subclass as a strategy pattern to handle persistence.
Formatters| Formatter          | Base class to handle a tabular output (rows and columns)
Formatters| CsvFormatter       | Handle CSV output (subclass of `Formatter`)
Formatters| FormatterOutputStrategy    | Strategy pattern to implement concrete output (to a file or a string) ; provide one of the `FormatterOutputStrategy` subclass.

When the package is mentionned in your composer.json, it will automatically require `Includes/Init.php` which will perform some initialization stuff, such as the default charset, locale and timezones. Currently, if not specified, the errors are displayed in the standard output, and the `mb_xxx` functions default encoding is set to UTF_8, as this is the most easy way to deal with foreign characters.

You may set other values, by defining the following constants BEFORE including your vendor/autoload.php :

Constant                             |  default value set     | Description
-------------------------------------|------------------------|---------------
K_NETTOOLS_DISPLAY_ERRORS            | `'stdout'`             | Provide `'stderr'` string to redirect errors to the error log
K_NETTOOLS_INIT_TIMEZONE             | None (PHP config used) | Provide the timezone string, such as `'Europe/Paris'` if PHP default timezone is not set correctly by the server config.
K_NETTOOLS_INIT_MB_INTERNAL_ENCODING | `'utf-8'`              | Provide the encoding to user with mb_xxx functions
K_NETTOOLS_INIT_LOCALE               | None (PHP uses US locale by default)   | Set the right locale, such as `'fr_FR.utf8'`



## Samples 

For most classes, the function names and their parameters are self-explanatory.

### Sample : CsvFormatter

The Formatters namespace has some classes to help export tabular data. 

Currently, only CSV export is implemented, but we could also implement HTML Table export rather simply by subclassing the `Formatter` class and providing code to it's abstract methods (those methods define how to print lines, rows, separate columns, etc.). For `CsvFormatter` subclass, the only implementation needed is how to separate columns (in CSV, this is done with ';' character). New rows are written with a newline character.

```php
// we create a file at $PATH
$fhandle = fopen($path, 'w');

// we create the formatter along with an output strategy, here to a file handle
$csv = new CsvFormatter(new FormatterFileOutputStrategy($fhandle));

// beginning export
$csv->newRow();
$csv->row(array('column1 header', 'column2 header', 'column3 header'));
$csv->closeRow();
$csv->newRow();
$csv->row(array('line2_column1_value', 'line3_column2_value', ''));
$csv->closeRow(true);   // true = this is the last row

// closing file handle
fclose($fhandle);
```


### Sample : PdoHelper

PdoHelper is a subclass of PHP Pdo class (instantiation needed, use the same constructor parameters as the Pdo constructor). So you may use any usual method of Pdo (such as `prepare` and `execute`).

There are simple functions such as `pdo_query` or `pdo_query_select` which prepare AND then execute the request with a single call.

There is a `pdo_dbexists` method you may use to test whether a value exists for a SQL Select statement, with only one PHP code line (the value is returned) :
```php
if ( $name = $pdoh->pdo_dbexists('SELECT name FROM Client WHERE id=?', array(123456)) )
    echo "found client ; its name is '$name' !";
```

The main benefit of PdoHelper is it's foreign key querying system. If you define your relationnal schema (just the tables having foreign keys, other tables are useless), you may ask the question "is there a table which has a line with a column referencing a particular foreign key ?". In other words, may I delete safely a row in a table X without breaking a table Y with a column referencing table X and the row deleted ?

To build the schema of tables/foreign keys, you just call `addForeignKey` method for all tables whoses rows may be referenced (tables being foreign keys to other). For example, in a Town and Client schema, Town is the table being referenced by a idTown column in the Client table.

```php
// defining a schema with 2 tables referencing the Town table through it's idTown column
$pdoh->addForeignKey('Town', 'idTown', ['Client', 'Merchants']);
```

To safely delete a row in Town table, we need to check that no row in Client or Merchants has a reference to the town being deleted :

```php
$test = $pdoh->pdo_foreignkeys('Town', 1234);
if ( $test['statut'] )
   // no foreign key detected, we may delete safely the town
   echo "deletion is safe";
else
   echo "deletion is not safe : " . $test['cause']['message'];
```   


## API Reference

To read the entire API reference, please refer to the PHPDoc here : 
http://net-tools.ovh/api-reference/net-tools/Nettools/Core.html


## PHPUnit

To test with PHPUnit, point the -c configuration option to the /phpunit.xml configuration file.





