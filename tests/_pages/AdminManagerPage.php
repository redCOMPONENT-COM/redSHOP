<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class AdminManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class AdminManagerPage
{
	public static $allExtensionPages = array (
		'Category Manager' => '/administrator/index.php?option=com_redshop&view=category',
		'Category Create Page' => '/administrator/index.php?option=com_redshop&view=category_detail&task=edit',
		'Country Manager' => '/administrator/index.php?option=com_redshop&view=country',
		'Country Create Page' => '/administrator/index.php?option=com_redshop&view=country_detail&task=edit',
		'Product Manager' => '/administrator/index.php?option=com_redshop&view=product',
		'Product Create Page' => '/administrator/index.php?option=com_redshop&view=product_detail&task=edit',
		'Manufacturer Manager' => '/administrator/index.php?option=com_redshop&view=manufacturer',
		'Manufacturer Create Page' => '/administrator/index.php?option=com_redshop&view=manufacturer_detail&task=edit',
		'Media Manager' => '/administrator/index.php?option=com_redshop&view=media',
		'Media Create Page' => '/administrator/index.php?option=com_redshop&view=media_detail&task=edit',
		'Order Manager' => '/administrator/index.php?option=com_redshop&view=order',
		'Order Create Page' => '/administrator/index.php?option=com_redshop&view=order_detail&task=edit',
		'Quotation Manager' => '/administrator/index.php?option=com_redshop&view=quotation',
		'Quotation Create Page' => '/administrator/index.php?option=com_redshop&view=quotation_detail&task=edit',
		'Discount Manager' => '/administrator/index.php?option=com_redshop&view=discount',
		'Discount Create Page' => '/administrator/index.php?option=com_redshop&view=discount_detail&task=edit',
		'Gift Card Manager' => '/administrator/index.php?option=com_redshop&view=giftcard',
		'Gift Card Create Page' => '/administrator/index.php?option=com_redshop&view=giftcard_detail&task=edit',
		'Voucher Manager' => '/administrator/index.php?option=com_redshop&view=voucher',
		'Voucher Create Page' => '/administrator/index.php?option=com_redshop&view=voucher_detail&task=edit',
		'Coupon Manager' => '/administrator/index.php?option=com_redshop&view=coupon',
		'Coupon Create Page' => '/administrator/index.php?option=com_redshop&view=coupon_detail&task=edit',
		'Mail  Manager' => '/administrator/index.php?option=com_redshop&view=mail',
		'Mail  Create Page' => '/administrator/index.php?option=com_redshop&view=mail_detail&task=edit',
		'News Letter Manager' => '/administrator/index.php?option=com_redshop&view=newsletter',
		'News Letter Create Page' => '/administrator/index.php?option=com_redshop&view=newsletter_detail&task=edit',
		'Shipping Box Manager' => '/administrator/index.php?option=com_redshop&view=shipping_box',
		'Shipping Box Create Page' => '/administrator/index.php?option=com_redshop&view=shipping_box_detail&task=edit',
		'Wrapping  Manager' => '/administrator/index.php?option=com_redshop&view=wrapper',
		'Wrapping  Create Page' => '/administrator/index.php?option=com_redshop&view=wrapper_detail&task=edit',
		'User Manager' => '/administrator/index.php?option=com_redshop&view=user',
		'Vat/Tax Group Manager' => '/administrator/index.php?option=com_redshop&view=tax_group',
		'Vat/Tax Group Create Page' => '/administrator/index.php?option=com_redshop&view=tax_group_detail&task=edit',
		'Currency Manager' => '/administrator/index.php?option=com_redshop&view=currency',
		'Currency Create Page' => '/administrator/index.php?option=com_redshop&view=currency_detail&task=edit',
		'State Manager' => '/administrator/index.php?option=com_redshop&view=state',
		'State Create Page' => '/administrator/index.php?option=com_redshop&view=state_detail&task=edit',
		'Custom Field Manager' => '/administrator/index.php?option=com_redshop&view=fields',
		'Custom Field Create Page' => '/administrator/index.php?option=com_redshop&view=fields_detail&task=edit',
		'Template Manager' => '/administrator/index.php?option=com_redshop&view=template',
		'Template Create Page' => '/administrator/index.php?option=com_redshop&view=template_detail&task=edit',
		'Text Library Manager' => '/administrator/index.php?option=com_redshop&view=textlibrary',
		'Text Library Create Page' => '/administrator/index.php?option=com_redshop&view=textlibrary_detail&task=edit',
		'Question Manager' => '/administrator/index.php?option=com_redshop&view=question',
		'Question Create Page' => '/administrator/index.php?option=com_redshop&view=question_detail&task=edit',
		'Rating Manager' => '/administrator/index.php?option=com_redshop&view=rating',
		'Rating Create Page' => '/administrator/index.php?option=com_redshop&view=rating_detail&task=edit'
	);
}
