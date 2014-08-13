<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ExtensionManagerPage
 *
 * @since  1.4
 */
class ExtensionManagerPage
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_installer';

	public static $extensionDirectoryPath = "#install_directory";

	public static $installButton = "//input[contains(@onclick,'Joomla.submitbutton3()')]";

	public static $installSuccessMessage = "//li[contains(text(),'Installing component was successful')]";

	public static $installDemoContent = "//input[@onclick=\"submitWizard('content');\" and @value='install Demo Content']";

	public static $demoDataInstallSuccessMessage = "//li[contains(text(),'Sample Data Installed Successfully')]";

}
