<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$billing = $this->billing;
$is_company = $billing->is_company;
$extra_field = new extra_field();

if (!isset($billing->order_info_id))
	$billing->order_info_id = 0;

$Itemid = JRequest::getVar('Itemid');
require_once JPATH_COMPONENT . '/helpers/extra_field.php';
?>
<script type="text/javascript">

	function validateInfo() {

		var frm = document.updateBillingAdd;

		if (frm.firstname.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
			return false;
		}
		if (frm.lastname.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
			return false;
		}
		if (frm.address.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS')?>");
			return false;
		}
		if (frm.zipcode.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE')?>");
			return false;
		}
		if (frm.city.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_CITY')?>");
			return false;
		}

		if (frm.phone.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE')?>");
			return false;
		}

		if (frm.user_email.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
			return false;
		}

		return true;
	}

</script>
<form action="index.php?option=com_redshop" method="post" name="updateBillingAdd" id="updateBillingAdd">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_BILLING_INFORMATION'); ?></legend>
			<table class="admintable" border="0">

				<tr>
					<td width="100" align="right" class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_FIRSTNAME'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="firstname" size="32" maxlength="250"
						       value="<?php echo $billing->firstname; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_LASTNAME'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="lastname" size="32" maxlength="250"
						       value="<?php echo $billing->lastname; ?>"/>
					</td>
				</tr>


				<tr>
					<td width="100" align="right" class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_ADDRESS'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="address" size="32" maxlength="250"
						       value="<?php echo @$billing->address; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="address">
							<?php echo JText::_('COM_REDSHOP_ZIP'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="zipcode" size="32" maxlength="250"
						       value="<?php echo @$billing->zipcode; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_CITY'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="city" size="32" maxlength="250"
						       value="<?php echo @$billing->city; ?>"/>
					</td>
				</tr>
				<tr <?php if ($this->showcountry == 0) echo " style='display:none;'";?>>
					<td width="100" align="right" class="key">
						<label for="contact_info">
							<?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['country_code'];?>
					</td>
				</tr>
				<tr <?php if ($this->showcountry == 0) echo " style='display:none;'";?> >
					<td width="100" align="right" class="key">
						<label for="address">
							<?php echo JText::_('COM_REDSHOP_STATE'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['state_code'];?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label>
							<?php echo JText::_('COM_REDSHOP_PHONE'); ?>:
						</label>
					</td>
					<td>
						<input class="inputbox" type="text" name="phone" size="32" maxlength="250"
						       value="<?php echo @$billing->phone; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_EMAIL'); ?>:</td>
					<td><input class="inputbox" type="text" name="user_email" size="32" maxlength="250"
					           value="<?php echo @$billing->user_email; ?>"/></td>
				</tr>
				<?php
				if ($is_company)
				{
					?>
					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_VAT_NUMBER'); ?>:
						</td>
						<td><input class="inputbox" type="text" name="vat_number" size="32" maxlength="250"
						           value="<?php echo $billing->vat_number; ?>"/></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_EAN_NUMBER'); ?>:
						</td>
						<td><input class="inputbox" type="text" name="ean_number" size="32" maxlength="250"
						           value="<?php echo $billing->ean_number; ?>"/></td>
					</tr>
					<!-- <tr>
									<td width="100" align="right"><?php echo JText::_('COM_REDSHOP_REQUISITION_NUMBER' ); ?>:</td>
									<td><?php echo ($billing->requisition_number!="") ? $billing->requisition_number : "N/A"; ?></td>
								</tr>-->
					<?php
					$fields = $extra_field->list_all_field_display(8, $billing->users_info_id);
				}
				else
				{
					$fields = $extra_field->list_all_field_display(7, $billing->users_info_id);
				}
				echo $fields; ?>

				<tr>
					<td></td>
					<td>
						<input type="submit" name="submit" value="<?php echo JText::_('COM_REDSHOP_SAVE'); ?>"
						       onclick="return validateInfo();">
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>
	<input type="hidden" name="task" value="updateBillingAdd"/>
	<input type="hidden" name="view" value="order_detail"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->order_id; ?>"/>
	<input type="hidden" name="order_info_id" value="<?php echo $billing->order_info_id; ?>"/>
	<input type="hidden" name="user_id" value="<?php echo $billing->user_id; ?>"/>
	<input type="hidden" name="users_info_id" value="<?php echo $billing->users_info_id; ?>"/>
	<input type="hidden" name="shopper_group_id" value="<?php echo $billing->shopper_group_id; ?>"/>
	<input type="hidden" name="tax_exempt_approved" value="<?php echo $billing->tax_exempt_approved; ?>"/>
	<input type="hidden" name="approved" value="<?php echo $billing->approved; ?>"/>
	<input type="hidden" name="is_company" value="<?php echo $billing->is_company; ?>"/>
	<input type="hidden" name="address_type" value="BT"/>


</form>
