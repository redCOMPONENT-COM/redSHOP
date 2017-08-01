<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model = $this->getModel('newsletter');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit')
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
			form.view.value = "newsletter_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="index.php?option=com_redshop" method="post"
      name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->state->get('filter'); ?>"
						   onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_USER_FILTER'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="reset" class="btn reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
						   onclick="document.getElementById('filter').value='';this.form.submit();">
				</div>
			</div>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM');?></th>
				<th width="5%"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
				<th width="25%"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NEWSLETTER_NAME', 'name', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th><?php echo JText::_('COM_REDSHOP_NEWSLETTER_SUB');?></th>
				<th width="10%"><?php echo JText::_('COM_REDSHOP_NO_SUBSCRIBERS');?></th>
				<th width="5%"
				    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'published', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="5%"
				    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'ID', 'newsletter_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->newsletters); $i < $n; $i++)
			{
				$row = $this->newsletters [$i];
				$row->id = $row->newsletter_id;
				$link = JRoute::_('index.php?option=com_redshop&view=newsletter_detail&task=edit&cid[]=' . $row->newsletter_id);
				$published = JHTML::_('grid.published', $row, $i);    ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i);?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id);?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_NEWSLETTER'); ?>"><?php echo $row->name;?></a>
					</td>
					<td><?php echo $row->subject;?></td>
					<td align="center"><?php echo $model->noofsubscribers($row->newsletter_id);?></td>
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->newsletter_id;?></td>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tfoot>
			<td colspan="7">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter();?></td>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="view" value="newsletter"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
</form>
