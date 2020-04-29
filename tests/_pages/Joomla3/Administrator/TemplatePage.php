<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class TemplatePage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class TemplatePage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=templates';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = "Template Management";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $nameEditPage = 'Template Management: [ Edit ]';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldSection = "#s2id_jform_section";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldSectionSearch = "#s2id_autogen1_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldSectionID = "#select2-results-1";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $statePath = "//tr/td[6]/a";
}
