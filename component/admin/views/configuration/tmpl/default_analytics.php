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
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_('COM_REDSHOP_GOOGLE_ANALYATICS_TRACKER_KEY' ); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_GOOGLE_ANALYATICS_TRACKER_KEY' ); ?>">
		<label 	for="google_ana_tracking"><?php echo JText::_('COM_REDSHOP_GOOGLE_ANALYATICS_TRACKER_KEY' ); ?></label>
		</td>
		<td><input type="text" name="google_ana_tracker" id="google_ana_tracker" value="<?php echo GOOGLE_ANA_TRACKER_KEY; ?>"></td>
	</tr>
</table>
