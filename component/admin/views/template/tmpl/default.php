<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$filter      = JRequest::getVar('filter');
$redtemplate = new Redtemplate;
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'copy')) {
			form.view.value = "template_detail";
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

		<table width="100%">
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td valign="top" align="left" class="key">
								<?php echo JText::_('COM_REDSHOP_TEMPLATE_NAME'); ?>:
								<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>"
								       onchange="document.adminForm.submit();">
								<button onclick="this.form.submit();"><?php echo JText::_('COM_REDSHOP_GO'); ?></button>
								<button
									onclick="document.getElementById('filter').value='';document.getElementById('template_section').value=0;this.form.submit();"><?php echo JText::_('COM_REDSHOP_RESET'); ?></button>
							</td>
						</tr>
					</table>
				</td>
				<td valign="top" align="right" class="key">

					<?php echo JText::_('COM_REDSHOP_TEMPLATE_SECTION'); ?>:

					<?php echo $this->lists['section']; ?>
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->templates); ?>);"/>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TEMPLATE_NAME', 'template_name', $this->lists['order_Dir'], $this->lists['order']); ?>

				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SECTION', 'template_section', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'template_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->templates); $i < $n; $i++)
			{
				$row        = $this->templates[$i];
				$row->id    = $row->template_id;
				$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $row->checked_out == $this->user->get('id') || $row->checked_out == 0;
				$link       = JRoute::_('index.php?option=com_redshop&view=template_detail&task=edit&cid[]=' . $row->template_id);
				$published  = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php if ($row->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time); ?>
						<?php else : ?>
							<?php echo JHtml::_('grid.id', $i, $row->template_id); ?>
						<?php endif; ?>
					</td>
					<td>
						<?php if (!$canCheckin) : ?>
							echo $row->template_name;
						<?php else : ?>
							<a href="<?php echo $link; ?>"
							   title="<?php echo JText::_('COM_REDSHOP_EDIT_TEMPLATES'); ?>"><?php echo $row->template_name; ?></a>
						<?php endif; ?>
					</td>
					<td>
						<?php echo $redtemplate->getTemplateSections($row->template_section); ?>
					</td>

					<td align="center">
						<?php echo $published;?>
					</td>
					<td align="center">
						<?php echo $row->template_id; ?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>

			<tfoot>
			<td colspan="9">
				<?php  echo $this->pagination->getListFooter(); ?>
			</td>
			</tfoot>
		</table>
	</div>

	<input type="hidden" name="view" value="template"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
