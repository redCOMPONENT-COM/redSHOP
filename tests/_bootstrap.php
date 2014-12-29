<?php
// This is global bootstrap for autoloading 

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla2/Administrator');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla2/System');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla3/Administrator');
\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages/Joomla3/System');
\Codeception\Util\Autoload::registerSuffix('Group', __DIR__.DIRECTORY_SEPARATOR.'_groups');