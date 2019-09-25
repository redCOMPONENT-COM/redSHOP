<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class ModuleManagerJoomlaPage
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
	public static $currentSelectEuro = 'Euro';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $currentSelectKorean = '(South) Korean Won';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputCurrent = '(//input[ @type="text"])[2]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $currentConfiguration = ['link' => "Redshop Multi Currencies"];

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $saveCloseButton = '#toolbar-save';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageModuleSaved = 'Module saved';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $h2 = '//h2';

    /**
     * @var string
     * @since 2.1.3
     */
	public static $labelAdjustToCategory = 'Adjust To Category';

    /**
     * @var array
     * @since 2.1.3
     */
    public static $productTabConfiguration = ['link' => "redSHOP - Product Tab Module"];

    /**
     * @var string
     * @since 2.1.3
     */
    public static $messageUnpublishSuccess = 'module unpublished.';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $inputCategories = "//div[@id='jform_params_category_chzn']//input[@class='default']";

    /**
     * @var array
     * @since 2.1.3
     */
    public static $redShopProductConfiguration = ['link' => "redSHOP - Products"];
}