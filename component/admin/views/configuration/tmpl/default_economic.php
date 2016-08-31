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

<legend><?php echo JText::_('COM_REDSHOP_ECONOMIC'); ?></legend>

<?php echo JText::_('COM_REDSHOP_ECONOMIC_NOTE_LBL'); ?>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ECONOMIC_INTEGRATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_INTEGRATION_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_ECONOMIC_INTEGRATION_LBL');
			?>
		</label>
	</span>
	<?php echo $this->lists ['economic_integration']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL'); ?>">
		<label for="name">
			<?php
			echo JText::_('COM_REDSHOP_ECONOMIC_CHOICE_OF_BOOK_INVOICE_LBL');
			?>
		</label></span>
	<?php echo $this->lists ['economic_invoice_draft'];    ?>&nbsp;&nbsp;<span id="booking_order_status"
		                                                                               style="display: <?php echo $style ?>;"><?php echo $order_functions->getstatuslist('booking_order_status', $this->config->get('BOOKING_ORDER_STATUS'), "class=\"inputbox\" size=\"1\" ");?></span>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_ECONOMIC_BOOK_INVOICE_NUMBER_LBL');?></label></span>
	<?php echo $this->lists ['economic_book_invoice_number']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_DEFAULT_ECONMOMIC_ACCOUNT_GROUP_LBL');?></label></span>
	<?php echo $this->lists ['default_economic_account_group']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL');?></label></span>
	<?php echo $this->lists ['attribute_as_product_in_economic']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_DETAIL_ERROR_MESSAGE_ON_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DETAIL_ERROR_MESSAGE_ON'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_DETAIL_ERROR_MESSAGE_ON_LBL');?></label></span>
	<?php echo $this->lists ['detail_error_message_on']; ?>
</div>

<p>
<?php echo JText::_('COM_REDSHOP_CONFIG_ECONOMIC_DESCRIPTION_IMG'); ?>
</p>
<?php
	$str_desc = str_replace('e-conomic', '<a href="http://www.e-conomic.dk?opendocument&ReferralID=63" target="_blank">e-conomic</a>', JText::_('COM_REDSHOP_CONFIG_ECONOMIC_DESCRIPTION'));
	echo $str_desc;
?>

