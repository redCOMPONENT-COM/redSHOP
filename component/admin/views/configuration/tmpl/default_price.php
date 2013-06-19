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
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_MAIN_PRICE'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_CURRENCY_NAME'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_NAME'); ?>">
			<label for="name"><?php    echo JText::_('COM_REDSHOP_CURRENCY_NAME');?></label></span>
		</td>
		<td>
			<?php echo $this->lists ['currency_data'];?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_SYMBOL'); ?>">
			<label for="name"><?php    echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL');?></label></span>
		</td>
		<td>
			<input type="text" name="currency_symbol" id="currency_symbol" value="<?php echo REDCURRENCY_SYMBOL; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL_POSITION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CURRENCY_SYMBOL_POSITION'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_CURRENCY_SYMBOL_POSITION_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['currency_symbol_position'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_SEPERATOR_LBL'); ?>">
			<label for="name"><?php    echo JText::_('COM_REDSHOP_PRICE_SEPERATOR_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="price_seperator" id="price_seperator" value="<?php echo PRICE_SEPERATOR; ?>">
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_THOUSAND_SEPERATOR_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_THOUSAND_SEPERATOR_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="thousand_seperator" id="thousand_seperator"
			       value="<?php echo THOUSAND_SEPERATOR; ?>">
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_DECIMAL_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_PRICE_DECIMAL_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="price_decimal" id="price_decimal" value="<?php echo PRICE_DECIMAL; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CALCULATION_PRICE_DECIMAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CALCULATION_PRICE_DECIMAL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_CALCULATION_PRICE_DECIMAL_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="calculation_price_decimal" id="calculation_price_decimal"
			       value="<?php echo CALCULATION_PRICE_DECIMAL; ?>">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_USE_TAX_EXEMPT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_TAX_EXEMPT_LBL'); ?>">
		<label for="usetax"><?php echo JText::_('COM_REDSHOP_USE_TAX_EXEMPT_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['use_tax_exempt'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SHOW_TAX_EXEMPT_INFRONT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_TAX_EXEMPT_INFRONT_LBL'); ?>">
		<label for="usetax"><?php echo JText::_('COM_REDSHOP_SHOW_TAX_EXEMPT_INFRONT_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['show_tax_exempt_infront'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT_APPLY_VAT'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT_APPLY_VAT_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_TAX_EXEMPT_APPLY_VAT_LBL');?></span>
		</td>
		<td><?php echo $this->lists ['tax_exempt_apply_vat'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_AS_CATALOG'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_AS_CATALOG_LBL'); ?>">
		<label for="showprice"><?php echo JText::_('COM_REDSHOP_USE_AS_CATALOG_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['use_as_catalog'];?></td>
	</tr>

	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_SHOW_PRICE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRICE_LBL'); ?>">
		<label for="showprice"><?php echo JText::_('COM_REDSHOP_SHOW_PRICE_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['show_price'];?></td>
	</tr>
	<!--<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SHOW_PRICE_SHOPPER_GROUP_LBL' ); ?>::<?php echo JText::_( 'COM_REDSHOP_TOOLTIP_SHOW_PRICE_SHOPPER_GROUP_LBL' ); ?>">
		<label for="showprice"><?php echo JText::_ ( 'SHOW_PRICE_SHOPPER_GROUP_LBL' );?></label>
		</td>
		<td>
		<?php
			//echo $this->lists ['show_price_shopper_group_list'];
			//echo $this->lists ['show_price_user_group_list'];
		?>
		</td>
	</tr>
	-->
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRICE_REPLACEMENT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_REPLACEMENT_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRICE_REPLACEMENT_LBL');?>
		</span>
		</td>
		<td>
			<input type="text" name="price_replacement" id="price_replacement" value="<?php echo PRICE_REPLACE; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRICE_REPLACEMENT_URL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRICE_REPLACEMENT_URL_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_PRICE_REPLACEMENT_URL_LBL');?></span>
		</td>
		<td>
			<input type="text" name="price_replacement_url" id="price_replacement_url"
			       value="<?php echo PRICE_REPLACE_URL; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ZERO_PRICE_REPLACEMENT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ZERO_PRICE_REPLACEMENT_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ZERO_PRICE_REPLACEMENT_LBL');?></span>
		</td>
		<td>
			<input type="text" name="zero_price_replacement" id="zero_price_replacement"
			       value="<?php echo ZERO_PRICE_REPLACE; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ZERO_PRICE_REPLACEMENT_URL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ZERO_PRICE_REPLACEMENT_URL_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ZERO_PRICE_REPLACEMENT_URL_LBL');?></span>
		</td>
		<td>
			<input type="text" name="zero_price_replacement_url" id="zero_price_replacement_url"
			       value="<?php echo ZERO_PRICE_REPLACE_URL; ?>">
		</td>
	</tr>
</table>
