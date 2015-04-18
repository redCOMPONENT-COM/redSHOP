<?php
// This is global bootstrap for autoloading 
include_once __DIR__.DIRECTORY_SEPARATOR . '_extensions/testsperformance.php';

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla2/Administrator');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla2/Installation');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla2/System');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla3/Administrator');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla3/Installation');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla3/System');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla3/Frontend');
\Codeception\Util\Autoload::registerSuffix('Group', __DIR__.DIRECTORY_SEPARATOR.'_groups');

