<?php
/*
 * If you are testing in Joomla Vagrant box, rename with your script this file into configdef.php
 */

class SeleniumConfig
{
	// $folder is the path to the apache root folder
	var $folder = '/var/www/selenium/redSHOP/'; // typical linux example

	// $host is normally 'http://localhost'
	var $host = 'http://joomla.dev';

	// $server would be the path to your localhost
	var $server = '/var/www/';

	// $path is the rest of the URL to the Joomla! home page
	// Example: Your full URL to Joomla! is http://localhost/joomla_16/index.php
	// then $path would be '/joomla_16/'
	var $path = '/selenium/redSHOP/tests/system/joomla-cms/';

	// $baseURI set in contructor to the full path
	var $baseURI;

	// Set to true if you want to capture screenshots on failure (only for Firefox)
	var $captureScreenshotOnFailure = false;
	var $screenShotPath = 'tests/system/screenshots';

	// set the database host, database username, database pasword, and database name
	var $db_host = 'localhost';
	var $db_user = 'root';
	var $db_pass = 'root';
	var $db_name = 'selenium_redshop1';
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
	var $admin_email = 'javier@redcoponent.com';
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
