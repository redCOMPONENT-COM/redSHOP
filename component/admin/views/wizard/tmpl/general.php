<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$params = JRequest::getVar('params');
?>
<style type="text/css">
fieldset.adminform label.radiobtn, table.admintable label.radiobtn
{
	float: none;
}
</style>
<fieldset class="adminform">
<div class="wizard_header">
	<div>&nbsp;</div>
	<div class="wizard_intro_text1"><?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_INTRO_TEXT1');?></div>
	<div>&nbsp;</div>
</div>
<div>
	<form action="?option=com_redshop" method="POST" name="installform" id="installform">
		<table class="admintable table">
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">
					<?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_SHOP_NAME');?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOP_NAME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_SHOP_NAME_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_SHOP_NAME_LBL');?></label></span>
				</td>
				<td>
					<input type="text" name="shop_name" id="shop_name"
					       value="<?php echo $this->temparray['SHOP_NAME']; ?>">
				</td>
			</tr>
			<tr style="display: none;">
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WELCOME_MESSAGE'); ?>::<?php echo JText::_('COM_REDSHOP_WELCOME_MESSAGE'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_WELCOME_MESSAGE');?></label></span>
				</td>
				<td>
					<input type="text" name="welcome_msg" id="welcome_msg"
					       value="<?php echo $this->temparray['WELCOME_MSG']; ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">
					<?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_ADMIN_EMAIL');?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADMINISTRATOR_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL'); ?></label></span>
				</td>
				<td>
					<input type="text" name="administrator_email" id="administrator_email"
					       value="<?php echo $this->temparray['ADMINISTRATOR_EMAIL']; ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">
					<?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_SHOP_COUNTRY');?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOP_COUNTRY'); ?>::<?php echo JText::_('COM_REDSHOP_SHOP_COUNTRY'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_SHOP_COUNTRY_LBL'); ?></label></span>
				</td>
				<td>
					<?php echo $this->lists ['shop_country'];?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_SHIPPING_COUNTRY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL');?></label></span>
				</td>
				<td>
					<?php echo $this->lists ['default_shipping_country']; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">
					<?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_COUNTRY_LIST');?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUNTRY_LIST_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_COUNTRY_LIST_LBL'); ?>">
			<label for="countryList"><?php echo JText::_('COM_REDSHOP_COUNTRY_LIST_LBL');?></label></span>
				</td>
				<td><?php echo $this->lists ['country_list'];?></td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">
					<?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_DEFAULT_DATEFORMAT');?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_DATEFORMAT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL');?></span>
				</td>
				<td>
					<?php echo $this->lists ['default_dateformat'];    ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="general_admin_email_info_txt">
					<?php echo JText::_('COM_REDSHOP_GENERAL_WIZARD_INVOICE_MAIL');?>
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_ENABLE'); ?>::<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL'); ?>">
		<label for="invoice_mail_enable"><?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL');?></label></span>
				</td>
				<td><?php echo $this->lists ['invoice_mail_enable'];?></td>
			</tr>
			<tr id="is_invoice_mail_enable">
				<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_SEND_OPTION'); ?>::<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL'); ?>">
		<label for="invoice_mail_send_option"><?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL');?></label></span>
				</td>
				<td><?php echo $this->lists ['invoice_mail_send_option'];?></td>
			</tr>
			<tr>
				<td>
					<input type="hidden" name="view" value="wizard"/>
					<input type="hidden" name="task" value="save"/>
					<input type="hidden" name="substep" value="<?php echo $params->step; ?>"/>
					<input type="hidden" name="go" value=""/>
				</td>
			</tr>
		</table>
	</form>
</div>
</fieldset>
<script>

	window.onload = enableInvoice('<?php echo $this->temparray["INVOICE_MAIL_ENABLE"];?>');

	function enableInvoice(en) {
		var send_opt_ele = document.getElementById('is_invoice_mail_enable');

		if (en == 0)
			send_opt_ele.style.display = "none";
		else
			send_opt_ele.style.display = "";
	}
</script>
