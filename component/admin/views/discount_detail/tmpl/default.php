<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$producthelper = productHelper::getInstance();
$now = JFactory::getDate();

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		function parseDate(date) {
		   var parts = date.split("-");
		   return new Date(parts[2], parts[1] - 1, parts[0]);
		}

		if (form.name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_DISCOUNT_NAME_MUST_FILLED', true ); ?>");
		} else if (form.amount.value == "" || form.amount.value == 0) {
			alert("<?php echo JText::_('COM_REDSHOP_DISCOUNT_AMOUNT_MUST_FILLED', true ); ?>");
		} else if (parseDate(form.start_date.value) > parseDate(form.end_date.value)) {
 			alert("<?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE_END_DATE_CONDITION', true); ?>");
  		} else if (form.shopper_group_id.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_MUST_BE_SELECTED', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_NAME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="name" id="name" size="32" maxlength="250"
						       value="<?php echo $this->detail->name; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_NAME'), JText::_('COM_REDSHOP_DISCOUNT_NAME'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_AMOUNT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="amount" id="amount" size="32" maxlength="250"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->amount); ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_AMOUNT'), JText::_('COM_REDSHOP_AMOUNT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_CONDITION'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['discount_condition']; ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CONDITION'), JText::_('COM_REDSHOP_CONDITION'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE'); ?>:
					</td>
					<td>
						<?php echo $this->lists['discount_type']; ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_TYPE'), JText::_('COM_REDSHOP_DISCOUNT_TYPE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_AMOUNT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="discount_amount" id="discount_amount" size="32"
						       maxlength="250"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->discount_amount); ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_AMOUNT'), JText::_('COM_REDSHOP_DISCOUNT_AMOUNT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->start_date)
							$datee = date("d-m-Y", $this->detail->start_date);

						echo JHTML::_('calendar', $datee, 'start_date', 'start_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_END_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->end_date)
							$datee = date("d-m-Y", $this->detail->end_date);

						echo JHTML::_('calendar', $datee, 'end_date', 'end_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP'); ?>:
						</label>
					</td>
					<td>
						<?php
						echo $this->lists['shopper_group_id'];
						?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->discount_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="discount_detail"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
