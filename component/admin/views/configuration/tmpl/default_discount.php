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
<table class="admintable">
<tr><td class="config_param"><?php echo JText::_( 'COUPONS_VOUCHERS_SETTING_TAB' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'DISCOUNT_TYPE_LBL' ); ?>::<?php echo JText::_( 'DISCOUNT_TYPE_LBL' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'DISCOUNT_TYPE_LBL' );
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
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COUPONS_ENABLE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_COUPONS_ENABLE_LBL' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'COUPONS_ENABLE_LBL' );
?>
</label></span></td>
		<td>
<?php echo $this->lists ['coupons_enable'];?>
</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'VOUCHERS_ENABLE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_VOUCHERS_ENABLE_LBL' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'VOUCHERS_ENABLE_LBL' );
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
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SPECIAL_DISCOUNT_MAIL_SEND_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SPECIAL_DISCOUNT_MAIL_SEND_LBL' ); ?>">
		<label for="name">
	<?php
	echo JText::_ ( 'SPECIAL_DISCOUNT_MAIL_SEND_LBL' );
	?></label></span></td>
		<td><?php
		echo $this->lists ['special_discount_mail_send'];
		?>
	</td>
	</tr>
</table>