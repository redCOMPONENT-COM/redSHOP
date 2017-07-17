<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$url = JURI::base();
$order_functions = order_functions::getInstance();
$redconfig = Redconfiguration::getInstance();
$producthelper = productHelper::getInstance();

$Itemid = JRequest::getInt('Itemid');
$order_id = JRequest::getInt('order_id', 0);

$order_detail = array();
$OrderProducts = array();

if ($order_id != 0)
{
	$order_detail  = $order_functions->getOrderDetails($order_id);
	$OrderProducts = $order_functions->getOrderItemDetail($order_id);
}

if ($this->params->get('show_page_heading', 1))
{
	?>
	<div class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php
		if ($this->params->get('show_page_heading', 1) && $this->params->get('page_title'))
		{
			echo $this->escape($this->params->get('page_title'));
		}    ?>
	</div>
<?php
}
?>
<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=ordertracker&Itemid=' . $Itemid); ?>"
      method="post" name="adminForm">
	<table cellpadding="3" cellspacing="0" border="0">
		<tr>
			<td colspan="2">
				<?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?>:
			</td>
			<td>
				<input type="text" class="inputbox" name="order_id" id="order_id" value="">
			</td>
			<td><input type="submit" id="go" name="go" value="<?php echo JText::_('COM_REDSHOP_GO'); ?>"></td>
		</tr>
	</table>
	<input type="hidden" name="view" value="ordertracker"/>
	<input type="hidden" name="task" value="details"/>
</form>
<table class="tblOrderdetail" cellpadding="4" cellspacing="0" border="0">
	<tr class="tblOrderDetailheading">
		<?php
		if (count($order_detail) > 0)
		{
			?>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?>    </th>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_NUMBER'); ?>    </th>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_ITEM');  ?></th>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_TOTAL');  ?></th>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_DATE'); ?></th>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_STATUS'); ?></th>
			<th><?php echo JText::_('COM_REDSHOP_ORDER_DETAIL'); ?></th>
		</tr>
		<?php    $order_item_name = array();

		for ($j = 0, $jn = count($OrderProducts); $j < $jn; $j++)
		{
			$order_item_name[$j] = $OrderProducts[$j]->order_item_name;
		}

		$itemlist = implode(',<br/>', $order_item_name);
		$statusname = $order_functions->getOrderStatusTitle($order_detail->order_status);
		$orderdetailurl = JRoute::_('index.php?option=com_redshop&view=order_detail&oid=' . $order_id);    ?>
		<tr class="rblOrderDetailItem">
			<td><?php echo $order_id;?></td>
			<td><?php echo $order_detail->order_number;?></td>
			<td><?php echo $itemlist;?></td>
			<td><?php echo $producthelper->getProductFormattedPrice($order_detail->order_total);?></td>
			<td><?php echo $redconfig->convertDateFormat($order_detail->cdate); ?></td>

			<td><?php echo $statusname; ?></td>
			<td><a href="<?php echo $orderdetailurl; ?>">
					<?php echo JText::_('COM_REDSHOP_ORDER_DETAIL');?></a></td>
		</tr>
		<?php
		}
		else
		{
			?>
			<td><?php echo JText::_('COM_REDSHOP_ORDER_NOT_FOUND'); ?></td>
		<?php
		}
		?>
	</tr>
</table>
