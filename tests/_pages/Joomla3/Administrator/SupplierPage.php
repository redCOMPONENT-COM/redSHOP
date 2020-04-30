<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class SupplierPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class SupplierPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'Supplier Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=suppliers';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldEmail = "#jform_email";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldMissing = 'Field required: Name';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldEmailInvalid = 'The email address you entered is invalid. Please enter another email address.';
}
