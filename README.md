# php-logs


## Description
[PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) compatible PHP logging class.
It implements `Psr\Log\LoggerInterface`
Use it to create and read log files.

Log file directories pattern: `/{path}/{channel}/{year}/{month}/{date}.{log_files_format}`
Example: `/logs/user-actions/2020/02/02.log`

## Composer

### Installation

From the Command line:

```
composer require alexivashchenko/php-logs:dev-master
```

In `composer.json`:
```
{
	"require": {
		"alexivashchenko/php-logs": "dev-master"
	}
}
```


### Update

```
composer update alexivashchenko/php-logs
```


## Usage

### Create logs

``` php
<?php

require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$Logger = new Ali\Logger([
	'path' => 'logs' . DIRECTORY_SEPARATOR,
	'channel' => 'examples',
	'log_files_format' => 'log', // log or txt
	'line_type' => 'string', // string or json
]);

$Logger->info('New user added', [
	'id' => 1,
	'firstname' => 'Barack',
	'lastname' => 'Obama',
	'status' => 'ex-president',
]);
$Logger->error('Error adding user');

```


### Read logs

``` php
<?php

require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$Logger = new Ali\Logger([
	'path' => 'logs' . DIRECTORY_SEPARATOR,
	'channel' => 'examples',
	'log_files_format' => 'log', // log or txt
	'line_type' => 'string', // string or json
]);

print_r($Logger->read(date('Y-m-d')));
```

### Log content
#### `line_type` = 'string'
```
2020-03-20 16:47:37	[info]	Test info message	[]
2020-03-20 16:47:37	[error]	Test error message	[]
2020-03-20 16:47:37	[debug]	Test debug message	[{"id":1,"first_name":"Barack","last_name":"Obama","status":"ex-president"},{"id":2,"first_name":"Don","last_name":"Trump","status":"president"}]
```
#### `line_type` = 'json'
```
{"time":"2020-03-21 17:13:58","level":"[info]","message":"Test info message","context":[]}
{"time":"2020-03-21 17:13:58","level":"[error]","message":"Test error message","context":[]}
{"time":"2020-03-21 17:13:58","level":"[debug]","message":"Test debug message","context":[{"id":1,"first_name":"Barack","last_name":"Obama","status":"ex-president"},{"id":2,"first_name":"Don","last_name":"Trump","status":"president"}]}
```

### Options

| Variable | Possible values | Default value | Description |
| ------ | ------- | ------- | ------ |
| `path` | Any directory path | `__DIR__ . DIRECTORY_SEPARATOR` | The root directory of your log files |
| `channel` | Any `string` | `common` | The log channel name |
| `log_files_format` | `txt`, `log`, ... | `txt` | The log file extension |
| `line_type` | `json`, `string` | `json` | The log record format |
| `date_format` | `Y-m-d H:i:s`, `D, d M Y H:i:s`, ...  | `Y-m-d H:i:s` | The log record date format |




