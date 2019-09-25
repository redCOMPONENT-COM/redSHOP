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
	 * @var string
	 * @since 2.1.3
	 */
	public static $productTabConfiguration = ['link' => "redSHOP - Product Tab Module"];

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageUnpublishSuccess = 'module unpublished.';

	//redSHOP - redMASSCART

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public static $redMassCartLink = ['link' => "redSHOP - redMASSCART"];

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $moduleClassSuffix = "#jform_params_moduleclass_sfx";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $titleButtonID = "#jform_params_cartbtntitle";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $titleInputBox = "#jform_params_title";

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function productQuantityBox($yesNo)
	{
		return $xPath = '//label[@for="jform_params_chk_quantity'.$yesNo.'"]';
	}
}