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
<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top" align="left"><td colspan="2"><fieldset>
		<legend><?php echo JText::_( 'COM_REDSHOP_PRODUCT_INTRO_TAB' ); ?></legend>
			<?php echo JText::_( 'COM_REDSHOP_PRODUCT_INTRO' );?>
		</fieldset></td></tr>
</table>
<?php
echo $this->pane->startPane( 'stat-pane' );
echo $this->pane->startPanel( JText::_( 'COM_REDSHOP_PRODUCT' ), 'events' );
?>

<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('product_unit');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('download');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('wrapping');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('catalog');?>
		</fieldset>
		<fieldset class="adminform">
			<?php echo $this->loadTemplate('color_sample');?>
		</fieldset>
	</td>
	<td width="50%">
	<?php echo $this->loadTemplate('product_template_image_settings');?>
	</td>
</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->startPanel( JText::_( 'COM_REDSHOP_ACCESSORY_PRODUCT_TAB' ), 'events');
echo $this->loadTemplate('accessory_product');
echo $this->pane->endPanel();
echo $this->pane->startPanel( JText::_( 'COM_REDSHOP_RELATED_PRODUCTS' ), 'events');
echo $this->loadTemplate('related_product');
echo $this->pane->endPanel();
			echo $this->pane->endPane();?>
