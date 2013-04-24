<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
$order_function = new order_functions();

$option = JRequest::getVar('option');
$filter = JRequest::getVar('filter');
$config = new Redconfiguration();
$model = $this->getModel('newslettersubscr');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}
	submitbutton = function (pressbutton) {

		var form = document.adminForm;

		form.task.value = "";
		form.view.value = "newslettersubscr";

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit') || (pressbutton == 'publish') || (pressbutton == 'unpublish')
			|| (pressbutton == 'remove') || (pressbutton == 'export_data') || (pressbutton == 'export_acy_data')) {
			form.view.value = "newslettersubscr_detail";

		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}

	function clearreset() {
		var form = document.adminForm;
		form.filter.value = "";
		form.submit();
	}
</script>

<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td valign="top" align="right" class="key">
					<?php echo JText::_('COM_REDSHOP_NEWSLETTER_FILTER'); ?>:
					<input type="text" name="filter" id="filter" value="<?php echo $filter; ?>">
					<input type="reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
					       onclick="return clearreset();">
				</td>
			</tr>
		</table>
		<table class="adminlist">
			<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th>
					<input type="checkbox" name="toggle" value=""
					       onclick="checkAll(<?php echo count($this->newslettersubscrs); ?>);"/>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NEWSLETTER_USERNAME', 'user_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NEWSLETTER_SUBSCR_DATE', 'date', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NEWSLETTER', 'n.newsletter_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'subscription_id', $this->lists['order_Dir'], $this->lists['order']); ?>
				</th>

			</tr>
			</thead>
			<?php
			$model = $this->getModel('newslettersubscr');
			$k = 0;
			for ($i = 0, $n = count($this->newslettersubscrs); $i < $n; $i++)
			{
				$row = & $this->newslettersubscrs[$i];
				$row->id = $row->subscription_id;

				$link = JRoute::_('index.php?option=' . $option . '&view=newslettersubscr_detail&task=edit&cid[]=' . $row->subscription_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td width="5">
						<?php echo $this->pagination->getRowOffset($i); ?>
					</td>
					<td width="5">
						<?php echo JHTML::_('grid.id', $i, $row->id); ?>
					</td>
					<td>
						<a href="<?php echo $link; ?>"
						   title="<?php echo JText::_('COM_REDSHOP_EDIT_NEWSLETTER_SUBSCR'); ?>"><?php $row->user_id == 0 ? $name = $row->name : $name = $order_function->getUserFullname($row->user_id);  echo $name;?></a>
					</td>
					<td align="center" width="15%">
						<?php echo $config->convertDateFormat($row->date); ?>
					</td>
					<td width="15%">
						<?php echo $row->n_name; ?>
					</td>
					<td align="center" width="8%">
						<?php echo $published;?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->subscription_id; ?>
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

	<input type="hidden" name="view" value="newslettersubscr"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>