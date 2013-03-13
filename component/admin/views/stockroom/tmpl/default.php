<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

$option = JRequest::getVar('option', '', 'request', 'string');
$filter = JRequest::getVar('filter');
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
		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy') || (pressbutton == 'frontpublish') || (pressbutton == 'frontunpublish')) {
			form.view.value = "stockroom_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}
		form.submit();
	}
</script>
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td valign="top" align="left" class="key">
					<?php echo JText::_('COM_REDSHOP_USER_FILTER'); ?>:
					<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"
					       onchange="document.adminForm.submit();">
					<button onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO'); ?></button>
					<button
						onclick="document.getElementById('filter').value='';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM');?></th>
				<th width="5%"><input type="checkbox" name="toggle" value=""
				                      onclick="checkAll(<?php echo count($this->stockroom); ?>);"/></th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_STOCKROOM_NAME', 'stockroom_name', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MINIMUM_DELIVERY_TIME', 'min_del_time', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="10%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MAXIMUM_DELIVERY_TIME', 'max_del_time', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="5%"
				    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="5%"
				    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SHOW_ON_FRONTEND', 'show_in_front', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="5%"
				    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'stockroom_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->stockroom); $i < $n; $i++)
			{
				$row = & $this->stockroom [$i];
				$row->id = $row->stockroom_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=stockroom_detail&task=edit&cid[]=' . $row->stockroom_id);?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i);?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
					<td><a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_REDSHOP_EDIT_STOCKROOM'); ?>">
							<?php echo $row->stockroom_name;?></a></td>
					<td align="center"><?php    echo $row->min_del_time;?></td>
					<td align="center"><?php echo $row->max_del_time;?></td>
					<td align="center"><?php echo $published = JHTML::_('grid.published', $row, $i);?></td>
					<td align="center"><?php $row->published = $row->show_in_front;
						echo $show_in_front = JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'front');?></td>
					<td align="center"><?php echo $row->stockroom_id;?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="9"><?php echo $this->pagination->getListFooter();?></td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="stockroom"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
</form>