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
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=questions';

    /**
     * @var string
     * @since 3.0.2
     */
    public static $namePage = "Question Management";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $userPhone = "//input[@id='telephone']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productNameDropDown = "//div[@id='s2id_product_id']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $productNameSearchField = "//input[@id='s2id_autogen1_search']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $toggleQuestionDescriptionEditor = "//div[@class='editor']//textarea[@id='question']/../div//div//a[@title='Toggle editor']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $question = "//textarea[@id='question']";

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
