<?php


class TestLogger extends PHPUnit\Framework\TestCase
{

	public
		$Logger,
		$log_types = [
			'emergency',
			'alert',
			'critical',
			'error',
			'warning',
			'notice',
			'info',
			'debug',
		],
		$log_data = [],
		$dummy_context = [
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
		];



	public function setUp()
	{
		$this->Logger = new Ali\Logger([
			'path' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR,
			'channel' => 'tests',
			'log_files_format' => 'log',
			'line_type' => 'string',
		]);

		foreach ($this->log_types as $level) {

			$this->log_data[] = $this->constructDummyLogLine($level);
		}
	}



	public function constructDummyLogLine($level)
	{
		return $this->Logger->lineData($this->Logger->time_stamp, $level, 'Test ' . $level . ' message', $this->dummy_context);
	}



	public function testPsr3Compatible()
	{
		$this->assertInstanceOf('Psr\Log\LoggerInterface', $this->Logger);
	}



	public function testWriteLogs()
	{
		foreach ($this->log_types as $level) {

			$line_data = $this->constructDummyLogLine($level);

			$this->Logger->{str_replace(['[', ']'], '', $line_data['level'])}($line_data['message'], $line_data['context']);
		}

		$this->assertFileExists($this->Logger->logFilePath());
	}



	public function testReadLogs()
	{
		$log_data = $this->Logger->read(date('Y-m-d', $this->Logger->time_stamp));

		$this->assertEquals($this->log_data, $log_data);

		$this->clearTestLogsDir();
	}



	public function clearTestLogsDir()
	{
		$dir = $this->Logger->path . $this->Logger->channel;
		$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($files as $file) {
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($dir);
	}



}
