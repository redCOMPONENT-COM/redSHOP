<?php
/**
 * @package     RedShop
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageAdministratorNoticesCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since    1.4
 */
class ManageAdministratorNoticesCest
{
	public function __construct()
	{
		$this->allExtensionPages = array (
			'Category Manager' => '/administrator/index.php?option=com_redshop&view=categories',
			'Country Manager' => '/administrator/index.php?option=com_redshop&view=countries',
			'Product Manager' => '/administrator/index.php?option=com_redshop&view=product',
			'Manufacturer Manager' => '/administrator/index.php?option=com_redshop&view=manufacturer',
			'Media Manager' => '/administrator/index.php?option=com_redshop&view=media',
			'Order Manager' => '/administrator/index.php?option=com_redshop&view=order',
			'Discount Manager' => '/administrator/index.php?option=com_redshop&view=discount',
			'Gift Card Manager' => '/administrator/index.php?option=com_redshop&view=giftcards',
			'Voucher Manager' => '/administrator/index.php?option=com_redshop&view=voucher',
			'Coupon Manager' => '/administrator/index.php?option=com_redshop&view=coupon',
			'Mail  Manager' => '/administrator/index.php?option=com_redshop&view=mail',
			'News Letter Manager' => '/administrator/index.php?option=com_redshop&view=newsletter',
			'Shipping Box Manager' => '/administrator/index.php?option=com_redshop&view=shipping_box',
			'Wrapping  Manager' => '/administrator/index.php?option=com_redshop&view=wrapper',
			'User Manager' => '/administrator/index.php?option=com_redshop&view=user',
			'Vat/Tax Group Manager' => '/administrator/index.php?option=com_redshop&view=tax_group',
			'Currency Manager' => '/administrator/index.php?option=com_redshop&view=currency',
			'State Manager' => '/administrator/index.php?option=com_redshop&view=states',
			'Custom Field Manager' => '/administrator/index.php?option=com_redshop&view=fields',
			'Template Manager' => '/administrator/index.php?option=com_redshop&view=template',
			'Text Library Manager' => '/administrator/index.php?option=com_redshop&view=textlibrary',
			'Question Manager' => '/administrator/index.php?option=com_redshop&view=question',
			'Rating Manager' => '/administrator/index.php?option=com_redshop&view=rating'
		);
	}

	/**
	 * Function to Verify Notices and Errors in Administrator Pages
	 *
	 */
	public function verifyNotices(AcceptanceTester $I, $scenario)
	{
		$I->wantTo('Test Presence of Notices, Warnings on Administrator Pages');
		$I->doAdministratorLogin();

		foreach ($this->allExtensionPages as $pageName => $url)
		{
			$I->checkForPhpNoticesOrWarnings($url);
		}
	}
}
