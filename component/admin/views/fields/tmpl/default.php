<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$pagination = $this->pagination;
$ordering = ($this->lists['order'] == 'ordering');
$redtemplate = Redtemplate::getInstance();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'remove'))
		{
			form.view.value = "fields_detail";
		}

		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	};
</script>
<form action="<?php echo 'index.php?option=com_redshop'; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">

				<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->state->get('filter'); ?>"
						   onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_USER_FILTER'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="button" class="btn reset" onclick="document.getElementById('filter').value='';document.getElementById('filtertype').value='0';document.getElementById('filtersection').value='0';this.form.submit();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
				</div>
			</div>
			<div class="filterItem">
				<?php echo JText::_('COM_REDSHOP_FIELD_TYPE'); ?>&nbsp;:&nbsp;<?php echo $this->lists['type'];?>
			</div>
			<div class="filterItem">
				<?php echo JText::_('COM_REDSHOP_FIELD_SECTION'); ?>
				&nbsp;:&nbsp;<?php echo $this->lists['section'];?>
			</div>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="20">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_TITLE', 'field_title', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_NAME', 'field_name', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_TYPE', 'field_type', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th width="1%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIELD_SECTION', 'field_section', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th class="order" width="10%">
					<?php  echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
					<?php  if (@$ordering) echo JHTML::_('grid.order', $this->fields);  ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'field_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->fields); $i < $n; $i++)
			{
				$row = $this->fields[$i];
				$row->id = $row->field_id;
				$link = JRoute::_('index.php?option=com_redshop&view=fields_detail&task=edit&cid[]=' . $row->field_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td>
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td>
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td width="30%">
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_FIELDS'); ?>"><?php echo JText::_($row->field_title); ?></a>
					</td>
					<td width="30%">
						<?php echo str_replace('-', '_', $row->field_name);?>

					</td>
					<td width="30%"><?php echo $redtemplate->getFieldTypeSections($row->field_type);?></td>
					<td class="order" width="30%"><?php echo $redtemplate->getFieldSections($row->field_section);?></td>
					<td class="order" width="30%">
						<span><?php echo $this->pagination->orderUpIcon($i, ($row->field_section == @$this->fields[$i - 1]->field_section), 'orderup', JText::_('JLIB_HTML_MOVE_UP'), $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $n, ($row->field_section == @$this->fields[$i + 1]->field_section), 'orderdown', JText::_('JLIB_HTML_MOVE_DOWN'), $ordering); ?></span>

						<?php $disabled = @$ordering ? '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5"
						       value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="text_area input-small"
						       style="text-align: center"/>
					</td>

					</td>
					<td align="center" width="5%">
						<?php echo $published;?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->field_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
					<div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox(); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="view" value="fields">
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
