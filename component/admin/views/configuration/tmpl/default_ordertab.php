<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<div id="config-document">
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
</div>
