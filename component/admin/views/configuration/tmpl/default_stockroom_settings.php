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

<div class="row">
	<div class="col-sm-6">
		<legend><?php echo JText::_('COM_REDSHOP_STOCKROOM_SETTINGS'); ?></legend>
		<div class="form-group">
			<span class="editlinktip hasTip"
									  title="<?php echo JText::_('COM_REDSHOP_USE_STOCKROOM_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_STOCKROOM_LBL'); ?>">
				<label for="container"><?php echo JText::_('COM_REDSHOP_USE_STOCKROOM_LBL');?></label>
			</span>
			<?php echo $this->lists ['use_stockroom']; ?>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_USE_BLANK_AS_INFINITE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_BLANK_AS_INFINITE_LBL'); ?>">
				<label
						for="container"><?php echo JText::_('COM_REDSHOP_USE_BLANK_AS_INFINITE_LBL');?></label>
			</span>
			<?php echo $this->lists ['use_blank_as_infinite'];?>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEDAULT_STOCKROOM_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEDAULT_STOCKROOM'); ?>">
				<label
					for="default_stockroom"><?php echo JText::_('COM_REDSHOP_DEDAULT_STOCKROOM_LBL');?></label>
			</span>
			<?php echo $this->lists ['default_stockroom'];?>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL'); ?>">
				<label
					for="default_stockroom"><?php echo JText::_('COM_REDSHOP_DEFAULT_STOCKAMOUNT_IMAGE_THUMB_WIDTH_HEIGHT_LBL');?></label>
			</span>

			<input type="text" name="default_stockamount_thumb_width"
							           value="<?php echo $this->config->get('DEFAULT_STOCKAMOUNT_THUMB_WIDTH'); ?>"/>
			<input type="text" name="default_stockamount_thumb_height"
								       value="<?php echo $this->config->get('DEFAULT_STOCKAMOUNT_THUMB_HEIGHT'); ?>"/>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_USE_PRODUCT_OUTOFSTOCK_IMAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_PRODUCT_OUTOFSTOCK_IMAGE'); ?>">
				<label for="name"><?php echo JText::_('COM_REDSHOP_USE_PRODUCT_OUTOFSTOCK_IMAGE_LBL');?></label></span>
			<?php echo $this->lists ['use_product_outofstock_image'];?>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
									  title="<?php echo JText::_('COM_REDSHOP_ENABLE_STOCKROOM_NOTIFICATION'); ?>::<?php echo JText::_('COM_REDSHOP_ENABLE_STOCKROOM_NOTIFICATION'); ?>">
				<label for="container"><?php echo JText::_('COM_REDSHOP_ENABLE_STOCKROOM_NOTIFICATION');?></label>
			</span>
			<?php echo $this->lists ['enable_stockroom_notification']; ?>
		</div>

		<div class="form-group">
			<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_STOCKROOM_BELOW_AMOUNT_NUMBER_LBL'); ?>">
				<label
					for="default_stockamount_below_amount_number"><?php echo JText::_('COM_REDSHOP_DEFAULT_STOCKROOM_BELOW_AMOUNT_NUMBER_LBL');?></label>
			</span>
			<input type="text" name="default_stockroom_below_amount_number"
								       value="<?php echo $this->config->get('DEFAULT_STOCKROOM_BELOW_AMOUNT_NUMBER'); ?>"/>
		</div>

	</div>
</div>

