<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class AcceptanceHelper extends \Codeception\Module
{
	public function _beforeSuite($settings = array()) {
		// Remove Joomla-cms old configuration.php file before do a clean joomla installation
		$joomlaConfigurationFile = 'tests/system/joomla-cms/configuration.php';

		if (file_exists($joomlaConfigurationFile))
		{
			chmod($joomlaConfigurationFile, 0777);
			unlink($joomlaConfigurationFile);
		}
	}

}