<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');

$now = JFactory::getDate();

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		else {
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
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_DISCOUNT_NAME'); ?>:
					</td>
					<td>
						<input class="text_area" type="text" name="discount_name" id="discount_name" size="32"
						       maxlength="250" value="<?php echo $this->detail->discount_name; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_NAME'), JText::_('COM_REDSHOP_DISCOUNT_NAME'), 'tooltip.png', '', '', false); ?>
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
						       maxlength="250" value="<?php echo $this->detail->discount_amount; ?>"/>
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
						if ($this->detail->discount_startdate)
							$date = date("d-m-Y", $this->detail->discount_startdate);

						echo JHTML::_('calendar', $date, 'discount_startdate', 'discount_startdate', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19')); ?>
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
						if ($this->detail->discount_enddate)
							$date = date("d-m-Y", $this->detail->discount_enddate);

						echo JHTML::_('calendar', $date, 'discount_enddate', 'discount_enddate', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="product">
							<?php echo JText::_('COM_REDSHOP_DISCOUNT_PRODUCT'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['discount_product'];?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="category">
							<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>:
						</label>
					</td>
					<td>
						<?php
						echo $this->lists['categories'];?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="category">
							<?php echo JText::_('COM_REDSHOP_MANUFACTURER'); ?>:
						</label>
					</td>
					<td>
						<?php
						echo $this->lists['manufacturers'];?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->mass_discount_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="mass_discount_detail"/>
</form>
