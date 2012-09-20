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

$uri = & JURI::getInstance ();
$url = $uri->root ();
?>
<table width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<strong><?php echo JText::_( 'REDSHOP_CURRENT_VERSION' ); ?></strong>
		</td>
		<td><blink><?php echo $this->current_version;?></blink></td>
	</tr>
	<tr valign="top">
		<td>
			<strong><?php echo JText::_( 'REDSHOP_LATEST_VERSION' ); ?></strong>
		</td>
		<td><blink><?php echo file_get_contents( REMOTE_UPDATE_DOMAIN_URL .'version_checker' .DS.'version_checker.txt', true);?></blink></td>
	</tr>
	<tr valign="top">
		<td colspan="2" align="right">
			<strong><?php echo JText::_( 'CLICK_HERE_TO_UPDATE' ); ?></strong>
		</td>

	</tr>
</table>
