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
<tr valign="top"><td colspan="2"><fieldset>
		<legend><?php echo JText::_( 'FEATURE_SETTING_TAB' ); ?></legend>
			<?php echo JText::_( 'FEATURE_SETTING' );?>
		</fieldset></td></tr>
</table>
<?php
echo $this->pane->startPane( 'stat-pane' );
echo $this->pane->startPanel( JText::_( 'RATING' ), 'events' );
?>

<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>
	<?php echo $this->loadTemplate('rating_settings');?>
</td>
</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->startPanel( JText::_( 'COMPARISON_PRODUCT_TAB' ), 'events');
?>
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td>
		<?php echo $this->loadTemplate('comparison_settings');?>
</td>
</tr>
</table>
<?php
echo $this->pane->endPanel();
echo $this->pane->startPanel( JText::_( 'STOCKROOM_TAB' ), 'events');
?>
<table class="adminlist" width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
<td width="50%">
		<?php echo $this->loadTemplate('stockroom_settings');?>
</td>

</tr>
</table>
<?php	echo $this->pane->endPanel();
			echo $this->pane->endPane();?>
