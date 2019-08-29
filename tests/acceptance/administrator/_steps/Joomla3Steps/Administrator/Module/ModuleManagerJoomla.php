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

class ModuleManagerJoomla extends AdminManagerJoomla3Steps
{
	public function configurationCurrent($moduleName, $position)
	{
		$I = $this;
		$I->amOnPage(ModuleManagerJoomlaPage::$URL);
		$I->pauseExecution();
		$I->searchForItem($moduleName);
		$ModuleManagerPage = new ModuleManagerJoomlaPage;
		$I->waitForElementVisible(ModuleManagerJoomlaPage::$searchResultRow, 30);
		$I->waitForText($moduleName, 30, ModuleManagerJoomlaPage::$searchResultRow);
		$I->click($moduleName);
		$I->click(ModuleManagerJoomlaPage::$showButton);
		$I->click(ModuleManagerJoomlaPage::$position);
		$I->waitForElement($ModuleManagerPage->returnChoice($position), 30);
		if ($position == 'position-7')
		{
			$I->pressKey(\ConfigurationPage::$countrySearchPrice, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		}
		else{
			$I->click($ModuleManagerPage->returnChoice($position));
		}


	}

}