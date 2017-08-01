<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<div class="discount_intro_text">
	<?php echo JText::_('COM_REDSHOP_DISCOUNT_INTRO_TEXT'); ?>
</div>
<div>&nbsp;</div>
<fieldset>
	<table class="admintable table">
		<tr>
			<td colspan="2" class="discount_enable_intro_text">
				<?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL'); ?>">
				<label for="name"><?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL');?></label>
			</span>
			</td>
			<td><?php echo $this->lists ['discount_enable']; ?></td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<table class="admintable table">
		<tr>
			<td colspan="2" class="coupons_enable_intro_text">
				<?php echo JText::_('COM_REDSHOP_COUPONS_ENABLE_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUPONS_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_COUPONS_ENABLE_LBL');?></label></span>
			</td>
			<td><?php echo $this->lists ['coupons_enable'];?></td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<table class="admintable table">
		<tr>
			<td colspan="2" class="voucher_enable_intro_text">
				<?php echo JText::_('COM_REDSHOP_VOUCHER_ENABLE_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_VOUCHERS_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_VOUCHERS_ENABLE_LBL');?></label></span>
			</td>
			<td><?php echo $this->lists ['vouchers_enable'];?></td>
		</tr>
	</table>
</fieldset>
<fieldset>
	<table class="admintable table">
		<tr>
			<td colspan="2" class="discount_type_intro_text">
				<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_TYPE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL'); ?>">
				<label for="name"><?php echo JText::_('COM_REDSHOP_DISCOUNT_TYPE_LBL');?></label>
			</span>
			</td>
			<td><?php echo $this->lists ['discount_type'];?></td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<table class="admintable table">
		<tr>
			<td colspan="2" class="discount_type_intro_text">
				<?php echo JText::_('COM_REDSHOP_SHIPPING_AFTER_INTRO_TEXT'); ?>
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHIPPING_AFTER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_SHIPPING_AFTER_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_SHIPPING_AFTER_LBL');?></label></span>
			</td>
			<td><?php echo $this->lists['shipping_after'];?></td>
		</tr>
	</table>
</fieldset>
