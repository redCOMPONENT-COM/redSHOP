<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$producthelper = new producthelper;
$now = JFactory::getDate();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.amount.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_DISCOUNT_AMOUNT_MUST_FILLED', true ); ?>");
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
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_PRODUCT_AMOUNT')//echo JText::_('COM_REDSHOP_AMOUNT' ); ?>:
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
							<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>:
						</label>
					</td>
					<td>
						<?php
						echo $this->lists['category_ids'];
						?>
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
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->discount_product_id; ?>"/>
	<input type="hidden" name="layout" value="product"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="discount_detail"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
