<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$filterSection = (int) $this->state->get('filter.field_section');

echo RedshopLayoutHelper::render('view.list', array('data' => $this));

?>
<?php if ($filterSection): ?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $("#fieldsAssignGroupBtn").click(function (event) {
                    event.preventDefault();
                    var $form = $("form[name='adminForm']");
                    var $group = $("#fieldsAssignGroup input[name='field_assign_group']:checked").val();

                    $("<input>").attr("type", "hidden")
                        .attr("name", "field_assign_group")
                        .val($group)
                        .appendTo($form);

                    Joomla.submitbutton('fields.massAssignGroup');
                });
            });
        })(jQuery);
    </script>
    <div class="modal fade" id="fieldsAssignGroup" tabindex="-1" role="dialog" aria-labelledby="fieldsAssignGroupLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="fieldsAssignGroupLabel">
						<?php echo JText::_('COM_REDSHOP_FIELDS_MASS_ASSIGN_GROUP_MODAL_TITLE') ?>
                    </h4>
                </div>
                <div class="modal-body">
					<?php if (!empty($this->fieldGroups)): ?>
                        <ul class="list-group no-margin">
                            <li class="list-group-item">
                                <label>
                                    <input type="radio" name="field_assign_group" value="" checked="checked"/>
			                        <?php echo JText::_('COM_REDSHOP_FIELDS_MASS_ASSIGN_GROUP_CLEAR') ?>
                                </label>
                            </li>
							<?php foreach ($this->fieldGroups as $fieldGroup): ?>
                                <li class="list-group-item">
                                    <label>
                                        <input type="radio" name="field_assign_group"
                                               value="<?php echo $fieldGroup->id ?>"
                                        />
										<?php echo $fieldGroup->name ?>
                                    </label>
                                </li>
							<?php endforeach; ?>
                        </ul>
					<?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
						<?php echo JText::_('JTOOLBAR_CANCEL') ?>
                    </button>
                    <button type="button" class="btn btn-primary" id="fieldsAssignGroupBtn">
						<?php echo JText::_('COM_REDSHOP_FIELDS_MASS_ASSIGN_GROUP_ASSIGN_BTN') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif;
