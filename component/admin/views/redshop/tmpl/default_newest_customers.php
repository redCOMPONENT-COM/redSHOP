<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$model = $this->getModel('redshop');
?>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped" width="100%">
			<thead>
			<tr>
				<th align="center"><?php echo JText::_('COM_REDSHOP_FULLNAME'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_NUMBER_OF_ORDERS'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_AVG_AMOUNT_OF_ORDERS'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_TOTAL_AMOUNT_OF_ORDERS'); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->newcustomers); $i < $n; $i++)
			{
				$row = $this->newcustomers[$i];
				$row->id = $row->users_info_id;

				$order = $model->getTotalOrder($row->id);

				$order->order_total = ($order->order_total) ? $order->order_total : 0;
				$avg_amount = ($order->tot_order > 0) ? $order->order_total / $order->tot_order : 0;

				$link = "index.php?option=com_redshop&view=user_detail&task=edit&cid[]=" . $row->id;
				?>
				<tr class="<?php echo "row$k"; ?>" onclick="window.location.href='<?php echo $link; ?>'">
					<td align="center"><a href="<?php echo $link; ?>"
					                      style="color:black;"><?php echo $row->firstname . ' ' . $row->lastname; ?></a>
					</td>
					<td align="center"><a href="<?php echo $link; ?>"
					                      style="color:black;"><?php echo $order->tot_order ?></a></td>
					<td align="center"><a href="<?php echo $link; ?>"
					                      style="color:black;"><?php echo RedshopHelperProductPrice::formattedPrice($avg_amount); ?></a>
					</td>
					<td align="center"><a href="<?php echo $link; ?>"
					                      style="color:black;"><?php echo RedshopHelperProductPrice::formattedPrice($order->order_total);?></a>
					</td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
		</table>
	</div>
</form>
