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
<legend><?php echo JText::_( 'CATEGORIES' ); ?></legend>
<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td width="50%">
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('category');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('cateory_suffix');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('cattab_nplinks');?>
		</fieldset>
	</td>
	<td width="50%">

		<fieldset class="adminform">
			<?php echo $this->loadTemplate('category_template');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('image_setting');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('procat_images');?>
		</fieldset>
	</td>
</tr>
</table>
</fieldset>
</div>