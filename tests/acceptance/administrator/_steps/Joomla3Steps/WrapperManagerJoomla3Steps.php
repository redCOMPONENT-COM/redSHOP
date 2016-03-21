<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace AcceptanceTester;
/**
 * Class WrapperManagerJoomla3Steps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    1.4
 */
class WrapperManagerJoomla3Steps extends AdminManagerJoomla3Steps
{
	/**
	 * Function to create a new Wrapper
	 *
	 * @param   string  $name      Name of the Wrapper
	 * @param   string  $price     Price of the Wrapper
	 * @param   string  $category  Category for the Wrapper
	 *
	 * @return void
	 */
	public function addWrapper($name = 'Sample Wrapper', $price = '100', $category = 'Events and Forms')
	{
		$I = $this;
		$I->amOnPage(\WrapperManagerJoomla3Page::$URL);
		$wrapperManagerPage = new \WrapperManagerJoomla3Page;
		$I->verifyNotices(false, $this->checkForNotices(), 'Wrapper Manager Page');
		$I->click('New');
		$I->waitForElement(\WrapperManagerJoomla3Page::$wrapperName,30);
		$I->fillField(\WrapperManagerJoomla3Page::$wrapperName, $name);
		$I->fillField(\WrapperManagerJoomla3Page::$wrapperPrice, $price);
		$I->click(\WrapperManagerJoomla3Page::$categoryDropDown);
		$I->click($wrapperManagerPage->category($category));
		$I->click('Save & Close');
		$I->waitForText(\WrapperManagerJoomla3Page::$wrapperCreateSuccessMessage,60,'.alert-success');
		$I->see(\WrapperManagerJoomla3Page::$wrapperCreateSuccessMessage, '.alert-success');
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($name, \WrapperManagerJoomla3Page::$firstResultRow);
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to Edit an existing Wrapper
	 *
	 * @param   string  $name     Name of the Wrapper which is to be edited
	 * @param   string  $newName  New name for the wrapper
	 *
	 * @return void
	 */
	public function editWrapper($name = 'Sample Wrapper', $newName = 'Updated Wrapper')
	{
		$I = $this;
		$I->amOnPage(\WrapperManagerJoomla3Page::$URL);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
		$I->click(['link' => 'ID']);
		$I->see($name, \WrapperManagerJoomla3Page::$firstResultRow);
		$I->click(\WrapperManagerJoomla3Page::$selectFirst);
		$I->click('Edit');
		$I->waitForElement(\WrapperManagerJoomla3Page::$wrapperName,30);
		$I->fillField(\WrapperManagerJoomla3Page::$wrapperName, $newName);
		$I->click('Save & Close');
		$I->waitForText(\WrapperManagerJoomla3Page::$wrapperCreateSuccessMessage,60,'.alert-success');
		$I->see(\WrapperManagerJoomla3Page::$wrapperCreateSuccessMessage, '.alert-success');
		$I->see($newName, \WrapperManagerJoomla3Page::$firstResultRow);
		$I->executeJS('window.scrollTo(0,0)');
		$I->click(['link' => 'ID']);
	}

	/**
	 * Function to change State of a Wrapper
	 *
	 * @param   string  $name   Name of the Wrapper
	 * @param   string  $state  State of the Wrapper
	 *
	 * @return void
	 */
	public function changeWrapperState($name, $state = 'unpublish')
	{
		$this->changeState(new \WrapperManagerJoomla3Page, $name, $state, \WrapperManagerJoomla3Page::$firstResultRow, \WrapperManagerJoomla3Page::$selectFirst);
	}

	/**
	 * Function to Search for a Wrapper
	 *
	 * @param   string  $name          Name of the Wrapper
	 * @param   string  $functionName  Name of the function After Which search is being Called
	 *
	 * @return void
	 */
	public function searchWrapper($name, $functionName = 'Search')
	{
		$this->search(new \WrapperManagerJoomla3Page, $name, \WrapperManagerJoomla3Page::$firstResultRow, $functionName);
	}

	/**
	 * Function to get State of the Wrapper
	 *
	 * @param   String  $name  Name of the Wrapper
	 *
	 * @return string
	 */
	public function getWrapperState($name)
	{
		$result = $this->getState(new \WrapperManagerJoomla3Page, $name, \WrapperManagerJoomla3Page::$firstResultRow, \WrapperManagerJoomla3Page::$wrapperStatePath);

		return $result;
	}

	/**
	 * Function to Delete Wrapper
	 *
	 * @param   String  $name  Name of the Wrapper which is to be Deleted
	 *
	 * @return void
	 */
	public function deleteWrapper($name)
	{
		$this->delete(new \WrapperManagerJoomla3Page, $name, \WrapperManagerJoomla3Page::$firstResultRow, \WrapperManagerJoomla3Page::$selectFirst);
	}
}
