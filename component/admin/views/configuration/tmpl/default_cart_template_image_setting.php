<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;

$url           = JUri::root();
$addToCartPath = "/components/com_redshop/assets/images/";
?>

<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover"
        data-bs-content="<?php echo Text::_('COM_REDSHOP_TOOLTIP_CART_THUMB_WIDTH_LBL'); ?>">
        <?php echo Text::_('COM_REDSHOP_CART_THUMB_WIDTH_HEIGHT'); ?>
    </label>
    <div class="col-md-8">
        <div class="row">
            <div class="col-sm-6">
                <input type="number" name="cart_thumb_width" id="cart_thumb_width" class="form-control"
                    value="<?php echo $this->config->get('CART_THUMB_WIDTH'); ?>" />
            </div>
            <div class="col-sm-6">
                <input type="number" name="cart_thumb_height" id="cart_thumb_height" class="form-control"
                    value="<?php echo $this->config->get('CART_THUMB_HEIGHT'); ?>" />
            </div>
        </div>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => Text::_('COM_REDSHOP_WATERMARK_CART_THUMB_IMAGE_LBL'),
        'desc'  => Text::_('COM_REDSHOP_TOOLTIP_WATERMARK_CART_THUMB_IMAGE'),
        'field' => $this->lists['watermark_cart_thumb_image']
    )
);
?>
<legend class="no-border text-danger">
    <?php echo Text::_('COM_REDSHOP_CART_DEFAULT_IMAGE_SETTINGS') ?>
</legend>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover" data-bs-content="<?php echo Text::_('COM_REDSHOP_TOOLTIP_ADDTOCART_IMAGE'); ?>">
        <?php echo Text::_('COM_REDSHOP_ADDTOCART_IMAGE_LBL'); ?>
    </label>
    <div class="col-md-8">
        <input class="form-control" type="file" name="cartimg" id="cartimg" size="50" />
        <input type="hidden" name="addtocart_image" id="addtocart_image"
            value="<?php echo $this->config->get('ADDTOCART_IMAGE'); ?>" />
        <?php if (File::exists(REDSHOP_FRONT_IMAGES_RELPATH . $this->config->get('ADDTOCART_IMAGE'))): ?>
            <div class="divimages" id="cartdiv">
                <?php echo
                    RedshopLayoutHelper::render(
                        'modal.a-image',
                        [
                            'selector'        => 'cartImage',
                            'imageAttributes' => ['alt' => 'Image name'],
                            'params'          => [
                                'imageThumbPath' => REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE'),
                                'imageMainPath'  => REDSHOP_FRONT_IMAGES_ABSPATH . $this->config->get('ADDTOCART_IMAGE'),
                            ]
                        ]
                    );
                ?>
                <a class="remove_link" href="#" onclick="delimg('<?php echo $this->config->get(
                    'ADDTOCART_IMAGE'
                ) ?>','cartdiv','<?php echo $addToCartPath ?>');">
                    <?php echo Text::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => Text::_('COM_REDSHOP_ADDTOCART_BACKGROUND_LBL'),
        'desc'  => Text::_('COM_REDSHOP_TOOLTIP_ADDTOCART_BACKGROUND'),
        'field' => '<input type="text" name="addtocart_background" id="addtocart_background" class="form-control"
           value="' . $this->config->get('ADDTOCART_BACKGROUND') . '" />'
    )
);

?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover"
        data-bs-content="<?php echo Text::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_IMAGE_LBL'); ?>">
        <?php echo Text::_('COM_REDSHOP_REQUESTQUOTE_IMAGE_LBL'); ?>
    </label>
    <div class="col-md-8">
        <?php $requestquoteImage = $this->config->get('REQUESTQUOTE_IMAGE'); ?>
        <input class="form-control" type="file" name="quoteimg" id="quoteimg" size="50" />
        <input type="hidden" name="requestquote_image" id="requestquote_image"
            value="<?php echo $requestquoteImage; ?>" />
        <?php if (File::exists(JPATH_ROOT . $addToCartPath . $requestquoteImage)): ?>
            <div class="divimages" id="quotediv">
                <?php echo
                    RedshopLayoutHelper::render(
                        'modal.a-image',
                        [
                            'selector'        => 'cartImage',
                            'imageAttributes' => ['alt' => 'Request quote image'],
                            'params'          => [
                                'imageThumbPath' => $url . $addToCartPath . $requestquoteImage,
                                'imageMainPath'  => $url . $addToCartPath . $requestquoteImage,
                            ]
                        ]
                    );
                ?>
                <a class="remove_link" href="#"
                    onclick="delimg('<?php echo $requestquoteImage ?>','quotediv','<?php echo $addToCartPath ?>');">
                    <?php echo Text::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
