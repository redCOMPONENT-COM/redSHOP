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

/**
 * Class InstallationGroup
 *
 * @since  1.4
 *
 */
class InstallationJ2Group extends \Codeception\Platform\Group
{
	public static $group = 'installationJ2';

	/**
	 * Function to delete the Configuration File before Doing the Installation
	 *
	 * @param   TestEvent  $e  Name of the Test Event
	 *
	 * @return void
	 */
	public function _before(TestEvent $e)
	{
		// Remove Joomla2 CMS old configuration.php file before do a clean joomla installation
		$joomla2ConfigurationFile = 'tests/system/joomla-cms2/configuration.php';

		if (file_exists($joomla2ConfigurationFile))
		{
			chmod($joomla2ConfigurationFile, 0777);
			unlink($joomla2ConfigurationFile);
		}
	}
}
