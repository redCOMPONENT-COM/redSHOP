<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class SupplierManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class SupplierPage extends CoreJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = "Supplier Management";

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=suppliers';

	/**
	 * @var array
	 */
	public static $fieldName = array('id' => "jform_name");

	/**
	 * @var array
	 */
	public static $fieldEmail = array('id' => "jform_email");

	/**
	 * @var string
	 */
	public static $fieldMissing = "Field required: Name";

	/**
	 * @var string
	 */
	public static $fieldEmailInvalid = "Invalid field:  Supplier Email ";
}
