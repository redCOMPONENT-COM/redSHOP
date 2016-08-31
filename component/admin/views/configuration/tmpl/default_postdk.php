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

<legend><?php echo JText::_('COM_REDSHOP_POST_DENMART'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_POST_DK_INTEGRATION_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POST_DK_INTEGRATION_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_POST_DK_INTEGRATION_LBL');?></label>
	</span>
	<?php echo $this->lists['postdk_integration'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_POST_DK_CUSTOMER_ID_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POST_DK_CUSTOMER_ID_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_POST_DK_CUSTOMER_ID_LBL');?></label>
	</span>
	<input type="text" name="postdk_customer_no" id="postdk_customer_no" value="<?php echo $this->config->get('POSTDK_CUSTOMER_NO'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_POST_DK_PASSWORD_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POST_DK_PASSWORD_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_POST_DK_PASSWORD_LBL');?></label>
	</span>
	<input type="password" name="postdk_customer_password" id="postdk_customer_password"
		           value="<?php echo $this->config->get('POSTDK_CUSTOMER_PASSWORD'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_POSTDANMARK_ADDRESS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POSTDANMARK_ADDRESS_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_POSTDANMARK_ADDRESS_LBL');?></label>
	</span>
	<input type="text" name="postdk_address" id="postdk_address" value="<?php echo $this->config->get('POSTDANMARK_ADDRESS'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_POSTDANMARK_POSTALCODE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_POSTDANMARK_POSTALCODE_LBL'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_POSTDANMARK_POSTALCODE_LBL');?></label>
	</span>
	<input type="text" name="postdk_postalcode" id="postdk_postalcode"
		           value="<?php echo $this->config->get('POSTDANMARK_POSTALCODE'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL_TOOLTIP'); ?>::<?php echo JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL_TOOLTIP_DESC'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL_LBL');?></label>
	</span>

	<?php
	$options   = array();
	$options[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_POSTDANMARK_GENERATE_LABEL_MANUALLY'));
	$options[] = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_POSTDANMARK_AUTO_GENERATE_LABEL'));

	echo JHTML::_(
		'select.genericlist',
		$options,
		'auto_generate_label',
		'class="inputbox"',
		'value',
		'text',
		$this->config->get('AUTO_GENERATE_LABEL')
	);

	$order_functions = order_functions::getInstance();

	echo $order_functions->getstatuslist(
		'generate_label_on_status',
		$this->config->get('GENERATE_LABEL_ON_STATUS'),
		"class=\"inputbox\" size=\"1\" "
	);
	?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_DETAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRODUCT_DETAIL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_SHOW_PRODUCT_DETAIL_LBL');?></label>
	</span>
	<?php echo $this->lists['show_product_detail'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_TRACK_AND_TRACE_EMAIL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_TRACK_AND_TRACE_EMAIL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_ENABLE_TRACK_AND_TRACE_EMAIL_LBL');?></label>
	</span>
	<?php echo $this->lists['webpack_enable_email_track'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
	      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SMS_FROM_WEBPACK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ENABLE_SMS_FROM_WEBPACK'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_ENABLE_SMS_FROM_WEBPACK_LBL');?></label>
	</span>
	<?php echo $this->lists['webpack_enable_sms'];?>
</div>

<script type="text/javascript">

window.addEvent('domready', function() {

	var toggleGenerateParcel = function(val){
		if (val == 1)
		{
			document.id('generate_label_on_status').style.display = 'block';
		}
		else
		{
			document.id('generate_label_on_status').style.display = 'none';
		}
	};

	// Set Toggle on page load
	toggleGenerateParcel(document.id('auto_generate_label').get('value'));

	document.id('auto_generate_label').addEvent('change', function() {
		toggleGenerateParcel(this.get('value'));
	});
});
</script>
