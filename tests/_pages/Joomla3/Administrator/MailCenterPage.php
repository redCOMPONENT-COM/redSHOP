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
class MailCenterPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'Mail Management';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=mails';

	/**
	 * @var array
	 */
	public static $fieldName = ['id' => 'jform_mail_name'];

	/**
	 * @var array
	 */
	public static $fieldSubject = ['id' => 'jform_mail_subject'];

	/**
	 * @var array
	 */
	public static $fieldSection = ['id' => 'jform_mail_section'];

	/**
	 * @var array
	 */
	public static $fieldBcc = ['id' => 'jform_mail_bcc'];

	/**
	 * @var string
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 */
	public static $mailTemplateStatePath = "//div[@class='table-responsive']/table/tbody/tr/td[7]/a";
}
