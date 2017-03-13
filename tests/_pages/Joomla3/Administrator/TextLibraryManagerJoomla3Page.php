<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TextLibraryManagerJoomla3Page
 *
 * @since  1.4
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 */
class TextLibraryManagerJoomla3Page
{
	// Include url of current page
	public static $URL = '/administrator/index.php?option=com_redshop&view=texts';

	public static $textTagName = "#jform_name";

	public static $textTagDescription = "#jform_description";

	public static $sectionDropDown = "//div[@id='section_chzn']/a";

	public static $textResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	public static $textLibraryStatePath = "//tbody/tr/td[6]/a";

	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	public static $firstResult = "//input[@id='cb0']";

	public static $textCreationSuccessMessage = "Text Library Detail Saved";

	/**
	 * Function to get the Path for Section for Text Library
	 *
	 * @param   String  $sectionType  Name of the Section
	 *
	 * @return string
	 */
	public function section($sectionType)
	{
		$path = "//div[@id='section_chzn']/div/ul/li[contains(text(), '" . $sectionType . "')]";

		return $path;
	}
}
