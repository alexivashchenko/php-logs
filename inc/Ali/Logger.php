<?php

// Created: 2020/03/19 17:02:52
// Last modified: 2020/03/21 19:17:18

// Developer: Alexander Ivashchenko
// Site: http://ivashchenko.in.ua/
// Skype: alex_iblisov
// Email: lex.ivashchenko@gmail.com
// https://www.facebook.com/lex.ivashchenko
// https://ua.linkedin.com/pub/alexander-ivashchenko/61/3a5/440

namespace Ali;

use DateTime;

use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;


class Logger extends AbstractLogger
{

	public

		/**
		 * Log line format
		 * Possible values: json, string
		 * @var string
		 */
		$line_type = 'json',


		/**
		 * Timestamp of the current log line
		 * Helps create dummy logs
		 * @var int
		 */
		$time_stamp = 0,


		/**
		 * Log files format
		 * @var string
		 */
		$date_format = 'Y-m-d H:i:s',


		/**
		 * Log files format
		 * @var string
		 */
		$log_files_format = 'txt',


		/**
		 * Channel name
		 * @var string
		 */
		$channel = 'common',


		/**
		 * Path to the root log folder
		 * @var string
		 */
		$path = __DIR__ . DIRECTORY_SEPARATOR;




	function __construct($data = [])
	{
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				if (isset($this->{$key})) {
					$this->{$key} = $value;
				}
			}
		}

		$this->time_stamp = ( $this->time_stamp === 0 ) ? strtotime('now') : (int) $this->time_stamp;
	}


	public function lineData(int $time_stamp, string $level = LogLevel::ERROR, string $message, array $context = [])
	{
		return [
			'time' => date($this->date_format, $time_stamp),
			'level' =>  '[' . str_replace(['[', ']'], '', $level) . ']',
			'message' => $message,
			'context' => $context,
		];
	}



	private function formatLine( int $time_stamp, string $level = LogLevel::ERROR, string $message, array $context = [])
	{

		$data = $this->lineData($time_stamp, $level, $message, $context);

		if ($this->line_type === 'json') {

			return json_encode($data) . PHP_EOL;
		} elseif ($this->line_type === 'string') {

			$data['level'] = $data['level'];

			$data['context'] = json_encode($data['context']);

			return implode("\t", array_values($data)) . PHP_EOL;
		}

		throw new \Exception('Wrong log line format. Acceptable values: [json, string].');
	}



	public function logFileDirPath()
	{
		return $this->path . $this->channel . DIRECTORY_SEPARATOR . date('Y', $this->time_stamp) . DIRECTORY_SEPARATOR . date('m', $this->time_stamp) . DIRECTORY_SEPARATOR;
	}

	public function logFilePath()
	{
		return $this->logFileDirPath() . date('d', $this->time_stamp) . '.' . $this->log_files_format;
	}


	public function log($level, $message, array $context = [])
	{

		$folder = $this->logFileDirPath();

		if (!file_exists($folder)) {

			if (!mkdir($folder, 0777, true)) {
				throw new \Exception('Failed to create log path: ' . $folder);
			}
		}

		$message = (is_array($message) or is_object($message)) ? json_encode($message) : (string) $message;

		if (file_put_contents($this->logFilePath(), $this->formatLine($this->time_stamp, $level, $message, $context), FILE_APPEND) !== false) {
			return true;
		}

		throw new \Exception('Error saving log file data.');
	}



	public function readLine($line)
	{

		$data = [];

		if ($this->line_type === 'json') {

			$data = json_decode($line);
		} elseif ($this->line_type === 'string') {

			$data = preg_split("/[\t]/", $line);

			$data = array_map(function ($element) {
				if (is_array(json_decode($element, true))) {
					$element = json_decode($element, true);
				}
				return $element;
			}, $data);

			$date = DateTime::createFromFormat($this->date_format, $data[0]);
			$data = $this->lineData($date->format('U'), $data[1], $data[2], $data[3]);

		}

		return $data;
	}



	public function read(string $date)
	{

		$date = DateTime::createFromFormat('Y-m-d', $date);
		$this->time_stamp = $date->format('U');

		$log = [];

		$handle = fopen($this->logFilePath(), 'r');

		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$log[] = $this->readLine($line);
			}

			fclose($handle);
		} else {
			throw new \Exception('Error opening log file.');
		}

		return $log;
	}
}
