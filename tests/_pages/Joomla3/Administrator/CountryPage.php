<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CountryManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class CountryPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'Country Management';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=countries';

	/**
	 * @var array
	 */
	public static $fieldName = ['id' => 'jform_country_name'];

	/**
	 * @var array
	 */
	public static $fieldTwoCode = ['id' => 'jform_country_2_code'];

	/**
	 * @var array
	 */
	public static $fieldThreeCode = ['id' => 'jform_country_3_code'];

	/**
	 * @var array
	 */
	public static $fieldText = ['id' => 'jform_country_jtext'];
}
