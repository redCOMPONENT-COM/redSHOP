<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');

$editor = JEditor::getInstance();
?>

<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (task) {
        var form = document.adminForm;
        var field_type = document.getElementById("jform_type").value;
        var field_section = document.getElementById("jform_section").value;

        if (task == "field.cancel") {
            Joomla.submitform(task);
        } else {
            var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";

            for (var i = 0; i < document.getElementById('jform_name').value.length; i++) {
                if (iChars.indexOf(document.getElementById("jform_name").value.charAt(i)) != -1) {
                    alert(" !@#$%^&*()+=-[]\\\';,./{}| \n Special characters are not allowed.\n Please remove them and try again.");
                    return false;
                }
            }

            if (document.getElementById("jform_name").value == "") {
                alert("<?php echo JText::_('COM_REDSHOP_FIELDS_ITEM_MUST_HAVE_A_NAME', true); ?>");
                document.getElementById("jform_name").focus();
                return false;
            } else if (document.getElementById("jform_title").value == "") {
                alert("<?php echo JText::_('COM_REDSHOP_FIELDS_ITEM_MUST_HAVE_A_TITLE', true); ?>");
                document.getElementById("jform_title").focus();
                return false;
            } else if ((document.getElementById("jform_section").value == 13) &&
                (document.getElementById("jform_type").value == 8 || document.getElementById("jform_type").value == 9 ||
                    document.getElementById("jform_type").value == 10)) {
                alert("<?php echo JText::_(
                    'COM_REDSHOP_ERROR_YOU_CAN_NOT_SELECT_THIS_SECTION_TYPE_UNDER_THIS_FIELD',
                    true
                );?>");
                return false;
            } else if (document.getElementById("jform_section").value == 0) {
                alert("<?php echo JText::_('COM_REDSHOP_FIELDS_ITEM_MUST_HAVE_A_SECTION'); ?>");
                return false;
            } else if (document.getElementById("jform_type").value == 0) {
                alert("<?php echo JText::_('COM_REDSHOP_FIELDS_ITEM_MUST_HAVE_A_TYPE'); ?>");
                return false;
            }
            if (field_type == 3 || field_type == 4 || field_type == 5 || field_type == 6 || field_type == 11 || field_type == 13) {
                var chks = document.getElementsByName("extra_value[]");//here extra_value[] is the name of the textbox

                for (var i = 0; i < chks.length; i++) {
                    if (chks[i].value == "") {
                        alert("Please fillup Option Value");
                        chks[i].focus();
                        return false;
                    }
                    else if (chks[i].value.indexOf(" ") !== -1) {
                        alert("<?php echo JText::_('COM_REDSHOP_CUSTOM_FIELD_NO_ENTER_SPACE'); ?>")
                        chks[i].focus();
                        return false;
                    }
                }
            }

            document.getElementById("jform_section").disabled = false;

            submitform(task);
        }
    };

    function loadFieldGroup() {
        (function ($) {
            var $fieldGroup = $("#jform_groupId");

            $.ajax({
                url: "index.php?option=com_redshop&task=field_groups.ajaxGetFieldsGroup",
                method: "POST",
                data: {
                    "<?php echo JSession::getFormToken() ?>": 1,
                    "section": $("#jform_section").val(),
                    "selected": <?php echo (int)$this->item->groupId ?>
                },
                beforeSend: function () {
                    $fieldGroup.prop("disabled", true).addClass("disabled");
                }
            })
                .done(function (response) {
                    $("#jform_groupId option").remove();
                    $fieldGroup.html(response);
                })
                .always(function () {
                    $fieldGroup.prop("disabled", false).removeClass("disabled");
                    $fieldGroup.select2({width: "auto"});
                });
        })(jQuery);
    }

    function sectionValidation() {
        (function ($) {
            var field_type = $("#jform_type").val();
            var field_section = $("#jform_section").val();

            // Field_section
            if ((field_section == 13) && (field_type == 8 || field_type == 9 || field_type == 10)) {
                alert("<?php echo JText::_(
                    'COM_REDSHOP_ERROR_YOU_CAN_NOT_SELECT_THIS_SECTION_TYPE_UNDER_THIS_FIELD'
                ) ?>");
                return false;
            }

            loadFieldGroup();
        })(jQuery);
    }

    function isAlphabet(elem, helperMsg) {
        var alphaExp = /^[a-zA-Z]+$/;
        if (elem.value.match(alphaExp)) {
            return true;
        } else {
            alert(helperMsg);
            elem.focus();
            return false;
        }
    }

    var manageFieldOptions = function (type) {
        type = parseInt(type);
        jQuery("#field_data").hide();

        if (jQuery.inArray(type, [3, 11, 13, 6, 4, 5]) >= 0) {
            jQuery("#field_data").show();

            if (jQuery.inArray(type, [11, 13]) >= 0) {
                jQuery(".divfieldText").addClass("hide").hide();
                jQuery(".divfieldFile").removeClass("hide").show();
            } else {
                jQuery(".divfieldText").removeClass("hide").show();
                jQuery(".divfieldFile").addClass("hide").hide();
            }
        }
    };

    window.onload = function () {
        var fieldType = jQuery("#jform_type");

        showMessage(fieldType.val());
        manageFieldOptions(fieldType.val());

        fieldType.on("change", function (el) {
            showMessage(jQuery(this).val());
            manageFieldOptions(jQuery(this).val());
        });
    };

    function showMessage(type) {
        // 9 is type of media
        if (type === "9") {
            // You can stack multiple messages of the same type
            var jmsgs = ['<?php echo JText::_("COM_REDSHOP_FIELDS_MEDIA_DEPRECATED") ?>'];
            Joomla.renderMessages({"notice": jmsgs});
        } else {
            jQuery("#system-message-container > .alert-notice").remove();
            jQuery("#toolbar-apply,#toolbar-save").show();
        }
    }
