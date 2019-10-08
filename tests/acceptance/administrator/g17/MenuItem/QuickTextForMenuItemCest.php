<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use AcceptanceTester\ProductUpdateOnQuantitySteps;

/**
 * Class QuickTextForMenuItemCest
 * @since 2.1.3
 */
class QuickTextForMenuItemCest
{
	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $menuCategory;

	/**
	 * @var string
	 * @since 2.1.3
	 */
	protected $menu;

	/**
	 * @var array
	 * @since 2.1.3
	 */
	protected $menuItem;

	/**
	 * QuickTextForMenuItemCest constructor.
	 * @since 2.1.3
	 */
	public function __construct()
	{
		$this->menuCategory = 'redSHOP';
		$this->menu = 'Main Menu';
		$this->menuItem = array(
			array(
				'menuItem' => 'All Wish Lists',
			),
			array(
				'menuItem' => 'Cart',
			),
			array(
				'menuItem' => 'Catalogue',
			),
			array(
				'menuItem' => 'Compare Products',
			),
			array(
				'menuItem' => 'Create Wish List',
			),
			array(
				'menuItem' => 'Featured Products',
			),
			array(
				'menuItem' => 'Frontpage category',
			),
			array(
				'menuItem' => 'Gift Cards List',
			),
			array(
				'menuItem' => 'Newsletter Subscription',
			),
			array(
				'menuItem' => 'Orders',
			),
			array(
				'menuItem' => 'Orders Tracker',
			),
			array(
				'menuItem' => 'Products On Sale',
			),
			array(
				'menuItem' => 'Quote',
			),
			array(
				'menuItem' => 'redFILTER',
			),
			array(
				'menuItem' => 'Sample',
			),
			array(
				'menuItem' => 'Login',
			),
			array(
				'menuItem' => 'Logout',
			),
			array(
				'menuItem' => 'Registration',
			)
		);
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function createQuickMenuItem(ProductUpdateOnQuantitySteps $I)
	{
		$I->doAdministratorLogin();
		$length = count($this->menuItem);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$menuItem  =  $this->menuItem[$x];
			$I->createNewMenuItem( $menuItem['menuItem'], $this->menuCategory, $menuItem['menuItem'], $this->menu);
		}
	}

	/**
	 * @param ProductUpdateOnQuantitySteps $I
	 * @throws Exception
	 * @since 2.1.3
	 */
	public function checkQuickMenuItemOnFrontEnd(ProductUpdateOnQuantitySteps $I)
	{
		$I->doFrontEndLogin();

		$length = count($this->menuItem);
		$I->wantToTest($length);
		for($x = 0;  $x < $length; $x ++ )
		{
			$menuItem = $this->menuItem[$x];

			if($menuItem['menuItem'] == 'Registration')
			{
				$I->doFrontendLogout();
			}

			$I->waitForElementVisible(['link' => $menuItem['menuItem']], 30);
			$I->click(['link' => $menuItem['menuItem']]);
			$I->checkForPhpNoticesOrWarnings();
		}
	}
}