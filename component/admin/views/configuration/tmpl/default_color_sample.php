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
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_COLOR_SAMPLE_MANAGEMENT'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_1_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_REMAINDER_1_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_1_LBL');
			?>
		</label></span></td>
		<td><input type="text" name="colour_sample_remainder_1"
		           id="colour_sample_remainder_1"
		           value="<?php
		           echo COLOUR_SAMPLE_REMAINDER_1;
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_2_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_REMAINDER_2_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_2_LBL');
			?>
		</label></span></td>
		<td><input type="text" name="colour_sample_remainder_2"
		           id="colour_sample_remainder_2"
		           value="<?php
		           echo COLOUR_SAMPLE_REMAINDER_2;
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_3_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_REMAINDER_3_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_REMAINDER_3_LBL');
			?>
		</label></span></td>
		<td><input type="text" name="colour_sample_remainder_3"
		           id="colour_sample_remainder_3"
		           value="<?php
		           echo COLOUR_SAMPLE_REMAINDER_3;
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COLOUR_COUPON_DURATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_COUPON_DURATION_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COLOUR_COUPON_DURATION_LBL');
			?>
		</label></span></td>
		<td><input type="text" name="colour_coupon_duration"
		           id="colour_coupon_duration"
		           value="<?php
		           echo COLOUR_COUPON_DURATION;
		           ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COLOUR_DISCOUNT_PERCENTAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_DISCOUNT_PERCENTAGE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COLOUR_DISCOUNT_PERCENTAGE_LBL');
			?>
		</label></span></td>
		<td><input type="text" name="colour_discount_percentage"
		           id="colour_discount_percentage"
		           value="<?php
		           echo COLOUR_DISCOUNT_PERCENTAGE;
		           ?>">
		</td>
	</tr>
	<tr style="display:none">
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_DAYS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COLOUR_SAMPLE_DAYS'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_COLOUR_SAMPLE_DAYS_LBL');
			?>
		</label></span></td>
		<td><input type="text" name="colour_sample_days"
		           id="colour_sample_days"
		           value="<?php
		           echo COLOUR_SAMPLE_DAYS;
		           ?>">
		</td>
	</tr>
</table>
