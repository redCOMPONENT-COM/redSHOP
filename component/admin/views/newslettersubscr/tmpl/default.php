<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

$order_function = order_functions::getInstance();

$config = Redconfiguration::getInstance();
$model = $this->getModel('newslettersubscr');
?>
<script language="javascript" type="text/javascript">

	Joomla.submitbutton = function (pressbutton) {

		var form = document.adminForm;

		form.task.value = "";
		form.view.value = "newslettersubscr";

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'add') || (pressbutton == 'edit')
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

<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input placeholder="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_FILTER'); ?>" type="text" name="filter" id="filter" value="<?php echo $this->state->get('filter'); ?>">
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
						   onclick="return clearreset();" class="reset btn">
				</div>
			</div>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th>
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
				</th>
				<th>
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
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
				$row = $this->newslettersubscrs[$i];
				$row->id = $row->subscription_id;

				$link = JRoute::_('index.php?option=com_redshop&view=newslettersubscr_detail&task=edit&cid[]=' . $row->subscription_id);

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

	<input type="hidden" name="view" value="newslettersubscr"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
