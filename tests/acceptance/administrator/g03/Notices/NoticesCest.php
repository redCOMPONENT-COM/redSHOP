<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManageAdministratorNoticesCest
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage
 *
 * @since 1.4.0
 */
class NoticesCest
{
	/**
	 * @var array
	 * @since 1.4.0
	 */
	public $allExtensionPages = array();

	/**
	 * ManageAdministratorNoticesCest constructor.
	 * @since 1.4.0
	 */
	public function __construct()
	{
		$this->allExtensionPages = array (
			'Category Manager' => '/administrator/index.php?option=com_redshop&view=categories',
			'Country Manager' => '/administrator/index.php?option=com_redshop&view=countries',
			'Product Manager' => '/administrator/index.php?option=com_redshop&view=product',
			'Manufacturer Manager' => '/administrator/index.php?option=com_redshop&view=manufacturers',
			'Media Manager' => '/administrator/index.php?option=com_redshop&view=media',
			'Order Manager' => '/administrator/index.php?option=com_redshop&view=order',
			'Mass Discounts Management' => '/administrator/index.php?option=com_redshop&view=mass_discounts',
			'Product Discount Management' => '/administrator/index.php?option=com_redshop&view=discounts',
			'Product price discounts' => '/administrator/index.php?option=com_redshop&view=discount_products',
			'Gift Card Manager' => '/administrator/index.php?option=com_redshop&view=giftcards',
			'Voucher Manager' => '/administrator/index.php?option=com_redshop&view=vouchers',
			'Coupon Manager' => 'administrator/index.php?option=com_redshop&view=coupons',
			'Mail Manager' => '/administrator/index.php?option=com_redshop&view=mails',
			'News Letter Manager' => '/administrator/index.php?option=com_redshop&view=newsletters',
			'Shipping Box Manager' => 'administrator/index.php?option=com_redshop&view=shipping_boxes',
			'Wrapping  Manager' => 'administrator/index.php?option=com_redshop&view=wrappers',
			'User Manager' => '/administrator/index.php?option=com_redshop&view=user',
			'Vat/Tax Group Manager' => 'administrator/index.php?option=com_redshop&view=tax_groups',
			'Currency Manager' => '/administrator/index.php?option=com_redshop&view=currencies',
			'State Manager' => '/administrator/index.php?option=com_redshop&view=states',
			'Custom Field Manager' => '/administrator/index.php?option=com_redshop&view=fields',
			'Template Manager' => 'administrator/index.php?option=com_redshop&view=templates',
			'Text Library' => '/administrator/index.php?option=com_redshop&view=texts',
			'Question Manager' => '/administrator/index.php?option=com_redshop&view=questions',
			'Rating Manager' => '/administrator/index.php?option=com_redshop&view=ratings'
		);
	}

	/**
	 * @param AcceptanceTester $tester
	 * @throws Exception
	 * @since 1.4.0
	 */
	public function verifyNotices(AcceptanceTester $tester)
	{
		$tester->wantTo('Test Presence of Notices, Warnings on Administrator Pages');
		$tester->doAdministratorLogin();

		foreach ($this->allExtensionPages as $pageName => $url)
		{
			$tester->checkForPhpNoticesOrWarnings($url);
		}
	}
}
