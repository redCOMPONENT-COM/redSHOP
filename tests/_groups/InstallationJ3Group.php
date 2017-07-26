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

class InstallationJ3Group extends \Codeception\Platform\Group
{
    public static $group = 'installationJ3';

    /**
     * Function to delete the Configuration File before Doing the Installation
     *
     * @param   TestEvent  $e  Name of the Test Event
     *
     * @return void
     */
    public function _before(TestEvent $e)
    {
        // Remove Joomla 3 CMS old configuration.php file before do a clean joomla installation
        $joomla3ConfigurationFile = 'tests/system/joomla-cms3/configuration.php';

        if (file_exists($joomla3ConfigurationFile))
        {
            chmod($joomla3ConfigurationFile, 0777);
            unlink($joomla3ConfigurationFile);
        }
    }
}