<?php

define('_JEXEC', 1);

define('JPATH_BASE', dirname(__DIR__) . '/tests/joomla-cms3/administrator');

include_once JPATH_BASE . '/includes/defines.php';
include_once JPATH_LIBRARIES . '/import.legacy.php';
include_once JPATH_LIBRARIES . '/cms.php';

// The configuration will only be available after the installation tests are executed
if (file_exists(JPATH_CONFIGURATION . '/configuration.php'))
{
	include_once JPATH_CONFIGURATION . '/configuration.php';
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
