<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$redtemplate = Redtemplate::getInstance();

?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'remove') || (pressbutton == 'saveorder') ||
            (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "mail_detail";
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
		<div class="filterTool">
			<div class="filterItem">

				<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->lists['filter']; ?>"
						   onchange="document.adminForm.submit();" placeholder="<?php echo JText::_('COM_REDSHOP_MAIL_NAME'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="button" class="btn reset" onclick="document.getElementById('filter').value='';document.getElementById('filter_section').value=0;this.form.submit();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>
				</div>
			</div>
			<div class="filterItem">
				<?php echo JText::_('COM_REDSHOP_MAIL_SECTION') . ": " . $this->lists['mailsection']; ?>
			</div>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MAIL_NAME', 'mail_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MAIL_SUBJECT', 'mail_subject', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_MAIL_SECTION', 'mail_section', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'mail_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0; $i < count($this->media); $i++)
			{
				$row = $this->media[$i];

				$row->id = $row->mail_id;

				$link = JRoute::_('index.php?option=com_redshop&view=mail_detail&task=edit&cid[]=' . $row->mail_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_MAIL'); ?>"><?php echo $row->mail_name; ?></a>
					</td>
					<td><?php    echo $row->mail_subject;     ?></td>
					<td align="center"><?php    echo $redtemplate->getMailSections($row->mail_section);     ?></td>
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->mail_id; ?></td>
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

	<input type="hidden" name="view" value="mail"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
