<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$config = Redconfiguration::getInstance();


$lists = $this->lists;?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'remove') || (pressbutton == 'auto_syncpublish') || (pressbutton == 'auto_syncunpublish') || (pressbutton == 'publish') || (pressbutton == 'unpublish')) {
			form.view.value = "xmlimport_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="<?php echo JRoute::_($this->request_url); ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%" class="title">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title" width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_XMLIMPORT_DISPLAY_FILENAME', 'display_filename', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="40%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_XMLIMPORT_FILENAME', 'filename', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SECTION_TYPE', 'section_type', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_AUTO_SYNCHRONIZE', 'auto_sync', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<!-- <th class="title" width="20%">
			<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_XMLIMPORT_DATE', 'xmlimport_date', $this->lists['order_Dir'], $this->lists['order'] ); ?></th> -->
				<th width="5%"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?></th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'xmlimport_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->data); $i < $n; $i++)
			{
				$row = $this->data[$i];
				$row->id = $row->xmlimport_id;
				$link = JRoute::_('index.php?option=com_redshop&view=xmlimport_detail&task=edit&cid[]=' . $row->id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td><a href="<?php echo $link; ?>"
					       title="<?php echo JText::_('COM_REDSHOP_EDIT_XMLIMPORT'); ?>"><?php echo $row->display_filename; ?></a>
					</td>
					<td><?php echo $row->filename;?></td>
					<td align="center"><?php echo $row->section_type;?></td>
					<td align="center"><?php $row->published = $row->auto_sync;
						echo $auto_sync = JHTML::_('grid.published', $row, $i, 'tick.png', 'publish_x.png', 'auto_sync');?></td>
					<!-- <td><?php echo $config->convertDateFormat($row->xmlimport_date);?></td> -->
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->id; ?></td>
				</tr>
				</tr>
				<?php    $k = 1 - $k;
			}    ?>
			<tr>
				<td colspan="8">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?></td>
		</table>
	</div>

	<input type="hidden" name="view" value="xmlimport"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
