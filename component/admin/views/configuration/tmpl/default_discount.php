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
<legend><?php echo JText::_('COM_REDSHOP_DISCOUNT_SETTING_TAB'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_ENABLE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL');?></label>
	</span>
	<?php echo $this->lists ['discount_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_COUPON_INFO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUPON_INFO_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_COUPON_INFO_LBL');?>
		</label>
	</span>
	<?php echo $this->lists ['couponinfo'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL');
			?>
		</label></span>
	<?php
			echo $this->lists ['discount_type'];
			?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUPONS_ENABLE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL');
			?>
		</label></span>
	<?php echo $this->lists ['coupons_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_VOUCHERS_ENABLE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL');
			?>
		</label>
	</span>
	<?php echo $this->lists ['vouchers_enable']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_MAIL_SEND_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SPECIAL_DISCOUNT_MAIL_SEND_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_SPECIAL_DISCOUNT_MAIL_SEND_LBL');
			?></label>
	</span>
	<?php echo $this->lists ['special_discount_mail_send']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT_LBL');
			?></label></span>
	<?php
			echo $this->lists ['apply_voucher_coupon_already_discount'];
			?>
</div>
