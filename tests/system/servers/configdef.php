<?php
/*
 * This is an example of a config class. To run tests,
 * copy this file to a file called "configdef.php" (in tests/system/server)
 * and set the variables according to the values in your test system.
 *
 * You can create more than one configuration file.
 *
 * You can select the desired config class at runtime with the following command:
 * phpunit --bootstrap servers\configtest.php tests\control_panel_menu.php
 * In this way, you can create multiple configurations and run them separately
 * using a batch file or shell script.
 */

class SeleniumConfig
{
	// $folder is used by the Extension installer test to "install from folder". Should be the path to the home of the repository where the redshom.xml manifest is localted
	var $folder = '/home/travis/build/redCOMPONENT-COM/redSHOP/'; // typical linux example

	// $host is normally 'http://localhost'
	var $host = 'http://localhost/';

	// $server would be the path to your localhost
	var $server = '/var/www'; // typical linux example

	// $path is the rest of the URL to the Joomla! home page
	// Example: Your full URL to Joomla! is http://localhost/joomla_16/index.php
	// then $path would be '/joomla_16/'
	var $path = '/joomla-cms/';

	// $baseURI set in contructor to the full path
	var $baseURI;

	// Set to true if you want to capture screenshots on failure (only for Firefox)
	var $captureScreenshotOnFailure = false;
	var $screenShotPath = 'tests/system/screenshots';

	// set the database host, database username, database pasword, and database name
	var $db_host = 'localhost';
	var $db_user = 'root';
	var $db_pass = '';
	var $db_name = 'redshop1';
	var $db_type = 'MySQLi';
	var $db_prefix = 'red_';

	// optional setting to install sample data
	// If not set or true, sample data is installed. Set to false to not install sample data
	// Note: This must be true for the standard tests to work!
	var $sample_data = true;

	// optional setting to select sample data
	// Set to partial text of sample data label on installation screen
	// Note: This must be 'Learn Joomla' for the standard tests to work!
	var $sample_data_file = 'Default English';

	// set the site name (keep to less than 14 characters)
	var $site_name = 'Joomla';

	// set the admin login, admin password, and admin email address
	var $username = 'tester';
	var $password = 'tester';
	var $admin_email = 'javier@redcomponent.com';
	// Language is set to English as we are testing on some English strings
	var $language = 'English (United Kingdom)';

	// this setting will use the default browser for your system
	var $browser = '*chrome'; // for firefox (weird name!)
// 	var $browser = '*googlechrome';
// 	var $browser = '*iexplore';

	// optional setting to turn on Cache: values are off, on-basic, on-full
	// change this value to set the caching in the doInstall.php test
	var $cache = 'off';

	// optional setting to set administive template to hathor: set to 'hathor' to make hathor the default
	// var $adminTemplate = 'hathor';

	// optional setting to set error reporting level
	var $errorReporting = 'maximum';

	// optional setting to set the initial window dimensions (Webdriver only)
	var $windowSize = array(1280, 1024);

	// optional setting to disable installing the site, required in environments that use FTP mode
	var $doInstall = 'true';

	public function __construct() {
		$this->baseURI = $this->folder . $this->path;
	}

}