</script>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var fieldNames = [];

            $.post(
                "index.php?option=com_redshop&task=field.ajaxGetAllFieldName",
                {
                    "<?php echo JSession::getFormToken() ?>": 1,
                    "field_id": "<?php echo $this->item->id ?>"
                },
                function (response) {
                    fieldNames = response.split(",");
                });

            document.formvalidator.setHandler("fieldNames", function (value) {
                value = value.replace(" ", "_");
                var tmp = value.split("_");

                if (tmp[0] != "rs") {
                    value = "rs_" + value;
                }

                return !fieldNames.contains(value);
            });

            <?php if (!empty($this->item->id) && !empty($this->item->section)): ?>
            loadFieldGroup();
            <?php endif; ?>
        });
    })(jQuery);
</script>
<form action="index.php?option=com_redshop&task=field.edit&id=<?php echo $this->item->id; ?>" method="post"
      name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate form-horizontal adminform">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAIL') ?></h3>
                </div>
                <div class="box-body">
                    <?php if ($this->item->id) $this->form->setFieldAttribute('type', 'disabled', 'disabled') ?>
                    <?php echo $this->form->renderField('type') ?>
                    <?php if ($this->item->id) $this->form->setFieldAttribute('section', 'disabled', 'disabled') ?>
                    <?php echo $this->form->renderField('section') ?>
                    <?php echo $this->form->renderField('groupId') ?>
                    <?php echo $this->form->renderField('name') ?>
                    <?php echo $this->form->renderField('title') ?>
                    <?php echo $this->form->renderField('class') ?>
                    <?php echo $this->form->renderField('maxlength') ?>
                    <?php echo $this->form->renderField('size') ?>
                    <?php echo $this->form->renderField('cols') ?>
                    <?php echo $this->form->renderField('rows') ?>
                    <?php
                    if ($this->item->section == 1 || $this->item->section == 17) {
                        $display = 'style="display:block;"';
                    } else {
                        $display = 'style="display:none;"';
                    }
                    ?>
                    <?php echo $this->form->renderField('display_in_product') ?>
                    <?php echo $this->form->renderField('is_searchable') ?>
                    <?php echo $this->form->renderField('display_in_checkout') ?>
                    <?php echo $this->form->renderField('show_in_front') ?>
                    <?php echo $this->form->renderField('required') ?>
                    <?php echo $this->form->renderField('published') ?>
                    <?php echo $this->form->renderField('desc') ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary" id="field_data">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_VALUE') ?></h3>
                </div>
                <div class="box-body">
                    <p class="text text-primary"><?php echo JText::_(
                            'COM_REDSHOP_USE_THE_TABLE_BELOW_TO_ADD_NEW_VALUES'
                        ) ?></p>
                    <p>
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> Warning</h4>
                        <?php echo JText::_('COM_REDSHOP_CUSTOM_FIELD_WARNING_CHANGE_OPTION_VALUE') ?>
                    </div>
                    </p>

                    <p><input type="button" name="addvalue" id="addvalue" class="btn btn-primary"
                              Value="<?php echo JText::_('COM_REDSHOP_ADD_VALUE'); ?>"
                              onclick="addNewRow('extra_table');"/></p>

                    <table cellpadding="0" cellspacing="5" border="0" id="extra_table" class="table table-striped">
                        <thead>
                        <tr>
                            <th width="40%"><?php echo JText::_('COM_REDSHOP_OPTION_NAME'); ?></th>
                            <th width="40%"><?php echo JText::_('COM_REDSHOP_OPTION_VALUE'); ?></th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($this->lists['extra_data'])) : ?>
                            <?php foreach ($this->lists['extra_data'] as $index => $extraData): ?>
                                <tr>
                                    <td>
                                        <input
                                                type="text"
                                                class="divfieldText hide form-control"
                                                name="extra_name[]"
                                                id="extra_name<?php echo $index ?>"
                                                value="<?php echo htmlentities($extraData->field_name); ?>"
                                        />
                                        <input
                                                type="file"
                                                class="divfieldFile hide pull-left"
                                                name="extra_name_file[]"
                                        />
                                    </td>
                                    <td>
                                        <input
                                                type="text"
                                                name="extra_value[]"
                                                class="form-control"
                                                value="<?php echo $extraData->field_value; ?>"
                                                id="extra_value<?php echo $index ?>"
                                        />
                                        <input
                                                type="hidden"
                                                value="<?php echo htmlentities($extraData->value_id); ?>"
                                                name="value_id[]"
                                                id="value_id<?php echo $index ?>"
                                        />
                                    </td>
                                    <td>
                                        <?php if (file_exists(
                                                REDSHOP_FRONT_IMAGES_RELPATH . 'extrafield/' . $extraData->field_name
                                            ) && $extraData->field_name != '') : ?>
                                            <img
                                                    width="100"
                                                    height="100"
                                                    class="img-polaroid"
                                                    src="<?php echo REDSHOP_FRONT_IMAGES_ABSPATH . 'extrafield/' . $extraData->field_name; ?>"
                                            />
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (count($this->lists['extra_data']) > 1) : ?>
                                            <input value="Delete" onclick="deleteRow(this);" class="btn btn-danger"
                                                   type="button"/>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php $k = 1; ?>
                            <tr>
                                <td>
                                    <input
                                            type="text"
                                            class="divfieldText hide form-control"
                                            name="extra_name[]"
                                            id="extra_name1"
                                            value="field_temp_opt_1"
                                    />
                                    <input
                                            type="file"
                                            class="divfieldFile hide"
                                            name="extra_name_file[]"
                                    />
                                </td>
                                <td>
                                    <input
                                            type="text"
                                            name="extra_value[]"
                                            class="form-control"
                                    />
                                    <input
                                            type="hidden"
                                            name="value_id[]"
                                    />
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->form->getInput('id'); ?>
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" value="<?php echo $k; ?>" name="total_extra" id="total_extra">
    <input type="hidden" name="task" value=""/>
</form>
<style type="text/css">
    .select2-container-disabled {
        opacity: 0.5;
    }
</style>