<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TemplatePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */

class TemplatePage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=templates';

	/**
	 * @var string
	 */
	public static $namePage = "Template Management";

	/**
	 * @var string
	 */
	public static $nameEditPage = 'Template Management: [ Edit ]';

	/**
	 * @var array
	 */
	public static $fieldName = ['id' => 'jform_template_name'];

	/**
	 * @var array
	 */
	public static $fieldSection = ['id' => 'jform_template_section'];

	public static $filter = ['id' => 'filter'];

	public static $templateSectionInput = "//div[@id='template_section_chzn']/div/div/input";

	public static $templateSuccessMessage = 'Template Saved';

	public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $templateStatePath = "//div[@id='editcell']//table[1]//tbody/tr[1]/td[5]/a";

	/**
	 * Function to get the path for Section
	 *
	 * @param   String  $section  Section for the Template
	 *
	 * @return string
	 */
	public function templateSection($section)
	{
		$path = "//div[@id='template_section_chzn']/div/ul/li[text() = '" . $section . "']";

		return $path;
	}
}
