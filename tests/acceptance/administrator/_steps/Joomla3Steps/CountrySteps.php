<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;

use Step\AbstractStep;

/**
 * Class CountryManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class CountrySteps extends AbstractStep
{
	/**
	 * Function to Delete a Country
	 *
	 * @param   String  $countryName  Name of the Country
	 *
	 * @return void
	 */
	public function deleteCountry($countryName)
	{
		$client = $this;
		$client->amOnPage(\CountryPage::$url);
		$client->checkForPhpNoticesOrWarnings();
		$client->searchCountry($countryName);
		$client->checkAllResults();
		$client->click(\CountryPage::$buttonDelete);
		$client->acceptPopup();
		$client->waitForText(\CountryPage::$messageDeleteSuccess, 60, \CountryPage::$selectorSuccess);
		$client->see(\CountryPage::$messageDeleteSuccess, \CountryPage::$selectorSuccess);
		$client->fillField(\CountryPage::$searchField, $countryName);
		$client->pressKey(\CountryPage::$searchField, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$client->dontSee($countryName, \CountryPage::$resultRow);
	}
}
