<?php
/**
 * @package     redSHOP
 * @subpackage  Steps redSHOPProductSteps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */


namespace Frontend\Module;
use CheckoutOnFrontEnd;
use FrontEndProductManagerJoomla3Page;

/**
 * Class redSHOPProduct
 * @package Frontend\Module
 * @since 2.1.3
 */
class redSHOPProductSteps extends CheckoutOnFrontEnd
{
	/**
	 * @param $moduleName
	 * @param $productName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function checkModuleRedSHOPProduct($moduleName, $productName)
	{
		$I = $this;
		$I->amOnPage(FrontEndProductManagerJoomla3Page::$URL);
		$I->waitForText($moduleName, 30);
		$text = $I->grabTextFrom(FrontEndProductManagerJoomla3Page::$nameRedSHOPProduct);
		$I->assertEquals($text, $productName);
	}
}