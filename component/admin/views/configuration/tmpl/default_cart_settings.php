<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$uri = JURI::getInstance();
$url = $uri->root();
$addtocart_path = "/components/com_redshop/assets/images/";
?>

<legend><?php echo JText::_('COM_REDSHOP_ADD_TO_CART_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_INDIVIDUAL_ADD_TO_CART_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_INDIVIDUAL_ADD_TO_CART_ENABLE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_INDIVIDUAL_ADD_TO_CART_ENABLE_LBL');?></label></span>
	<?php echo $this->lists ['individual_add_to_cart_enable'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ALLOW_PRE_ORDER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ALLOW_PRE_ORDER'); ?>">
		<label
						for="allow_pre_order"><?php echo JText::_('COM_REDSHOP_ALLOW_PRE_ORDER_LBL');?></label></span>
	<?php echo $this->lists ['allow_pre_order'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_QUOTATION_MODE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_QUOTATION_MODE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_QUOTATION_MODE_LBL');?></label></span>
	<?php echo $this->lists ['quotation_mode'];?>
</div>

<div class="form-group" id="quotationprice">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_QUOTATION_PRICE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_QUOTATION_PRICE'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_SHOW_QUOTATION_PRICE_LBL');?></label></span>
	<?php echo $this->lists ['show_quotation_price'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX'); ?>">
		<label for="ajax_cart_box"><?php echo JText::_('COM_REDSHOP_AJAX_CART_BOX_LBL');?></label></span>
	<?php echo $this->lists ['ajax_cart_box'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CARTDISPLAY_TIME_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_AJAX_CARTDISPLAY_TIME'); ?>">
		<label><?php echo JText::_('COM_REDSHOP_AJAX_CART_DISPLAY_TIME_LBL');?></label></span>
	<input type="text" name="ajax_cart_display_time" id="ajax_cart_display_time"
							       value="<?php echo Redshop::getConfig()->get('AJAX_CART_DISPLAY_TIME'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_TIMEOUT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_TIMEOUT'); ?>">
		<label for="cart_timeout"><?php echo JText::_('COM_REDSHOP_CART_TIMEOUT_LBL');?></label></span>
	<input type="text" name="cart_timeout" id="cart_timeout"
							       value="<?php echo $this->config->get('CART_TIMEOUT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_AJAX_DETAIL_BOX_WIDTH_AND_HEIGHT_TOOLTIP_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_AJAX_DETAIL_BOX_WIDTH_AND_HEIGHT_TOOLTIP'); ?>">
		<label
					for="ajax_detail_box_width_and_height"><?php echo JText::_('COM_REDSHOP_AJAX_DETAIL_BOX_WIDTH_AND_HEIGHT_TOOLTIP_LBL');?></label></span>
	<input type="text" name="ajax_detail_box_width" id="ajax_detail_box_width"
							       value="<?php echo $this->config->get('AJAX_DETAIL_BOX_WIDTH'); ?>">
	<input type="text" name="ajax_detail_box_height" id="ajax_detail_box_height"
							       value="<?php echo $this->config->get('AJAX_DETAIL_BOX_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
				      title="<?php echo JText::_('COM_REDSHOP_AJAX_BOX_WIDTH_AND_HEIGHT_TOOLTIP_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_AJAX_BOX_WIDTH_AND_HEIGHT_TOOLTIP'); ?>">
		<label
					for="ajax_box_width_and_height"><?php echo JText::_('COM_REDSHOP_AJAX_BOX_WIDTH_AND_HEIGHT_TOOLTIP_LBL');?></label></span>
	<input type="text" name="ajax_box_width" id="ajax_box_width"
							       value="<?php echo $this->config->get('AJAX_BOX_WIDTH'); ?>">
	<input type="text" name="ajax_box_height" id="ajax_box_height"
							       value="<?php echo $this->config->get('AJAX_BOX_HEIGHT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_CART_CHECKOUT_ITEMID_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CART_CHECKOUT_ITEMID'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DEFAULT_CART_CHECKOUT_ITEMID_LBL');?></label></span>
	<?php
		$site = JApplication::getInstance('site');
		$menu = $site->getMenu();

		// Get menu items - array filtered by access level of current user.
		$menuItems = $menu->getItems(null, null);

		// Build please select option for no itemid
		$selectOption   = array();
		$selectOption[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_PLEASE_SELECT'), 'id', 'title');

		// Merge items to build select list.
		$items = array_merge($selectOption, $menuItems);

		echo JHTML::_(
			'select.genericlist',
			$items,
			'default_cart_checkout_itemid',
			'',
			'id',
			'title',
			$this->config->get('DEFAULT_CART_CHECKOUT_ITEMID')
		);
	?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ALLOW_PRE_ORDER_MESSAGE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ALLOW_PRE_ORDER_MESSAGE'); ?>">
		<label for="allow_pre_order_message"><?php echo JText::_('COM_REDSHOP_ALLOW_PRE_ORDER_MESSAGE_LBL');?></label>
	</span>
	<input type="text" name="allow_pre_order_message" id="allow_pre_order_message"
							       value="<?php echo $this->config->get('ALLOW_PRE_ORDER_MESSAGE'); ?>">
</div>

<div class="form-group">
	<span class="addtocartbutton hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_ADDTOCART_BUTTON_BEHAVE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BUTTON_BEHAVE'); ?>">
					<?php echo JText::_('COM_REDSHOP_ADDTOCART_BUTTON_BEHAVE_LBL');?></span>
	<?php echo $this->lists ['addtocart_behaviour'];?>
</div>

<legend><?php echo JText::_('COM_REDSHOP_CART_SETTINGS'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_SHOW_SHIPPING_IN_CART_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_SHOW_SHIPPING_IN_CART_LBL'); ?>">
		<label
						for="show_shipping_in_cart"><?php echo JText::_('COM_REDSHOP_SHOW_SHIPPING_IN_CART_LBL');?></label></span>
	<?php echo $this->lists ['show_shipping_in_cart'];?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL'); ?>">
		<label
							for="name"><?php echo JText::_('COM_REDSHOP_WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL');?></label></span>
	<?php echo $this->lists ['wanttoshowattributeimage']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_QUANTITY_TEXT_DISPLAY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_QUANTITY_TEXT_DISPLAY_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_QUANTITY_TEXT_DISPLAY_LBL');?></label></span>
	<?php echo $this->lists ['quantity_text_display']; ?>
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_LBL');?></span>
	<input type="text" name="default_quantity" id="default_quantity"
							       value="<?php echo $this->config->get('DEFAULT_QUANTITY'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
						      title="<?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'); ?>">
						<?php echo JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL');?></span>
	<input type="text" name="default_quantity_selectbox_value"
							       id="default_quantity_selectbox_value"
							       value="<?php echo $this->config->get('DEFAULT_QUANTITY_SELECTBOX_VALUE'); ?>">
</div>

<hr/>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_CONTINUE_REDIRECT_LINK_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_CONTINUE_REDIRECT_LINK'); ?>">
		<label
						for="continue_redirect_link"><?php echo JText::_('COM_REDSHOP_CONTINUE_REDIRECT_LINK_LBL');?></label></span>
	<input type="text" name="continue_redirect_link" id="continue_redirect_link" size="50"
							       value="<?php echo $this->config->get('CONTINUE_REDIRECT_LINK'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
					      title="<?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_ORDER_TOTAL'); ?>">
		<label
						for="minimum_order_total"><?php echo JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_LBL');?></label></span>
	<input type="text" name="minimum_order_total" id="minimum_order_total" size="20"
							       value="<?php echo $this->config->get('MINIMUM_ORDER_TOTAL'); ?>">
</div>

<script language="javascript" type="text/javascript">
	function quote_price(val) {

		if (val == "0") {
			document.getElementById('quotationprice').style.display = "none";
		} else {
			document.getElementById('quotationprice').style.display = "";
		}
	}
	quote_price(<?php echo $this->config->get('DEFAULT_QUOTATION_MODE_PRE'); ?>);
</script>
