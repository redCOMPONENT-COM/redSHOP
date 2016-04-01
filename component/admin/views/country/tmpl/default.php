<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$filter = JRequest::getVar('filter');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'saveorder') || (pressbutton == 'orderup') || (pressbutton == 'orderdown')) {
			form.view.value = "country_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="index.php?option=com_redshop" class="admin" id="adminForm" method="post" name="adminForm">
	<table width="100%">
		<tr>
			<td valign="top" class="key">
				<div class="btn-wrapper input-append">
					<input type="text" name="filter" id="filter" value="<?php echo $this->filter; ?>"
						   placeholder="<?php echo JText::_('COM_REDSHOP_COUNTRY_FILTER'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				</div>
			</td>
		</tr>
	</table>
	<table class="adminlist table table-striped">
		<thead>
		<tr>
			<th width="5"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
			<th width="10">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
			</th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_NAME'), 'country_name', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_3_CODE'), 'country_3_code', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_2_CODE'), 'country_2_code', $this->lists['order_Dir'], $this->lists['order']);?></th>
			<!-- <th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_COUNTRY_JTEXT'), 'country_jtext', $this->lists['order_Dir'], $this->lists['order']);?></th> -->
			<th><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ID'), 'country_id', $this->lists['order_Dir'], $this->lists['order']); ?>    </th>
		</tr>
		</thead>
		<?php
		$k = 0;
		for ($i = 0, $n = count($this->fields); $i < $n; $i++)
		{
			$row = $this->fields[$i];
			$row->id = $row->country_id;
			$link = JRoute::_('index.php?option=com_redshop&view=country_detail&task=edit&cid[]=' . $row->country_id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td><?php echo $this->pagination->getRowOffset($i); ?></td>
				<td><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
				<td><a href="<?php echo $link; ?>"
				       title="<?php echo JText::_('COM_REDSHOP_EDIT_COUNTRY'); ?>"><?php echo $row->country_name ?></a>
				</td>
				<td align="center" width="10%"><?php echo $row->country_3_code; ?></td>
				<td align="center" width="10%"><?php echo $row->country_2_code; ?></td>
				<!-- <td align="center" width="10%"><?php echo $row->country_jtext; ?></td> -->
				<td align="center" width="10%"><?php echo $row->country_id;?></td>

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
	<input type="hidden" name="view" value="country"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>


