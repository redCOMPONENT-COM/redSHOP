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
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_MANUFACTURER_SETTINGS'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_MANUFACTURER_EMAIL_LBL'); ?>">
			<label
				for="allow_pre_order"><?php echo JText::_('COM_REDSHOP_ENABLE_MANUFACTURER_EMAIL_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['manufacturer_mail_enable'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SUPPLIER_EMAIL_LBL'); ?>">
			<label for="allow_pre_order"><?php echo JText::_('COM_REDSHOP_ENABLE_SUPPLIER_EMAIL_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['supplier_mail_enable'];?></td>
	</tr>
</table>
