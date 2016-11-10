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

	public static $productNameDropDown = "//*[@id='select2-chosen-1']";

	public static $productNameSearchField = "//*[@id='s2id_autogen1_search']";

	public static $toggleQuestionDescriptionEditor = "//div[2]/fieldset/div/table/tbody/tr/td/div/div[2]/div/a";

	public static $newquestion = "//*[@id='question']";

	public static $questionSuccessMessage = 'Question Detail Saved';

	public static $Messagechangestate = 'Question Detail Unpublished Successfully';

	public static $Messagedeletestate = 'Question Detail Deleted Successfully';

	public static $firstResultRow = "//tbody/tr[1]/td[3]/a";

	public static $selectFirst = "//tbody/tr[1]/td[2]/div";

	public static $questionStatePath = "//div[@id='editcell']//table//tbody/tr[1]/td[9]/a";

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
