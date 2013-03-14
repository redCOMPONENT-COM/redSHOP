<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');
$order_function = new order_functions();
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
$producthelper = new producthelper();

$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter');
$lists = $this->lists;
$model = $this->getModel('orderreddesign');

$live_site = JURI::base();
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

		if ((pressbutton == 'allstatus')) {
			if (document.getElementById('order_status_all').value == '0') {
				alert('<?php echo JText::_('COM_REDSHOP_SELECT_NEW_STATUS' ); ?>');
				return false;
			}

		} else if ((pressbutton == 'edit') || (pressbutton == 'remove')) {
			form.view.value = "order_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=' . $option . '&view=orderreddesign'); ?>" method="post"
      name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist">
			<tr>
				<td><?php echo JText::_('COM_REDSHOP_NEW_STATUS'); ?>
					: <?php echo $order_function->getstatuslist('order_status_all', '', "class=\"inputbox\" size=\"1\" "); ?> </td>
				<td valign="top" align="right" class="key">

					<?php echo JText::_('COM_REDSHOP_FILTER'); ?>:
					<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"
					       onchange="document.adminForm.submit();">
					<?php echo $lists['filter_status']; ?>

					<button
						onclick="document.getElementById('filter').value='';document.getElementById('filter_status').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>

				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5" class="title">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->orders); ?>);"/>
				</th>
				<th class="title" width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_ID', 'order_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="20%">
					<?php echo JHTML::_('grid.sort', 'FULLNAME', 'uf.firstname', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="40%">
					<?php echo JHTML::_('grid.sort', 'ORDER_STATUS', 'order_status', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="40%">
					<?php echo JText::_('COM_REDSHOP_DESIGN'); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_DATE', 'cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDER_TOTAL', 'order_total', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->orders); $i < $n; $i++)
			{
				$row = & $this->orders[$i];

				/*if(!in_array($row->order_id,$lists['detailorder'] ))
				{
					continue;
				}*/

				$row->id = $row->order_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=order_detail&task=edit&cid[]=' . $row->order_id);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="order">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td class="order">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDER'); ?>"><?php echo $row->order_id; ?></a>
					</td>
					<td>
						<?php echo $order_function->getUserFullname($row->user_id); ?>
					</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td colspan="2">
									<?php
									$linkupdate = JRoute::_('index.php?option=' . $option . '&view=orderreddesign&task=update_status&return=order&order_id[]=' . $row->order_id);
									echo $order_function->getstatuslist('order_status' . $row->order_id, $row->order_status, "class=\"inputbox\" size=\"1\" ");
									echo "&nbsp";
									echo $order_function->getpaymentstatuslist('order_paymentstatus' . $row->order_id, $row->order_payment_status, "class=\"inputbox\" size=\"1\" ");
									?>
								</td>
							</tr>
							<tr>
								<td><textarea name="order_comment<?php echo $row->order_id; ?>"></textarea></td>
								<td>
									<input class="inputbox" name="ic<?php echo $row->order_id; ?>"
									       id="include_comment<?php echo $row->order_id; ?>" value="N" type="checkbox"
									       onclick="if(this.checked==true) {this.value = 'Y';} else {this.value = 'N';}"><?php echo JText::_('COM_REDSHOP_INCLUDE_COMMENT_MSG'); ?>
									<br/>
									<input class="inputbox" name="nc<?php echo $row->order_id; ?>"
									       id="notify_customer<?php echo $row->order_id; ?>" value="N" type="checkbox"
									       onclick="if(this.checked==true) {this.value = 'Y';} else {this.value = 'N';}"><?php echo JText::_('COM_REDSHOP_NOTIFY_CUSTOMER_MSG'); ?>
								</td>
							</tr>
							<tr>
								<td></td>
								<td>
									<input class="button"
									       onclick="if(document.adminForm.order_status<?php echo $row->order_id; ?>.value=='<?php echo $row->order_status; ?>') { alert('Please change the Order Status first!'); return false;} else {  location.href = '<?php echo $linkupdate; ?>&status='+document.adminForm.order_status<?php echo $row->order_id; ?>.value+'&nc='+document.adminForm.nc<?php echo $row->order_id; ?>.value +'&ic='+document.adminForm.ic<?php echo $row->order_id; ?>.value+'&order_comment='+document.adminForm.order_comment<?php echo $row->order_id; ?>.value+'&order_paymentstatus='+document.adminForm.order_paymentstatus<?php echo $row->order_id; ?>.value  ; }"
									       name="order_status" value="Update Status" type="button">
								</td>
							</tr>
						</table>
					</td>
					<td align="center">
						<?php $reddesigndetail = $model->getorderdesign($row->order_id);
						for ($design_i = 0; $design_i < count($reddesigndetail); $design_i++)
						{
							?>
							<a href="../components/com_reddesign/assets/order/pdf/<?php echo $reddesigndetail[$design_i]->reddesignfile; ?>.pdf"
							   target="_blank"> <img
									src="<?php echo $live_site; ?>components/com_reddesign/assets/images/design.png"
									border="0" alt=""/></a><br/>
							<!--<a href="index.php?tmpl=component&option=com_redshop&view=orderreddesign&task=downloaddesign&filename=<?php echo $reddesigndetail[0]->reddesignfile; ?>&type=pdf"    > <img src="<?php echo $live_site; ?>components/com_reddesign/assets/images/design.png" border="0"  alt="" /></a>-->
						<?php } ?>
					</td>
					<td align="center">
						<?php echo date('d-m-Y H:i', $row->cdate); ?>
					</td>
					<td>
						<?php echo $producthelper->getProductFormattedPrice($row->order_total); ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
			<td colspan="6">
				<?php  echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>


	<input type="hidden" name="return" value="order"/>
	<input type="hidden" name="view" value="orderreddesign"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>