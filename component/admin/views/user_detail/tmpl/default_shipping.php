<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$shipping_data = $this->order_functions->getShippingAddress($this->detail->user_id);

$addlink = JRoute::_('index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id=' . $this->detail->users_info_id . '&cid[]=0');
?>
<div id="editcell">

	<div align="right"><a href="<?php echo $addlink; ?>" class="btn btn-success"
	                      style="text-decoration: none;"><?php echo JText::_('COM_REDSHOP_ADD');?></a></div>
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th class="title"><?php echo JText::_('COM_REDSHOP_FIRST_NAME');?></th>
			<th><?php echo JText::_('COM_REDSHOP_LAST_NAME');?></th>
			<th><?php echo JText::_('COM_REDSHOP_ID');?></th>
			<th><?php echo JText::_('COM_REDSHOP_DELETE');?></th>
		</tr>
		</thead>
		<?php
		$x = 0;
		for ($j = 0, $n = count($shipping_data); $j < $n; $j++)
		{
			$row = $shipping_data[$j];
			$link = JRoute::_('index.php?option=com_redshop&view=user_detail&task=edit&shipping=1&info_id=' . $this->detail->users_info_id . '&cid[]=' . $row->users_info_id);
			$link_delete = JRoute::_('index.php?option=com_redshop&view=user_detail&task=remove&shipping=1&info_id=' . $this->detail->users_info_id . '&cid[]=' . $row->users_info_id);    ?>
			<tr class="<?php echo "row$x"; ?>">
				<td align="center"><a href="<?php echo $link; ?>"
				                      title="<?php echo JText::_('COM_REDSHOP_EDIT_USER'); ?>"><?php echo $row->firstname;?></a>
				</td>
				<td align="center"><?php echo $row->lastname;?></td>
				<td align="center"><?php echo $row->users_info_id;?></td>
				<td align="center"><a href="<?php echo $link_delete; ?>"
				                      title="<?php echo JText::_('COM_REDSHOP_DELETE_SHIPPING_DETAIL'); ?>"><?php echo JText::_('COM_REDSHOP_DELETE');?></a>
				</td>
			</tr>
			<?php
			$x = 1 - $x;
		}?>
	</table>
</div>
