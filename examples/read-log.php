<?php

// Created: 2020/03/19 16:38:52
// Last modified: 2020/03/21 18:55:47

// Developer: Alexander Ivashchenko
// Site: http://ivashchenko.in.ua/
// Skype: alex_iblisov
// Email: lex.ivashchenko@gmail.com
// https://www.facebook.com/lex.ivashchenko
// https://ua.linkedin.com/pub/alexander-ivashchenko/61/3a5/440



define('ABS', dirname(__DIR__, 1) . DIRECTORY_SEPARATOR);

include ABS . 'inc' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


if( !function_exists('pr') ){
	function pr(...$data){
		if( !empty($data) ){
			foreach( $data as $key => $value ){
				echo '<pre data-key="'.$key.'" style="padding:1rem;margin:0;background-color:#333;color:#fff;max-height:90vh;overflow:auto;border-bottom:1px solid #666;">';print_r($value);echo '</pre>';
			}
		}
	}
}



$Logger = new Ali\Logger([
	'path' => ABS . 'logs' . DIRECTORY_SEPARATOR,
	'channel' => 'examples',
	'log_files_format' => 'log',
	'line_type' => 'string',
]);

$log = $Logger->read(date('Y-m-d'));

pr($log);
