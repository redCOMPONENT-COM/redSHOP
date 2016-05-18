<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$redhelper = redhelper::getInstance();
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if (pressbutton == 'add')
		{
			<?php
				$link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=orderstatus_detail');
			?>
			window.location = '<?php echo $link;?>';
			return;
		}
		else if ((pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove')) {
			form.view.value = "orderstatus_detail";
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
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th width="35%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERSTATUS_CODE', 'order_status_code', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="35%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERSTATUS_NAME', 'order_status_name', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'order_status_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php

			$k = 0;
			for ($i = 0, $n = count($this->orderstatus); $i < $n; $i++)
			{
				$row = $this->orderstatus[$i];
				$row->id = $row->order_status_id;
				$link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=orderstatus_detail&task=edit&cid[]=' . $row->order_status_id);
				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td align="center">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_ORDERSTATUS'); ?>"><?php echo $row->order_status_code; ?></a>
					</td>
					<td>
						<?php echo $row->order_status_name; ?>
					</td>
					<td align="center"><?php echo $published;?></td>
					<td align="center"><?php echo $row->order_status_id; ?></td>
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

	<input type="hidden" name="view" value="orderstatus"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
