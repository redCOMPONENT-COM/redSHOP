<?php
/**
 * @package     RedShop
 * @subpackage  Cept
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

$allExtensionPages = array (
	'Category Manager' => '/administrator/index.php?option=com_redshop&view=category',
	'Country Manager' => '/administrator/index.php?option=com_redshop&view=country',
	'Product Manager' => '/administrator/index.php?option=com_redshop&view=product',
	'Manufacturer Manager' => '/administrator/index.php?option=com_redshop&view=manufacturer',
	'Media Manager' => '/administrator/index.php?option=com_redshop&view=media',
	'Order Manager' => '/administrator/index.php?option=com_redshop&view=order',
	'Discount Manager' => '/administrator/index.php?option=com_redshop&view=discount',
	'Gift Card Manager' => '/administrator/index.php?option=com_redshop&view=giftcard',
	'Voucher Manager' => '/administrator/index.php?option=com_redshop&view=voucher',
	'Coupon Manager' => '/administrator/index.php?option=com_redshop&view=coupon',
	'Mail  Manager' => '/administrator/index.php?option=com_redshop&view=mail',
	'News Letter Manager' => '/administrator/index.php?option=com_redshop&view=newsletter',
	'Shipping Box Manager' => '/administrator/index.php?option=com_redshop&view=shipping_box',
	'Wrapping  Manager' => '/administrator/index.php?option=com_redshop&view=wrapper',
	'User Manager' => '/administrator/index.php?option=com_redshop&view=user',
	'Vat/Tax Group Manager' => '/administrator/index.php?option=com_redshop&view=tax_group',
	'Currency Manager' => '/administrator/index.php?option=com_redshop&view=currency',
	'State Manager' => '/administrator/index.php?option=com_redshop&view=state',
	'Custom Field Manager' => '/administrator/index.php?option=com_redshop&view=fields',
	'Template Manager' => '/administrator/index.php?option=com_redshop&view=template',
	'Text Library Manager' => '/administrator/index.php?option=com_redshop&view=textlibrary',
	'Question Manager' => '/administrator/index.php?option=com_redshop&view=question',
	'Rating Manager' => '/administrator/index.php?option=com_redshop&view=rating'
);

// Load the Step Object Page

$I = new AcceptanceTester($scenario);
$I->wantTo('Test Presence of Notices, Warnings on Administrator');
$I->doAdministratorLogin();

foreach ($allExtensionPages as $page => $url)
{
	$I->checkForPhpNoticesOrWarnings($url);
}
