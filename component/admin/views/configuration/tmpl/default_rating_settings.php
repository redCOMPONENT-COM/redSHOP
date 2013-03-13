<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<div id="config-document">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td width="50%">
				<fieldset class="adminform">
					<table class="admintable">
						<tr>
							<td class="config_param"><?php echo JText::_('COM_REDSHOP_RATING_SETTING'); ?></td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_RATING_DONE_MSG'); ?>::<?php echo JText::_('TOOLTIP_RATING_DONE_MSG'); ?>">
						<label for="name"><?php echo JText::_('COM_REDSHOP_RATING_DONE_MSG');?></label></span>
							</td>
							<td>
								<input type="text" name="rating_msg" id="rating_msg" value="<?php echo RATING_MSG; ?>"
								       size="50">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_FAVOURED_REVIEWS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_FAVOURED_REVIEWS_LBL'); ?>">
						<label for="name"><?php echo JText::_('COM_REDSHOP_FAVOURED_REVIEWS_LBL');?></label></span>
							</td>
							<td>
								<input type="text" name="favoured_reviews" id="favoured_reviews"
								       value="<?php echo FAVOURED_REVIEWS; ?>">
							</td>
						</tr>
						<tr>
							<td width="100" align="right" class="key">
						<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_RATING_REVIEW_LOGIN_REQUIRED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_RATING_REVIEW_LOGIN_REQUIRED_LBL'); ?>">
						<label for="name"><?php echo JText::_('COM_REDSHOP_RATING_REVIEW_LOGIN_REQUIRED_LBL');?></label></span>
							</td>
							<td>
								<?php echo $this->lists['rating_review_login_required'];?>
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td width="50%">
			</td>
		</tr>
	</table>
</div>
