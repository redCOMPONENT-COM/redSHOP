<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

if (isset($this->lists['attributes'])) {
    $attributes = $this->lists['attributes'];
}
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?></h3>
            </div>
            <div class="box-body">
                <?php if (empty($attributes)) : ?>
                    <fieldset class="adminform">
                        <div class="alert alert-success" role="alert"><?php echo JText::_(
                                                                            'COM_REDSHOP_HINT_ATTRIBUTE'
                                                                        ); ?></div>

                        <div class="col-sm-4">
                            <?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE_SET_LBL'); ?>
                        </div>
                        <div class="col-sm-8">
                            <?php echo $this->lists['attributesSet']; ?>
                        </div>

                        <div class="col-sm-4">
                            <?php echo JText::_('COM_REDSHOP_COPY_ATTRIBUTES_FROM_ATTRIBUTE_SET'); ?>
                        </div>
                        <div class="col-sm-8">
                            <?php echo $this->lists['copy_attribute']; ?>
                        </div>

                        <a class="btn btn-success btn-apply-attribute-set pull-right" href="#"> <?php echo JText::_(
                                                                                                    'COM_REDSHOP_APPLY'
                                                                                                ); ?></a>
                    </fieldset>
                    <hr />
                <?php endif ?>
                <?php echo RedshopLayoutHelper::render('product_detail.product_attribute', array('this' => $this)); ?>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        jQuery('body').on('click', '.btn-apply-attribute-set', () => {
            let decide = confirm('Are u sure');

            if (decide == true) {

            }
        });
    });

    const applyAttributeSet = (aid, pid) => {

    }
</script>