<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$order_functions = order_functions::getInstance();
$style = "none";
if ($this->config->get('ECONOMIC_INVOICE_DRAFT') == 2)
{
	$style = "";
}
?>
<table class="admintable">
	<tr>
		<td colspan='2'>
			<?php
			echo JText::_('COM_REDSHOP_ECONOMIC_NOTE_LBL');
			?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ECONOMIC_INTEGRATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_INTEGRATION_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_ECONOMIC_INTEGRATION_LBL');
			?>
		</label></span></td>
		<td><?php
			echo $this->lists ['economic_integration'];
			?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL');
			?>
		</label></span></td>
		<td><?php echo $this->lists ['economic_invoice_draft'];    ?>&nbsp;&nbsp;<span id="booking_order_status"
		                                                                               style="display: <?php echo $style ?>;"><?php echo $order_functions->getstatuslist('booking_order_status', $this->config->get('BOOKING_ORDER_STATUS'), "class=\"inputbox\" size=\"1\" ");?></span>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL');?></label></span></td>
		<td><?php echo $this->lists ['economic_book_invoice_number'];    ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL');?></td>
		<td><?php    echo $this->lists ['default_economic_account_group'];    ?></td>
	</tr>
	<tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'); ?>">
			<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL');?></td>
		<td><?php    echo $this->lists ['attribute_as_product_in_economic'];    ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DETAIL_ERROR_MESSAGE_ON_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DETAIL_ERROR_MESSAGE_ON'); ?>">
			<?php echo JText::_('COM_REDSHOP_DETAIL_ERROR_MESSAGE_ON_LBL');?></td>
		<td><?php    echo $this->lists ['detail_error_message_on'];    ?></td>
	</tr>
	<tr>
		<td></td>
		<td><?php
			echo JText::_('COM_REDSHOP_CONFIG_ECONOMIC_DESCRIPTION_IMG');
			?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php
			$str_desc = str_replace('e-conomic', '<a href="http://www.e-conomic.dk?opendocument&ReferralID=63" target="_blank">e-conomic</a>', JText::_('COM_REDSHOP_CONFIG_ECONOMIC_DESCRIPTION'));
			?>
			<?php echo $str_desc;?></td>
	</tr>
</table>
