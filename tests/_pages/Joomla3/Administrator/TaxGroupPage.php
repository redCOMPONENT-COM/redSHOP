<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TaxGroupPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class TaxGroupPage extends AdminJ3Page
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
	public static $fieldName = ['id' => 'jform_name'];

	/**
	 * @var string
	 */
	public static $pathCheckAll = "//input[@onclick='Joomla.checkAll(this)']";

	/**
	 * @var string
	 */
	public static $pathName = "//table[contains(@class, 'adminlist')]/tbody/tr[1]/td[4]/a";

	/**
	 * @var string
	 */
	public static $pathStatus = "//table[contains(@class, 'adminlist')]/tbody/tr[1]/td[5]/a";

	/**
	 * @var string
	 */
	public static $messageErrorFieldMissing = "Field required: VAT / Tax Group Name";

	/**
	 * @var string
	 */
	public static $selectorError = ".alert-danger";
}