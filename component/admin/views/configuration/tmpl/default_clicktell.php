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
<table class="admintable" id="measurement">
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLICKTELL_ENABLE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CLICKTELL_ENABLE_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'CLICKTELL_ENABLE_LBL' );
		?></label></span></td>
		<td>
		<?php
		echo $this->lists ['clickatell_enable'];
		?>
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLICKATELL_USERNAME_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CLICKATELL_USERNAME_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'CLICKATELL_USERNAME_LBL' );
		?></label></span></td>
		<td><input type="text" name="clickatell_username"
			id="clickatell_username"
			value="<?php
			echo CLICKATELL_USERNAME;
			?>">
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLICKATELL_PASSWORD_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CLICKATELL_PASSWORD_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'CLICKATELL_PASSWORD_LBL' );
		?></label></span></td>
		<td><input type="password" name="clickatell_password"
			id="clickatell_password"
			value="<?php
			echo CLICKATELL_PASSWORD;
			?>">
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLICKATELL_API_ID_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CLICKATELL_API_ID_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'CLICKATELL_API_ID_LBL' );
		?></label></span></td>
		<td><input type="text" name="clickatell_api_id" id="clickatell_api_id"
			value="<?php
			echo CLICKATELL_API_ID;
			?>">
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'CLICKTELL_ORDER_STATUS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CLICKTELL_ORDER_STATUS_LBL' ); ?>">
		<label for="name"><?php
		echo JText::_ ( 'CLICKTELL_ORDER_STATUS_LBL' );
		?></label></span></td>
		<td>
		<?php
		echo $this->lists ['clickatell_order_status'];
		?>
		</td>
	</tr>

</table>