<?php
/**
 * @package     redshop1.travis
 * @subpackage  
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/*
 * This script sends the screen captured errors found by Codeception framework into Slack chat
 *
 * remind to modify the following two variables if you want to use it in other projects
 */
$slackChannel = 'C02L0SE5E'; // reports to #travis-errors slack channel
$slackToken= 'xoxp-2309442657-2309442659-2680880772-1a436d';

// Only run on the CLI SAPI
(php_sapi_name() == 'cli' ?: die('CLI only'));

// Script defines
define('REPO_BASE', dirname(__DIR__));

// Welcome message
fwrite(STDOUT, "\033[33;1mCheck if there is Codeception snapshots and sending them to Slack.\033[0m\n");

$codeceptionOutputFolder = REPO_BASE . '/tests/_output';

if (!file_exists($codeceptionOutputFolder) || !(new \FilesystemIterator($codeceptionOutputFolder))->valid())
{
    fwrite(STDOUT, "\033[32;1mThere are no errors found by Codeception\033[0m\n");
    exit(0);
}

$error = false;
$errorSnapshot = '';

// Loop throught Codeception snapshots
if ($handler = opendir($codeceptionOutputFolder)) {
    while (false !== ($errorSnapshot = readdir($handler))) {
        if ('.' === $errorSnapshot) continue;
        if ('..' === $errorSnapshot) continue;

        // Sends error snapshot to Slack channel
        $command = 'curl -F file=@' . $codeceptionOutputFolder . '/' . $errorSnapshot . ' -F '
            . 'channels='. $slackChannel  . ' -F '
            . 'title=Codeception_error -F '
            . 'initial_comment="error found by travis in redSHOP1 on build:"' . getenv('TRAVIS_BUILD_NUMBER') . ' -F'
            . 'token=' . $slackToken . ' '
            . 'https://slack.com/api/files.upload';

        $response = json_decode(shell_exec($command));

        if($response->ok)
        {
            fwrite(STDOUT, "\033[31;1mAn error image was sent to slack\033[0m\n");
        }
        else
        {
            fwrite(STDOUT, "\033[32;1mSlack could not be reached\033[0m\n");
        }

        $error = true;
    }
    closedir($handler);
}

// Make travis fail warning that there are Errors to fix
exit(1);

