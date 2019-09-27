<?php
/**
 * @package     redSHOP
 * @subpackage  Page ModuleCheckout
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CheckoutModulePage'
 * @since 2.1.3
 */
class CheckoutModulePage extends FrontEndProductManagerJoomla3Page
{
	//redSHOP - redMASSCART module

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $redMASSCART = 'redSHOP - redMASSCART';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $selectorRedMasscart = "//h3[contains(text(),'redSHOP - redMASSCART')]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productNumberTextarea = "#numbercart";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productQuantity = "#mod_quantity";

	/**
	 * @param $title
	 * @return string
	 * @since 2.1.3
	 */
	public function titleButton($title)
	{
		return $xPath = "//input[@value='".$title."']";
	}

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $productNameOnCart = "//div[@class='product_name']/a";
}