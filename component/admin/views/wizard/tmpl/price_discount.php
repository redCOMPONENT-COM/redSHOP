<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

?>
<div class="discount_intro_text">
	<?php echo JText::_('DISCOUNT_INTRO_TEXT'); ?>
</div>
<div>&nbsp;</div>
<fieldset>
<table class="admintable">
	<tr>
		<td colspan="2" class="discount_enable_intro_text">
			<?php echo JText::_('DISCOUNT_ENABLE_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DISCOUNT_ENABLE_LBL' ); ?>::<?php echo JText::_( 'DISCOUNT_ENABLE_LBL' ); ?>">
				<label for="name"><?php echo JText::_ ( 'DISCOUNT_ENABLE_LBL' );?></label>
			</span>
		</td>
		<td><?php echo $this->lists ['discount_enable']; ?></td>
	</tr>
</table>
</fieldset>
<fieldset>
<table class="admintable">
	<tr>
		<td colspan="2" class="coupons_enable_intro_text">
			<?php echo JText::_('COUPONS_ENABLE_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_COUPONS_ENABLE_LBL' ); ?>::<?php echo JText::_( 'COUPONS_ENABLE_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'COUPONS_ENABLE_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['coupons_enable'];?></td>
	</tr>
</table>
</fieldset>
<fieldset>
<table class="admintable">
	<tr>
		<td colspan="2" class="voucher_enable_intro_text">
			<?php echo JText::_('VOUCHER_ENABLE_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_VOUCHERS_ENABLE_LBL' ); ?>::<?php echo JText::_( 'VOUCHERS_ENABLE_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'VOUCHERS_ENABLE_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['vouchers_enable'];?></td>
	</tr>
</table>
</fieldset>
<fieldset>
<table class="admintable">
	<tr>
		<td colspan="2" class="discount_type_intro_text">
			<?php echo JText::_('DISCOUNT_TYPE_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DISCOUNT_TYPE_LBL' ); ?>::<?php echo JText::_( 'DISCOUNT_TYPE_LBL' ); ?>" >
				<label for="name"><?php echo JText::_ ( 'DISCOUNT_TYPE_LBL' );?></label>
			</span>
		</td>
		<td><?php echo $this->lists ['discount_type'];?></td>
	</tr>
</table>
</fieldset>

<fieldset>
<table class="admintable">
	<tr>
		<td colspan="2" class="discount_type_intro_text">
			<?php echo JText::_('SHIPPING_AFTER_INTRO_TEXT'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_SHIPPING_AFTER_LBL' ); ?>::<?php echo JText::_( 'SHIPPING_AFTER_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'SHIPPING_AFTER_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists['shipping_after'];?></td>
	</tr>
</table>
</fieldset>