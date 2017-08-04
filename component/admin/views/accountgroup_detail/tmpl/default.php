<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;    ?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
		if ((form.accountgroup_name.value) == "") {
			alert("<?php echo JText::_('COM_REDSHOP_ACCOUNTGROUP_MUST_HAVE_A_NAME', true ); ?>");
			return false;
		}
		else if ((form.economic_nonvat_account.value) == "" || isNaN(form.economic_nonvat_account.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_ECONOMIC_NON_VAT_ACCOUNT_NUMBER', true ); ?>");
			return false;
		}
		else if ((form.economic_vat_account.value) == "" || isNaN(form.economic_vat_account.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_ECONOMIC_VAT_ACCOUNT_NUMBER', true ); ?>");
			return false;
		}
		else if ((form.economic_discount_product_number.value) == "") {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_ECONOMIC_DISCOUNT_PRODUCT_NUMBER', true ); ?>");
			return false;
		}
		else if ((form.economic_discount_vat_account.value) == "" || isNaN(form.economic_discount_vat_account.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_PRODUCT_GROUP_FOR_DISCOUNT_VAT', true ); ?>");
			return false;
		}
		else if ((form.economic_discount_nonvat_account.value) == "" || isNaN(form.economic_discount_nonvat_account.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_PRODUCT_GROUP_FOR_DISCOUNT_NOVAT', true ); ?>");
			return false;
		}
		else if ((form.economic_shipping_nonvat_account.value) == "" || isNaN(form.economic_shipping_nonvat_account.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_PRODUCT_GROUP_FOR_SHIPPING_NOVAT', true ); ?>");
			return false;
		}
		else if ((form.economic_shipping_vat_account.value) == "" || isNaN(form.economic_shipping_vat_account.value)) {
			alert("<?php echo JText::_('COM_REDSHOP_ENTER_PRODUCT_GROUP_FOR_SHIPPING', true ); ?>");
			return false;
		}
		else {
			submitform(pressbutton);
		}
	}
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>
			<table class="admintable table">
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ACCOUNTGROUP_NAME'); ?></td>
					<td><input class="text_area" type="text" name="accountgroup_name" id="accountgroup_name"
					           value="<?php echo $this->detail->accountgroup_name; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_VAT_ACCOUNT_NUMBER'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_vat_account" id="economic_vat_account"
					           value="<?php echo $this->detail->economic_vat_account; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_NON_VAT_ACCOUNT_NUMBER'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_nonvat_account" id="economic_nonvat_account"
					           value="<?php echo $this->detail->economic_nonvat_account; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_DISCOUNT_PRODUCT_NUMBER_LBL'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_discount_product_number"
					           id="economic_discount_product_number"
					           value="<?php echo $this->detail->economic_discount_product_number; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_DISCOUNT_VAT_ACCOUNT'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_discount_vat_account"
					           id="economic_discount_vat_account"
					           value="<?php echo $this->detail->economic_discount_vat_account; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_DISCOUNT_NONVAT_ACCOUNT'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_discount_nonvat_account"
					           id="economic_discount_nonvat_account"
					           value="<?php echo $this->detail->economic_discount_nonvat_account; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_SHIPPING_VAT_ACCOUNT'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_shipping_vat_account"
					           id="economic_shipping_vat_account"
					           value="<?php echo $this->detail->economic_shipping_vat_account; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right"
					    class="key"><?php echo JText::_('COM_REDSHOP_ECONOMIC_SHIPPING_NONVAT_ACCOUNT'); ?>:
					</td>
					<td><input class="text_area" type="text" name="economic_shipping_nonvat_account"
					           id="economic_shipping_nonvat_account"
					           value="<?php echo $this->detail->economic_shipping_nonvat_account; ?>"/></td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
					<td><?php echo $this->lists['published']; ?></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->accountgroup_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="accountgroup_detail"/>
</form>
