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
<div id="config-document">
<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td>
		<table width="100%" cellpadding="0" cellspacing="0">
			<?php
			/*<tr>
				<td width="50%">
					<fieldset class="adminform">
					<legend><?php echo JText::_( 'REDSHOP_VERSION' ); ?></legend>
						<?php echo $this->loadTemplate('redshop_version');?>
					</fieldset>
				</td>
			</tr>*/
			?>
			<tr>
				<td width="50%">
					<fieldset class="adminform">
					<legend><?php echo JText::_( 'SYSTEM_INFORMATION' ); ?></legend>
						<?php echo $this->loadTemplate('system_information');?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="50%">
					<fieldset class="adminform">
					<legend><?php echo JText::_( 'REDSHOP_MODULES' ); ?></legend>
						<?php echo $this->loadTemplate('redshop_modules');?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="50%">
					<fieldset class="adminform">
					<legend><?php echo JText::_( 'REDSHOP_SHIPPING_PLUGINS' ); ?></legend>
						<?php echo $this->loadTemplate('redshop_shipping');?>
					</fieldset>
				</td>
			</tr>
		</table>
	</td>
	<td>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td width="50%">
					<fieldset class="adminform">
					<legend><?php echo JText::_( 'REDSHOP_PAYMENT_PLUGINS' ); ?></legend>
						<?php echo $this->loadTemplate('redshop_plugins');?>
					</fieldset>
				</td>
			</tr>
		</table>
	</tr>
</table>
</div>