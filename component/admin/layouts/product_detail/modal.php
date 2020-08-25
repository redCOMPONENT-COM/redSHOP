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

<!-- Sub property modal -->
<div id="new_subproperty">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="row-fluid function-bottom">
                <div class="span10"></div>
                <div class="span2">
                    <button id="save_subproperty_top" class="btn button-apply btn-success btn-ajax">
                        <?php echo \JText::_('COM_REDSHOP_SAVE'); ?>
                    </button>
                    <span class="btn modal-close" target="new_subproperty" task="cancel" layout="subproperties">
                        <?php echo \JText::_('COM_REDSHOP_CLOSE'); ?>
                    </span>
                </div>
            </div>
            <div class="row-fluid">
                <div class="row-fluid span5">
                    <div class="row-fluid element_title">
                        <span><?php echo \JText::_('COM_REDSHOP_SUBPROPERTY_LBL') ?></span>
                        <small>[ <?php echo \JText::_('COM_REDSHOP_NEW') ?> ]</small>
                    </div>
                    <div id="subproperty_result" class="row-fluid element-result-box"></div>
                    <input type="hidden" data-name="attribute_id" value="0" />
                    <input type="hidden" name="subattribute_id" data-name="property_id" value="0" />
                    <input type="hidden" name="subattribute_color_id" data-name="subproperty_id" value="0" />

                    <div class="row-fluid">

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_TITLE') ?></div>
                            <div class="td span7"><input name="subattribute_color_title" type="text" /></div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_NAME') ?></div>
                            <div class="td span7"><input name="subattribute_color_name" type="text" /></div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PRODUCT_VIRTUAL_NUMBER') ?></div>
                            <div class="td span7"><input name="subattribute_color_number" type="text" /></div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PARENT'); ?></div>
                            <div class="td span7"><select name="parent_id"></select></div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4" data-content="subproperty-image-lbl"><?php echo \JText::_('COM_REDSHOP_IMAGE') ?></div>
                            <div class="td span7" data-content="subattribute_color_image">
                                <?php
                                echo RedshopHelperMediaImage::render(
                                    'subattribute_color_image',
                                    'subcolor',
                                    '0',
                                    'tmp',
                                    '',
                                    false,
                                    true,
                                    ''
                                );
                                ?>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_OPERAND') ?></div>
                            <div class="td span7">
                                <select name="oprand">
                                    <option><?php echo \JText::_('COM_REDSHOP_SELECT') ?></option>
                                    <option value="+">+</option>
                                    <option value="-">-</option>
                                    <option value="*">*</option>
                                    <option value="/">/</option>
                                    <option value="=">=</option>
                                </select>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PRICE') ?></div>
                            <div class="td span7"><input name="subattribute_color_price" type="text" /></div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PRESELECT') ?></div>
                            <div class="td span7">
                                <?php echo JHtml::_('redshopselect.booleanlist', 'setdefault_selected', 'class="form-control" size="1"', 0); ?>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PUBLISHED') ?></div>
                            <div class="td span7">
                                <?php echo JHtml::_('redshopselect.booleanlist', 'subattribute_published', 'class="form-control" size="1"', 1); ?>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_HIDE') ?></div>
                            <div class="td span7">
                                <?php echo JHtml::_('redshopselect.booleanlist', 'subattribute_color_hide', 'class="form-control" size="1"', 0); ?>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_EXTRA_FIELD') ?></div>
                            <div class="td span7">
                                <textarea name="extra_field"></textarea>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="th span4"><?php echo \JText::_('COM_REDSHOP_ORDER') ?></div>
                            <div class="td span7"><input name="ordering" type="text" value="0" /></div>
                        </div>
                    </div>
                </div>
                <div id="subproperty_dependencies_view" class="span7">
                    <div class="row-fluid" style="padding-bottom: 10px;"><?php echo \Jtext::_('COM_REDSHOP_DEPENDENCIES') ?></div>
                    <div class="row-fluid">
                        <div class="row-fluid" style="background-color: black; color: white; padding: 10px;">
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_ATTRIBUTE') ?></div>
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_PROPERTY') ?></div>
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_SUBPROPERTY') ?></div>
                        </div>
                        <div class="row-fluid" style="padding: 10px; background-color: cornsilk;">
                            <div class="span3"><select data-name="dependency_attribute"></select></div>
                            <div class="span3"><select data-name="dependency_property"></select></div>
                            <div class="span3"><select data-name="dependency_subproperty"></select></div>
                            <div class="span2"><button class="btn btn-success btn-add-dependency"><?php echo \JText::_('COM_REDSHOP_ADD_DEPENDENCY_ROW') ?></button></div>
                        </div>
                        <div class="row-fluid" style="padding: 10px; border-left: 1px solid gray; font-weight: normal;" data-id="data-dependency" data-dependency="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid function-bottom">
                <div class="span10"></div>
                <div class="span2">
                    <button id="save_subproperty" class="btn button-apply btn-success btn-ajax">
                        <?php echo \JText::_('COM_REDSHOP_SAVE'); ?>
                    </button>
                    <span class="btn modal-close" target="new_subproperty" task="cancel" layout="subproperties">
                        <?php echo \JText::_('COM_REDSHOP_CLOSE'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Property modal -->
<div id="new_property">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="row-fluid function-bottom">
                <div class="span10"></div>
                <div class="span2">
                    <button id="save_property_top" class="btn button-apply btn-success btn-ajax">
                        <?php echo \JText::_('COM_REDSHOP_SAVE'); ?>
                    </button>
                    <span class="btn modal-close" target="new_property" task="cancel" layout="properties">
                        <?php echo \JText::_('COM_REDSHOP_CLOSE'); ?>
                    </span>
                </div>
            </div>
            <div class="row-fluid property_title">
                <span><?php echo \JText::_('COM_REDSHOP_PROPERTY_LBL') ?></span>
                <small>[ <?php echo \JText::_('COM_REDSHOP_NEW') ?> ]</small>
            </div>
            <div id="property_result" class="row-fluid element-result-box"></div>
            <input type="hidden" name="property_id" value="0" />
            <input type="hidden" name="attribute_id" value="0" />
            <div class="row-fluid">
                <div class="row-fluid span5">
                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_NAME') ?></div>
                        <div class="td span7"><input name="property_name" type="text" /></div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PRODUCT_VIRTUAL_NUMBER') ?></div>
                        <div class="td span7"><input name="property_number" type="text" /></div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4" data-content="propduct-image-lbl"><?php echo \JText::_('COM_REDSHOP_IMAGE') ?></div>
                        <div class="td span7" data-content="property_image">
                            <?php
                            echo RedshopHelperMediaImage::render(
                                'property_image',
                                'product_attributes',
                                '55339',
                                'tmp',
                                '',
                                false,
                                true,
                                ''
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_DISPLAY_TYPE') ?></div>
                        <div class="td span7">
                            <?php
                            $displayType         = array();
                            $displayType[]       = JHtml::_('select.option', 'dropdown', JText::_('COM_REDSHOP_DROPDOWN_LIST'));
                            $displayType[]       = JHtml::_('select.option', 'radio', JText::_('COM_REDSHOP_RADIOBOX'));
                            echo JHtml::_('redshopselect.radiolist', $displayType, 'setdisplay_type', '', 'value', 'text', 'dropdown');
                            ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_OPERAND') ?></div>
                        <div class="td span7">
                            <select name="oprand">
                                <option><?php echo \JText::_('COM_REDSHOP_SELECT') ?></option>
                                <option value="+">+</option>
                                <option value="-">-</option>
                                <option value="*">*</option>
                                <option value="/">/</option>
                                <option value="=">=</option>
                            </select>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PRICE') ?></div>
                        <div class="td span7"><input name="property_price" type="text" /></div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PRESELECT') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'property_setdefault_selected', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_REQUIRED') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'property_setrequire_selected', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_MULTI_SELECTION') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'setmulti_selected', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PUBLISHED') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'property_published', 'class="form-control" size="1"', 1); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_HIDE') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'property_hide', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_EXTRA_FIELD') ?></div>
                        <div class="td span7">
                            <textarea name="extra_field"></textarea>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_ORDER') ?></div>
                        <div class="td span7"><input name="ordering" type="text" value="0" /></div>
                    </div>
                </div>
                <div id="property_dependencies_view" class="span7">
                    <div class="row-fluid" style="padding-bottom: 10px;"><?php echo \Jtext::_('COM_REDSHOP_DEPENDENCIES') ?></div>
                    <div class="row-fluid">
                        <div class="row-fluid" style="background-color: black; color: white; padding: 10px;">
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_ATTRIBUTE') ?></div>
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_PROPERTY') ?></div>
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_SUBPROPERTY') ?></div>
                        </div>
                        <div class="row-fluid" style="padding: 10px; background-color: cornsilk;">
                            <div class="span3"><select data-name="dependency_attribute"></select></div>
                            <div class="span3"><select data-name="dependency_property"></select></div>
                            <div class="span3"><select data-name="dependency_subproperty"></select></div>
                            <div class="span2"><button class="btn btn-success btn-add-dependency"><?php echo \JText::_('COM_REDSHOP_ADD_DEPENDENCY_ROW') ?></button></div>
                        </div>
                        <div class="row-fluid" style="padding: 10px; border-left: 1px solid gray; font-weight: normal;" data-id="data-dependency" data-dependency="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid function-bottom">
                <div class="span10"></div>
                <div class="span2">
                    <button id="save_property" class="btn button-apply btn-success btn-ajax">
                        <?php echo \JText::_('COM_REDSHOP_SAVE'); ?>
                    </button>
                    <span class="btn modal-close" target="new_property" task="cancel" layout="properties">
                        <?php echo \JText::_('COM_REDSHOP_CLOSE'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attribute modal -->
<div id="new_attribute">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="row-fluid function-bottom">
                <div class="span10"></div>
                <div class="span2">
                    <button id="save_attribute_top" class="btn button-apply btn-success btn-ajax">
                        <?php echo \JText::_('COM_REDSHOP_SAVE'); ?>
                    </button>
                    <span class="btn modal-close" target="new_attribute" task="cancel" layout="attributes">
                        <?php echo \JText::_('COM_REDSHOP_CLOSE'); ?>
                    </span>
                </div>
            </div>
            <div class="row-fluid attribute-title">
                <span><?php echo \JText::_('COM_REDSHOP_ATTRIBUTE') ?></span>
                <small>[ <?php echo \JText::_('COM_REDSHOP_NEW') ?> ]</small>
            </div>
            <div id="attribute_result" class="row-fluid element-result-box"></div>
            <input type="hidden" name="product_id" value="<?php echo $productId ?>" />
            <input type="hidden" name="attribute_id" value="0" />
            <div class="row-fluid">
                <div class="span5">
                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_NAME') ?></div>
                        <div class="td span7"><input name="attribute_name" type="text" /></div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_DESCRIPTION') ?></div>
                        <div class="td span7">
                            <textarea name="attribute_description"></textarea>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_HIDE_ATTRIBUTE_PRICE') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'hide_attribute_price', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_REQUIRED') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'attribute_required', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_MULTI_SELECTION') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'allow_multiple_selection', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_PUBLISHED') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'attribute_published', 'class="form-control" size="1"', 1); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_DISPLAY_TYPE') ?></div>
                        <div class="td span7">
                            <?php
                            $displayType         = array();
                            $displayType[]       = JHtml::_('select.option', 'dropdown', JText::_('COM_REDSHOP_DROPDOWN_LIST'));
                            $displayType[]       = JHtml::_('select.option', 'radio', JText::_('COM_REDSHOP_RADIOBOX'));
                            echo JHtml::_('redshopselect.radiolist', $displayType, 'display_type', '', 'value', 'text', 'dropdown');
                            ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_HIDE') ?></div>
                        <div class="td span7">
                            <?php echo JHtml::_('redshopselect.booleanlist', 'attribute_hide', 'class="form-control" size="1"', 0); ?>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="th span4"><?php echo \JText::_('COM_REDSHOP_ORDERING') ?></div>
                        <div class="td span7">
                            <input type="text" name="ordering" value="0" />
                        </div>
                    </div>
                </div>
                <div id="attribute_dependencies_view" class="span7">
                    <div class="row-fluid" style="padding-bottom: 10px;"><?php echo \Jtext::_('COM_REDSHOP_DEPENDENCIES') ?></div>
                    <div class="row-fluid">
                        <div class="row-fluid" style="background-color: black; color: white; padding: 10px;">
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_ATTRIBUTE') ?></div>
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_PROPERTY') ?></div>
                            <div class="span3"><?php echo \JText::_('COM_REDSHOP_SUBPROPERTY') ?></div>
                        </div>
                        <div class="row-fluid" style="padding: 10px; background-color: cornsilk;">
                            <div class="span3"><select data-name="dependency_attribute"></select></div>
                            <div class="span3"><select data-name="dependency_property"></select></div>
                            <div class="span3"><select data-name="dependency_subproperty"></select></div>
                            <div class="span2"><button class="btn btn-success btn-add-dependency"><?php echo \JText::_('COM_REDSHOP_ADD_DEPENDENCY_ROW') ?></button></div>
                        </div>
                        <div class="row-fluid" style="padding: 10px; border-left: 1px solid gray; font-weight: normal;" data-id="data-dependency" data-dependency="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-fluid function-bottom">
                <div class="span10"></div>
                <div class="span2">
                    <button id="save_attribute" class="btn button-apply btn-success btn-ajax">
                        <?php echo \JText::_('COM_REDSHOP_SAVE'); ?>
                    </button>
                    <span class="btn modal-close" target="new_attribute" task="cancel" layout="attributes">
                        <?php echo \JText::_('COM_REDSHOP_CLOSE'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>