echo RedshopLayoutHelper::render(
    'config.config',
    array(
        'title' => Text::_('COM_REDSHOP_REQUESTQUOTE_BACKGROUND_LBL'),
        'desc'  => Text::_('COM_REDSHOP_TOOLTIP_REQUESTQUOTE_BACKGROUND_LBL'),
        'field' => '<input type="text" name="requestquote_background" id="requestquote_background" class="form-control"
           value="' . $this->config->get('REQUESTQUOTE_BACKGROUND') . '" />'
    )
);
?>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover"
        data-bs-content="<?php echo Text::_('COM_REDSHOP_TOOLTIP_ADDTOCART_UPDATE_LBL'); ?>">
        <?php echo Text::_('COM_REDSHOP_ADDTOCART_UPDATE_LBL'); ?>
    </label>
    <div class="col-md-8">
        <?php $addtocartUpdate = $this->config->get('ADDTOCART_UPDATE'); ?>
        <input class="form-control" type="file" name="cartupdate" id="cartupdate" size="50" />
        <input type="hidden" name="addtocart_update" id="addtocart_update" value="<?php echo $addtocartUpdate; ?>" />
        <?php if (File::exists(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartUpdate)): ?>
            <div class="divimages" id="cartupdatediv">
                <?php echo
                    RedshopLayoutHelper::render(
                        'modal.a-image',
                        [
                            'selector'        => 'addToCartUpdateImage',
                            'imageAttributes' => ['alt' => 'Add To Cart Update Image'],
                            'params'          => [
                                'imageThumbPath' => REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate,
                                'imageMainPath'  => REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartUpdate,
                            ]
                        ]
                    );
                ?>
                <a class="remove_link" href="#"
                    onclick="delimg('<?php echo $addtocartUpdate ?>','cartupdatediv','<?php echo $addToCartPath ?>');">
                    <?php echo Text::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="form-group row-fluid">
    <label class="col-md-4 hasPopover"
        data-bs-content="<?php echo Text::_('COM_REDSHOP_TOOLTIP_ADDTOCART_DELETE_LBL'); ?>">
        <?php echo Text::_('COM_REDSHOP_ADDTOCART_DELETE_LBL'); ?>
    </label>
    <div class="col-md-8">
        <?php $addtocartDelete = $this->config->get('ADDTOCART_DELETE'); ?>
        <input class="form-control" type="file" name="cartdelete" id="cartdelete" size="50" />
        <input type="hidden" name="addtocart_delete" id="addtocart_delete" value="<?php echo $addtocartDelete; ?>" />
        <?php if (File::exists(REDSHOP_FRONT_IMAGES_RELPATH . $addtocartDelete)): ?>
            <div class="divimages" id="cartdeldiv">
                <?php echo
                    RedshopLayoutHelper::render(
                        'modal.a-image',
                        [
                            'selector'        => 'addToCartDeleteImage',
                            'imageAttributes' => ['alt' => 'Add To Cart Delete Image'],
                            'params'          => [
                                'imageThumbPath' => REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete,
                                'imageMainPath'  => REDSHOP_FRONT_IMAGES_ABSPATH . $addtocartDelete,
                            ]
                        ]
                    );
                ?>
                <a class="remove_link" href="#"
                    onclick="delimg('<?php echo $addtocartDelete ?>','cartdeldiv','<?php echo $addToCartPath ?>');">
                    <?php echo Text::_('COM_REDSHOP_REMOVE_IMAGE') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
