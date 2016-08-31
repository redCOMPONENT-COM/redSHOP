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

<legend><?php echo JText::_('COM_REDSHOP_CATALOG_MANAGEMENT'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CATALOG_REMAINDER_1_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATALOG_REMAINDER_1'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CATALOG_REMAINDER_1_LBL');
			?>
		</label></span>
	<input type="text" name="catalog_reminder_1"
		           id="catalog_reminder_1"
		           value="<?php
		           echo $this->config->get('CATALOG_REMINDER_1');
		           ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CATALOG_REMAINDER_2_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATALOG_REMAINDER_2'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CATALOG_REMAINDER_2_LBL');
			?>
		</label></span>
	<input type="text" name="catalog_reminder_2"
		           id="catalog_reminder_2"
		           value="<?php
		           echo $this->config->get('CATALOG_REMINDER_2');
		           ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_DURATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_DURATION'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_DURATION_LBL');
			?>
		</label></span>
	<input type="text" name="discount_duration" id="discount_duration"
		           value="<?php
		           echo $this->config->get('DISCOUNT_DURATION');
		           ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_PERCENTAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_PERCENTAGE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DISCOUNT_PERCENTAGE_LBL');
			?>
		</label></span>
	<input type="text" name="discount_percentage"
		           id="discount_percentage"
		           value="<?php
		           echo $this->config->get('DISCOUNT_PERCENTAGE');
		           ?>">
</div>

<div class="form-group" style="display:none">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATALOG_DAYS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CATALOG_DAYS'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CATALOG_DAYS_LBL');
			?>
		</label></span>
	<input type="text" name="catalog_days" id="catalog_days"
		           value="<?php
		           echo $this->config->get('CATALOG_DAYS');
		           ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_CATALOG_REMINDER_MAI_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SEND_CATALOG_REMINDER_MAI'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_SEND_CATALOG_REMINDER_MAIL_LBL');
			?>
		</label>
		</span>
	<?php
		echo $this->lists ['send_catalog_reminder_mail'];
	?>
</div>
