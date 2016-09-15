<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$Itemid = JRequest::getInt('Itemid');
$userhelper = rsUserHelper::getInstance();

$post = (array) $this->shippingaddresses;

$post['firstname_ST']    = $post['firstname'];
$post['lastname_ST']     = $post['lastname'];
$post['address_ST']      = $post['address'];
$post['city_ST']         = $post['city'];
$post['zipcode_ST']      = $post['zipcode'];
$post['phone_ST']        = $post['phone'];
$post['country_code_ST'] = $post['country_code'];
$post['state_code_ST']   = $post['state_code'];
?>
<script type="text/javascript">
	function cancelForm(frm) {
		frm.task.value = 'cancel';
		frm.submit();
	}

	function submitForm(frm)
	{
		if (validateInfo())
		{
			frm.submit();
		}
	}

	function validateInfo() {
		var frm = document.adminForm;

		if (frm.firstname_ST.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_FIRST_NAME')?>");
			return false;
		}

		if (frm.lastname_ST.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME')?>");
			return false;
		}

		if (frm.address_ST.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS')?>");
			return false;
		}

		if (frm.zipcode_ST.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_ZIPCODE')?>");
			return false;
		}

		if (frm.city_ST.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_CITY')?>");
			return false;
		}

		if (frm.phone_ST.value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_PHONE')?>");
			return false;
		}

		return true;
	}

</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">

	<div id="divShipping">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_SHIPPING_ADDRESSES');?></legend>
			<?php    echo $userhelper->getShippingTable($post, $this->billingaddresses->is_company, $this->lists);    ?>
			<table cellspacing="3" cellpadding="0" border="0" width="100%">
				<tr>
					<td align="right"><input type="button" class="button btn" name="back"
					                         value="<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>"
					                         onclick="javascript:cancelForm(this.form);"></td>
					<td align="left"><input type="submit" class="button btn btn-primary" name="submitbtn"
					                        onclick="javascript:validateInfo(this.form);"
					                        value="<?php echo JText::_('COM_REDSHOP_SAVE'); ?>"></td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="hidden" name="cid" value="<?php echo $this->shippingaddresses->users_info_id; ?>"/>
	<input type="hidden" name="user_id" value="<?php echo $this->billingaddresses->user_id; ?>"/>
	<input type="hidden" name="is_company" value="<?php echo $this->billingaddresses->is_company; ?>"/>
	<input type="hidden" name="email" value="<?php echo $this->billingaddresses->user_email; ?>"/>
	<input type="hidden" name="shopper_group_id" value="<?php echo $this->billingaddresses->shopper_group_id; ?>"/>
	<input type="hidden" name="company_name" value="<?php echo $this->billingaddresses->company_name; ?>"/>
	<input type="hidden" name="vat_number" value="<?php echo $this->billingaddresses->vat_number; ?>"/>
	<input type="hidden" name="tax_exempt" value="<?php echo $this->billingaddresses->tax_exempt; ?>"/>
	<input type="hidden" name="requesting_tax_exempt"
	       value="<?php echo $this->billingaddresses->requesting_tax_exempt; ?>"/>
	<input type="hidden" name="tax_exempt_approved"
	       value="<?php echo $this->billingaddresses->tax_exempt_approved; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="address_type" value="ST"/>
	<input type="hidden" name="view" value="account_shipto"/>
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>"/>
	<input type="hidden" name="option" value="com_redshop"/>
</form>
