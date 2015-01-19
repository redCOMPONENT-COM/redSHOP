<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class QuestionManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class QuestionManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=question';

	public static $userPhone = "//input[@id='telephone']";

	public static $productNameDropDown = "//div[@id='s2id_product_id']/a";

	public static $productNameSearchField = "//input[@id='s2id_autogen1_search']";

	public static $toggleQuestionDescriptionEditor = "//div[@class='editor']//textarea[@id='question']/../div//div//a[@title='Toggle editor']";

	public static $question = "//textarea[@id='question']";

	public static $questionSuccessMessage = 'Question Detail Saved';

	public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $questionStatePath = "//div[@id='editcell']//table[2]//tbody/tr[1]/td[9]/a";

	/**
	 * Function to get the path for Product Name
	 *
	 * @param   String  $name  Name of the Product for which question is to be posted
	 *
	 * @return string
	 */
	public function productName($name)
	{
		$path = "//div[@id='select2-drop']//ul//li//div//span[contains(text(),'" . $name . "')]";

		return $path;
	}
}
