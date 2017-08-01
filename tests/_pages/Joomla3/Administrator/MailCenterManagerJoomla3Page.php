<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class MailCenterManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class MailCenterManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=mail';

	public static $mailName = "//input[@id='mail_name']";

	public static $mailSubject = "//input[@id='mail_subject']";

	public static $mailBcc = "//input[@id='mail_bcc']";

	public static $mailSectionDropDown = "//div[@id='mail_section_chzn']/a";

	public static $mailSuccessMessage = 'Mail template saved';

	public static $firstResultRow = "//div[@id='editcell']//table//tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $mailTemplateStatePath = "//table[contains(@class,'adminlist')]/tbody/tr/td[6]/a";

	public static $searchField = ['id' => 'filter'];

	/**
	 * Function to get the path for Section
	 *
	 * @param   String  $section  Section for the mail Template
	 *
	 * @return string
	 */
	public function mailSection($section)
	{
		$path = "//div[@id='mail_section_chzn']/div/ul/li[contains(text(), '" . $section . "')]";

		return $path;
	}
}
