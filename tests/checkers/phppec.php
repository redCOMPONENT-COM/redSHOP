<?php
/**
 * Command line script for executing detecting PHP Parse Errors.
 *
 * This CLI is used instead normal travis.yml execution to avoid error in travis build when
 * PHPMD exits with 2.
 *
 * @copyright  Copyright (C) 2008 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @example    : php .travis/phppec.php component/ libraries/
 */

// Only run on the CLI SAPI
(php_sapi_name() == 'cli' ?: die('CLI only'));

// Script defines
define('REPO_BASE', dirname(__DIR__));

// Welcome message
fwrite(STDOUT, "\033[32;1mInitializing PHP Parse Error checks.\033[0m\n");

$error   = 0;
$folders = array('component', 'libraries/redshop', 'modules', 'plugins');

foreach ($folders as $folder)
{
	$folderToCheck = REPO_BASE . '/../' . $folder;

	if (!file_exists($folderToCheck))
	{
		fwrite(STDOUT, "\033[31;1mFolder: " . $folderToCheck . " does not exist\033[0m\n");
		continue;
	}

	fwrite(STDOUT, "\033[32;1m- Checking errors at: " . $folder . "\033[0m\n");
	$parseErrors = shell_exec('find ' . $folderToCheck . ' -name "*.php" -exec php -l {} \; | grep "Parse error";');

	if ($parseErrors)
	{
		$error = 1;
		fwrite(STDOUT, "\033[31;1mParse error found:\033[0m\n");
		fwrite(STDOUT, $parseErrors);
	}
}

exit($error);
