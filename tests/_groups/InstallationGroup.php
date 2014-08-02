<?php


use \Codeception\Event\TestEvent;

/**
 * Group class is Codeception Extension which is allowed to handle to all internal events.
 * This class itself can be used to listen events for test execution of one particular group.
 * It may be especially useful to create fixtures data, prepare server, etc.
 *
 * INSTALLATION:
 *
 * To use this group extension, include it to "extensions" option of global Codeception config.
 */
class InstallationGroup extends \Codeception\Platform\Group
{
	public static $group = 'installation';

	public function _before(TestEvent $e)
	{
		// Remove Joomla-cms old configuration.php file before do a clean joomla installation
		$joomlaConfigurationFile = 'tests/system/joomla-cms/configuration.php';

		if (file_exists($joomlaConfigurationFile))
		{
			//chmod($joomlaConfigurationFile, 0777);
			unlink($joomlaConfigurationFile);
		}
	}
}
