<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTML::_('behavior.tooltip');

$section = JRequest::getVar('section');
$producthelper = productHelper::getInstance();
?>
<fieldset>
	<div style="float: right">
		<button type="button" class="btn btn-small" onclick="submitbutton('save');">
			<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
		</button>
		<button type="button" class="btn btn-small" onclick="window.parent.location.reload();">
			<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
		</button>
	</div>
	<div class="configuration"><?php echo JText::_('COM_REDSHOP_ADD_ATTRIBUTE_PRICE'); ?></div>
</fieldset>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if (form.product_price.value == "" || isNaN(form.product_price.value) || form.product_price.value == 0) {
			alert("ATTRIBUTE_PRICE_NOT_VALID");
			form.product_price.focus();
		} else if (isNaN(form.price_quantity_start.value)) {
			alert("QUANTITY_NOT_VALID");
			form.product_price.focus();
		} else if (isNaN(form.price_quantity_end.value)) {
			alert("QUANTITY_NOT_VALID");
			form.product_price.focus();
		} else if (isNaN(form.price_quantity_start.value) > isNaN(form.price_quantity_end.value)) {
			alert("ERROR_SAVING_PRICE_QUNTITY_DETAIL");
			form.product_price.focus();
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
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PROPERTY_NAME'); ?>:</td>
					<td><?php echo $this->property->property_name;?></td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_NAME'); ?>
						:
					</td>
					<td><?php echo $this->lists['shopper_group_name'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_LBL'); ?>
						:
					</td>
					<td><input class="text_area" type="text" name="product_price" id="product_price" size="10"
					           maxlength="10"
					           value="<?php echo $producthelper->redpriceDecimal($this->detail->product_price); ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_QUANTITY_START_LBL'); ?>
						:
					</td>
					<td><input class="text_area" type="text" name="price_quantity_start" id="price_quantity_start"
					           size="10" maxlength="10" value="<?php echo $this->detail->price_quantity_start; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_QUANTITY_END_LBL'); ?>:
					</td>
					<td><input class="text_area" type="text" name="price_quantity_end" id="price_quantity_end" size="10"
					           maxlength="20" value="<?php echo $this->detail->price_quantity_end; ?>"/></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_PRICE'); ?>:
					</td>
					<td><input class="text_area" type="text" name="discount_price" id="discount_price" size="10"
					           maxlength="10"
					           value="<?php echo $producthelper->redpriceDecimal($this->detail->discount_price); ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_START_DATE'); ?>
						:
					</td>
					<td>
						<?php
						$sdate = "";

						if ($this->detail->discount_start_date) :
							$sdate = date("d-m-Y", $this->detail->discount_start_date);
						endif;

						echo JHTML::_('calendar', $sdate, 'discount_start_date', 'discount_start_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19'));?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_DISCOUNT_END_DATE'); ?>
						:
					</td>
					<td>
					<?php
						$sdate = "";

						if ($this->detail->discount_end_date) :
							$sdate = date("d-m-Y", $this->detail->discount_end_date);
						endif;

						echo JHTML::_('calendar', $sdate, 'discount_end_date', 'discount_end_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '15', 'maxlength' => '19'));?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->price_id; ?>"/>
	<input type="hidden" name="section_id" value="<?php echo $this->detail->section_id; ?>"/>
	<input type="hidden" name="section" value="<?php echo $section; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="attributeprices_detail"/>
</form>
