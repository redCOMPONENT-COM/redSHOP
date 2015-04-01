<?php

namespace Joomla\Tests;


require_once __DIR__.'/../../vendor/autoload.php';

use Codeception\Events;
use Codeception\Event\SuiteEvent;
use Codeception\Event\TestEvent;

class TestsPerformance extends \Codeception\Platform\Extension
{
	public static $testTimes = array();

	public function _initialize()
	{
		$this->options['silent'] = false; // turn on printing for this extension
		//$this->_reconfigure(['settings' => ['silent' => true]]); // turn off printing for everything else
	}

	// we are listening for events
	static $events = array(
		Events::TEST_END     => 'after',
		Events::SUITE_AFTER  => 'afterSuite'
	);

	// we are printing test status and time taken
	public function after(TestEvent $e)
	{
		$test = new \stdClass;
		$testName = $e->getTest()->toString();
		preg_match('#\((.*?)\)#', $testName, $fileName);
		$test->name = $fileName[1];


		// stack overflow: http://stackoverflow.com/questions/16825240/how-to-convert-microtime-to-hhmmssuu
		$seconds_input = $e->getTime();
		$seconds = (int)($milliseconds = (int)($seconds_input * 1000)) / 1000;
		$time    = ($seconds % 60);

		$test->time = $time;


		self::$testTimes[] = $test;
	}
	public function afterSuite(SuiteEvent $e)
	{
		$this->writeln("");
		$this->writeln("Tests Performance times");
		$this->writeln("------------------------------------------");

		foreach (self::$testTimes as $test)
		{
			$this->writeln(str_pad($test->name, 35) . ' ' . $test->time . 's');
		}
	}
}
