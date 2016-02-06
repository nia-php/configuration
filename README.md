# nia - configuration

Unified reading of configurations.

## Installation

Require this package with Composer.

```bash
	composer require nia/configuration
```

## Tests
To run the unit test use the following command:

    $ cd /path/to/nia/component/
    $ phpunit --bootstrap=vendor/autoload.php tests/



## Sample: How to use it
The following sample shows you how to use this component for a string which contains a ini configuration. It uses the `Nia\Configuration\Reader\Ini\IniStringConfiguration` class. If you want to read a ini file just use the `Nia\Configuration\Reader\Ini\IniFileConfiguration` class.

There is also an implementation for json strings (`Nia\Configuration\Reader\Json\JsonStringConfiguration`) and json files (`Nia\Configuration\Reader\Json\JsonFileConfiguration`).

```php
	$content = <<<EOL
	[database]
	    hostname=127.0.0.1
	    username=root
	    password=
	    port=1234

	[environment]
	    debug.mode=development
	    debug.log=/var/log/application.log
	EOL;

	$configuration = new IniStringConfiguration($content);

	$hostname = $configuration->getSection('database')->get('hostname');
	$debugLog = $configuration->getSection('environment')->get('debug.log');
```
