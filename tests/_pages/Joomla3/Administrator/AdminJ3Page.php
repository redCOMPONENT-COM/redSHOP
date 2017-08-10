<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Abstract Class Core J3 Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
abstract class AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = "";

	/**
	 * @var string
	 */
	public static $url;

	/**
	 * @var string
	 */
	public static $messageHead = "Message";

	/**
	 * @var string
	 */
	public static $messageItemSaveSuccess = "Item saved.";

	/**
	 * @var string
	 */
	public static $messageItemDeleteSuccess = "1 item successfully deleted";

	/**
	 * @var string
	 */
	public static $resultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

	/**
	 * @var array
	 */
	public static $searchField = ['id' => 'filter_search'];

	/**
	 * @var string
	 */
	public static $namePath = "//div[@class='table-responsive']/table/tbody/tr/td[2]";

	/**
	 * @var string
	 */
	public static $statePath = "//div[@class='table-responsive']/table/tbody/tr/td[6]/a";

	/**
	 * @var array
	 */
	public static $headPage = ['xpath' => "//h1"];

	/**
	 * @var string
	 */
	public static $selectorSuccess = ".alert-success";

	/**
	 * @var string
	 */
	public static $selectorMissing = '.alert-danger';

	/**
	 * @var string
	 */
	public static $selectorPageTitle = '.page-title';

	/**
	 * @var string
	 */
	public static $buttonSaveClose = "Save & Close";

	/**
	 * @var string
	 */
	public static $buttonSave = "Save";

	/**
	 * @var string
	 */
	public static $buttonDelete = "Delete";

	/**
	 * @var string
	 */
	public static $buttonNew = "New";

	/**
	 * @var string
	 */
	public static $buttonCancel = "Cancel";

	/**
	 * @var string
	 */
	public static $buttonPublish = "Publish";

	/**
	 * @var string
	 */
	public static $buttonUnpublish = "Unpublish";

	/**
	 * @var string
	 */
	public static $buttonEdit = "Edit";

	/**
	 * @var string
	 */
	public static $buttonReset = "Reset";

	/**
	 * @var string
	 */
	public static $buttonCheckIn = "Check-in";

	/**
	 * @var string
	 */
	public static $buttonClose = "Close";

	/**
	 * Function get value
	 *
	 * @param   string  $value  Value
	 *
	 * @return  array
	 */
	public static function returnChoice($value)
	{
		$path = ['xpath' => "//span[contains(text(), '" . $value . "')]"];

		return $path;
	}
}