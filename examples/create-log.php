<?php

// Created: 2020/03/19 16:38:52
// Last modified: 2020/03/21 19:16:52

// Developer: Alexander Ivashchenko
// Site: http://ivashchenko.in.ua/
// Skype: alex_iblisov
// Email: lex.ivashchenko@gmail.com
// https://www.facebook.com/lex.ivashchenko
// https://ua.linkedin.com/pub/alexander-ivashchenko/61/3a5/440



define('ABS', dirname(__DIR__, 1) . DIRECTORY_SEPARATOR);

include ABS . 'inc' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


$Logger = new Ali\Logger([
	'path' => ABS . 'logs' . DIRECTORY_SEPARATOR,
	'channel' => 'examples',
	'log_files_format' => 'log',
	'line_type' => 'string',
	'date_format' => 'Y+m+d',
]);

$Logger->info('Test info message');

$Logger->error('Test error message');

$Logger->debug('Test debug message', [
	[
		'id' => 1,
		'first_name' => 'Barack',
		'last_name' => 'Obama',
		'status' => 'ex-president',
	],
	[
		'id' => 2,
		'first_name' => 'Don',
		'last_name' => 'Trump',
		'status' => 'president',
	]
]);

