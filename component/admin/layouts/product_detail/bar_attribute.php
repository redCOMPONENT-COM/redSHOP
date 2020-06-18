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

<div class="thead row-fluid" style="background-color: #0a001f; color: #ffffff;">
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_ID') ?></div>
    <div class="th span2"><?php echo \JText::_('COM_REDSHOP_ATTRIBUTE_NAME') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_ATTRIBUTE_REQUIRED') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_ALLOW_MULTIPLE_PROPERTY_SELECTION') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_PUBLISHED') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_DISPLAY_ATTRIBUTE_TYPE') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_HIDE') ?></div>
    <div class="th span1"><?php echo \JText::_('COM_REDSHOP_CHILD') ?></div>
    <div class="th span2">
        <div class="btn btn-primary btn-ajax" style="background-color: #ffffff; color: black;" target="new_attribute" func="new" type="attribute">
            <?php echo \JText::_('COM_REDSHOP_ADD') ?>
        </div>
    </div>
</div>