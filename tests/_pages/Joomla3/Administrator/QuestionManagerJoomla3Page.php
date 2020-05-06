<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class QuestionManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class QuestionManagerJoomla3Page extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=questions';

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $namePage = "Question Management";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $userPhone = "#jform_telephone";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $userAddress = "#jform_address";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $productNameDropDown = "#s2id_jform_product_id";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $productNameSearchField = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $toggleQuestionDescriptionEditor = "//div[@id='mceu_50-body']";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $question = "#jform_question";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $buttonToggle = "Toggle editor";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $questionSuccessMessage = 'Question Detail Saved';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $questionStatePath = "//div[@id='editcell']//table//tbody/tr[1]/td[9]/a";

	/**
	 * Function to get the path for Product Name
	 *
	 * @param   String  $name  Name of the Product for which question is to be posted
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function productName($name)
	{
		$path = "//div[@id='select2-drop']//ul//li//div//span[contains(text(),'" . $name . "')]";

		return $path;
	}

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $fieldNameQuestion = "#jform_your_name";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $fieldEmailQuestion = "#jform_your_email";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $fieldYourQuestion = "#jform_your_question";

	/**
	 * @var string
	 * @since 3.0.2
	 */
	public static $sendButton = "//input[@class='btn']";
}
