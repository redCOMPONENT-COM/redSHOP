<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
$uri = JURI::getInstance();
$url = $uri->root();
$addtocart_path = "/components/com_redshop/assets/images/";
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td width="50%">
			<fieldset class="adminform">
				<table class="admintable">
					<tr>
						<td class="config_param"><?php echo JText::_('COM_REDSHOP_PAYMENT_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_PAYMENT_CALCULATION_ON_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PAYMENT_CALCULATION_ON'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_PAYMENT_CALCULATION_ON_LBL');?></label></span>
						</td>
						<td><?php echo $this->lists ['payment_calculation_on']; ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_FIRST_INVOICE_NUMBER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_FIRST_INVOICE_NUMBER_LBL'); ?>">
				<label for="first_invoice_number"><?php echo JText::_('COM_REDSHOP_FIRST_INVOICE_NUMBER_LBL');?></label></span>
						</td>
						<td>
							<input type="text" name="first_invoice_number" id="first_invoice_number"
							       value="<?php echo FIRST_INVOICE_NUMBER; ?>">
						</td>
					</tr>
				</table>
			</fieldset>

			<fieldset class="adminform">
				<table class="admintable" width="100%">
					<tr>
						<td class="config_param"><?php echo JText::_('COM_REDSHOP_SHIPPING_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_OPTIONAL_SHIPPING_ADDRESS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_OPTIONAL_SHIPPING_ADDRESS'); ?>">
					<label
						for=optional_shipping_address><?php echo JText::_('COM_REDSHOP_OPTIONAL_SHIPPING_ADDRESS_LBL');?></label></span>
						</td>
						<td><?php echo $this->lists ['optional_shipping_address'];?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_METHOD_ENABLE'); ?>">
					<label
						for=shipping_method_enable><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_ENABLE_LBL');?></label></span>
						</td>
						<td><?php echo $this->lists ['shipping_method_enable'];?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SPLIT_DELIVERY_COST'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SPLIT_DELIVERY_COST'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_SPLIT_DELIVERY_COST');?></label></span>
						</td>
						<td>
							<input type="text" name="split_delivery_cost" id="split_delivery_cost"
							       value="<?php echo SPLIT_DELIVERY_COST; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_TIME_DIFF_SPILT_CALCULATION'); ?>::<?php echo JText::_('COM_REDSHOP_TIME_DIFF_SPILT_CALCULATION'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_TIME_DIFF_SPILT_CALCULATION');?></label></span>
						</td>
						<td>
							<input type="text" name="time_diff_split_delivery" id="time_diff_split_delivery"
							       value="<?php echo TIME_DIFF_SPLIT_DELIVERY; ?>">
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DELIVERY_RULE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DELIVERY_RULE'); ?>">
					<label for="name"><?php echo JText::_('COM_REDSHOP_DELIVERY_RULE');?></label></span>
						</td>
						<td>
							<input type="text" name="delivery_rule" id="delivery_rule"
							       value="<?php echo DELIVERY_RULE; ?>">
						</td>
					</tr>


				</table>
			</fieldset>

			<fieldset class="adminform">
				<table class="admintable" width="100%">
					<tr>
						<td class="config_param"><?php echo JText::_('COM_REDSHOP_SECURING_SETTINGS'); ?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SSL_ENABLE_IN_CHECKOUT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_CHECKOUT_LBL'); ?>">
					<label
						for="ssl_enable_in_checkout"><?php echo JText::_('COM_REDSHOP_SSL_ENABLE_IN_CHECKOUT_LBL'); ?></label></span>
						</td>
						<td><?php echo $this->lists ['ssl_enable_in_checkout'];?></td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
					<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_BACKEND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SSL_ENABLE_IN_BACKEND'); ?>">
					<?php echo JText::_('COM_REDSHOP_SSL_ENABLE_IN_BACKEND_LBL');?></span>
						</td>
						<td><?php echo $this->lists ['ssl_enable_in_backend'];?></td>
					</tr>
				</table>
			</fieldset>
		</td>
	</tr>
</table>
