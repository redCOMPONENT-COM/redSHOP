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

<legend><?php echo JText::_('COM_REDSHOP_CLICKATELL'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_CLICKTELL_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKTELL_ENABLE_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKTELL_ENABLE_LBL');
			?></label></span>
	<?php
			echo $this->lists ['clickatell_enable'];
			?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_CLICKATELL_USERNAME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_USERNAME_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKATELL_USERNAME_LBL');
			?></label></span>
	<input type="text" name="clickatell_username"
				   id="clickatell_username"
				   value="<?php
				   echo $this->config->get('CLICKATELL_USERNAME');
				   ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_CLICKATELL_PASSWORD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_PASSWORD_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKATELL_PASSWORD_LBL');
			?></label></span>
	<input type="password" name="clickatell_password"
	   id="clickatell_password"
	   value="<?php
	   echo $this->config->get('CLICKATELL_PASSWORD');
	   ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_CLICKATELL_API_ID_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKATELL_API_ID_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKATELL_API_ID_LBL');
			?></label></span>
	<input type="text" name="clickatell_api_id" id="clickatell_api_id"
				   value="<?php
				   echo $this->config->get('CLICKATELL_API_ID');
				   ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			  title="<?php echo JText::_('COM_REDSHOP_CLICKTELL_ORDER_STATUS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CLICKTELL_ORDER_STATUS_LBL'); ?>">
		<label for="name"><?php
			echo JText::_('COM_REDSHOP_CLICKTELL_ORDER_STATUS_LBL');
			?></label></span>
	<?php
			echo $this->lists ['clickatell_order_status'];
			?>
</div>
