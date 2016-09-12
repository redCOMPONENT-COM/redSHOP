<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$producthelper = productHelper::getInstance();
$config        = Redconfiguration::getInstance();

$views = array(
	'statistic'      => array(
						'icon' => 'statistic48.png',
						'text' => 'COM_REDSHOP_STATISTIC'
					),
	'product'        => array(
						'icon' => 'products48.png',
						'text' => 'COM_REDSHOP_PRODUCTS'
					),
	'category'       => array(
						'icon' => 'categories48.png',
						'text' => 'COM_REDSHOP_CATEGORIES'
					),
	'media'          => array(
						'icon' => 'media48.png',
						'text' => 'COM_REDSHOP_MEDIA'
					),
	'wrapper'        => array(
						'icon' => 'wrapper48.png',
						'text' => 'COM_REDSHOP_WRAPPER'
					),
	'order'          => array(
						'icon' => 'order48.png',
						'text' => 'COM_REDSHOP_ORDER'
					),
	'quotation'      => array(
						'icon' => 'quotation_48.jpg',
						'text' => 'COM_REDSHOP_QUOTATION',
					),
	'user'           => array(
						'icon' => 'user48.png',
						'text' => 'COM_REDSHOP_USER'
					),
	'stockroom'      => array(
						'icon' => 'stockroom48.png',
						'text' => 'COM_REDSHOP_STOCKROOM',
					),
	'manufacturer'   => array(
						'icon' => 'manufact48.png',
						'text' => 'COM_REDSHOP_MANUFACTURERS',
					),
	'newsletter'     => array(
						'icon' => 'newsletter48.png',
						'text' => 'COM_REDSHOP_NEWSLETTER',
					),
	'mail'           => array(
						'icon' => 'mailcenter48.png',
						'text' => 'COM_REDSHOP_MAIL_CENTER',
					),
	'coupon'         => array(
						'icon' => 'coupon48.png',
						'text' => 'COM_REDSHOP_COUPON_MANAGEMENT',
					),
	'discount'       => array(
						'icon' => 'discountmanagmenet48.png',
						'text' => 'COM_REDSHOP_DISCOUNT_MANAGEMENT',
					),
	'voucher'        => array(
						'icon' => 'voucher48.png',
						'text' => 'COM_REDSHOP_VOUCHER',
					),
	'fields'         => array(
						'icon' => 'fields48.png',
						'text' => 'COM_REDSHOP_FIELDS',
					),
	'textlibrary'    => array(
						'icon' => 'textlibrary48.png',
						'text' => 'COM_REDSHOP_TEXT_LIBRARY',
					),
	'template'       => array(
						'icon' => 'templates48.png',
						'text' => 'COM_REDSHOP_TEMPLATES',
					),
	'shipping'       => array(
						'icon' => 'shipping48.png',
						'text' => 'COM_REDSHOP_SHIPPING',
					),
	'tax_group'      => array(
						'icon' => 'vatgroup_48.png',
						'text' => 'COM_REDSHOP_TAX_GROUP',
					),
	'catalog'        => array(
						'icon' => 'catalogmanagement48.png',
						'text' => 'COM_REDSHOP_CATALOG_MANAGEMENT',
					),
	'sample_request' => array(
						'icon' => 'catalogmanagement48.png',
						'text' => 'COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT',
					),
	'import'         => array(
						'icon' => 'import48.png',
						'text' => 'COM_REDSHOP_IMPORT_EXPORT'
					),
	'xmlimport'      => array(
						'icon' => 'export48.png',
						'text' => 'COM_REDSHOP_XML_IMPORT_EXPORT',
					),
	'accountgroup'   => array(
						'icon' => 'user48.png',
						'text' => 'COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'
					),
	'configuration'  => array(
						'icon' => 'icon-48-settings.png',
						'text' => 'COM_REDSHOP_CONFIG',
					),
	'giftcard'       => array(
						'icon' => 'giftcard_48.png',
						'text' => 'COM_REDSHOP_GIFTCARD',
					),
	'state'          => array(
						'icon' => 'region_48.png',
						'text' => 'COM_REDSHOP_STATE',
					),
	'country'        => array(
						'icon' => 'country_48.png',
						'text' => 'COM_REDSHOP_COUNTRY',
					),
	'currency'       => array(
						'icon' => 'currencies_48.png',
						'text' => 'COM_REDSHOP_CURRENCY',
					),
	'question'       => array(
						'icon' => 'question_48.jpg',
						'text' => 'COM_REDSHOP_QUESTION',
					),
	'accessmanager'  => array(
						'icon' => 'catalogmanagement48.png',
						'text' => 'COM_REDSHOP_ACCESS_MANAGER',
					),
	'wizard'         => array(
						'icon' => 'wizard_48.png',
						'text' => 'COM_REDSHOP_WIZARD'
					)
);
?>
<div class="views" id="cpanel">
	<?php foreach ($views as $view => $info) : ?>
		<div>
			<div class="icon">
				<a href="index.php?option=com_redshop&view=accessmanager_detail&section=<?php echo $view; ?>">
					<img
						alt="<?php echo $view; ?>"
						src="components/com_redshop/assets/images/<?php echo $info['icon']; ?>"
					><span><?php echo JText::_($info['text']); ?></span>
				</a>
			</div>
		</div>
	<?php endforeach; ?>
</div>
