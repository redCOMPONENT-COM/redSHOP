<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class leftmenu
{
	public function  __construct()
	{
		jimport('joomla.html.pane');

		$view      = JRequest::getVar('view');
		$redhelper = new redhelper;
		$stk       = 0;
		$cnt       = 6;

		if (USE_CONTAINER)
		{
			if (USE_STOCKROOM)
			{
				$stk     = $cnt + 1;
				$counter = $cnt + 2;
			}
			else
			{
				$counter = $cnt + 1;
			}
		}
		else
		{
			if (USE_STOCKROOM)
			{
				$stk     = $cnt;
				$counter = $cnt + 1;
			}
			else
			{
				$counter = $cnt;
			}
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

			case "container":
			case "product_container":
				$selected = $stk;
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
				$selected = $counter + $acocnt + $ecocnt + 4;
				break;

			case "customprint":
				$selected = $counter + $acocnt + $ecocnt + 5;
				break;

			default:
				$selected = 0;
				break;
		}

		$pane = @JPane::getInstance('sliders', array('startOffset' => $selected));
		echo $pane->startPane('stat-pane');
		?>
		<table>
		<tr>
			<td class="distitle"><?php echo JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT'); ?></td>
		</tr>
		</table><?php
		$title = JText::_('COM_REDSHOP_PRODUCTS');
		echo $pane->startPanel($title, 'COM_REDSHOP_NEW PRODUCT');    ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=product';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_PRODUCT_LISTING') . '">' . JText::_('COM_REDSHOP_PRODUCT_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=product&task=listing';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_PRODUCT_PRICE_VIEW') . '">' . JText::_('COM_REDSHOP_PRODUCT_PRICE_VIEW') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=product_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_PRODUCT') . '">' . JText::_('COM_REDSHOP_ADD_PRODUCT') . '</a>'; ?>
				</td>
			</tr>


			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=mass_discount_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_MASS_DISCOUNT') . '">' . JText::_('COM_REDSHOP_ADD_MASS_DISCOUNT') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=mass_discount';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_MASS_DISCOUNT') . '">' . JText::_('COM_REDSHOP_MASS_DISCOUNT') . '</a>'; ?>
				</td>
			</tr>
			<?php
			if (ECONOMIC_INTEGRATION == 1 && $ecoIsenable)
			{
				?>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=product&layout=importproduct';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC') . '">' . JText::_('COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC') . '</a>'; ?>
					</td>
				</tr>
				<?php
				if (ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC == 1)
				{
					?>
					<tr>
						<td><?php
							$link = 'index.php?option=com_redshop&view=product&layout=importattribute';
							echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC') . '">' . JText::_('COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC') . '</a>'; ?>
						</td>
					</tr>
				<?php
				}
			}    ?>
		</table>
		<?php

		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_CATEGORY');
		echo $pane->startPanel($title, 'COM_REDSHOP_CATEGORY');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=category';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CATEGORY_LISTING') . '">'
						. JText::_('COM_REDSHOP_CATEGORY_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=category_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_CATEGORY') . '">' . JText::_('COM_REDSHOP_ADD_CATEGORY')
						. '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_MANUFACTURER');
		echo $pane->startPanel($title, 'COM_REDSHOP_MANUFACTURER');    ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=manufacturer';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_MANUFACTURER_LISTING') . '">'
						. JText::_('COM_REDSHOP_MANUFACTURER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=manufacturer_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_MANUFACTURER') . '">'
						. JText::_('COM_REDSHOP_ADD_MANUFACTURER') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php    echo $pane->endPanel();


		$title = JText::_('COM_REDSHOP_MEDIA');
		echo $pane->startPanel($title, 'COM_REDSHOP_MEDIA');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=media';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_MEDIA_LISTING') . '">'
						. JText::_('COM_REDSHOP_MEDIA_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=media_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_MEDIA_ITEM') . '">'
						. JText::_('COM_REDSHOP_BULK_UPLOAD') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();
		?>
		<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_ORDER'); ?></td>
			</tr>
		</table>

		<?php     $title = JText::_('COM_REDSHOP_ORDER');
		echo $pane->startPanel($title, 'COM_REDSHOP_ORDER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=order';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ORDER_LISTING') . '">'
						. JText::_('COM_REDSHOP_ORDER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=addorder_detail';
					$link = $redhelper->sslLink($link);
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_ORDER') . '">'
						. JText::_('COM_REDSHOP_ADD_ORDER') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=order&layout=labellisting';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_DOWNLOAD_LABEL') . '">'
						. JText::_('COM_REDSHOP_DOWNLOAD_LABEL') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=orderstatus';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ORDERSTATUS_LISTING') . '">'
						. JText::_('COM_REDSHOP_ORDERSTATUS_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=opsearch';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH') . '">'
						. JText::_('COM_REDSHOP_PRODUCT_ORDER_SEARCH') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=barcode';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_BARCODE') . '">'
						. JText::_('COM_REDSHOP_BARCODE') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=barcode&layout=barcode_order';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_BARCODE_ORDER') . '">'
						. JText::_('COM_REDSHOP_BARCODE_ORDER') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_QUOTATION');
		echo $pane->startPanel($title, 'COM_REDSHOP_QUOTATION'); ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=quotation';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_QUOTATION_LISTING') . '">'
						. JText::_('COM_REDSHOP_QUOTATION_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=addquotation_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_QUOTATION') . '">'
						. JText::_('COM_REDSHOP_ADD_QUOTATION') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		if (USE_STOCKROOM != 0)
		{
			$title = JText::_('COM_REDSHOP_STOCKROOM');
			echo $pane->startPanel($title, 'COM_REDSHOP_STOCKROOM');
			?>
			<table class="adminlist">
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=stockroom';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_STOCKROOM_LISTING') . '">'
							. JText::_('COM_REDSHOP_STOCKROOM_LISTING') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=stockroom_detail';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_STOCKROOM') . '">'
							. JText::_('COM_REDSHOP_ADD_STOCKROOM') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=stockroom_listing';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_STOCKROOM_AMOUNT_LISTING') . '">'
							. JText::_('COM_REDSHOP_STOCKROOM_AMOUNT_LISTING') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=stockimage';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_STOCKIMAGE_LISTING') . '">'
							. JText::_('COM_REDSHOP_STOCKIMAGE_LISTING') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=stockimage_detail';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_STOCKIMAGE') . '">'
							. JText::_('COM_REDSHOP_ADD_STOCKIMAGE') . '</a>'; ?>
					</td>
				</tr>
			<?php
				if (ECONOMIC_INTEGRATION && $ecoIsenable)
				{
					?>
					<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=stockroom_detail&layout=importstock';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC') . '">'
							. JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC') . '</a>'; ?>
					</td>
					</tr>
			<?php
				}
			?>
			</table>
			<?php
			echo $pane->endPanel();
		}

		if (USE_CONTAINER != 0)
		{
			$title = JText::_('COM_REDSHOP_CONTAINER');
			echo $pane->startPanel($title, 'COM_REDSHOP_CONTAINER');?>
			<table class="adminlist">
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=container';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CONTAINER_LISTING') . '">'
							. JText::_('COM_REDSHOP_CONTAINER_LISTING') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=container_detail';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_CONTAINER') . '">'
							. JText::_('COM_REDSHOP_ADD_CONTAINER') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=product_container';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER') . '">'
							. JText::_('COM_REDSHOP_CONTAINER_PRE_ORDER') . '</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=product_container&container=1';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CONTAINER_ORDER_PRODUCTS') . '">'
							. JText::_('COM_REDSHOP_CONTAINER_ORDER_PRODUCTS') . '</a>'; ?>
					</td>
				</tr>
			</table>
			<?php

			echo $pane->endPanel();
		}

		$title = JText::_('COM_REDSHOP_DELIVERY_LISTING');
		echo $pane->startPanel($title, 'COM_REDSHOP_DELIVERY_LISTING');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=delivery';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_DELIVERY_LISTING') . '">'
						. JText::_('COM_REDSHOP_DELIVERY_LISTING') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_SUPPLIER');
		echo $pane->startPanel($title, 'COM_REDSHOP_SUPPLIER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=supplier';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_SUPPLIER_LISTING') . '">'
						. JText::_('COM_REDSHOP_SUPPLIER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=supplier_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_SUPPLIER') . '">'
						. JText::_('COM_REDSHOP_ADD_SUPPLIER') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		?>
		<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_DISCOUNT'); ?></td>
			</tr>
		</table>

		<?php

		$title = JText::_('COM_REDSHOP_DISCOUNT');
		echo $pane->startPanel($title, 'COM_REDSHOP_DISCOUNT');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=discount';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_DISCOUNT_LISTING') . '">'
						. JText::_('COM_REDSHOP_DISCOUNT_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=discount_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_DISCOUNT') . '">'
						. JText::_('COM_REDSHOP_ADD_DISCOUNT') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=discount&layout=product';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_DISCOUNT_LISTING') . '">'
						. JText::_('COM_REDSHOP_DISCOUNT_PRODUCT_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=discount_detail&layout=product';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_DISCOUNT') . '">'
						. JText::_('COM_REDSHOP_ADD_DISCOUNT_PRODUCT') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_GIFTCARD');
		echo $pane->startPanel($title, 'COM_REDSHOP_GIFTCARD');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=giftcard';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_GIFTCARD_LISTING') . '">'
						. JText::_('COM_REDSHOP_GIFTCARD_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=giftcard_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_GIFTCARD') . '">'
						. JText::_('COM_REDSHOP_ADD_GIFTCARD') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_VOUCHER');
		echo $pane->startPanel($title, 'COM_REDSHOP_VOUCHER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=voucher';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_VOUCHER_LISTING') . '">'
						. JText::_('COM_REDSHOP_VOUCHER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=voucher_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_VOUCHER') . '">'
						. JText::_('COM_REDSHOP_ADD_VOUCHER') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_COUPON');
		echo $pane->startPanel($title, 'COM_REDSHOP_COUPON');?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=coupon';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_COUPON_LISTING') . '">'
						. JText::_('COM_REDSHOP_COUPON_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=coupon_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_COUPON') . '">'
						. JText::_('COM_REDSHOP_ADD_COUPON') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();



		?>
		<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_COMMUNICATION'); ?></td>
			</tr>
		</table>

		<?php
		$title = JText::_('COM_REDSHOP_MAIL_CENTER');
		echo $pane->startPanel($title, 'COM_REDSHOP_MAIL_CENTER');?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=mail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_MAIL_CENTER_LISTING') . '">'
						. JText::_('COM_REDSHOP_MAIL_CENTER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=mail_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_MAIL_CENTER') . '">'
						. JText::_('COM_REDSHOP_ADD_MAIL_CENTER') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php    echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_NEWSLETTER');
		echo $pane->startPanel($title, 'COM_REDSHOP_NEWSLETTER');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=newsletter';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_NEWSLETTER_LISTING') . '">'
						. JText::_('COM_REDSHOP_NEWSLETTER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=newsletter_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_NEWSLETTER') . '">'
						. JText::_('COM_REDSHOP_ADD_NEWSLETTER') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=newslettersubscr';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING') . '">'
						. JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=newslettersubscr_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_NEWSLETTER_SUBSCR') . '">'
						. JText::_('COM_REDSHOP_ADD_NEWSLETTER_SUBSCR') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=newsletter_detail&layout=statistics';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_NEWSLETTER_STATISTICS') . '">'
						. JText::_('COM_REDSHOP_NEWSLETTER_STATISTICS') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php    echo $pane->endPanel();

		?>
		<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_SHIPPING'); ?></td>
			</tr>
		</table>

		<?php

		$title = JText::_('COM_REDSHOP_SHIPPING_METHOD');
		echo $pane->startPanel($title, 'COM_REDSHOP_SHIPPING_METHOD');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=shipping';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_SHIPPING_METHOD_LISTING') . '">'
						. JText::_('COM_REDSHOP_SHIPPING_METHOD_LISTING') . '</a>'; ?>
				</td>
			</tr>


		<?php
			if (ECONOMIC_INTEGRATION == 1 && $ecoIsenable)
			{
		?>
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=shipping&task=importeconomic';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC') . '">'
							. JText::_('COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC') . '</a>'; ?>
					</td>
				</tr>
		<?php
			}
		?>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_SHIPPING_BOX');
		echo $pane->startPanel($title, 'COM_REDSHOP_SHIPPING_BOX');
		?>
		<table class="adminlist">


			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=shipping_box';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_SHIPPING_BOXES') . '">'
						. JText::_('COM_REDSHOP_SHIPPING_BOXES') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=shipping_box_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_SHIPPING_BOXES') . '">'
						. JText::_('COM_REDSHOP_ADD_SHIPPING_BOXES') . '</a>'; ?>
				</td>
			</tr>

		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_SHIPPING_DETAIL');
		echo $pane->startPanel($title, 'COM_REDSHOP_SHIPPING_DETAIL');
		?>
		<table class="adminlist">


			<tr>
				<td><?php
					$link = 'index.php?option=com_installer';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_SHIPPING_METHOD') . '">'
						. JText::_('COM_REDSHOP_ADD_SHIPPING_METHOD') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_WRAPPER');
		echo $pane->startPanel($title, 'COM_REDSHOP_WRAPPER');
		?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=wrapper';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_WRAPPER_LISTING') . '">'
					. JText::_('COM_REDSHOP_WRAPPER_LISTING') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=wrapper_detail';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_WRAPPER') . '">'
					. JText::_('COM_REDSHOP_ADD_WRAPPER') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();?>
		<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_USER'); ?></td>
			</tr>
		</table>

		<?php

		$title = JText::_('COM_REDSHOP_USER');
		echo $pane->startPanel($title, 'COM_REDSHOP_USER'); ?>
		<table class="adminlist">

			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=user';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_USER_LISTING') . '">'
						. JText::_('COM_REDSHOP_USER_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=user_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_USER') . '">'
						. JText::_('COM_REDSHOP_ADD_USER') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=user&sync=1';
					echo '<a href="javascript:userSync();" title="' . JText::_('COM_REDSHOP_USER_SYNC') . '">'
						. JText::_('COM_REDSHOP_USER_SYNC') . '</a>'; ?>
					<script type="text/javascript">
						function userSync() {
							if (confirm("<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_SYNC');?>") == true)
								window.location = "<?php echo $link;?>";
						}</script>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=shopper_group';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_SHOPPER_GROUP_LISTING') . '">'
						. JText::_('COM_REDSHOP_SHOPPER_GROUP_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=shopper_group_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_SHOPPER_GROUP') . '">'
						. JText::_('COM_REDSHOP_ADD_SHOPPER_GROUP') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php    echo $pane->endPanel();

		if (ENABLE_BACKENDACCESS):
			$title = JText::_('COM_REDSHOP_ACCESS_MANAGER');
			echo $pane->startPanel($title, 'COM_REDSHOP_ACCESS_MANAGER');
			?>
			<table class="adminlist">
				<tr>
					<td><?php
						$link = 'index.php?option=com_redshop&view=accessmanager';
						echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ACCESS_MANAGER') . '">'
							. JText::_('COM_REDSHOP_ACCESS_MANAGER') . '</a>'; ?>
					</td>
				</tr>
			</table>    <?php
			echo $pane->endPanel();
		endif;?>
		<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_VAT_AND_CURRENCY'); ?></td>
			</tr>
		</table>    <?php
		$title = JText::_('COM_REDSHOP_TAX_GROUP');
		echo $pane->startPanel($title, 'COM_REDSHOP_TAX_GROUP');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=tax_group';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TAX_GROUP_LISTING') . '">'
						. JText::_('COM_REDSHOP_TAX_GROUP_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=tax_group_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TAX_GROUP_DETAIL') . '">'
						. JText::_('COM_REDSHOP_TAX_GROUP_DETAIL') . '</a>'; ?>
				</td>
			</tr>
		</table>    <?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_CURRENCY');
		echo $pane->startPanel($title, 'COM_REDSHOP_CURRENCY');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=currency';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CURRENCY_LISTING') . '">'
						. JText::_('COM_REDSHOP_CURRENCY_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=currency_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_CURRENCY') . '">'
						. JText::_('COM_REDSHOP_ADD_CURRENCY') . '</a>'; ?>
				</td>
			</tr>
		</table>        <?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_COUNTRY');
		echo $pane->startPanel($title, 'COM_REDSHOP_COUNTRY');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=country';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_COUNTRY_LISTING') . '">'
						. JText::_('COM_REDSHOP_COUNTRY_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=country_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_COUNTRY') . '">'
						. JText::_('COM_REDSHOP_ADD_COUNTRY') . '</a>'; ?>
				</td>
			</tr>
		</table>        <?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_STATE');
		echo $pane->startPanel($title, 'COM_REDSHOP_STATE');        ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=state';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_STATE_LISTING') . '">'
					. JText::_('COM_REDSHOP_STATE_LISTING') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=state_detail';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_STATE') . '">'
					. JText::_('COM_REDSHOP_ADD_STATE') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_ZIPCODE');
		echo $pane->startPanel($title, 'COM_REDSHOP_ZIPCODE');    ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=zipcode';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ZIPCODE_LISTING') . '">'
						. JText::_('COM_REDSHOP_ZIPCODE_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=zipcode_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_ZIPCODE') . '">'
						. JText::_('COM_REDSHOP_ADD_ZIPCODE') . '</a>'; ?>
				</td>
			</tr>
		</table>        <?php
		echo $pane->endPanel();?>
		<table>
		<tr>
			<td class="distitle"><?php echo JText::_('COM_REDSHOP_IMPORT_EXPORT'); ?></td>
		</tr>
		</table><?php
		$title = JText::_('COM_REDSHOP_IMPORT_EXPORT');
		echo $pane->startPanel($title, 'COM_REDSHOP_IMPORT_EXPORT');    ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=import';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_DATA_IMPORT') . '">'
						. JText::_('COM_REDSHOP_DATA_IMPORT') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=export';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_DATA_EXPORT') . '">'
						. JText::_('COM_REDSHOP_DATA_EXPORT') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=import&vm=1';
					echo '<a href="javascript:vmImport();" title="' . JText::_('COM_REDSHOP_IMPORT_FROM_VM') . '">'
						. JText::_('COM_REDSHOP_IMPORT_FROM_VM') . '</a>'; ?>
					<script type="text/javascript">
						function vmImport() {
							if (confirm("<?php echo JText::_('COM_REDSHOP_DO_YOU_WANT_TO_IMPORT_VM');?>") == true)
								window.location = "<?php echo $link;?>";
						}
					</script>
				</td>
			</tr>
		</table>        <?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_XML_IMPORT_EXPORT');
		echo $pane->startPanel($title, 'COM_REDSHOP_XML_IMPORT_EXPORT');    ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=xmlimport';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_XML_IMPORT') . '">'
					. JText::_('COM_REDSHOP_XML_IMPORT') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=xmlexport';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_XML_EXPORT') . '">'
					. JText::_('COM_REDSHOP_XML_EXPORT') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();   ?>
		<table>
		<tr>
			<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOMIZATION'); ?></td>
		</tr>
		</table><?php
		$title = JText::_('COM_REDSHOP_FIELDS');
		echo $pane->startPanel($title, 'COM_REDSHOP_FIELDS');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=fields';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_FIELDS_LISTING') . '">'
						. JText::_('COM_REDSHOP_FIELDS_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=fields_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_FIELD') . '">'
						. JText::_('COM_REDSHOP_ADD_FIELD') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_TEMPLATE');
		echo $pane->startPanel($title, 'COM_REDSHOP_TEMPLATE');        ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=template';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TEMPLATE_LISTING') . '">'
					. JText::_('COM_REDSHOP_TEMPLATE_LISTING') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=template_detail';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_TEMPLATE') . '">'
					. JText::_('COM_REDSHOP_ADD_TEMPLATE') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_TEXT_LIBRARY');
		echo $pane->startPanel($title, 'COM_REDSHOP_TEXT_LIBRARY');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=textlibrary';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TEXT_LIBRARY_LISTING') . '">'
						. JText::_('COM_REDSHOP_TEXT_LIBRARY_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=textlibrary_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_TEXT_LIBRARY_TAG') . '">'
						. JText::_('COM_REDSHOP_ADD_TEXT_LIBRARY_TAG') . '</a>'; ?>
				</td>
			</tr>
		</table>    <?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_CATALOG_MANAGEMENT');
		echo $pane->startPanel($title, 'COM_REDSHOP_CATALOG_MANAGEMENT');        ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=catalog';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CATALOG') . '">'
					. JText::_('COM_REDSHOP_CATALOG') . '</a>'; ?>
			</td>
		</tr>

		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=catalog_request';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CATALOG_REQUEST') . '">'
					. JText::_('COM_REDSHOP_CATALOG_REQUEST') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();

		$title = JText::_('COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT');
		echo $pane->startPanel($title, 'COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT');    ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=sample';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_CATALOG_PRODUCT_SAMPLE') . '">'
					. JText::_('COM_REDSHOP_CATALOG_PRODUCT_SAMPLE') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=sample_request';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_SAMPLE_REQUEST') . '">'
					. JText::_('COM_REDSHOP_SAMPLE_REQUEST') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_TAGS');
		echo $pane->startPanel($title, 'COM_REDSHOP_TAGS');    ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=producttags';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TAGS_LISTING') . '">'
					. JText::_('COM_REDSHOP_TAGS_LISTING') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_ATTRIBUTE_BANK');
		echo $pane->startPanel($title, 'COM_REDSHOP_ATTRIBUTE_BANK');    ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=attribute_set';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ATTRIBUTE_SET_LISTING') . '">'
					. JText::_('COM_REDSHOP_ATTRIBUTE_SET_LISTING') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=attribute_set_detail';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_ATTRIBUTE_SET') . '">'
					. JText::_('COM_REDSHOP_ADD_ATTRIBUTE_SET') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_INTEGRATION');
		echo $pane->startPanel($title, 'COM_REDSHOP_INTEGRATION');    ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=integration&task=googlebase';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_GOOGLEBASE') . '">'
						. JText::_('COM_REDSHOP_GOOGLEBASE') . '</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();   ?>
		<table>
		<tr>
			<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOMER_INPUT'); ?></td>
		</tr>
		</table><?php
		$title = JText::_('COM_REDSHOP_QUESTION');
		echo $pane->startPanel($title, 'COM_REDSHOP_QUESTION');        ?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=question';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_QUESTION_LISTING') . '">'
						. JText::_('COM_REDSHOP_QUESTION_LISTING') . '</a>'; ?>
				</td>
			</tr>
		</table>   <?php
		echo $pane->endPanel();
		$title = JText::_('COM_REDSHOP_REVIEW');
		echo $pane->startPanel($title, 'COM_REDSHOP_REVIEW');    ?>
		<table class="adminlist">

		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=rating';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_RATING_REVIEW') . '">'
					. JText::_('COM_REDSHOP_RATING_REVIEW') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();

		if (ECONOMIC_INTEGRATION && $ecoIsenable)
		{
			?>
			<table>
			<tr>
				<td class="distitle"><?php echo JText::_('COM_REDSHOP_ACCOUNTING'); ?></td>
			</tr>
			</table><?php
			$title = JText::_('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP');
			echo $pane->startPanel($title, 'COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP');            ?>
			<table class="adminlist">
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=accountgroup';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ACCOUNTGROUP_LISTING') . '">'
						. JText::_('COM_REDSHOP_ACCOUNTGROUP_LISTING') . '</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link = 'index.php?option=com_redshop&view=accountgroup_detail';
					echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_ADD_ACCOUNTGROUP') . '">'
						. JText::_('COM_REDSHOP_ADD_ACCOUNTGROUP') . '</a>'; ?>
				</td>
			</tr>
			</table><?php
			echo $pane->endPanel();
		}?>
		<table>
		<tr>
			<td class="distitle"><?php echo JText::_('COM_REDSHOP_STATISTIC'); ?></td>
		</tr>
		</table><?php
		$title = JText::_('COM_REDSHOP_STATISTIC');
		echo $pane->startPanel($title, 'COM_REDSHOP_STATISTIC');    ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TOTAL_VISITORS') . '">'
					. JText::_('COM_REDSHOP_TOTAL_VISITORS') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=pageview');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TOTAL_PAGEVIEWERS') . '">'
					. JText::_('COM_REDSHOP_TOTAL_PAGEVIEWERS') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=turnover');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TOTAL_TURNOVER') . '">'
					. JText::_('COM_REDSHOP_TOTAL_TURNOVER') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=avrgorder');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER') . '">'
					. JText::_('COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=amountorder');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER') . '">'
					. JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=amountprice');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER') . '">'
					. JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=amountspent');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL') . '">'
					. JText::_('COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=bestsell');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_BEST_SELLERS') . '">'
					. JText::_('COM_REDSHOP_BEST_SELLERS') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=popularsell');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_MOST_VISITED_PRODUCTS') . '">'
					. JText::_('COM_REDSHOP_MOST_VISITED_PRODUCTS') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=newprod');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_NEWEST_PRODUCTS') . '">'
					. JText::_('COM_REDSHOP_NEWEST_PRODUCTS') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = JRoute::_('index.php?option=com_redshop&view=statistic&layout=neworder');
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_NEWEST_ORDERS') . '">'
					. JText::_('COM_REDSHOP_NEWEST_ORDERS') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();    ?>
		<table>
		<tr>
			<td class="distitle"><?php echo JText::_('COM_REDSHOP_CONFIG'); ?></td>
		</tr>
		</table><?php
		$title = JText::_('COM_REDSHOP_CONFIG');
		echo $pane->startPanel($title, 'COM_REDSHOP_CONFIG');    ?>
		<table class="adminlist">
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=configuration';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_RESHOP_CONFIGURATION') . '">'
					. JText::_('COM_REDSHOP_RESHOP_CONFIGURATION') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&wizard=1';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_START_CONFIGURATION_WIZARD') . '">'
					. JText::_('COM_REDSHOP_START_CONFIGURATION_WIZARD') . '</a>'; ?>
			</td>
		</tr>
		<tr>
			<td><?php
				$link = 'index.php?option=com_redshop&view=configuration&layout=resettemplate';
				echo '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_RESET_TEMPLATE_LBL') . '">'
					. JText::_('COM_REDSHOP_RESET_TEMPLATE_LBL') . '</a>'; ?>
			</td>
		</tr>
		</table><?php
		echo $pane->endPanel();

		if (JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_date')
			|| JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_person')
			|| JPluginHelper::isEnabled('redshop_custom_views', 'rs_custom_views_company'))
		{
			?>
			<table>
				<tr>
					<td class="distitle"><?php echo JText::_('COM_REDSHOP_CUSTOM_VIEWS'); ?></td>
				</tr>
			</table>    <?php
			$title = JText::_('COM_REDSHOP_CUSTOM_VIEWS');
			echo $pane->startPanel($title, 'COM_REDSHOP_CUSTOM_VIEWS');    ?>
			<table class="adminlist">
			<?php

			JPluginHelper::importPlugin('redshop_custom_views');
			$dispatcher = JDispatcher::getInstance();
			$data       = $dispatcher->trigger('getMenuLink');

			for ($d = 0; $d < count($data); $d++)
			{
				?>
                <tr>
                    <td>
              		<?php

						$link = JRoute::_('index.php?option=com_redshop&view=customprint&layout=customview&printoption=' . $data[$d]['name'] . '');
						echo '<a href="' . $link . '" title="' . $data[$d]['title'] . '">' . $data[$d]['title'] . '</a>';
					?>
                    </td>
                </tr><?php
			}
			?>
			</table>
			<?php
			echo $pane->endPanel();
		}

		echo '</div>';
	}
}
