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
<tr><td class="config_param"><?php echo JText::_( 'CATALOG_MANAGEMENT' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATALOG_REMAINDER_1_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATALOG_REMAINDER_1' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'CATALOG_REMAINDER_1_LBL' );
?>
</label></span></td>
		<td><input type="text" name="catalog_reminder_1"
			id="catalog_reminder_1"
			value="<?php
			echo CATALOG_REMINDER_1;
			?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CATALOG_REMAINDER_2_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATALOG_REMAINDER_2' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'CATALOG_REMAINDER_2_LBL' );
?>
</label></span></td>
		<td><input type="text" name="catalog_reminder_2"
			id="catalog_reminder_2"
			value="<?php
			echo CATALOG_REMINDER_2;
			?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'DISCOUNT_DURATION_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DISCOUNT_DURATION' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'DISCOUNT_DURATION_LBL' );
?>
</label></span></td>
		<td><input type="text" name="discount_duration" id="discount_duration"
			value="<?php
			echo DISCOUNT_DURATION;
			?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'DISCOUNT_PERCENTAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DISCOUNT_PERCENTAGE' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'DISCOUNT_PERCENTAGE_LBL' );
?>
</label></span></td>
		<td><input type="text" name="discount_percentage"
			id="discount_percentage"
			value="<?php
			echo DISCOUNT_PERCENTAGE;
			?>">
		</td>
	</tr>
	<tr style="display:none">
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_CATALOG_DAYS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CATALOG_DAYS' ); ?>">
		<label for="name">
<?php
echo JText::_ ( 'CATALOG_DAYS_LBL' );
?>
</label></span></td>
		<td><input type="text" name="catalog_days" id="catalog_days"
			value="<?php
			echo CATALOG_DAYS;
			?>">
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
	<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_SEND_CATALOG_REMINDER_MAI_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SEND_CATALOG_REMINDER_MAI' ); ?>">

		<?php
		echo JText::_ ( 'SEND_CATALOG_REMINDER_MAIL_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['send_catalog_reminder_mail'];
		?>
			</td>
	</tr>
</table>