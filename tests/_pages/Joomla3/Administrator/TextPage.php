<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TextLibraryManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class TextPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'Text Management';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=texts';

    /**
     * @var string
     */
	public static $fieldName = "#jform_name";

    /**
     * @var string
     */
	public static $fieldDescription = "#jform_desc";
}
