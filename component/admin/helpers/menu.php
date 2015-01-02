<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class leftmenu
{
	public function  __construct()
	{
		$view      = JRequest::getVar('view');
		$redhelper = new redhelper;
		$cnt       = 6;

		if (USE_STOCKROOM)
		{
			$counter = $cnt + 1;
		}
		else
		{
			$counter = $cnt;
		}

		$acocnt = 11;

		if (ENABLE_BACKENDACCESS)
		{
			$acocnt = 12;
		}

		$ecoIsenable = JPluginHelper::isEnabled('economic');
		$ecocnt      = 16;

		if (ECONOMIC_INTEGRATION && $ecoIsenable)
		{
			$ecocnt = 17;
		}

		if (JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_date')
			|| JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_person')
			|| JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_company'))
		{
			$ecocnt = 18;
		}

		switch ($view)
		{
			case "product":
			case "product_detail":
			case "prices":
			case "mass_discount_detail":
			case "mass_discount":
				$selected = 0;
				break;

			case "category":
				$selected = 1;
				break;

			case "manufacturer":
			case "manufacturer_detail":
				$selected = 2;
				break;

			case "media":
				$selected = 3;
				break;

			case "order":
			case "order_detail":
			case "addorder_detail":
			case "orderstatus":
			case "orderstatus_detail":
			case "opsearch":
			case "barcode":

				$selected = 4;
				break;

			case "quotation":
			case "quotation_detail":
				$selected = 5;
				break;

			case "stockroom":
			case "stockroom_listing":
			case "stockimage":
				$selected = $cnt;
				break;

			case "delivery":
				$selected = $counter;
				break;

			case "supplier":
			case "supplier_detail":
				$selected = $counter + 1;
				break;

			case "discount":
				$selected = $counter + 2;
				break;

			case "giftcard":
			case "giftcard_detail":
				$selected = $counter + 3;
				break;

			case "voucher":
				$selected = $counter + 4;
				break;

			case "coupon":
			case "coupon_detail":
				$selected = $counter + 5;
				break;

			case "mail":
				$selected = $counter + 6;
				break;

			case "newsletter":
			case "newslettersubscr":
				$selected = $counter + 7;
				break;

			case "shipping":
			case "shipping_rate":
				$selected = $counter + 8;
				break;

			case "shipping_box":
				$selected = $counter + 9;
				break;

			case "shipping_detail":
				$selected = $counter + 10;
				break;

			case "wrapper":
				$selected = $counter + 11;
				break;

			case "user":
			case "shopper_group":
				$selected = $counter + 12;
				break;

			case "accessmanager":
				$selected = $counter + $acocnt + 1;
				break;

			case "tax_group":
			case "tax_group_detail":
			case "tax":
				$selected = $counter + $acocnt + 2;
				break;

			case "currency":
			case "currency_detail":
				$selected = $counter + $acocnt + 3;
				break;

			case "country":
			case "country_detail":
				$selected = $counter + $acocnt + 4;
				break;

			case "state":
			case "state_detail":
				$selected = $counter + $acocnt + 5;
				break;

			case "zipcode":
			case "zipcode_detail":
				$selected = $counter + $acocnt + 6;
				break;

			case "importexport":
			case "import":
			case "export":
			case "vmimport":
				$selected = $counter + $acocnt + 7;
				break;

			case "xmlimport":
			case "xmlexport":
				$selected = $counter + $acocnt + 8;
				break;

			case "fields":
			case "addressfields_listing":
				$selected = $counter + $acocnt + 9;
				break;

			case "template":
				$selected = $counter + $acocnt + 10;
				break;

			case "textlibrary":
				$selected = $counter + $acocnt + 11;
				break;

			case "catalog":
			case "catalog_request":
				$selected = $counter + $acocnt + 12;
				break;

			case "sample":
			case "sample_request":
				$selected = $counter + $acocnt + 13;
				break;

			case "producttags":
			case "producttags_detail":
				$selected = $counter + $acocnt + 14;
				break;

			case "attribute_set":
			case "attribute_set_detail":
				$selected = $counter + $acocnt + 15;
				break;

			case "integration":
				$selected = $counter + $acocnt + 16;
				break;

			case "question":
			case "question_detail":
			case "answer":
			case "answer_detail":
				$selected = $counter + $acocnt + 17;
				break;

			case "rating":
				$selected = $counter + $acocnt + 18;
				break;

			case "accountgroup":
			case "accountgroup_detail":
				$selected = $counter + $acocnt + 19;
				break;

			case "statistic":
				$selected = $counter + $acocnt + $ecocnt + 3;
				break;

			case "configuration":
			case 'update':
				$selected = $counter + $acocnt + $ecocnt + 4;
				break;

			case "customprint":
				$selected = $counter + $acocnt + $ecocnt + 5;
				break;

			default:
				$selected = 0;
				break;
		}

		echo JHtml::_('sliders.start', 'stat-pane', array('startOffset' => $selected));
		echo $this->generateHeader('COM_REDSHOP_PRODUCT_MANAGEMENT');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_PRODUCTS'), 'COM_REDSHOP_NEW PRODUCT'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=product', 'COM_REDSHOP_PRODUCT_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=product&task=listing', 'COM_REDSHOP_PRODUCT_PRICE_VIEW');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=product_detail', 'COM_REDSHOP_ADD_PRODUCT');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=mass_discount_detail', 'COM_REDSHOP_ADD_MASS_DISCOUNT');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=mass_discount', 'COM_REDSHOP_MASS_DISCOUNT');

			if (ECONOMIC_INTEGRATION == 1 && $ecoIsenable)
			{
				echo $this->generateMenuItem('index.php?option=com_redshop&view=product&layout=importproduct', 'COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC');

				if (ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC == 1)
				{
					echo $this->generateMenuItem('index.php?option=com_redshop&view=product&layout=importattribute', 'COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC');
				}
			} ?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATEGORY'), 'COM_REDSHOP_CATEGORY'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=category', 'COM_REDSHOP_CATEGORY_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=category_detail', 'COM_REDSHOP_ADD_CATEGORY');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_MANUFACTURER'), 'COM_REDSHOP_MANUFACTURER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=manufacturer', 'COM_REDSHOP_MANUFACTURER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=manufacturer_detail', 'COM_REDSHOP_ADD_MANUFACTURER');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_MEDIA'), 'COM_REDSHOP_MEDIA'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=media', 'COM_REDSHOP_MEDIA_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=media_detail', 'COM_REDSHOP_ADD_MEDIA_ITEM', 'COM_REDSHOP_BULK_UPLOAD');
			?>
		</table>
		<?php
		echo $this->generateHeader('COM_REDSHOP_ORDER');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ORDER'), 'COM_REDSHOP_ORDER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=order', 'COM_REDSHOP_ORDER_LISTING');
			echo $this->generateMenuItem($redhelper->sslLink('index.php?option=com_redshop&view=addorder_detail'), 'COM_REDSHOP_ADD_ORDER');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=order&layout=labellisting', 'COM_REDSHOP_DOWNLOAD_LABEL');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=orderstatus', 'COM_REDSHOP_ORDERSTATUS_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=opsearch', 'COM_REDSHOP_PRODUCT_ORDER_SEARCH');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=barcode', 'COM_REDSHOP_BARCODE');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=barcode&layout=barcode_order', 'COM_REDSHOP_BARCODE_ORDER');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_QUOTATION'), 'COM_REDSHOP_QUOTATION'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=quotation', 'COM_REDSHOP_QUOTATION_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=addquotation_detail', 'COM_REDSHOP_ADD_QUOTATION');
			?>
		</table>
		<?php

		if (USE_STOCKROOM != 0)
		{
			echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_STOCKROOM'), 'COM_REDSHOP_STOCKROOM'); ?>
			<table class="adminlist">
				<?php
				echo $this->generateMenuItem('index.php?option=com_redshop&view=stockroom', 'COM_REDSHOP_STOCKROOM_LISTING');
				echo $this->generateMenuItem('index.php?option=com_redshop&view=stockroom_detail', 'COM_REDSHOP_ADD_STOCKROOM');
				echo $this->generateMenuItem('index.php?option=com_redshop&view=stockroom_listing', 'COM_REDSHOP_STOCKROOM_AMOUNT_LISTING');
				echo $this->generateMenuItem('index.php?option=com_redshop&view=stockimage', 'COM_REDSHOP_STOCKIMAGE_LISTING');
				echo $this->generateMenuItem('index.php?option=com_redshop&view=stockimage_detail', 'COM_REDSHOP_ADD_STOCKIMAGE');

				if (ECONOMIC_INTEGRATION && $ecoIsenable)
				{
					echo $this->generateMenuItem('index.php?option=com_redshop&view=stockroom_detail&layout=importstock', 'COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC');
				}
			?>
			</table>
			<?php
		}

		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_DELIVERY_LISTING'), 'COM_REDSHOP_DELIVERY_LISTING'); ?>
		<table class="adminlist">
			<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=delivery', 'COM_REDSHOP_DELIVERY_LISTING'); ?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_SUPPLIER'), 'COM_REDSHOP_SUPPLIER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=supplier', 'COM_REDSHOP_SUPPLIER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=supplier_detail', 'COM_REDSHOP_ADD_SUPPLIER');
			?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_DISCOUNT');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_DISCOUNT'), 'COM_REDSHOP_DISCOUNT');
		?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=discount', 'COM_REDSHOP_DISCOUNT_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=discount_detail', 'COM_REDSHOP_ADD_DISCOUNT');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=discount&layout=product', 'COM_REDSHOP_DISCOUNT_PRODUCT_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=discount_detail&layout=product', 'COM_REDSHOP_ADD_DISCOUNT', 'COM_REDSHOP_ADD_DISCOUNT_PRODUCT');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_GIFTCARD'), 'COM_REDSHOP_GIFTCARD'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=giftcard', 'COM_REDSHOP_GIFTCARD_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=giftcard_detail', 'COM_REDSHOP_ADD_GIFTCARD');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_VOUCHER'), 'COM_REDSHOP_VOUCHER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=voucher', 'COM_REDSHOP_VOUCHER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=voucher_detail', 'COM_REDSHOP_ADD_VOUCHER');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_COUPON'), 'COM_REDSHOP_COUPON'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=coupon', 'COM_REDSHOP_COUPON_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=coupon_detail', 'COM_REDSHOP_ADD_COUPON');
			?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_COMMUNICATION');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_MAIL_CENTER'), 'COM_REDSHOP_MAIL_CENTER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=mail', 'COM_REDSHOP_MAIL_CENTER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=mail_detail', 'COM_REDSHOP_ADD_MAIL_CENTER');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_NEWSLETTER'), 'COM_REDSHOP_NEWSLETTER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=newsletter', 'COM_REDSHOP_NEWSLETTER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=newsletter_detail', 'COM_REDSHOP_ADD_NEWSLETTER');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=newslettersubscr', 'COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=newslettersubscr_detail', 'COM_REDSHOP_ADD_NEWSLETTER_SUBSCR');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=newsletter_detail&layout=statistics', 'COM_REDSHOP_NEWSLETTER_STATISTICS');
			?>
		</table>
		<?php
		echo $this->generateHeader('COM_REDSHOP_SHIPPING');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_SHIPPING_METHOD'), 'COM_REDSHOP_SHIPPING_METHOD');
		?>
		<table class="adminlist">
			<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=shipping', 'COM_REDSHOP_SHIPPING_METHOD_LISTING');

			if (ECONOMIC_INTEGRATION == 1 && $ecoIsenable)
			{
				echo $this->generateMenuItem('index.php?option=com_redshop&view=shipping&task=importeconomic', 'COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC');
			}
		?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_SHIPPING_BOX'), 'COM_REDSHOP_SHIPPING_BOX'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=shipping_box', 'COM_REDSHOP_SHIPPING_BOXES');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=shipping_box_detail', 'COM_REDSHOP_ADD_SHIPPING_BOXES');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_SHIPPING_DETAIL'), 'COM_REDSHOP_SHIPPING_DETAIL'); ?>
		<table class="adminlist">
			<?php echo $this->generateMenuItem('index.php?option=com_installer', 'COM_REDSHOP_ADD_SHIPPING_METHOD'); ?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_WRAPPER'), 'COM_REDSHOP_WRAPPER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=wrapper', 'COM_REDSHOP_WRAPPER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=wrapper_detail', 'COM_REDSHOP_ADD_WRAPPER');
			?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_USER');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_USER'), 'COM_REDSHOP_USER'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=user', 'COM_REDSHOP_USER_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=user_detail', 'COM_REDSHOP_ADD_USER');
			echo $this->generateMenuItem('javascript:userSync();', 'COM_REDSHOP_USER_SYNC');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=shopper_group', 'COM_REDSHOP_SHOPPER_GROUP_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=shopper_group_detail', 'COM_REDSHOP_ADD_SHOPPER_GROUP');
			?>
		</table>
		<script type="text/javascript">
			function userSync() {
				if (confirm("<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_SYNC');?>") == true)
					window.location = "index.php?option=com_redshop&view=user&sync=1";
			}
		</script>
		<?php

		if (ENABLE_BACKENDACCESS)
		{
			echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ACCESS_MANAGER'), 'COM_REDSHOP_ACCESS_MANAGER'); ?>
			<table class="adminlist">
				<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=accessmanager', 'COM_REDSHOP_ACCESS_MANAGER'); ?>
			</table>
		<?php
		}

		echo $this->generateHeader('COM_REDSHOP_VAT_AND_CURRENCY');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_TAX_GROUP'), 'COM_REDSHOP_TAX_GROUP'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=tax_group', 'COM_REDSHOP_TAX_GROUP_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=tax_group_detail', 'COM_REDSHOP_TAX_GROUP_DETAIL');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CURRENCY'), 'COM_REDSHOP_CURRENCY'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=currency', 'COM_REDSHOP_CURRENCY_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=currency_detail', 'COM_REDSHOP_ADD_CURRENCY');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_COUNTRY'), 'COM_REDSHOP_COUNTRY'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=country', 'COM_REDSHOP_COUNTRY_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=country_detail', 'COM_REDSHOP_ADD_COUNTRY');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_STATE'), 'COM_REDSHOP_STATE'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=state', 'COM_REDSHOP_STATE_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=state_detail', 'COM_REDSHOP_ADD_STATE');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ZIPCODE'), 'COM_REDSHOP_ZIPCODE'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=zipcode', 'COM_REDSHOP_ZIPCODE_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=zipcode_detail', 'COM_REDSHOP_ADD_ZIPCODE');
			?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_IMPORT_EXPORT');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_IMPORT_EXPORT'), 'COM_REDSHOP_IMPORT_EXPORT'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=import', 'COM_REDSHOP_DATA_IMPORT');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=export', 'COM_REDSHOP_DATA_EXPORT');
			echo $this->generateMenuItem('javascript:vmImport();', 'COM_REDSHOP_IMPORT_FROM_VM');
			?>
		</table>
		<script type="text/javascript">
			function vmImport() {
				if (confirm("<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_IMPORT_VM');?>") == true)
					window.location = "index.php?option=com_redshop&view=import&vm=1";
			}
		</script>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_XML_IMPORT_EXPORT'), 'COM_REDSHOP_XML_IMPORT_EXPORT'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=xmlimport', 'COM_REDSHOP_XML_IMPORT');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=xmlexport', 'COM_REDSHOP_XML_EXPORT');
			?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_CUSTOMIZATION');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_FIELDS'), 'COM_REDSHOP_FIELDS'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=fields', 'COM_REDSHOP_FIELDS_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=fields_detail', 'COM_REDSHOP_ADD_FIELD');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_TEMPLATE'), 'COM_REDSHOP_TEMPLATE'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=template', 'COM_REDSHOP_TEMPLATE_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=template_detail', 'COM_REDSHOP_ADD_TEMPLATE');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_TEXT_LIBRARY'), 'COM_REDSHOP_TEXT_LIBRARY'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=textlibrary', 'COM_REDSHOP_TEXT_LIBRARY_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=textlibrary_detail', 'COM_REDSHOP_ADD_TEXT_LIBRARY_TAG');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CATALOG_MANAGEMENT'), 'COM_REDSHOP_CATALOG_MANAGEMENT'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=catalog', 'COM_REDSHOP_CATALOG');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=catalog_request', 'COM_REDSHOP_CATALOG_REQUEST');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT'), 'COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=sample', 'COM_REDSHOP_CATALOG_PRODUCT_SAMPLE');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=sample_request', 'COM_REDSHOP_SAMPLE_REQUEST');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_TAGS'), 'COM_REDSHOP_TAGS'); ?>
		<table class="adminlist">
			<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=producttags', 'COM_REDSHOP_TAGS_LISTING'); ?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ATTRIBUTE_BANK'), 'COM_REDSHOP_ATTRIBUTE_BANK'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=attribute_set', 'COM_REDSHOP_ATTRIBUTE_SET_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=attribute_set_detail', 'COM_REDSHOP_ADD_ATTRIBUTE_SET');
			?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_INTEGRATION'), 'COM_REDSHOP_INTEGRATION'); ?>
		<table class="adminlist">
			<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=integration&task=googlebase', 'COM_REDSHOP_GOOGLEBASE'); ?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_CUSTOMER_INPUT');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_QUESTION'), 'COM_REDSHOP_QUESTION'); ?>
		<table class="adminlist">
			<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=question', 'COM_REDSHOP_QUESTION_LISTING'); ?>
		</table>
		<?php echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_REVIEW'), 'COM_REDSHOP_REVIEW'); ?>
		<table class="adminlist">
		<?php echo $this->generateMenuItem('index.php?option=com_redshop&view=rating', 'COM_REDSHOP_RATING_REVIEW'); ?>
		</table>
		<?php

		if (ECONOMIC_INTEGRATION && $ecoIsenable)
		{
			echo $this->generateHeader('COM_REDSHOP_ACCOUNTING');
			echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'), 'COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP'); ?>
			<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=accountgroup', 'COM_REDSHOP_ACCOUNTGROUP_LISTING');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=accountgroup_detail', 'COM_REDSHOP_ADD_ACCOUNTGROUP');
			?>
			</table><?php
		}

		echo $this->generateHeader('COM_REDSHOP_STATISTIC');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_STATISTIC'), 'COM_REDSHOP_STATISTIC'); ?>
		<table class="adminlist">
			<?php
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic', 'COM_REDSHOP_TOTAL_VISITORS');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=pageview', 'COM_REDSHOP_TOTAL_PAGEVIEWERS');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=turnover', 'COM_REDSHOP_TOTAL_TURNOVER');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=avrgorder', 'COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=amountorder', 'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=amountprice', 'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=amountspent', 'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=bestsell', 'COM_REDSHOP_BEST_SELLERS');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=popularsell', 'COM_REDSHOP_MOST_VISITED_PRODUCTS');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=newprod', 'COM_REDSHOP_NEWEST_PRODUCTS');
			echo $this->generateMenuItem('index.php?option=com_redshop&view=statistic&layout=neworder', 'COM_REDSHOP_NEWEST_ORDERS');
			?>
		</table>
		<?php echo $this->generateHeader('COM_REDSHOP_CONFIG');
		echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CONFIG'), 'COM_REDSHOP_CONFIG'); ?>
		<table class="adminlist">
		<?php
		echo $this->generateMenuItem('index.php?option=com_redshop&view=configuration', 'COM_REDSHOP_RESHOP_CONFIGURATION');
		echo $this->generateMenuItem('index.php?option=com_redshop&wizard=1', 'COM_REDSHOP_START_CONFIGURATION_WIZARD');
		echo $this->generateMenuItem('index.php?option=com_redshop&view=configuration&layout=resettemplate', 'COM_REDSHOP_RESET_TEMPLATE_LBL');
		echo $this->generateMenuItem('index.php?option=com_redshop&view=update', 'COM_REDSHOP_UPDATE_TITLE');
		?>
		</table><?php

		if (JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_date')
			|| JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_person')
			|| JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_company'))
		{
			echo $this->generateHeader('COM_REDSHOP_CUSTOM_VIEWS');
			echo JHtml::_('sliders.panel', JText::_('COM_REDSHOP_CUSTOM_VIEWS'), 'COM_REDSHOP_CUSTOM_VIEWS'); ?>
			<table class="adminlist">
			<?php

			JPluginHelper::importPlugin('redshop_custom_views');
			$dispatcher = JDispatcher::getInstance();
			$data       = $dispatcher->trigger('getMenuLink');

			for ($d = 0; $d < count($data); $d++)
			{
				echo $this->generateMenuItem(
					JRoute::_('index.php?option=com_redshop&view=customprint&layout=customview&printoption=' . $data[$d]['name']),
					$data[$d]['title']
				);
			}
			?>
			</table>
			<?php
		}

		echo JHtml::_('sliders.end');
	}

	/**
	 * Generate Menu Item
	 *
	 * @param   string  $link   Link
	 * @param   string  $title  Title
	 * @param   string  $text   Text in link
	 *
	 * @return string
	 */
	private function generateMenuItem($link, $title, $text = '')
	{
		if ($text == '')
		{
			$text = $title;
		}

		return '<tr><td><a href="' . $link . '" title="' . JText::_($title) . '">' . JText::_($text) . '</a></td></tr>';
	}

	/**
	 * Generate slider header
	 *
	 * @param   string  $header  Header text
	 *
	 * @return string
	 */
	private function generateHeader($header)
	{
		return '</div></div><div><div><table><tr><td class="distitle">' . JText::_($header) . '</td></tr></table>';
	}
}
