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

	//redSHOP - ShopperGroup Product

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $shopperGroupProduct = ['link' => "redSHOP - ShopperGroup Product"];

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $numberOfProductDisplay = '#jform_params_count';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productImageHeight = '#jform_params_thumbwidth';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productImageWidth = '//label[@data-original-title=\'Show product image\']';

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function showProductImage($yesNo)
	{
		return $path = "//input[@id='jform_params_image".$yesNo."']";
	}

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function showProductPrice($yesNo)
	{
		return $path = "//input[@id='jform_params_show_price0']".$yesNo;
	}

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function showVAT($yesNo)
	{
		return $path = '#jform_params_show_vat'.$yesNo;
	}

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function showShortDescription($yesNo)
	{
		return $path = '#jform_params_show_short_description'.$yesNo;
	}

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function showReadMore($yesNo)
	{
		return $path = '#jform_params_show_readmore'.$yesNo;
	}

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function showAddToCart($yesNo)
	{
		return $path = '#jform_params_show_addtocart'.$yesNo;
	}

	/**
	 * @param $yesNo
	 * @return string
	 * @since 2.1.3
	 */
	public function displayDiscountPrice($yesNo)
	{
		return $path = '#jform_params_show_discountpricelayout'.$yesNo;
	}
}