<?php
// This is global bootstrap for autoloading 

\Codeception\Util\Autoload::registerSuffix('Page', __DIR__.DIRECTORY_SEPARATOR.'_pages');
\Codeception\Util\Autoload::registerSuffix('Group', __DIR__.DIRECTORY_SEPARATOR.'_groups');