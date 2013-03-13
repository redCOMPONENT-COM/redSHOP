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
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_DISCOUNT_SETTING_TAB'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL');
			?>
		</label></span></td>
		<td>
			<?php
			echo $this->lists ['discount_type'];
			?>
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUPONS_ENABLE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL');
			?>
		</label></span></td>
		<td>
			<?php echo $this->lists ['coupons_enable'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_VOUCHERS_ENABLE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL');
			?>
		</label></span></td>
		<td>
			<?php
			echo $this->lists ['vouchers_enable'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_MAIL_SEND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SPECIAL_DISCOUNT_MAIL_SEND_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_MAIL_SEND_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['special_discount_mail_send'];
			?>
		</td>
	</tr>
</table>
