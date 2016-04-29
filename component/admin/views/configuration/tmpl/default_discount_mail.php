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
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_DISCOUNT_MAIL'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_MAIL_SEND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_MAIL_SEND_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_MAIL_SEND_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['discount_mail_send'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_MAIL1_AFTER_ORDER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MAIL1_AFTER_ORDER_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_MAIL1_AFTER_ORDER_LBL');
			?></label></span></td>
		<td><input type="text" name="days_mail1" id="days_mail1"
		           value="<?php
		           echo $this->config->get('DAYS_MAIL1');
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_MAIL2_AFTER_ORDER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MAIL2_AFTER_ORDER_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_MAIL2_AFTER_ORDER_LBL');
			?></label></span></td>
		<td><input type="text" name="days_mail2" id="days_mail2"
		           value="<?php
		           echo $this->config->get('DAYS_MAIL2');
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_MAIL3_AFTER_ORDER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MAIL3_AFTER_ORDER_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_MAIL3_AFTER_ORDER_LBL');
			?></label></span></td>
		<td><input type="text" name="days_mail3" id="days_mail3"
		           value="<?php
		           echo $this->config->get('DAYS_MAIL3');
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_COUPON_DURATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_COUPON_DURATION'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_COUPON_DURATION_LBL');
			?></label></span></td>
		<td><input type="text" name="discoupon_duration"
		           id="discoupon_duration"
		           value="<?php
		           echo $this->config->get('DISCOUPON_DURATION');
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_AFTER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_AFTER'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_SHIPPING_AFTER_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists['shipping_after'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PERCENT_OR_TOTAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PERCENT_OR_TOTAL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_PERCENT_OR_TOTAL_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['discoupon_percent_or_total'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_COUPON_VALUE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_COUPON_VALUE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_COUPON_VALUE_LBL');
			?></label></td>
		<td><input type="text" name="discoupon_value" id="discoupon_value"
		           value="<?php
		           echo $this->config->get('DISCOUPON_VALUE');
		           ?>">
		</td>
	</tr>

</table>
