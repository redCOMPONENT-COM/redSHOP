<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountProductPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class Field_GroupPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = 'administrator/index.php?option=com_redshop&view=field_groups';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $applyFieldGroup = "//button[@onclick=\"Joomla.submitbutton('field_group.apply');)\"]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'Field Group';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $missingName = 'Field required: Name';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $descriptionField = ['name' => 'jform[description]'];
}