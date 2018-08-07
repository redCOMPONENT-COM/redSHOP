<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManufacturerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class ManufacturerPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'Manufacturer Management';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=manufacturers';

	/**
	 * @var array
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var array
	 */
	public static $fieldEmail = "#jform_email";

	/**
	 * @var string
	 */
	public static $fieldMissing = 'Field required: Name';

	/**
	 * @var string
	 */
	public static $fieldEmailInvalid = 'Invalid field: Email';
}
