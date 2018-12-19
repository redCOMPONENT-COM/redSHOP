<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountProductPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class Field_GroupPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $url = 'administrator/index.php?option=com_redshop&view=field_groups';

    /**
     * @var array
     */
	public static $applyFieldGroup = "//button[@onclick=\"Joomla.submitbutton('field_group.apply');)\"]";

	/**
	 * @var string
	 */
	public static $namePage = 'Field Group';

	/**
	 * @var string
	 */
	public static $missingName = 'Field required: Name';

	/**
	 * @var array
	 */
	public static $descriptionField = ['name' => 'jform[description]'];
}