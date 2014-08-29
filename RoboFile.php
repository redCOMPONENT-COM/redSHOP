<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 *
 */

require_once 'vendor/autoload.php';

class RoboFile extends \Robo\Tasks
{
	use \Codeception\Task\MergeReports;
	use \Codeception\Task\SplitTestsByGroups;

	public function parallelSplitTests()
	{
		$this->taskSplitTestsByGroups(5)
			->projectRoot('.')
			->testsFrom('tests/acceptance')
			->groupsTo('tests/_log/p')
			->run();
	}

	public function parallelRun()
	{

	}

	public function parallelMergeResults()
	{

	}
}
