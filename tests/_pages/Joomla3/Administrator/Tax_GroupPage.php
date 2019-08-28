<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TaxGroupPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class Tax_GroupPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = "VAT / Tax Group Management";

	/**
	 * @var string
	 */
	public static $nameEditPage = 'VAT / Tax Group Management: [ Edit ]';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=tax_groups';

	/**
	 * @var array
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var string
	 */
	public static $pathName = "//div[@id='name-1']";

	/**
	 * @var string
	 */
	public static $pathStatus = "//tr/td[5]/a";

	/**
	 * @var string
	 */
	public static $messageErrorFieldMissing = "Field required: VAT / Tax Group Name";

	/**
	 * @var array
	 */
	public static $buttonSaveTax = "//button[@onclick=\"Joomla.submitbutton('tax_group.apply');\"]";

}