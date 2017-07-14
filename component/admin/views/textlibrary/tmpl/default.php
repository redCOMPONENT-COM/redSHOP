<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit')
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
			form.view.value = "textlibrary_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<input type="text" name="filter" id="filter" value="<?php echo $this->state->get('filter'); ?>"
					   onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_FILTER'); ?>">
				<button class="btn" onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO'); ?></button>
				<button class="btn"
					onclick="this.form.getElementById('section').value='0';this.form.getElementById('filter').value='';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET');?></button>
			</div>
		</div>
		<div class="filterItem">
			<?php echo JText::_('COM_REDSHOP_SECTION'); ?>:
			<?php echo $this->lists['section']; ?>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%" class="title">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title" width="30%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TAG_NAME', 'text_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="50%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TEXT_DESCRIPTION', 'text_desc', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="50%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SECTION', 'section', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'textlibrary_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->textlibrarys); $i < $n; $i++)
			{
				$row = $this->textlibrarys[$i];
				$row->id = $row->textlibrary_id;
				$link = JRoute::_('index.php?option=com_redshop&view=textlibrary_detail&task=edit&cid[]=' . $row->textlibrary_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

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
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_TAG'); ?>">{<?php echo $row->text_name; ?>}</a>
					</td>
					<td><?php echo $row->text_desc; ?></td>
					<td>
						<?php echo $row->section; ?>
					</td>
					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->textlibrary_id; ?>
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

	<input type="hidden" name="view" value="textlibrary"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
