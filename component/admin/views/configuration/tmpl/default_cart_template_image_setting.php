<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');

$url           = JUri::root();
$addToCartPath = "/components/com_redshop/assets/images/";
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_CART_THUMB_WIDTH_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_CART_THUMB_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="cart_thumb_width" id="cart_thumb_width" class="form-control"
                       value="<?php echo $this->config->get('CART_THUMB_WIDTH'); ?>"/>
            </div>
            <div class="col-sm-6">
                <input type="number" name="cart_thumb_height" id="cart_thumb_height" class="form-control"
                       value="<?php echo $this->config->get('CART_THUMB_HEIGHT'); ?>"/>
            </div>
        </div>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_WATERMARK_CART_THUMB_IMAGE_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_WATERMARK_CART_THUMB_IMAGE'),
		'field' => $this->lists['watermark_cart_thumb_image']
	)
);
?>
<legend class="no-border text-danger"><?php echo JText::_('COM_REDSHOP_CART_DEFAULT_IMAGE_SETTINGS') ?></legend>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_IMAGE'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDTOCART_IMAGE_LBL'); ?>
    </label>
    <div class="col-md-8">
        <input class="text_area" type="file" name="cartimg" id="cartimg" size="50"/>
        <input type="hidden" name="addtocart_image" id="addtocart_image"
               value="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>"/>
		<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $this->config->get('ADDTOCART_IMAGE'))): ?>
            <div class="divimages" id="cartdiv">
                <a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE') ?>"
                   title="<?php echo $this->config->get('ADDTOCART_IMAGE') ?>" rel="{handler: 'image', size: {}}">
                    <img alt="<?php echo $this->config->get('ADDTOCART_IMAGE') ?>" class="thumbnail"
                         src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE') ?>"/>
                </a>
                <a class="remove_link" href="#"
                   onclick="delimg('<?php echo $this->config->get('ADDTOCART_IMAGE') ?>','cartdiv','<?php echo $addToCartPath ?>');">
					<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_ADDTOCART_BACKGROUND_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BACKGROUND'),
		'field' => '<input type="text" name="addtocart_background" id="addtocart_background" class="form-control"
           value="' . $this->config->get('ADDTOCART_BACKGROUND') . '" />'
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_IMAGE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_REQUESTQUOTE_IMAGE_LBL'); ?>
    </label>
    <div class="col-md-8">
		<?php $requestquoteImage = $this->config->get('REQUESTQUOTE_IMAGE'); ?>
        <input class="text_area" type="file" name="quoteimg" id="quoteimg" size="50"/>
        <input type="hidden" name="requestquote_image" id="requestquote_image" value="<?php echo $requestquoteImage; ?>"/>
		<?php if (JFile::exists(JPATH_ROOT . $addToCartPath . $requestquoteImage)): ?>
            <div class="divimages" id="quotediv">
                <a class="joom-box" href="<?php echo $url . $addToCartPath . $requestquoteImage; ?>"
                   title="<?php echo $requestquoteImage; ?>" rel="{handler: 'image', size: {}}">
                    <img alt="<?php echo $requestquoteImage; ?>" src="<?php echo $url . $addToCartPath . $requestquoteImage; ?>" class="thumbnail"/>
                </a>
                <a class="remove_link" href="#"
                   onclick="delimg('<?php echo $requestquoteImage ?>','quotediv','<?php echo $addToCartPath ?>');">
					<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_REQUESTQUOTE_BACKGROUND_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'),
		'field' => '<input type="text" name="requestquote_background" id="requestquote_background" class="form-control"
           value="' . $this->config->get('REQUESTQUOTE_BACKGROUND') . '" />'
	)
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDTOCART_UPDATE_LBL'); ?>
    </label>
    <div class="col-md-8">
		<?php $addtocartUpdate = $this->config->get('ADDTOCART_UPDATE'); ?>
        <input class="text_area" type="file" name="cartupdate" id="cartupdate" size="50"/>
        <input type="hidden" name="addtocart_update" id="addtocart_update"
               value="<?php echo $addtocartUpdate; ?>"/>
		<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartUpdate)): ?>
            <div class="divimages" id="cartupdatediv">
                <a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate; ?>"
                   title="<?php echo $addtocartUpdate; ?>" rel="{handler: 'image', size: {}}">
                    <img alt="<?php echo $addtocartUpdate; ?>" class="thumbnail"
                         src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate; ?>"/>
                </a>
                <a class="remove_link" href="#"
                   onclick="delimg('<?php echo $addtocartUpdate ?>','cartupdatediv','<?php echo $addToCartPath ?>');">
					<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-content="<?php echo JText::_('COM_REDSHOP_TOOLTIP_ADDTOCART_DELETE_LBL'); ?>">
		<?php echo JText::_('COM_REDSHOP_ADDTOCART_DELETE_LBL'); ?>
    </label>
    <div class="col-md-8">
		<?php $addtocartDelete = $this->config->get('ADDTOCART_DELETE'); ?>
        <input class="text_area" type="file" name="cartdelete" id="cartdelete" size="50"/>
        <input type="hidden" name="addtocart_delete" id="addtocart_delete"
               value="<?php echo $addtocartDelete; ?>"/>
		<?php if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartDelete)): ?>
            <div class="divimages" id="cartdeldiv">
                <a class="joom-box" href="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete; ?>"
                   title="<?php echo $addtocartDelete; ?>" rel="{handler: 'image', size: {}}">
                    <img alt="<?php echo $addtocartDelete; ?>" class="thumbnail"
                         src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete; ?>"/>
                </a>
                <a class="remove_link" href="#"
                   onclick="delimg('<?php echo $addtocartDelete ?>','cartdeldiv','<?php echo $addToCartPath ?>');">
					<?php echo JText::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
		<?php endif; ?>
    </div>
</div>
