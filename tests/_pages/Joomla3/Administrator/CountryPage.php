<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CountryManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class CountryPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'Country Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=countries';
}
