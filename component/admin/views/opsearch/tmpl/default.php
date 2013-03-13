<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

JHTMLBehavior::modal();

$option = JRequest::getVar('option', '', 'request', 'string');

$order_function = new order_functions();
$config = new Redconfiguration();
$redhelper = new redhelper();

$filter_product = JRequest::getVar('filter_product', 0);
$parent = JRequest::getVar('parent');
$showbuttons = JRequest::getVar('showbuttons', '', 'request', 0);    ?>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post"
      name="adminForm" id="adminForm">
	<div id="editcell">
		<?php if ($showbuttons != 1)
		{
			?>
			<table class="adminlist">
				<tr>
					<td valign="top" align="left" class="key">
						<?php    echo JText::_("COM_REDSHOP_PRODUCT_NAME") . ": ";?>
						<input class="text_area" type="text" name="parent" id="parent"
						       size="32" maxlength="250" value="<?php echo $parent; ?>"/> <input
							class="text_area" type="hidden" name="filter_product_opsearch"
							id="filter_product_opsearch" size="32" maxlength="250"
							value="<?php echo $filter_product; ?>"/>
						<button
							onclick="document.getElementById('filter_product').value='0';document.getElementById('filter_product_opsearch').value='0';document.getElementById('parent').value='';document.getElementById('filter_user').value='0';document.getElementById('filter_status').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
					</td>
					<td valign="top" align="right" class="key">
						<?php    echo $this->lists['filter_status'] . " " . $this->lists['filter_user'];  ?></td>
				</tr>
			</table>
		<?php } ?>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<!--<th style="display:none;"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->products ); ?>);" /></th>-->
				<th width="25%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'op.order_item_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'op.order_item_sku', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_ID', 'op.order_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_DATE', 'op.mdate', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_STATUS', 'op.order_status', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_QUANTITY'); ?></th>
				<th width="20%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FULLNAME', 'fullname', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			$totvolume = 0;
			for ($i = 0, $n = count($this->products); $i < $n; $i++)
			{
				$row = & $this->products[$i];

				$link = JRoute::_('index.php?option=' . $option . '&view=product_detail&task=edit&cid[]=' . $row->product_id);
				$link_order = 'index.php?option=' . $option . '&view=order_detail&task=edit&cid[]=' . $row->order_id;
				$link_order = $redhelper->sslLink($link_order);    ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<!-- <td style="display:none;"><?php echo JHTML::_('grid.id', $i, $row->id ); ?></td>-->
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_PRODUCT'); ?>"><?php echo $row->order_item_name; ?></a>
					</td>
					<td align="center"><?php echo $row->order_item_sku; ?></td>
					<td align="center"><a href="<?php echo $link_order; ?>"
					                      title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER'); ?>"><?php echo $row->order_id; ?></a>
					</td>
					<td><?php echo $config->convertDateFormat($row->mdate);?></td>
					<td align="center"><?php echo $order_function->getOrderStatusTitle($row->order_status);?></td>
					<td align="center"><?php echo $row->product_quantity; ?></td>
					<td align="center"><?php echo $row->fullname; ?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			</td>
			</tr>
			<?php if ($showbuttons != 1)
			{ ?>
				<tfoot>
				<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
				</tfoot>
			<?php } ?>
		</table>
	</div>
	<input type="hidden" name="view" value="opsearch"/> <input
		type="hidden" name="boxchecked" value="0"/> <input type="hidden"
	                                                       name="filter_order"
	                                                       value="<?php echo $this->lists['order']; ?>"/> <input
		type="hidden" name="filter_order_Dir"
		value="<?php echo $this->lists['order_Dir']; ?>"/> <input
		type="hidden" name="filter_product" id="filter_product" value="<?php echo $filter_product; ?>"/></form>
<script type="text/javascript">
	var options = {
		script: "index.php?tmpl=component&option=com_redshop&view=search&json=true&product_id=<?php echo $filter_product;?>&",
		varname: "input",
		json: true,
		shownoresults: true,
		callback: function (obj) {
			if (document.getElementById('filter_product')) {
				document.getElementById('filter_product').value = obj.id;
			}
			document.adminForm.submit();
		}
	};
	var as_json = new bsn.AutoSuggest('parent', options);
</script>