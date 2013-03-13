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
					<legend><?php echo JText::_('COM_REDSHOP_QUICK_LINKS'); ?></legend>
					<?php echo $this->loadTemplate('quicklink');?>
				</fieldset>

			</td>
			<td width="50%">
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS'); ?></legend>
					<?php echo $this->loadTemplate('new_customers');?>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS'); ?></legend>
					<?php echo $this->loadTemplate('new_orders');?>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_SHOW_LAST_MONTH_STATISTIC'); ?></legend>
					<?php echo $this->loadTemplate('statistic');?>
				</fieldset>
				<fieldset class="adminform">
					<legend><?php echo JText::_('COM_REDSHOP_EXPAND_ALL_LBL'); ?></legend>
					<?php echo $this->loadTemplate('expand');?>
				</fieldset>

			</td>
		</tr>
	</table>
</div>
