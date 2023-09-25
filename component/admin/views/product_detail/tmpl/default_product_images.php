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
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo Text::_('COM_REDSHOP_PRODUCT_IMAGES'); ?></h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="product_thumb_image">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_THUMB_IMAGE'),
                            Text::_('COM_REDSHOP_PRODUCT_THUMB_IMAGE')
                        );
                        ?>
                    </label>
                    <?php
                    echo RedshopLayoutHelper::render(
                        'component.image',
                        array(
                            'id'        => 'product_thumb_image',
                            'deleteid'  => 'thumb_image_delete',
                            'displayid' => 'thumb_image_display',
                            'type'      => 'product',
                            'image'     => $this->detail->product_thumb_image
                        )
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label for="product_back_thumb_image">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_BACK_THUMB_IMAGE'),
                            Text::_('COM_REDSHOP_PRODUCT_BACK_THUMB_IMAGE')
                        );
                        ?>
                    </label>
                    <?php
                    echo RedshopLayoutHelper::render(
                        'component.image',
                        array(
                            'id'        => 'product_back_thumb_image',
                            'deleteid'  => 'back_thumb_image_delete',
                            'displayid' => 'thumb_back_image_display',
                            'type'      => 'product',
                            'image'     => $this->detail->product_back_thumb_image
                        )
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label for="product_preview_image">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_IMAGE'),
                            Text::_('COM_REDSHOP_PRODUCT_PREVIEW_IMAGE')
                        );
                        ?>
                    </label>
                    <?php
                    echo RedshopLayoutHelper::render(
                        'component.image',
                        array(
                            'id'        => 'product_preview_image',
                            'deleteid'  => 'preview_image_delete',
                            'displayid' => 'preview_image_display',
                            'type'      => 'product',
                            'image'     => $this->detail->product_preview_image
                        )
                    );
                    ?>
                </div>

                <div class="form-group">
                    <label for="product_preview_image">
                        <?php echo Text::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE'); ?>
                        <?php
                        echo HtmlHelper::_('redshop.tooltip',
                            Text::_('COM_REDSHOP_TOOLTIP_PRODUCT_PREVIEW_BACK_IMAGE'),
                            Text::_('COM_REDSHOP_PRODUCT_PREVIEW_BACK_IMAGE')
                        );
                        ?>
                    </label>
                    <?php
                    echo RedshopLayoutHelper::render(
                        'component.image',
                        array(
                            'id'        => 'product_preview_back_image',
                            'deleteid'  => 'preview_back_image_delete',
                            'displayid' => 'preview_back_image_display',
                            'type'      => 'product',
                            'image'     => $this->detail->product_preview_back_image
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>