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
<table class="admintable" width="100%">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_TAX_TAB'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_COUNTRY'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_COUNTRY_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['default_vat_country'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_STATE'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_STATE_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['default_vat_state'];
			?>
		</td>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_GROUP'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_GROUP_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['default_vat_group'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_VAT_CALCULATION_BASED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_VAT_CALCULATION_BASED'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_DEFAULT_VAT_CALCULATION_BASED_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['vat_based_on'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_APPLY_VAT_ON_DISCOUNT'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_APPLY_VAT_ON_DISCOUNT_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['apply_vat_on_discount'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CALCULATE_VAT_BASED_ON_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_CALCULATE_VAT_BASED_ON_LBL');
			?></label></span></td>
		<td><?php
			echo $this->lists ['calculate_vat_on'];
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_REQUIRED_VAT_NUMBER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUIRED_VAT_NUMBER_LBL'); ?>">
		<label for="new_customer_selection"><?php echo JText::_('COM_REDSHOP_REQUIRED_VAT_NUMBER_LBL');?></label></td>
		<td><?php echo $this->lists ['required_vat_number'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_VAT_INTRO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_VAT_INTRO_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_VAT_INTRO_LBL');?>:</span>
		</td>
		<td>
			<textarea class="text_area" type="text" name="vat_introtext" id="vat_introtext" rows="4"
			          cols="40"/><?php echo stripslashes(VAT_INTROTEXT); ?></textarea>
		</td>
	</tr>

	<tr>
		<td align="right" class="key">
	  <span class="editlinktip hasTip"
	        title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITH_VAT_TEXT_INFO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITH_VAT_TEXT_INFO'); ?>">
		<?php echo JText::_('COM_REDSHOP_WITH_VAT_TEXT_INFO_LBL');?>:</span></td>
		<td>
			<textarea class="text_area" type="text" name="with_vat_text_info" id="with_vat_text_info" rows="4"
			          cols="40"/><?php echo stripslashes(WITH_VAT_TEXT_INFO); ?></textarea>
		</td>
	</tr>
	<tr>
		<td align="right" class="key">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITHOUT_VAT_TEXT_INFO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WITHOUT_VAT_TEXT_INFO'); ?>">
		<?php echo JText::_('COM_REDSHOP_WITHOUT_VAT_TEXT_INFO_LBL');?>:</span></td>
		<td>
			<textarea class="text_area" type="text" name="without_vat_text_info" id="without_vat_text_info" rows="4"
			          cols="40"/><?php echo stripslashes(WITHOUT_VAT_TEXT_INFO); ?></textarea>
		</td>
	</tr>
</table>
