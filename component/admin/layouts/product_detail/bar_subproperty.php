<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtmlBehavior::modal('a.joom-box');
JHtml::_('behavior.framework', true);
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

extract($displayData);

?>
<div class="thead row-fluid" <?php if (isset($dataId)) : ?> data-id="<?php echo $dataId ?>" style="display:none; background-color: darkgreen; color: #ffffff;" <?php else : ?> style="background-color: darkgreen; color: #ffffff;" <?php endif ?>>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_ID') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_IMAGE') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_MEDIA') ?></div>
    <div class="th span2"><?php echo \JText::_('COM_REDSHOP_NAME') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_OPERAND') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_PRICE') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_PRESELECT') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_PUBLISHED') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_HIDE') ?></div>
    <div class="th span2">
        <div class="btn btn-primary btn-ajax" style="background-color: #ffffff; color: darkgreen;" target="new_subproperty" func="new" property-id="<?php echo $p->property_id ?>" attribute-id="<?php echo $a['attribute_id'] ?>" type="subproperty">
            <?php echo \JText::_('COM_REDSHOP_ADD') ?>
        </div>
    </div>
</div>