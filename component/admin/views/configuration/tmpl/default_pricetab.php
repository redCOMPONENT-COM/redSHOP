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
<fieldset class="adminform">
<legend><?php echo JText::_( 'COM_REDSHOP_PRICING' ); ?></legend>
<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td width="50%">
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('price');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('vat');?>
		</fieldset>
	</td>
	<td width="50%">
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('images_giftcard');?>
		</fieldset>
		<fieldset class="adminform">

			<?php echo $this->loadTemplate('discount');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('discount_mail');?>
		</fieldset>
	</td>
</tr>
</table>
</fieldset>
</div>
