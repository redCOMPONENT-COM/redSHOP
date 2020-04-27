<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class ModuleManagerJoomlaPage
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
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
	public static $currentConfiguration = ['link' => "\Redshop Multi Currencies"];

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
	 * @var array
	 * @since 2.1.3
	 */
	public static $tabModule = ['link' => "Module"];

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $categoryOption = "#jform_params_category_chzn";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $categoryInput = "//input[@class='default']";

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

	/**
	 * @var array
	 * @since 2.1.3
	 */
	public static $redShopProductConfiguration = ['link' => "redSHOP - Products"];

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $labelShowProductPrice = 'Show product price';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $idLabelShowProductPrice = "#jform_params_show_price-lbl";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $labelDiscountPriceLayout = 'Display Discount Price Layout';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $specificProducts = "//div[@id='jform_params_specific_products_chzn']//input[@class='default']";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $specificProducts2 = '//div[@id="jform_params_specific_products_chzn"]/ul/li[2]/input';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productsDisplay = '#jform_params_count';

	/**
	 * @var string
	 * @since
	 */
	public static $labelModuleType = 'Module Type';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $showDiscountProductPrice = '#jform_params_show_discountpricelayout-lbl';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $optionYesDisplayDisscountPrice = '//fieldset[@id="jform_params_show_discountpricelayout"]/label[1]';

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
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function showProductImage($option)
	{
		return $path = "//label[@for='jform_params_image".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function showProductPrice($option)
	{
		return $path = "//label[@for='jform_params_show_price".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function showVAT($option)
	{
		return $path = "//label[@for='jform_params_show_vat".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function showShortDescription($option)
	{
		return $path = "//label[@for='jform_params_show_short_description".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function showReadMore($option)
	{
		return $path = "//label[@for='jform_params_show_readmore".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function showAddToCart($option)
	{
		return $path = "//label[@for='jform_params_show_addtocart".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 2.1.3
	 */
	public function displayDiscountPrice($option)
	{
		return $path = "//label[@for='jform_params_show_discountpricelayout".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 3.0.2
	 */
	public function displayStockroomStatus($option)
	{
		return $path = "//label[@for='jform_params_show_stockroom_status".$option."']";
	}

	/**
	 * @param $option
	 * @return string
	 * @since 3.0.2
	 */
	public function showWishlist($option)
	{
		return $path = "//label[@for='jform_params_show_wishlist".$option."']";
	}
}