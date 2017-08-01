<?php
/**
 * Command line script for executing PHP Debug Checker during a Travis build.
 *
 * This CLI is used instead normal travis.yml execution to avoid error in travis build when
 * PHPMD exits with 2.
 *
 * @copyright  Copyright (C) 2005 - 2014 redCOMPONENT.com, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @example    : php .travis/phpmd.php component/ libraries/
 */

// Only run on the CLI SAPI
(php_sapi_name() == 'cli' ?: die('CLI only'));

// Script defines
define('REPO_BASE', dirname(__DIR__));

// Welcome message
fwrite(STDOUT, "\033[32;1mInitializing PHP Debug Missed Debug Code Checker.\033[0m\n");

$arguments = count($argv);
$error     = 0;

$folders = array('component', 'libraries/redshop', 'modules', 'plugins');

foreach ($folders as $folder)
{
	$folderToCheck = REPO_BASE . '/../' . $folder;

	if (!file_exists($folderToCheck))
	{
		fwrite(STDOUT, "\033[32;1mFolder: " . $folderToCheck . " does not exist\033[0m\n");
		continue;
	}

	fwrite(STDOUT, "\033[32;1m- Checking missed debug code at: " . $folder . "\033[0m\n");
	$phpDebugCheck = shell_exec('grep -r --include "*.php" var_dump ' . $folderToCheck);
	$jsDebugCheck  = shell_exec('grep -r --include "*.js" console.log ' . $folderToCheck);

	if ($phpDebugCheck)
	{
		fwrite(STDOUT, "\033[31;1mWARNING: Missed Debug code detected: var_dump was found\033[0m\n");
		fwrite(STDOUT, $phpDebugCheck);
		$error = 1;
	}

	if ($jsDebugCheck)
	{
		fwrite(STDOUT, "\033[31;1mWARNING: Missed Debug code detected: console.log was found\033[0m\n");
		fwrite(STDOUT, $jsDebugCheck);
		$error = 0;
	}
}

exit($error);
