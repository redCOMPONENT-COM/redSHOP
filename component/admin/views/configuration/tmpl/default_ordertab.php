<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');
defined('_JEXEC') or die ('Restricted access');
$uri = JURI::getInstance();
$url = $uri->root();
$ord_path = "/components/com_redshop/assets/images/";

?>
<div id="config-document">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_ORDERS'); ?></legend>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td width="50%">
					<fieldset class="adminform">
						<table class="admintable">
							<tr>
								<td class="config_param"><?php echo JText::_('COM_REDSHOP_ORDER_MAIN_SETTINGS'); ?></td>
							</tr>
							<tr>
								<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_LISTS_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_LISTS_INTRO_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ORDER_LISTS_INTRO_LBL');?>:</span>
								</td>
								<td>
									<textarea class="text_area" type="text" name="order_lists_introtext"
									          id="order_lists_introtext" rows="4"
									          cols="40"/><?php echo stripslashes(ORDER_LIST_INTROTEXT); ?></textarea>
								</td>
							</tr>
							<tr>
								<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_DETAILS_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_DETAILS_INTRO_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ORDER_DETAILS_INTRO_LBL');?>:</span>
								</td>
								<td>
									<textarea class="text_area" type="text" name="order_detail_introtext"
									          id="order_detail_introtext" rows="4"
									          cols="40"/><?php echo stripslashes(ORDER_DETAIL_INTROTEXT); ?></textarea>
								</td>
							</tr>

							<tr>
								<td align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_RECEIPT_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ORDER_RECEIPT_INTRO'); ?>">
		<?php echo JText::_('COM_REDSHOP_ORDER_RECEIPT_INTRO_LBL'); ?>:</span>
								</td>
								<td>
									<textarea class="text_area" type="text" name="order_receipt_introtext"
									          id="order_receipt_introtext" rows="4"
									          cols="40"/><?php echo stripslashes(ORDER_RECEIPT_INTROTEXT); ?></textarea>
								</td>
							</tr>

							<tr>
								<td align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER'); ?>">
		<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_MAIL_TO_CUSTOMER_LBL'); ?>:</span>
								</td>
								<td>
									<?php echo $this->lists ['send_mail_to_customer'];?>
								</td>
							</tr>

						</table>
					</fieldset>
				</td>
				<td></td>
			</tr>
		</table>
	</fieldset>
</div>
