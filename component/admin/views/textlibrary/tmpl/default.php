<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$option = JRequest::getVar('option');
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
<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist">
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_FILTER'); ?>:
					<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"
					       onchange="document.adminForm.submit();">

					<?php echo JText::_('COM_REDSHOP_SECTION'); ?>:

					<?php echo $this->lists['section']; ?>&nbsp;
					<button onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO'); ?></button>
					&nbsp;
					<button
						onclick="this.form.getElementById('section').value='0';this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET');?></button>

				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%" class="title">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->textlibrarys); ?>);"/>
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
				$row = & $this->textlibrarys[$i];
				$row->id = $row->textlibrary_id;
				$link = JRoute::_('index.php?option=' . $option . '&view=textlibrary_detail&task=edit&cid[]=' . $row->textlibrary_id);

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
					<td>
						<?php echo $row->text_desc; ?>
					</td>
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
