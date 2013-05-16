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
<table class="admintable" id="measurement">
	<tr>
		<td class="key">
			<label for="name">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_POST_DK_INTEGRATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POST_DK_INTEGRATION_LBL'); ?>">
					<?php echo JText::_('COM_REDSHOP_POST_DK_INTEGRATION_LBL');?>
				</span>
			</label>
		</td>
		<td><?php echo $this->lists['postdk_integration'];?></td>
	</tr>
	<tr>
		<td class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_POST_DK_CUSTOMER_ID_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POST_DK_CUSTOMER_ID_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_POST_DK_CUSTOMER_ID_LBL');?>
			</span>
		</td>
		<td><input type="text" name="postdk_customer_no" id="postdk_customer_no"
		           value="<?php echo POSTDK_CUSTOMER_NO; ?>"></td>
	</tr>
	<tr>
		<td class="key">
			<label for="name">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_POST_DK_PASSWORD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POST_DK_PASSWORD_LBL'); ?>">
					<?php echo JText::_('COM_REDSHOP_POST_DK_PASSWORD_LBL');?>
				</span>
			</label>
		</td>
		<td><input type="password" name="postdk_customer_password" id="postdk_customer_password"
		           value="<?php echo POSTDK_CUSTOMER_PASSWORD; ?>"></td>
	</tr>
	<tr>
		<td class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_POSTDANMARK_ADDRESS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POSTDANMARK_ADDRESS_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_POSTDANMARK_ADDRESS_LBL');?>
			</span>
		</td>
		<td><input type="text" name="postdk_address" id="postdk_address" value="<?php echo POSTDANMARK_ADDRESS; ?>">
		</td>
	</tr>
	<tr>
		<td class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_POSTDANMARK_POSTALCODE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POSTDANMARK_POSTALCODE_LBL'); ?>">
				<?php echo JText::_('COM_REDSHOP_POSTDANMARK_POSTALCODE_LBL');?>
			</span>
		</td>
		<td><input type="text" name="postdk_postalcode" id="postdk_postalcode"
		           value="<?php echo POSTDANMARK_POSTALCODE; ?>"></td>
	</tr>
	<tr>
		<td class="key">
			<label for="name">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_DETAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_DETAIL'); ?>">
					<?php echo JText::_('COM_REDSHOP_SHOW_PRODUCT_DETAIL_LBL');?>
				</span>
			</label>
		</td>
		<td><?php echo $this->lists['show_product_detail'];?></td>
	</tr>
	<tr>
		<td class="key">
			<label for="name">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_TRACK_AND_TRACE_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_TRACK_AND_TRACE_EMAIL'); ?>">
					<?php echo JText::_('COM_REDSHOP_ENABLE_TRACK_AND_TRACE_EMAIL_LBL');?>
				</span>
			</label>
		</td>
		<td><?php echo $this->lists['webpack_enable_email_track'];?></td>
	</tr>
	<tr>
		<td class="key">
			<label for="name">
				<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SMS_FROM_WEBPACK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SMS_FROM_WEBPACK'); ?>">
					<?php echo JText::_('COM_REDSHOP_ENABLE_SMS_FROM_WEBPACK_LBL');?>
				</span>
			</label>
		</td>
		<td><?php echo $this->lists['webpack_enable_sms'];?></td>
	</tr>
</table>
