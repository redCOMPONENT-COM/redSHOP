<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$db = JFactory::getDBO();
$option = JRequest::getVar('option');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		form.onsubmit();
		catch
		(e)
		{
		}

		form.submit();
	}
</script>

<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">

		<table class="adminlist">
			<thead>
			<tr>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NAME', 'firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JText::_('COM_REDSHOP_TELEPHONE'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDSHOP_PRODUCT_QUANTITY'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDSHOP_PRODUCT_NUMBER'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDSHOP_PRODUCT'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDSHOP_PRODUCT_VOLUME'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDSHOP_SOLD_FROM_STOCKROOM'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?>
				</th>
			</tr>
			</thead>
			<?php
			$query = "SELECT * FROM #__" . TABLE_PREFIX . "_orders AS o "
				. "LEFT JOIN #__" . TABLE_PREFIX . "_users_info AS uf ON o.user_id=uf.user_id "
				. "LEFT JOIN #__" . TABLE_PREFIX . "_order_status AS os ON o.order_status=os.order_status_code "
				. "WHERE uf.address_type='BT' "
				. "AND o.order_status IN ('RD','RD1','RD2') "
				. "ORDER BY " . $this->lists['order'] . " " . $this->lists['order_Dir'];
			$db->setQuery($query);
			$orders = $db->loadObjectList();
			$k = 0;
			for ($i = 0, $n = count($orders); $i < $n; $i++)
			{
				$row = & $orders[$i];
				$row->id = $row->order_id;

				$query = "SELECT oi.*,p.product_volume FROM #__" . TABLE_PREFIX . "_order_item oi "
					. "LEFT JOIN #__" . TABLE_PREFIX . "_product p ON p.product_id = oi.product_id "
					. "WHERE order_id = '" . $row->order_id . "' ORDER BY delivery_time";
				$db->setQuery($query);
				$products = $db->loadObjectList();
				$total = count($products);
				for ($j = 0; $j < count($products); $j++)
				{
					$product = $products[$j];
					$query = "SELECT * FROM #__" . TABLE_PREFIX . "_container WHERE container_id = '" . $product->container_id . "'";
					$db->setQuery($query);
					if (!$container = $db->loadObject())
					{
						$container->container_name = '';
					}
					if ($j == 0)
					{
						?>
						<tr>
							<td align="center" rowspan="<?php echo $total; ?>"><?php echo $row->order_id; ?> </td>
							<td rowspan="<?php echo $total; ?>"><?php echo $row->firstname; ?>  <?php //echo $row->lastname; ?> </td>
							<td rowspan="<?php echo $total; ?>"><?php echo $row->address; ?></td>
							<td rowspan="<?php echo $total; ?>"><?php echo $row->phone; ?></td>
							<td align="center"><?php echo $product->product_quantity;?></td>
							<td align="center"><?php echo $product->order_item_sku;?></td>
							<td><?php echo $product->order_item_name;?></td>
							<td align="center"><?php echo $product->product_volume;?></td>
							<td><?php echo $container->container_name;?></td>
							<td rowspan="<?php echo $total; ?>"><?php echo $row->order_status_name;?></td>
						</tr>
					<?php }
					else
					{ ?>
						<tr>
							<td align="center"><?php echo $product->product_quantity;?></td>
							<td align="center"><?php echo $product->order_item_sku;?></td>
							<td><?php echo $product->order_item_name;?></td>
							<td align="center"><?php echo $product->product_volume;?></td>
							<td><?php echo $container->container_name;?></td>
						</tr>
					<?php
					}
				}
				$k = 1 - $k;
			}
			?>
		</table>
	</div>
	<input type="hidden" name="view" value="delivery"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>