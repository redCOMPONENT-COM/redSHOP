<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
    <legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_ADD_TO_CART_SETTINGS'); ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_INDIVIDUAL_ADD_TO_CART_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_INDIVIDUAL_ADD_TO_CART_ENABLE_LBL'),
		'field' => $this->lists['individual_add_to_cart_enable']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ALLOW_PRE_ORDER_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ALLOW_PRE_ORDER'),
		'field' => $this->lists['allow_pre_order']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_ALLOW_PRE_ORDER_MESSAGE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_ALLOW_PRE_ORDER_MESSAGE'),
		'field'  => '<input type="text" name="allow_pre_order_message" id="allow_pre_order_message" class="form-control"
           value="' . $this->config->get('ALLOW_PRE_ORDER_MESSAGE') . '" />',
		'id'     => 'allow_pre_order_message',
		'showOn' => 'allow_pre_order:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_QUOTATION_MODE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_QUOTATION_MODE'),
		'field' => $this->lists['quotation_mode']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_SHOW_QUOTATION_PRICE_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_SHOW_QUOTATION_PRICE'),
		'field'  => $this->lists['show_quotation_price'],
		'id'     => 'show_quotation_price',
		'showOn' => 'default_quotation_mode:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CART_TIMEOUT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CART_TIMEOUT'),
		'field' => '<input type="number" name="cart_timeout" id="cart_timeout" class="form-control"
            value="' . $this->config->get('CART_TIMEOUT') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_AJAX_CART_BOX_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX'),
		'field' => $this->lists['ajax_cart_box']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_AJAX_CART_BOX_TEMPLATE'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_AJAX_CART_BOX_TEMPLATE'),
		'field'  => $this->lists['ajax_detail_template'],
		'id'     => 'ajax_detail_template',
		'showOn' => 'ajax_cart_box:1'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title'  => JText::_('COM_REDSHOP_AJAX_CART_DISPLAY_TIME_LBL'),
		'desc'   => JText::_('COM_REDSHOP_TOOLTIP_AJAX_CARTDISPLAY_TIME'),
		'field'  => '<input type="number" name="ajax_cart_display_time" id="ajax_cart_display_time" class="form-control"
            value="' . $this->config->get('AJAX_CART_DISPLAY_TIME') . '" />',
		'id'     => 'ajax_cart_display_time',
		'showOn' => 'ajax_cart_box:1'
	)
);
?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                rsConfigShowOn("ajax_cart_box", "1", "ajax_detail_box_width_height_wrapper");
                rsConfigShowOn("ajax_cart_box", "1", "ajax_box_width_height_wrapper");
            });
        })(jQuery);
    </script>
    <div class="row form-group" id="ajax_detail_box_width_height_wrapper">
        <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_AJAX_DETAIL_BOX_WIDTH_AND_HEIGHT_TOOLTIP'); ?>">
			<?php echo JText::_('COM_REDSHOP_AJAX_DETAIL_BOX_WIDTH_AND_HEIGHT_TOOLTIP_LBL'); ?>
        </label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <input type="number" name="ajax_detail_box_width" id="ajax_detail_box_width" class="form-control"
                           value="<?php echo $this->config->get('AJAX_DETAIL_BOX_WIDTH'); ?>"/>
                </div>
                <div class="col-sm-6">
                    <input type="number" name="ajax_detail_box_height" id="ajax_detail_box_height" class="form-control"
                           value="<?php echo $this->config->get('AJAX_DETAIL_BOX_HEIGHT'); ?>"/>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="row form-group" id="ajax_box_width_height_wrapper">
        <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_AJAX_BOX_WIDTH_AND_HEIGHT_TOOLTIP'); ?>">
			<?php echo JText::_('COM_REDSHOP_AJAX_BOX_WIDTH_AND_HEIGHT_TOOLTIP_LBL'); ?>
        </label>
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <input type="number" name="ajax_box_width" id="ajax_box_width" class="form-control"
                           value="<?php echo $this->config->get('AJAX_BOX_WIDTH'); ?>"/>
                </div>
                <div class="col-sm-6">
                    <input type="number" name="ajax_box_height" id="ajax_box_height" class="form-control"
                           value="<?php echo $this->config->get('AJAX_BOX_HEIGHT'); ?>"/>
                </div>
            </div>
        </div>
    </div>
    <hr/>
<?php
$site = JApplicationCms::getInstance('site');
$menu = $site->getMenu();

// Get menu items - array filtered by access level of current user.
$menuItems = $menu->getItems(null, null);

// Build please select option for no itemid
$selectOption   = array();
$selectOption[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_PLEASE_SELECT'), 'id', 'title');

// Merge items to build select list.
$items = array_merge($selectOption, $menuItems);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_CART_CHECKOUT_ITEMID_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_CART_CHECKOUT_ITEMID'),
		'field' => JHTML::_(
			'select.genericlist',
			$items,
			'default_cart_checkout_itemid',
			'',
			'id',
			'title',
			$this->config->get('DEFAULT_CART_CHECKOUT_ITEMID')
		)
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ADDTOCART_BUTTON_BEHAVE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BUTTON_BEHAVE'),
		'field' => $this->lists['addtocart_behaviour']
	)
);
?>
    <legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_CART_SETTINGS') ?></legend>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_ENABLE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ONESTEP_CHECKOUT_ENABLE_LBL'),
		'field' => $this->lists['onestep_checkout_enable']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_SHOW_SHIPPING_IN_CART_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_SHOW_SHIPPING_IN_CART_LBL'),
		'field' => $this->lists['show_shipping_in_cart']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL'),
		'field' => $this->lists['wanttoshowattributeimage']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_QUANTITY_TEXT_DISPLAY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_QUANTITY_TEXT_DISPLAY_LBL'),
		'field' => $this->lists['quantity_text_display']
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_QUANTITY_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_LBL'),
		'field' => '<input type="number" name="default_quantity" id="default_quantity" class="form-control"
            value="' . $this->config->get('DEFAULT_QUANTITY') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL'),
		'field' => '<input type="text" name="default_quantity_selectbox_value" id="default_quantity_selectbox_value" class="form-control"
            value="' . $this->config->get('DEFAULT_QUANTITY_SELECTBOX_VALUE') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_CONTINUE_REDIRECT_LINK_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_CONTINUE_REDIRECT_LINK'),
		'field' => '<input type="text" name="continue_redirect_link" id="continue_redirect_link" class="form-control" size="50"
            value="' . $this->config->get('CONTINUE_REDIRECT_LINK') . '" />'
	)
);
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_MINIMUM_ORDER_TOTAL_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_MINIMUM_ORDER_TOTAL'),
		'field' => '<input type="number" name="minimum_order_total" id="minimum_order_total" class="form-control" size="20"
            value="' . $this->config->get('MINIMUM_ORDER_TOTAL') . '" />',
		'line'  => false
	)
);
