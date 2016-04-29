<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_GOOGLE_ANALYTICS_TRACKER_KEY'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_GOOGLE_ANALYATICS_TRACKER_KEY'); ?>">
		<label for="google_ana_tracking"><?php echo JText::_('COM_REDSHOP_GOOGLE_ANALYTICS_TRACKER_KEY'); ?></label>
		</td>
		<td><input type="text" name="google_ana_tracker" id="google_ana_tracker"
		           value="<?php echo $this->config->get('GOOGLE_ANA_TRACKER_KEY'); ?>"></td>
	</tr>
</table>
