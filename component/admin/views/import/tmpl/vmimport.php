<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$jinput = JFactory::getApplication()->input;

$product_total      = $jinput->getInt('product_total', 0);
$product_inserted   = $jinput->getInt('product_inserted', 0);
$product_updated    = $jinput->getInt('product_updated', 0);
$shopper_total      = $jinput->getInt('shopper_total', 0);
$orders_total       = $jinput->getInt('orders_total', 0);
$customer_total     = $jinput->getInt('customer_total', 0);
$status_total       = $jinput->getInt('status_total', 0);
$category_total     = $jinput->getInt('category_total', 0);
$manufacturer_total = $jinput->getInt('manufacturer_total', 0);
?>
<div id="element-box">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
	</div>
	<div class="m">
		<div>
			<div>
				<div>
					<?php
					if ($this->check_vm > 0)
					{
						echo '<font color=green>';
						echo $product_inserted . " " . JText::_('COM_REDSHOP_PRODUCT_SYNC') . "<br />&nbsp;&nbsp;" . $product_updated . " " . JText::_('COM_REDSHOP_PRODUCT_SYNC_UPDATED') . "<br />";
						echo $category_total . " " . JText::_('COM_REDSHOP_CATEGORY_SYNC') . "<br />";
						echo $shopper_total . " " . JText::_('COM_REDSHOP_SHOPPER_SYNC') . "<br />";
						echo $customer_total . " " . JText::_('COM_REDSHOP_CUSTOMER_SYNC') . "<br />";
						echo $orders_total . " " . JText::_('COM_REDSHOP_ORDER_SYNC') . "<br />";
						echo $status_total . " " . JText::_('COM_REDSHOP_STATUS_SYNC') . "<br />";
						echo $manufacturer_total . " " . JText::_('COM_REDSHOP_MANUFACTURER_SYNC') . "<br />";
						echo '</font>';
					}
					?>
				</div>
			</div>
		</div>
		<div class="clr"></div>
	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
	</div>
</div>
