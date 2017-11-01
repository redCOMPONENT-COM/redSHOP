<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$producthelper = productHelper::getInstance();
?>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped" width="100%">
			<thead>
			<tr>
				<th align="center"><?php echo JText::_('COM_REDSHOP_HASH'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_ORDER_ID'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_FULLNAME'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PRICE'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_STATUS'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PAYMENT_STATUS'); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->neworders); $i < $n; $i++)
			{
				$row = $this->neworders[$i];
				$row->id = $row->order_id;
				$link = "index.php?option=com_redshop&view=order_detail&task=edit&cid[]=" . $row->id;        ?>
				<tr class="<?php echo "row$k"; ?>" onclick="window.location.href='<?php echo $link; ?>'">
					<td align="center"><a href="<?php echo $link; ?>" style="color:black;"><?php echo $i + 1; ?></a>
					</td>

					<td align="center"><a href="<?php echo $link; ?>" style="color:black;"><?php echo $row->id; ?></a>
					</td>

					<td align="center"><a href="<?php echo $link; ?>" style="color:black;"><?php echo $row->name; ?></a>
					</td>
					<td align="center"><a href="<?php echo $link; ?>"
					                      style="color:black;"><?php echo $producthelper->getProductFormattedPrice($row->order_total); ?></a>
					</td>
					<td align="center"><div class="label order_status_<?php echo strtolower($row->order_status); ?>"><?php echo $row->order_status_name; ?></div></td>

					<td align="center"><div class="label order_payment_status_<?php echo strtolower($row->order_payment_status); ?>"><?php echo $row->order_payment_status; ?></div></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
		</table>
	</div>
	<input type="hidden" name="view" value="statistic"/>
	<input type="hidden" name="layout" value="<?php echo $this->layout; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
