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
	<td width="50%">
		<fieldset class="adminform">
			<table class="admintable">
				<tr><td class="config_param"><?php echo JText::_( 'RATING_SETTING' ); ?></td></tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'RATING_DONE_MSG' ); ?>::<?php echo JText::_( 'TOOLTIP_RATING_DONE_MSG' ); ?>">
						<label for="name"><?php echo JText::_ ( 'RATING_DONE_MSG' );?></label></span>
					</td>
					<td>
						<input type="text" name="rating_msg" id="rating_msg" value="<?php echo RATING_MSG;?>" size="50">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'FAVOURED_REVIEWS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_FAVOURED_REVIEWS_LBL' ); ?>">
						<label for="name"><?php echo JText::_ ( 'FAVOURED_REVIEWS_LBL' );?></label></span>
					</td>
					<td>
						<input type="text" name="favoured_reviews" id="favoured_reviews" value="<?php echo FAVOURED_REVIEWS; ?>">
					</td>
				</tr>
			</table>
		</fieldset>
	</td><td></td>
</tr>
</table>
</div>