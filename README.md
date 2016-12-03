# net-tools/core

## Composer library to provide PHP core functionalities

The package contains classes for :
- containers (cache, pool)
- formatters (export data to CSV of any other format - currently only CSV export is implemented)
- helpers (to help processing data in requests, to sanitize user data, to encode/decode data, etc.)


### Setup instructions

To install net-tools/core package, just require it through composer : `require net-tools/core:^1.0.0`


### How to use ?

The classes provided in the helpers are rather self-explanatory, each class and method dealing with only one purpose. All helper classes are not meant to be instantiated (all methods are static), **except PdoHelper which MUST be instantiated**. The Containers and Formatters class must also be instantiated.

Namespace | Class              |  Description
----------|--------------------|----------------
Helpers   | DataHelper         | Miscellaneous functions to deal with string, dates, and arrays
Helpers   | EncodingHelper     | Encode/decode accented characters to/from html entities or remove any accented character
Helpers   | FileHelper         | Guess the file type (image, video, etc...) or the Mime type of a file by looking at it's name
Helpers   | ImagingHelper      | Resize an image to a given with and/or height (aspect ratio preserved)
Helpers   | NetworkingHelper   | Construct JSON data with function calls ; add parameters to an url ; get relative folder (to webroot) for a file
Helpers   | SecurityHelper     | Create tokens, token with an expiration delay, sanitize strings
Helpers   | PdoHelper          | Provide convenient functions to make queries with PDO. Deals with foreign keys (database schema with foreign keys must be supplied within an array). Can be used as any Pdo instance, as PdoHelper is a subclass of PHP Pdo class.
Containers| Pool               | Manage a pool of objects (refer to 'Pool' design pattern)
Containers| Cache              | Manage a cache of objects 
Containers| PersistentCache    | Manage a cache of objects which is meant to be serialized (to disk) ; provide one of the `CachePersistentProvider` subclass as a strategy pattern to handle persistence.
Formatters| Formatter          | Base class to handle a tabular output (rows and columns)
Formatters| CsvFormatter       | Handle CSV output (subclass of `Formatter`)
Formatters| FormatterOutputStrategy    | Strategy pattern to implement concrete output (to a file or a string) ; provide one of the `FormatterOutputStrategy` subclass.

When the package is mentionned in you composer.json, it will automatically require `Includes/Init.php` which will perform some initialization stuff, as the default charset, locale and timezones. Currently, it sets UTF8 as charset, and French locale and timezone. Futures releases will be more opened to i18n ;-) !



### Samples 

For helpers classes other than PdoHelper and NetworkingHelper, the function names and their parameters are self-explanatory.

#### Sample : NetworkingHelper / Json functions

The Helpers\NetworkingHelper class has methods to produce Json formatted data in a programmatic way, rather than build a large PHP array and then 'json_encoding' it. When reading the source code, I think reading function calls is more confortable than dealing with (nested) arrays, when there's a large amount of properties in the JSON object litteral.

For example, to return the following Json :
```json
{
  "name" : "John",
  "age" : 36,
  "phones" : [
    "0123456789",
    "9876543210"
  ]
}
```
We can write : 
```php
$t = array(
    "name" => "john",
    "age" => 36,
    "phones" => ['0123456789', '9876543210']
);
$t = json_encode($t);
```

or (see nested call, the last parameter of `json_value` is a another call to the same method, adding a new property to the returned JSON data) :
```php
$t = NetworkingHelper::json_value('name', 'john', 
     NetworkingHelper::json_value('age', 36, 
     NetworkingHelper::json_value('phones', ['0123456789', '9876543210']
   )));
```
