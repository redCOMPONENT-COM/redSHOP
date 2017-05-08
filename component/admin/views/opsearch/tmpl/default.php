<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$order_function = order_functions::getInstance();
$config = Redconfiguration::getInstance();
$productHelper = productHelper::getInstance();
$redhelper = redhelper::getInstance();
$showbuttons = JRequest::getVar('showbuttons', '', 'request', 0);    ?>
<form action="index.php?option=com_redshop" method="post"
      name="adminForm" id="adminForm">
	<div id="editcell">
		<?php if ($showbuttons != 1)
		{
			?>
		<div class="filterTool">
			<div class="filterItem">
				<?php  echo JText::_("COM_REDSHOP_PRODUCT_NAME") . ": ";
				$filterObject = new stdClass;
				$filterObject->text = '';

				if ($this->state->get('filter_product') && ($productData = $productHelper->getProductById($this->state->get('filter_product'))))
				{
					$filterObject->text = $productData->product_name;
				}

				$filterObject->value = $this->state->get('filter_product');

				echo JHTML::_('redshopselect.search', $filterObject, 'filter_product',
					array(
						'select2.options' => array(
							'events' => array('select2-selecting' => 'function(e) {document.getElementById(\'filter_product\').value = e.object.id;document.adminForm.submit();}')
						)
					)
				);
				?>
				<button class="btn"
					onclick="document.getElementById('filter_product').value='0';document.getElementById('filter_user').value='0';document.getElementById('filter_status').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['filter_status']; ?>
			</div>
			<div class="filterItem">
				<?php echo $this->lists['filter_user']; ?>
			</div>
		</div>
		<?php } ?>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
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
				$row = $this->products[$i];

				$link = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id);
				$link_order = 'index.php?option=com_redshop&view=order_detail&task=edit&cid[]=' . $row->order_id;
				$link_order = RedshopHelperUtility::getSSLLink($link_order);    ?>
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
				<td colspan="8">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?></td>
				</tfoot>
			<?php } ?>
		</table>
	</div>
	<input type="hidden" name="view" value="opsearch"/> <input
		type="hidden" name="boxchecked" value="0"/> <input type="hidden"
	                                                       name="filter_order"
	                                                       value="<?php echo $this->lists['order']; ?>"/> <input
		type="hidden" name="filter_order_Dir"
		value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
