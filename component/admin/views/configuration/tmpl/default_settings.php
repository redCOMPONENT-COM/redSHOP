<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<table class="admintable" width="100%">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_STORE_SETTINGS'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_SHOP_NAME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOP_NAME_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_SHOP_NAME_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="shop_name" id="shop_name" value="<?php echo SHOP_NAME; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_SHOP_COUNTRY'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOP_COUNTRY'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_SHOP_COUNTRY_LBL'); ?></label></span>
		</td>
		<td>
			<?php echo $this->lists ['shop_country'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_COUNTRY_LIST_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUNTRY_LIST_LBL'); ?>">
			<label for="countryList"><?php echo JText::_('COM_REDSHOP_COUNTRY_LIST_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['country_list'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_SHIPPING_COUNTRY_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_SHIPPING_COUNTRY_LBL');?></label></span>
		</td>
		<td>
			<?php echo $this->lists ['default_shipping_country']; ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_DATEFORMAT_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_DEFAULT_DATEFORMAT_LBL');?></span>
		</td>
		<td>
			<?php echo $this->lists ['default_dateformat'];    ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_WELCOME_MESSAGE'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WELCOME_MESSAGE'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_WELCOME_MESSAGE');?></label>
		</td>
		<td>
			<input type="text" name="welcome_msg" id="welcome_msg" value="<?php echo WELCOME_MSG; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADMINISTRATOR_EMAIL_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_ADMINISTRATOR_EMAIL_LBL'); ?></label></span>
		</td>
		<td>
			<input type="text" name="administrator_email" id="administrator_email"
			       value="<?php echo ADMINISTRATOR_EMAIL; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_MAIL_AFTER'); ?>">
		<label for="order_mail_after"><?php echo JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['order_mail_after'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_ENABLE'); ?>">
		<label for="invoice_mail_enable"><?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['invoice_mail_enable'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_SEND_OPTION'); ?>">
		<label for="invoice_mail_send_option"><?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['invoice_mail_send_option'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_BACKEND_ACCESS_LEVEL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_BACKEND_ACCESS_LEVEL'); ?>">
		<label for="invoice_mail_send_option"><?php echo JText::_('COM_REDSHOP_BACKEND_ACCESS_LEVEL');?></label></span>
		</td>
		<td><?php echo $this->lists ['enable_backendaccess'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_USE_ENCODING_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_ENCODING'); ?>">
		<label for="invoice_mail_send_option"><?php echo JText::_('COM_REDSHOP_USE_ENCODING_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['use_encoding'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TABLE_PREFIX'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_TABLE_PREFIX'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_TABLE_PREFIX');?></label></span>
		</td>
		<td>
			<input type="text" name="table_prefix" id="table_prefix" value="<?php echo TABLE_PREFIX; ?>"
			       readonly="readonly">
		</td>
	</tr>
	</tr>
</table>
