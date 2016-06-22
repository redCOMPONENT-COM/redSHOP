<?php
/**
 * @package     Redgit.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2015 - 2016 redcomponent.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


$app = JFactory::getApplication();
$user = JFactory::getUser();

$option = 'com_redshop';
$activeView = $app->input->get('view');

$ecoIsenable = JPluginHelper::isEnabled('economic');

switch ($activeView)
{
	case "product":
	case "product_detail":
	case "prices":
	case "mass_discount_detail":
	case "mass_discount":
		$itemSelected = "product";
		$groupSelected = "product";
		break;

	case "category":
	case "category_detail":
		$itemSelected = "category";
		$groupSelected = "product";
		break;

	case "manufacturer":
	case "manufacturer_detail":
		$itemSelected = "manufacturer";
		$groupSelected = "product";
		break;

	case "media":
	case 'media_detail':
		$itemSelected = "media";
		$groupSelected = "product";
		break;

	case "order":
	case "order_detail":
	case "addorder_detail":
	case "orderstatus":
	case "orderstatus_detail":
	case "opsearch":
	case "barcode":
		$itemSelected = "order";
		$groupSelected = "order";
		break;

	case "quotation":
	case "addquotation_detail":
		$itemSelected = "quotation";
		$groupSelected = "order";
		break;

	case "stockroom":
	case "stockroom_listing":
	case "stockimage":
		$itemSelected = "stockroom";
		$groupSelected = "order";
		break;

	case "supplier":
	case "supplier_detail":
		$itemSelected = "supplier";
		$groupSelected = "order";
		break;

	case "discount":
	case "discount_detail":
		$itemSelected = "discount";
		$groupSelected = "discount";
		break;

	case "giftcards":
	case "giftcard":
		$itemSelected = "giftcards";
		$groupSelected = "discount";
		break;

	case "voucher":
	case "voucher_detail":
		$itemSelected = "voucher";
		$groupSelected = "discount";
		break;

	case "coupon":
	case "coupon_detail":
		$itemSelected = "coupon";
		$groupSelected = "discount";
		break;

	case "mail":
	case "mail_detail":
		$itemSelected = "mail";
		$groupSelected = "communication";
		break;

	case "newsletter":
	case "newsletter_detail":
	case "newslettersubscr":
		$itemSelected = "newsletter";
		$groupSelected = "communication";
		break;

	case "shipping":
	case "shipping_detail":
	case "shipping_rate":
		$itemSelected = "shipping_method";
		$groupSelected = "shipping";
		break;

	case "shipping_box":
	case "shipping_box_detail":
		$itemSelected = "shipping_box";
		$groupSelected = "shipping";
		break;

	case "wrapper":
	case "wrapper_detail":
		$itemSelected = "wrapper";
		$groupSelected = "shipping";
		break;

	case "user":
	case "shopper_group":
		$itemSelected = "user";
		$groupSelected = "user";
		break;

	case "accessmanager":
	case 'accessmanager_detail':
		$itemSelected = "accessmanager";
		$groupSelected = "user";
		break;

	case "tax_group":
	case "tax_group_detail":
	case "tax":
		$itemSelected = "tax";
		$groupSelected = "tax";
		break;

	case "currency":
	case "currency_detail":
		$itemSelected = "currency";
		$groupSelected = "tax";
		break;

	case "country":
	case "country_detail":
		$itemSelected = "country";
		$groupSelected = "tax";
		break;

	case "state":
	case "state_detail":
		$itemSelected = "state";
		$groupSelected = "tax";
		break;

	case "zipcode":
	case "zipcode_detail":
		$itemSelected = "zipcode";
		$groupSelected = "tax";
		break;

	case "importexport":
	case "import":
	case "export":
	case "vmimport":
		$itemSelected = "importexport";
		$groupSelected = "importexport";
		break;

	case "xmlimport":
	case "xmlexport":
		$itemSelected = "xmlimportexport";
		$groupSelected = "importexport";
		break;

	case "fields":
	case "fields_detail":
	case "addressfields_listing":
		$itemSelected = "fields";
		$groupSelected = "customization";
		break;

	case "template":
	case "template_detail":
		$itemSelected = "template";
		$groupSelected = "customization";
		break;

	case "textlibrary":
	case "textlibrary_detail":
		$itemSelected = "textlibrary";
		$groupSelected = "customization";
		break;

	case "catalog":
	case "catalog_request":
		$itemSelected = "catalog";
		$groupSelected = "customization";
		break;

	case "sample":
	case "sample_request":
		$itemSelected = "sample";
		$groupSelected = "customization";
		break;

	case "producttags":
	case "producttags_detail":
		$itemSelected = "producttags";
		$groupSelected = "customization";
		break;

	case "attribute_set":
	case "attribute_set_detail":
		$itemSelected = "attribute_set";
		$groupSelected = "customization";
		break;

	case "question":
	case "question_detail":
	case "answer":
	case "answer_detail":
		$itemSelected = "question";
		$groupSelected = "customer";
		break;

	case "rating":
	case "rating_detail":
		$itemSelected = "rating";
		$groupSelected = "customer";
		break;

	case "accountgroup":
	case "accountgroup_detail":
		$itemSelected = "accountgroup";
		$groupSelected = "accountgroup";
		break;

	case "statistic":
		$itemSelected = "statistic";
		$groupSelected = "statistic";
		break;

	case "configuration":
	case 'update':
		$itemSelected = "configuration";
		$groupSelected = "configuration";
		break;

	default:
		$itemSelected = '';
		$groupSelected = '';
		break;
}

?>

<script type="text/javascript">
	function userSync()
	{
		if (confirm("<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_SYNC');?>") == true)
			window.location = "index.php?option=com_redshop&view=user&sync=1";
	}

	function vmImport()
	{
		if (confirm("<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_IMPORT_VM');?>") == true)
					window.location = "index.php?option=com_redshop&view=import&vm=1";
	}
</script>

<ul class="sidebar-menu">
	<!-- Product management -->
	<li class="treeview <?php echo $groupSelected == 'product' ? 'active' : ''; ?>">
		<a href="#">
			<i class="product_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'product' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_PRODUCTS'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_PRODUCT_LISTING'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product&layout=listing'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_VIEW'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_PRODUCT'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=mass_discount_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_MASS_DISCOUNT'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=mass_discount'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_MASS_DISCOUNT'); ?></span>
						</a>
					</li>

					<?php if (ECONOMIC_INTEGRATION == 1 && $ecoIsenable) { ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product&layout=importproduct'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC'); ?></span>
						</a>
					</li>
					<?php } ?>

					<?php if (ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC == 1) { ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=product&layout=importattribute'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC'); ?></span>
						</a>
					</li>
					<?php } ?>

				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'category' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_CATEGORY'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=category'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_CATEGORY_LISTING'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=category_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_CATEGORY'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'manufacturer' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_MANUFACTURER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=manufacturer'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_MANUFACTURER_LISTING'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=manufacturer_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_MANUFACTURER'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'media' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_MEDIA'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=media'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_MEDIA_LISTING'); ?></span>
						</a>
					</li>

					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=media_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_MEDIA_ITEM'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>

	</li>

	<!-- Order -->
	<li class="treeview <?php echo $groupSelected == 'order' ? 'active' : ''; ?>">
		<a href="#">
			<i class="order_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_ORDER'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'order' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_ORDER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=order'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ORDER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=addorder_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_ORDER'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=order&layout=labellisting'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_DOWNLOAD_LABEL'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=orderstatus'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ORDERSTATUS_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=opsearch'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=barcode'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_BARCODE'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=barcode&layout=barcode_order'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_BARCODE_ORDER'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'quotation' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_QUOTATION'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=quotation'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_QUOTATION_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=addquotation_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_QUOTATION'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<?php if (USE_STOCKROOM != 0) { ?>
			<li class="treeview <?php echo $itemSelected == 'stockroom' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_STOCKROOM'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=stockroom'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_STOCKROOM_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=stockroom_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_STOCKROOM'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=stockroom_listing'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_STOCKROOM_AMOUNT_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=stockimage'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_STOCKIMAGE_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=stockimage_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_STOCKIMAGE'); ?></span>
						</a>
					</li>

					<?php if (ECONOMIC_INTEGRATION && $ecoIsenable) { ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=importstock'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC'); ?></span>
						</a>
					</li>
					<?php } ?>

				</ul>
			</li>
			<?php } ?>

			<li class="treeview <?php echo $itemSelected == 'supplier' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_SUPPLIER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=supplier'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_SUPPLIER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=supplier_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_SUPPLIER'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- Discount -->
	<li class="treeview <?php echo $groupSelected == 'discount' ? 'active' : ''; ?>">
		<a href="#">
			<i class="discount_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_DISCOUNT'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'discount' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_DISCOUNT'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=discount'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_DISCOUNT_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=discount_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_DISCOUNT'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=discount&layout=product'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=discount_detail&layout=product'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_DISCOUNT'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'giftcards' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_GIFTCARD'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=giftcards'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_GIFTCARD_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=giftcard&task=giftcard.edit'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_GIFTCARD'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'voucher' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_VOUCHER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=voucher'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_VOUCHER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=voucher_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_VOUCHER'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'coupon' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_COUPON'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=coupon'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_COUPON_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=coupon_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_COUPON'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- Communication -->
	<li class="treeview <?php echo $groupSelected == 'communication' ? 'active' : ''; ?>">
		<a href="#">
			<i class="communication_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_COMMUNICATION'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'mail' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_MAIL_CENTER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=mail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_MAIL_CENTER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=mail_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_MAIL_CENTER'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'newsletter' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_NEWSLETTER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=newsletter'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_NEWSLETTER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=newsletter_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_NEWSLETTER'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=newslettersubscr'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=newslettersubscr_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_NEWSLETTER_SUBSCR'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=newsletter_detail&layout=statistics'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_NEWSLETTER_STATISTICS'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- shipping -->
	<li class="treeview <?php echo $groupSelected == 'shipping' ? 'active' : ''; ?>">
		<a href="#">
			<i class="shipping_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_SHIPPING'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'shipping_method' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=shipping'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_installer'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_SHIPPING_METHOD'); ?></span>
						</a>
					</li>
					<?php if (ECONOMIC_INTEGRATION == 1 && $ecoIsenable) { ?>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=shipping&task=importeconomic'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC'); ?></span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
		</ul>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'shipping_box' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_SHIPPING_BOX'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=shipping_box'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_SHIPPING_BOXES'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=shipping_box_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_SHIPPING_BOXES'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'wrapper' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_WRAPPER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=wrapper'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_WRAPPER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=wrapper_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_WRAPPER'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- user -->
	<li class="treeview <?php echo $groupSelected == 'user' ? 'active' : ''; ?>">
		<a href="#">
			<i class="user_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_USER'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'user' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_USER'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=user'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_USER_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=user_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_USER'); ?></span>
						</a>
					</li>
					<li>
						<a href="javascript:userSync();">
							<span><?php echo JText::_('COM_REDSHOP_USER_SYNC'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=shopper_group'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=shopper_group_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_SHOPPER_GROUP'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<?php if (ENABLE_BACKENDACCESS) { ?>
			<li class="<?php echo $itemSelected == 'accessmanager' ? 'active' : ''; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=accessmanager'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_ACCESS_MANAGER'); ?></span>
				</a>
			</li>
			<?php } ?>
		</ul>
	</li>

	<!-- tax -->
	<li class="treeview <?php echo $groupSelected == 'tax' ? 'active' : ''; ?>">
		<a href="#">
			<i class="tax_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_VAT_AND_CURRENCY'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'tax' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_TAX_GROUP'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=tax_group'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_TAX_GROUP_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=tax_group_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_TAX_GROUP_DETAIL'); ?></span>
						</a>
					</li>
				</ul>
			</li>
			<li class="treeview <?php echo $itemSelected == 'currency' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_CURRENCY'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=currency'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_CURRENCY_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=currency_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_CURRENCY'); ?></span>
						</a>
					</li>
				</ul>
			</li>
			<li class="treeview <?php echo $itemSelected == 'country' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=country'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_COUNTRY_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=country_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_COUNTRY'); ?></span>
						</a>
					</li>
				</ul>
			</li>
			<li class="treeview <?php echo $itemSelected == 'state' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_STATE'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=state'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_STATE_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=state_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_STATE'); ?></span>
						</a>
					</li>
				</ul>
			</li>
			<li class="treeview <?php echo $itemSelected == 'zipcode' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_ZIPCODE'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=zipcode'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ZIPCODE_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=zipcode_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_ZIPCODE'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- import export -->
	<li class="treeview <?php echo $groupSelected == 'importexport' ? 'active' : ''; ?>">
		<a href="#">
			<i class="importexport_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_IMPORT_EXPORT'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'importexport' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_IMPORT_EXPORT'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=import'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_DATA_IMPORT'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=export'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_DATA_EXPORT'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('javascript:vmImport();'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_IMPORT_FROM_VM'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'xmlimportexport' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_XML_IMPORT_EXPORT'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=xmlimport'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_XML_IMPORT'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=xmlexport'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_XML_EXPORT'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- customization -->
	<li class="treeview <?php echo $groupSelected == 'customization' ? 'active' : ''; ?>">
		<a href="#">
			<i class="customization_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_CUSTOMIZATION'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="treeview <?php echo $itemSelected == 'fields' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_FIELDS'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=fields'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_FIELDS_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=fields_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_FIELD'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'template' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_TEMPLATE'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=template'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_TEMPLATE_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=template_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_TEMPLATE'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'textlibrary' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_TEXT_LIBRARY'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=textlibrary'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_TEXT_LIBRARY_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=textlibrary_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_TEXT_LIBRARY_TAG'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'catalog' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_CATALOG_MANAGEMENT'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=catalog'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_CATALOG'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=catalog_request'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_CATALOG_REQUEST'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="treeview <?php echo $itemSelected == 'sample' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=sample'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_CATALOG_PRODUCT_SAMPLE'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=sample_request'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_SAMPLE_REQUEST'); ?></span>
						</a>
					</li>
				</ul>
			</li>

			<li class="<?php echo $itemSelected == 'producttags' ? 'active' : ''; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=producttags'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TAGS_LISTING'); ?></span>
				</a>
			</li>

			<li class="treeview <?php echo $itemSelected == 'attribute_set' ? 'active' : ''; ?>">
				<a href="#">
					<span><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_BANK'); ?></span>
					<i class="fa fa-angle-left pull-right"></i>
				</a>
				<ul class="treeview-menu">
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=attribute_set'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_SET_LISTING'); ?></span>
						</a>
					</li>
					<li>
						<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=attribute_set_detail'); ?>">
							<span><?php echo JText::_('COM_REDSHOP_ADD_ATTRIBUTE_SET'); ?></span>
						</a>
					</li>
				</ul>
			</li>
		</ul>
	</li>

	<!-- customer -->
	<li class="treeview <?php echo $groupSelected == 'customer' ? 'active' : ''; ?>">
		<a href="#">
			<i class="customer_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_CUSTOMER_INPUT'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li class="<?php echo $itemSelected == 'question' ? 'active' : ''; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=question'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_QUESTION_LISTING'); ?></span>
				</a>
			</li>

			<li class="<?php echo $itemSelected == 'rating' ? 'active' : ''; ?>">
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=rating'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_RATING_REVIEW'); ?></span>
				</a>
			</li>
		</ul>
	</li>

	<?php if (ECONOMIC_INTEGRATION && $ecoIsenable) { ?>
	<!-- accountgroup -->
	<li class="treeview <?php echo $groupSelected == 'accountgroup' ? 'active' : ''; ?>">
		<a href="#">
			<i class="accountgroup_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_ACCOUNTING'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=accountgroup'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_ACCOUNTGROUP_LISTING'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=accountgroup_detail'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_ADD_ACCOUNTGROUP'); ?></span>
				</a>
			</li>
		</ul>
	</li>
	<?php } ?>

	<!-- statistic -->
	<li class="treeview <?php echo $groupSelected == 'statistic' ? 'active' : ''; ?>">
		<a href="#">
			<i class="statistic_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_STATISTIC'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TOTAL_VISITORS'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=pageview'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TOTAL_PAGEVIEWERS'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=turnover'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TOTAL_TURNOVER'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=avrgorder'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=amountorder'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=amountprice'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=amountspent'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=bestsell'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_BEST_SELLERS'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=popularsell'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_MOST_VISITED_PRODUCTS'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=newprod'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_NEWEST_PRODUCTS'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=statistic&layout=neworder'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS'); ?></span>
				</a>
			</li>
		</ul>
	</li>

	<!-- configuration -->
	<li class="treeview <?php echo $groupSelected == 'configuration' ? 'active' : ''; ?>">
		<a href="#">
			<i class="configuration_management"></i>
			<span><?php echo JText::_('COM_REDSHOP_CONFIG'); ?></span>
		</a>

		<ul class="treeview-menu">
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=configuration'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_RESHOP_CONFIGURATION'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&wizard=1'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_START_CONFIGURATION_WIZARD'); ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=configuration&layout=resettemplate'); ?>">
					<span><?php echo JText::_('COM_REDSHOP_RESET_TEMPLATE_LBL'); ?></span>
				</a>
			</li>
		</ul>
	</li>
</ul>
