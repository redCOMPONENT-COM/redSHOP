<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
$producthelper = productHelper::getInstance();

$quotationHelper = quotationHelper::getInstance();

$config = Redconfiguration::getInstance();


$lists = $this->lists;
$model = $this->getModel('quotation');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if ((pressbutton == 'edit') || (pressbutton == 'remove')) {
			form.view.value = "quotation_detail";
		} else if ((pressbutton == 'add')) {
			form.view.value = "addquotation_detail";
		}

		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_redshop&view=quotation'); ?>" method="post"
      name="adminForm" id="adminForm">
	<div id="editcell">
		<div class="filterTool">
			<div class="filterItem">
				<div class="btn-wrapper input-append">
					<input placeholder="<?php echo JText::_('COM_REDSHOP_FILTER'); ?>" type="text" name="filter" id="filter" value="<?php echo $this->state->get('filter'); ?>" />
					<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
					<input type="reset" class="btn reset" name="reset" id="reset" value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>"
						   onclick="document.getElementById('filter').value='';document.getElementById('filter_status').value='0';this.form.submit();">
				</div>
			</div>
			<div class="filterItem">
				<?php echo JText::_('COM_REDSHOP_QUOTATION_STATUS') . ": " . $lists['filter_status']; ?>
			</div>
		</div>
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM'); ?></th>
				<th width="5%" class="title">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
				</th>
				<th class="title" width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_ID', 'quotation_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th class="title" width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_NUMBER', 'quotation_number', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="20%">
					<?php echo JText::_('COM_REDSHOP_FULLNAME'); ?></th>
				<th width="20%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_STATUS', 'quotation_status', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_TOTAL', 'quotation_total', $this->lists['order_Dir'], $this->lists['order']); ?></th>
				<th width="10%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_QUOTATION_DATE', 'quotation_cdate', $this->lists['order_Dir'], $this->lists['order']); ?></th>
			</tr>
			</thead>
			<?php
			$k = 0;
			for ($i = 0, $n = count($this->quotation); $i < $n; $i++)
			{
				$row = $this->quotation[$i];
				$row->id = $row->quotation_id;
				$display = $row->user_email;
				if ($row->user_id)
				{
					$userarr = $producthelper->getUserInformation($row->user_id);
					if (count($userarr) > 0)
					{
						$display = $userarr->firstname . ' ' . $userarr->lastname;
						$display .= ($userarr->is_company && $userarr->company_name != "") ? "<br>" . $userarr->company_name : "";
					}
				}
				$link = JRoute::_('index.php?option=com_redshop&view=quotation_detail&task=edit&cid[]=' . $row->quotation_id);
				$status = $quotationHelper->getQuotationStatusName($row->quotation_status);
				if ($row->quotation_status == 5)
				{
					$status .= " (" . JText::_('COM_REDSHOP_ORDER_ID') . "-" . $row->order_id . " )";
				}    ?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->id); ?></td>
					<td align="center"><a href="<?php echo $link; ?>"
					                      title="<?php echo JText::_('COM_REDSHOP_VIEW_QUOTATION'); ?>"><?php echo $row->quotation_id; ?></a>
					</td>
					<td align="center"><a href="<?php echo $link; ?>"
					                      title="<?php echo JText::_('COM_REDSHOP_VIEW_QUOTATION'); ?>"><?php echo $row->quotation_number; ?></a>
					</td>
					<td><?php echo $display; ?></td>
					<td align="center"><?php echo $status;?></td>
					<td align="center"><?php echo $producthelper->getProductFormattedPrice($row->quotation_total); ?></td>
					<td align="center"><?php echo $config->convertDateFormat($row->quotation_cdate); ?></td>
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

	<input type="hidden" name="view" value="quotation"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
