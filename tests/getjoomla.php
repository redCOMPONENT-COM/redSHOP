<?php
/**
 * @package     Joomla
 * @subpackage  Tests
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// Maximise error reporting.
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';


use Joomla\Application\AbstractCliApplication;
use Joomla\Filesystem\Folder;

/**
 * Class GetJoomlaCli
 *
 * Simple Command Line Application to get the latest Joomla for running the tests
 */
class GetJoomlaCli extends AbstractCliApplication
{
	private function getFromGithub($repository, $branch, $toFolder)
	{
		$this->out('Cleaning Joomla site Folder...');

		if (is_dir($toFolder))
		{
			Folder::delete($toFolder);
		}

		Folder::create($toFolder);

		$this->out('Downloading Joomla...');
		$command = "git clone -b ${branch} --single-branch --depth 1 ${repository} ${toFolder}";

		exec($command, $output, $returnValue);

		return $returnValue;
	}

	protected function doExecute()
	{
		$repository = 'https://github.com/joomla/joomla-cms.git';
		$branch = 'staging';
		$testingsite = __DIR__ . '/system/joomla-cms3';


		if($this->getFromGithub($repository, $branch, $testingsite))
		{
			$this->out('Sadly we were not able to download Joomla 3.x');
		}
		else
		{
			$this->out('Joomla 3 Downloaded and ready for executing the tests.');
		}

		$repository = 'https://github.com/joomla/joomla-cms.git';
		$branch = '2.5.x';
		$testingsite = __DIR__ . '/system/joomla-cms2';


		if($this->getFromGithub($repository, $branch, $testingsite))
		{
			$this->out('Sadly we were not able to download Joomla 2.5.');
		}
		else
		{
			$this->out('Joomla 2.5 Downloaded and ready for executing the tests.');
		}
	}
}

define('JPATH_ROOT', realpath(dirname(__DIR__)));

$app = new GetJoomlaCli;
$app->execute();

