<?php

// Here you can initialize variables that will be available to your tests

define('REDSHOP_CONFIG_PATH', __DIR__ . '/../joomla-cms3/administrator/components/com_redshop/config/config.php');

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__ . DIRECTORY_SEPARATOR . '_pages');
\Codeception\Util\Autoload::registerSuffix('Steps', __DIR__ . DIRECTORY_SEPARATOR . 'administrator/_steps/Joomla3Steps');
