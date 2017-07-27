<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageFrontEndNoticesCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageFrontEndNoticesCest
{
	public function __construct()
	{
		$this->allFrontEndPages = array (
			"Account Menu Type" => "/index.php?option=com_redshop&view=account",
			"Cart Menu Type" => "/index.php?option=com_redshop&view=cart",
			"Category Detailed Templates Menu Type" => "/index.php?option=com_redshop&view=category&layout=detail&cid=4&manufacturer_id=0",
			"Category Detailed CCK and e-Commerce Menu Type" => "/index.php?option=com_redshop&view=category&layout=detail&cid=3&manufacturer_id=0",
			"Category Detailed Events and Forms Menu Type" => "/index.php?option=com_redshop&view=category&layout=detail&cid=2&manufacturer_id=0",
			"Checkout Menu Type" => "/index.php?option=com_redshop&view=checkout",
			"Compare Products Menu Type" => "/index.php?option=com_redshop&view=product&layout=compare",
			"Gift Cards List Menu Type" => "/index.php?option=com_redshop&view=giftcard",
			"Login Layout Menu Type" => "/index.php?option=com_redshop&view=login",
			"Logout Layout Menu Type" => "/index.php?option=com_redshop&view=login&layout=logout",
			"Manufacturer Details Menu Type" => "/index.php?option=com_redshop&view=manufacturers&layout=detail",
			"Manufacturer Menu Type" => "/index.php?option=com_redshop&view=manufacturers",
			"My Wish List Menu Type" => "/index.php?option=com_redshop&amp;view=wishlist&amp;layout=viewwishlist&amp;Itemid=",
			"Newsletter Subscription Menu Type" => "/index.php?option=com_redshop&view=newsletter",
			"Orders Menu Type" => "/index.php?option=com_redshop&view=orders",
			"Order Tracker Menu Type" => "/index.php?option=com_redshop&view=ordertracker",
			"Portal Detail Layout For Shopper Group Menu Type" => "/index.php?option=com_redshop&view=login&layout=portals",
			"Product Download Menu Type" => "/index.php?option=com_redshop&view=product&layout=downloadproduct",
			"Quotation Menu Type" => "/index.php?option=com_redshop&view=quotation",
			"Registration Menu Type" => "/index.php?option=com_redshop&view=registration",
//			"Sample Catalogue Menu Type" => "/index.php?option=com_redshop&view=catalog&layout=sample",
//			"redFILTER Menu Type" => "/index.php?option=com_redshop&view=search&layout=redfilter",
//            "Product Search Menu Type" => "/index.php?option=com_redshop&view=search",
//            "Product From Selected Manufacturer Menu  Type" => "/index.php?option=com_redshop&view=manufacturers&layout=products",
//			"All Categorised Products From a Category Menu Type" => "/index.php?option=com_redshop&view=category&layout=categories",
//			"All Wish Lists Menu Type" => "/index.php?option=com_redshop&view=wishlist&layout=viewwishlist",
//			"Catalogue Menu Type" => "/index.php?option=com_redshop&view=catalog",
//			"Categories Menu Type" => "/index.php?option=com_redshop&view=category",
////			"Create Wish List Menu Type" => "/index.php?option=com_redshop&view=wishlist",
		);
	}

	/**
	 * Function to Verify Notices and Errors in Frontend Pages
	 *
	 */
	public function verifyNotices(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Presence of Notices, Warnings on FrontEnd Menus');
		$I->doFrontEndLogin();

		foreach ($this->allFrontEndPages as $pageName => $url)
		{
			$I->checkForPhpNoticesOrWarnings($url);
		}
	}
}
