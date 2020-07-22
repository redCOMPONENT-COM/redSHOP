<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JPluginHelper::importPlugin('redshop_promotion');
$dispatcher = \RedshopHelperUtility::getDispatcher();
//echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
$fields = $this->form->getFieldset('details');
?>
<form action="index.php?option=com_redshop&amp;task=promotion.edit&amp;id=" method="post" id="adminForm" name="adminForm" class="form-validate form-horizontal adminform" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-4">
            <div class="box-primary box">
                <div class="box-header with-border">
                    <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_PROMOTION_GENERAL') ?></h3>
                </div>
                <div class="box-body">
                    <?php if (count($fields) > 0): ?>
                        <?php foreach ($fields as $field): ?>
                            <?php echo '<pre>'; var_dump($field->id); var_dump($field->value); echo '</pre>'; ?>
                            <?php if (($field->id == 'jform_type') && ($this->id > 0)): ?>
                                <?php $field->disabled = true; ?>
                            <?php endif ?>
                            <div class="form-group row-fluid ">
                                <?php echo $field->label ?>
                                <div class="col-md-10">
                                    <?php echo $field->input ?>
                                </div>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box-primary box">
                <div class="box-header with-border">
                    <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_PROMOTION_CONDITIONS') ?></h3>
                </div>
                <div class="box-body">
                    <?php echo $dispatcher->trigger('onRenderBackEndLayoutConditions', [])[0]; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box-primary box">
                <div class="box-header with-border">
                    <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_PROMOTION_AWARDS') ?></h3>
                </div>
                <div class="box-body">
                    <?php echo $dispatcher->trigger('onRenderBackEndLayoutAwards', [])[0]; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden">
        <?php echo JHtml::_('form.token'); ?>
        <input type="hidden" name="view" value="promotion" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="layout" value="edit" />
    </div>
</form>
