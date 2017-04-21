<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageStockImageAdministratorCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageStockImageAdministratorCest
{
	public function __construct()
	{
		$this->faker = Faker\Factory::create();
		$this->imageTooltip = $this->faker->bothify('ManageStockImageAdministratorCest ?##?');
		$this->newImageTooltip = 'Updated ' . $this->imageTooltip;
		$this->quantity = '100';
	}

	/**
	 * Function to Test Stock Images Creation in Backend
	 *
	 */
	public function createStockImage(AcceptanceTester $I)
	{
		$I->wantTo('Test Stock Image creation in Administrator');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=stockimage');
		$I->executeJS('window.scrollTo(0,0)');
		$I->checkForPhpNoticesOrWarnings();
		$I->click('New');
		$I->waitForText('Stock Amount Image', 60, ['css' => 'h1']);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['id' => 'stock_amount_image_tooltip'], $this->imageTooltip);
		$I->fillField(['id' => 'stock_quantity'], $this->quantity);
		$I->click(['xpath' => "//div[@id='s2id_stock_option']/a"]);
		$I->fillField(['xpath' => "//input[@id='s2id_autogen2_search']"], 'Higher than');
		$I->waitForElement(['xpath' => "//div[@id='select2-drop']//ul//li//div//span[contains(text(), 'Higher than')]"], 30);
		$I->click(['xpath' => "//div[@id='select2-drop']//ul//li//div//span[contains(text(), 'Higher than')]"]);
		$I->click('Save & Close');
		$I->waitForText('Stock Amount Image saved',60, ['id' => 'system-message-container']);
		$I->seeElement(['link' => $this->imageTooltip]);
	}

	/**
	 * Function to Test Stock Image Updation in the Administrator
	 *
	 * @depends createStockImage
	 */
	public function updateStockImage(AcceptanceTester $I)
	{
		$I->am('administrator');
		$I->wantTo('Test if Stock Image gets updated in Administrator');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=stockimage');
		$I->executeJS('window.scrollTo(0,0)');
		$I->waitForElement(['link' => $this->imageTooltip],60);
		$I->click(['xpath' => "//a[contains(text(), '$this->imageTooltip')]/ancestor::*[1]/preceding-sibling::*[1]/input"]);
		$I->click('Edit');
		$I->waitForText('Stock Amount Image', 60, ['css' => 'h1']);
		$I->checkForPhpNoticesOrWarnings();
		$I->fillField(['id' => 'stock_amount_image_tooltip'], $this->newImageTooltip);
		$I->click('Save & Close');
		$I->waitForText('Stock Amount Image saved',60, ['id' => 'system-message-container']);
		$I->seeElement(['link' => $this->newImageTooltip]);
		$I->dontSeeElement(['link' => $this->imageTooltip]);

	}

	/**
	 * Function to Test Stock Image Deletion
	 *
	 * @depends updateStockImage
	 */
	public function deleteStockImage(AcceptanceTester $I)
	{
		$I->am('administrator');
		$I->wantToTest('Deletion of Stock Images in Administrator');
		$I->doAdministratorLogin();
		$I->amOnPage('/administrator/index.php?option=com_redshop&view=stockimage');
		$I->waitForText('Stock Image Management', 60, ['css' => 'h1']);
		$I->click(['xpath' => "//a[contains(text(), '$this->newImageTooltip')]/ancestor::*[1]/preceding-sibling::*[1]/input"]);
		$I->click('Delete');
		$I->waitForText('Stock Image detail deleted successfully', 60, ['id' => 'system-message-container']);
		$I->dontSeeElement(['link' => $this->newImageTooltip]);
	}
}
