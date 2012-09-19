<?php
/** 
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved. 
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com 
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );


$product_total = JRequest::getVar('product_total',0);
$product_inserted = JRequest::getVar('product_inserted',0);
$product_updated = JRequest::getVar('product_updated',0);
$shopper_total = JRequest::getVar('shopper_total',0);
$orders_total = JRequest::getVar('orders_total',0);
$customer_total = JRequest::getVar('customer_total',0);
$status_total = JRequest::getVar('status_total',0);
$category_total = JRequest::getVar('category_total',0);
$manufacturer_total = JRequest::getVar('manufacturer_total',0);
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
					if($this->check_vm > 0){ 
					echo '<font color=green>';
						echo $product_inserted." ".JText::_('COM_REDSHOP_PRODUCT_SYNC')."<br />&nbsp;&nbsp;".$product_updated." ".JText::_('COM_REDSHOP_PRODUCT_SYNC_UPDATED')."<br />";
						echo $category_total." ".JText::_('COM_REDSHOP_CATEGORY_SYNC')."<br />";
						echo $shopper_total." ".JText::_('COM_REDSHOP_SHOPPER_SYNC')."<br />";
						echo $customer_total." ".JText::_('COM_REDSHOP_CUSTOMER_SYNC')."<br />";
						echo $orders_total." ".JText::_('COM_REDSHOP_ORDER_SYNC')."<br />";
						echo $status_total." ".JText::_('COM_REDSHOP_STATUS_SYNC')."<br />";
						echo $manufacturer_total." ".JText::_('COM_REDSHOP_MANUFACTURER_SYNC')."<br />";	
						echo '</font>';
					}else{
						echo '<font color=red>';
						echo JText::_('COM_REDSHOP_NO_VM');
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
