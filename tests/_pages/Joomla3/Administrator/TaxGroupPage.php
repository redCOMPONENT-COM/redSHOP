<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TaxGroupPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class TaxGroupPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = "VAT / Tax Group Management";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameEditPage = 'VAT / Tax Group Management: [ Edit ]';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=tax_groups';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pathName = "//div[@id='name-1']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pathStatus = "//tr/td[5]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageErrorFieldMissing = "Field required: VAT / Tax Group Name";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $buttonSaveTax = "//button[@onclick=\"Joomla.submitbutton('tax_group.apply');\"]";
}