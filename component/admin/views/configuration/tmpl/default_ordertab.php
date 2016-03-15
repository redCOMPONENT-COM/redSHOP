<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<div class="row-fluid">
	<div class="span8" id="config-document">
		<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_ORDER_MAIN_SETTINGS'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
							title="<?php echo JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_MAIL_AFTER'); ?>"
						>
							<label for="order_mail_after">
								<?php echo JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_LBL');?>
							</label>
						</span>
					</td>
					<td><?php echo $this->lists['order_mail_after'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
							title="<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_ENABLE'); ?>"
						>
							<label for="invoice_mail_enable">
								<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_ENABLE_LBL');?>
							</label>
						</span>
					</td>
					<td><?php echo $this->lists ['invoice_mail_enable'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
						    title="<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_MAIL_SEND_OPTION'); ?>"
						>
							<label for="invoice_mail_send_option">
								<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL_SEND_OPTION_LBL');?>
							</label>
						</span>
					</td>
					<td><?php echo $this->lists ['invoice_mail_send_option'];?></td>
				</tr>
				<tr>
					<td align="right" class="key">
						<span
							class="editlinktip hasTip"
							title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER'); ?>"
						>
							<label for="send_mail_to_customer">
								<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER_LBL');?>
							</label>
						</span>
					</td>
					<td>
						<?php echo $this->lists ['send_mail_to_customer'];?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<div class="span4">
		<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_ORDER_INVOICE_SETTINGS'); ?></legend>
			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
						    title="<?php echo JText::_('COM_REDSHOP_FIRST_INVOICE_NUMBER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_FIRST_INVOICE_NUMBER_LBL'); ?>"
						>
							<label for="first_invoice_number">
								<?php echo JText::_('COM_REDSHOP_FIRST_INVOICE_NUMBER_LBL');?>
							</label>
						</span>
					</td>
					<td>
						<input
							type="text"
							name="first_invoice_number"
							id="first_invoice_number"
						    value="<?php echo FIRST_INVOICE_NUMBER; ?>"
						>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
						    title="<?php echo JText::_('COM_REDSHOP_ORDER_NUMBER_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_NUMBER_TEMPLATE'); ?>"
						   >
							<label for="invoice_number_template">
								<?php echo JText::_('COM_REDSHOP_ORDER_NUMBER_TEMPLATE_LBL');?>
							</label>
						</span>
					</td>
					<td>
						<input
							type="text"
							name="invoice_number_template"
							id="invoice_number_template"
						    value="<?php echo INVOICE_NUMBER_TEMPLATE; ?>"
						>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
						    title="<?php echo JText::_('COM_REDSHOP_INVOICE_NUMBER_TEMPLATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_NUMBER_TEMPLATE'); ?>"
						   >
							<label for="invoice_number_template">
								<?php echo JText::_('COM_REDSHOP_INVOICE_NUMBER_TEMPLATE_LBL');?>
							</label>
						</span>
					</td>
					<td>
						<input
							type="text"
							name="real_invoice_number_template"
							id="real_invoice_number_template"
						    value="<?php echo REAL_INVOICE_NUMBER_TEMPLATE; ?>"
						>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span
							class="editlinktip hasTip"
						    title="<?php echo JText::_('COM_REDSHOP_INVOICE_NUMBER_FOR_FREE_ORDER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INVOICE_NUMBER_FOR_FREE_ORDER_LBL'); ?>"
						   >
							<label for="invoice_number_template">
								<?php echo JText::_('COM_REDSHOP_INVOICE_NUMBER_FOR_FREE_ORDER_LBL');?>
							</label>
						</span>
					</td>
					<td>
						<?php
							echo JHtml::_(
								'redshopselect.booleanlist',
								'invoice_number_for_free_order',
								'',
								INVOICE_NUMBER_FOR_FREE_ORDER
							);
						?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
</div>
