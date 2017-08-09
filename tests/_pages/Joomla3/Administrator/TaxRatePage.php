<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TaxRatePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class TaxRatePage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'VAT Rates';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=tax_rates';

	/**
	 * @var string
	 */
	public static $nameEditPage = 'VAT Rates: [ Edit ]';

	/**
	 * @var array
	 */
	public static $fieldName = ['id' => 'jform_name'];

	/**
	 * @var array
	 */
	public static $fieldValue = ['id' => 'jform_tax_rate'];

	/**
	 * @var array
	 */
	public static $fieldState = ['id' => 'rs_state_jformtax_state'];

	/**
	 * @var array
	 */
	public static $fieldCountry = ['id' => 'jform_tax_country'];

	/**
	 * @var array
	 */
	public static $fieldGroup = ['id' => 'jform_tax_group_id'];

	/**
	 * @var string
	 */
	public static $messageError = "Error";

	/**
	 * @var string
	 */
	public static $messageSuccess = "Message";

	/**
	 * @var string
	 */
	public static $selectorError = '.alert-danger';
}
