<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ModuleManagerJoomlaPage
 * @since 2.1.3
 */
class ModuleManagerJoomlaPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $URL = '/administrator/index.php?option=com_modules';
	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $currentSelect = 'Euro';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $input = '//input[@value="Type or select some options"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $curentConfiguration = '//div[@class="pull-left"]';
}