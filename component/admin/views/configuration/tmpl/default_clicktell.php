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
<table class="admintable" id="measurement">
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CLICKTELL_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKTELL_ENABLE_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKTELL_ENABLE_LBL');
			?></label></span></td>
		<td>
			<?php
			echo $this->lists ['clickatell_enable'];
			?>
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CLICKATELL_USERNAME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_USERNAME_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKATELL_USERNAME_LBL');
			?></label></span></td>
		<td><input type="text" name="clickatell_username"
		           id="clickatell_username"
		           value="<?php
		           echo CLICKATELL_USERNAME;
		           ?>">
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CLICKATELL_PASSWORD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_PASSWORD_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKATELL_PASSWORD_LBL');
			?></label></span></td>
		<td><input type="password" name="clickatell_password"
		           id="clickatell_password"
		           value="<?php
		           echo CLICKATELL_PASSWORD;
		           ?>">
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CLICKATELL_API_ID_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_API_ID_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKATELL_API_ID_LBL');
			?></label></span></td>
		<td><input type="text" name="clickatell_api_id" id="clickatell_api_id"
		           value="<?php
		           echo CLICKATELL_API_ID;
		           ?>">
		</td>
	</tr>
	<tr>
		<td class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CLICKTELL_ORDER_STATUS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKTELL_ORDER_STATUS_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKTELL_ORDER_STATUS_LBL');
			?></label></span></td>
		<td>
			<?php
			echo $this->lists ['clickatell_order_status'];
			?>
		</td>
	</tr>

</table>
