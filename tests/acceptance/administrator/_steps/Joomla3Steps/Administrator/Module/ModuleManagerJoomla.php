<?php
/**
 * @package     redSHOP
 * @subpackage  Steps
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Administrator\Module;
use AcceptanceTester\AdminManagerJoomla3Steps;
use ModuleManagerJoomlaPage;

/**
 * Class ModuleManagerJoomla
 * @package Administrator\Module
 * @since 2.1.3
 */
class ModuleManagerJoomla extends AdminManagerJoomla3Steps
{
	/**
	 * @param $moduleName
	 * @throws \Exception
	 * @since 2.1.3
	 */
	public function configurationCurrent($moduleName)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->searchForItem($moduleName);
		$I->waitForElement(ModuleManagerJoomlaPage::$curentConfiguration);
		$I->click(ModuleManagerJoomlaPage::$curentConfiguration);
		$I->fillField(ModuleManagerJoomlaPage::$input, ModuleManagerJoomlaPage::$currentSelect);
		$I->pressKey(ModuleManagerJoomlaPage::$input, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->setModulePosition($moduleName);
	}

}