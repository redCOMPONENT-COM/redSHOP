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
JHTMLBehavior::modal();
JLoader::load('RedshopHelperProduct');
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
			alert("<?php echo JText::_('COM_REDSHOP_VOUCHER_AMOUNT_MUST_FILLED', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_VOUCHER_CODE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="voucher_code" id="voucher_code" size="32"
						       maxlength="250" value="<?php echo $this->detail->voucher_code; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VOUCHER_CODE'), JText::_('COM_REDSHOP_VOUCHER_CODE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_VOUCHER_AMOUNT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="amount" id="voucher_amount" size="32" maxlength="250"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->amount); ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VOUCHER_AMOUNT'), JText::_('COM_REDSHOP_VOUCHER_AMOUNT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_VOUCHER_TYPE'); ?>:
					</td>
					<td>
						<?php echo $this->lists['voucher_type']; ?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VOUCHER_TYPE'), JText::_('COM_REDSHOP_VOUCHER_TYPE'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_VOUCHER_PRODUCT'); ?>:
					</td>
					<td>
						<?php echo $this->lists['voucher_product']; ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_START_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->start_date)
							$datee = date("d-m-Y", $this->detail->start_date);
						else
							$datee = null;
						echo JHTML::_('calendar', $datee, 'start_date', 'start_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_END_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->end_date)
							$datee = date("d-m-Y", $this->detail->end_date);
						else
							$datee = null;

						echo JHTML::_('calendar', $datee, 'end_date', 'end_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_FREE_SHIPPING'); ?>:
					</td>
					<td>
						<?php echo $this->lists['free_shipping']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="voucher_left">
							<?php echo JText::_('COM_REDSHOP_LBL_VOUCHER_LEFT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="voucher_left" id="voucher_left" size="20"
						       value="<?php echo $this->detail->voucher_left; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_VOUCHER_LEFT'), JText::_('COM_REDSHOP_LBL_VOUCHER_LEFT'), 'tooltip.png', '', '', false); ?>
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
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->voucher_id; ?>"/>
	<input type="hidden" name="old_voucher_code" value="<?php echo $this->detail->voucher_code; ?>"/>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="voucher_detail"/>
</form>
