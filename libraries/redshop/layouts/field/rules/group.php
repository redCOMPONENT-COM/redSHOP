<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * Layout variables
 * -----------------
 * @var   array        $displayData    List of available data.
 * @var   array        $groups         List of available user groups.
 * @var   array        $actions        List of available actions.
 * @var   JFormField   $field          Field object data.
 * @var   boolean      $newItem        Is that new item.
 * @var   JAccessRules $assetRules     Access Rules
 * @var   integer      $assetId        Asset ID
 * @var   string       $component      Component
 * @var   boolean      $isGlobalConfig Is global configuration
 */
extract($displayData);
?>
<?php foreach ($groups as $group): ?>
	<?php $active = ($group->value == 1) ? ' active' : ''; ?>
    <div class="tab-pane<?php echo $active ?>" id="permission-<?php echo $group->value ?>">
        <div class="container-fluid">
            <div class="row">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th width="1" style="text-align: center;">#</th>
                        <th class="actions" id="actions-th<?php echo $group->value ?>">
                            <span class="acl-action"><?php echo JText::_('JLIB_RULES_ACTION') ?> (<?php echo $group->text ?>)</span>
                        </th>
                        <th class="settings" id="settings-th<?php echo $group->value ?>-view" width="15%" style="text-align: center;">
                            <span class="acl-action"><?php echo JText::_('COM_REDSHOP_ACTION_VIEW') ?></span>
                        </th>
                        <th class="settings" id="settings-th<?php echo $group->value ?>-create" width="15%" style="text-align: center;">
                            <span class="acl-action"><?php echo JText::_('COM_REDSHOP_ACTION_CREATE') ?></span>
                        </th>
                        <th class="settings" id="settings-th<?php echo $group->value ?>-edit" width="15%" style="text-align: center;">
                            <span class="acl-action"><?php echo JText::_('COM_REDSHOP_ACTION_EDIT') ?></span>
                        </th>
                        <th class="settings" id="settings-th<?php echo $group->value ?>-delete" width="15%" style="text-align: center;">
                            <span class="acl-action"><?php echo JText::_('COM_REDSHOP_ACTION_DELETE') ?></span>
                        </th>
                    </tr>
                    </thead>
					<?php $isSuperUserGroup = JAccess::checkGroup($group->value, 'core.admin'); ?>
                    <tbody>
					<?php $index = 1; ?>
					<?php foreach ($actions as $actionGroupName => $groupActions): ?>
                        <tr>
                            <td style="text-align: center;">
								<?php echo $index ?>
                            </td>
                            <td>
                                <div class="pull-left"><?php echo JText::_('COM_REDSHOP_ACTION_MANAGE_' . strtoupper($actionGroupName)) ?></div>
								<?php if (!$isSuperUserGroup): ?>
                                    <select class="input-sm input-medium disableBootstrapChosen select-permission-all-row pull-right">
                                        <option value="" selected><?php echo JText::_('COM_REDSHOP_RULES_SET_ALL_FOR_ROW') ?></option>
                                        <option value="inherit"><?php echo JText::_('JLIB_RULES_INHERITED') ?></option>
                                        <option value="allow"><?php echo JText::_('JLIB_RULES_ALLOWED') ?></option>
                                        <option value="denied"><?php echo JText::_('JLIB_RULES_DENIED') ?></option>
                                    </select>
								<?php endif; ?>
                            </td>
							<?php foreach ($groupActions as $action): ?>
								<?php $actionMethod = explode('.', $action->name); ?>
								<?php $actionMethod = $actionMethod[count($actionMethod) - 1]; ?>
                                <td nowrap style="text-align: center;" class="cell-permission cell-permission-<?php echo $actionMethod ?>">
									<?php
									$inheritedRule = JAccess::checkGroup($group->value, $action->name, $assetId);
									$assetRule     = ($newItem === false) ? $assetRules->allow($action->name, $group->value) : null;
									?>
                                    <select class="input-sm input-medium disableBootstrapChosen select-permission"
                                            name="<?php echo $field->name . '[' . $action->name . '][' . $group->value . ']' ?>"
                                            id="<?php echo $field->id . '_' . $action->name . '_' . $group->value ?>"
                                            style="display: none;"
                                            title="<?php echo JText::sprintf('JLIB_RULES_SELECT_ALLOW_DENY_GROUP', JText::_($action->title), trim($group->text)) ?>">
										<?php
										$inheritedRule = JAccess::checkGroup($group->value, $action->name, $assetId);
										$assetRule     = ($newItem === false) ? $assetRules->allow($action->name, $group->value) : null;
										?>
                                        <option value="" <?php echo $assetRule === null ? ' selected="selected"' : '' ?>>
											<?php echo JText::_(empty($group->parent_id) && empty($component) ? 'JLIB_RULES_NOT_SET' : 'JLIB_RULES_INHERITED') ?>
                                        </option>
                                        <option value="1"<?php echo $assetRule === true ? ' selected="selected"' : '' ?>>
											<?php echo JText::_('JLIB_RULES_ALLOWED') ?>
                                        </option>
                                        <option value="0"<?php echo $assetRule === false ? ' selected="selected"' : '' ?>>
											<?php echo JText::_('JLIB_RULES_DENIED') ?>
                                        </option>
                                    </select>
									<?php
									if (($assetRule === true) && ($inheritedRule === false)): ?>
										<?php echo JText::_('JLIB_RULES_CONFLICT') ?>
									<?php endif; ?>
									<?php
									$result                        = array();
									$inheritedGroupRule            = JAccess::checkGroup((int) $group->value, $action->name, $assetId);
									$inheritedGroupParentAssetRule = !empty($parentAssetId) ? JAccess::checkGroup($group->value, $action->name, $parentAssetId) : null;
									$inheritedParentGroupRule      = !empty($group->parent_id) ? JAccess::checkGroup($group->parent_id, $action->name, $assetId) : null;

									if ($isSuperUserGroup)
									{
										$result['class'] = 'text-primary';
										$result['text']  = '<span class="icon-lock"></span>' . JText::_('JLIB_RULES_ALLOWED_ADMIN');
									}
									else
									{
										if ($inheritedGroupRule === null || $inheritedGroupRule === false)
										{
											$result['class'] = 'text-danger';
											$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED_INHERITED');
										}
										else
										{
											$result['class'] = 'text-success';
											$result['text']  = JText::_('JLIB_RULES_ALLOWED_INHERITED');
										}

										if ($assetRule === false)
										{
											$result['class'] = 'text-danger';
											$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED');
										}
                                        elseif ($assetRule === true)
										{
											$result['class'] = 'text-success';
											$result['text']  = JText::_('JLIB_RULES_ALLOWED');
										}

										if (empty($group->parent_id) && $isGlobalConfig === true && $assetRule === null)
										{
											$result['class'] = 'text-danger';
											$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED_DEFAULT');
										}
                                        elseif ($inheritedGroupParentAssetRule === false || $inheritedParentGroupRule === false)
										{
											$result['class'] = 'text-muted';
											$result['text']  = '<span class="icon-lock icon-white"></span>' . JText::_('JLIB_RULES_NOT_ALLOWED_LOCKED');
										}
									}
									?>
									<?php if ($result['class'] == 'text-primary'): ?>
                                        <label class="<?php echo $result['class'] ?>">
                                            <strong><?php echo $result['text'] ?></strong>
                                        </label>
									<?php else: ?>
                                        <label class="<?php echo $result['class'] ?> label-permission"><strong><?php echo $result['text'] ?></strong></label>
									<?php endif; ?>
                                </td>
							<?php endforeach; ?>
							<?php $count = abs(count($groupActions) - 4); ?>
							<?php if ($count): ?>
                                <td colspan="<?php echo $count ?>">&nbsp;</td>
							<?php endif; ?>
                        </tr>
						<?php $index++; ?>
					<?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2">
                            &nbsp;
                        </td>
                        <td style="text-align: center;">
							<?php if (!$isSuperUserGroup): ?>
                                <select class="input-sm input-medium disableBootstrapChosen select-permission-all-view">
                                    <option value="" selected><?php echo JText::_('COM_REDSHOP_RULES_SET_ALL_FOR_COLUMN') ?></option>
                                    <option value="inherit"><?php echo JText::_('JLIB_RULES_INHERITED') ?></option>
                                    <option value="allow"><?php echo JText::_('JLIB_RULES_ALLOWED') ?></option>
                                    <option value="denied"><?php echo JText::_('JLIB_RULES_DENIED') ?></option>
                                </select>
							<?php endif; ?>
                        </td>
                        <td style="text-align: center;">
							<?php if (!$isSuperUserGroup): ?>
                                <select class="input-sm input-medium disableBootstrapChosen select-permission-all-create">
                                    <option value="" selected><?php echo JText::_('COM_REDSHOP_RULES_SET_ALL_FOR_COLUMN') ?></option>
                                    <option value="inherit"><?php echo JText::_('JLIB_RULES_INHERITED') ?></option>
                                    <option value="allow"><?php echo JText::_('JLIB_RULES_ALLOWED') ?></option>
                                    <option value="denied"><?php echo JText::_('JLIB_RULES_DENIED') ?></option>
                                </select>
							<?php endif; ?>
                        </td>
                        <td style="text-align: center;">
							<?php if (!$isSuperUserGroup): ?>
                                <select class="input-sm input-medium disableBootstrapChosen select-permission-all-edit">
                                    <option value="" selected><?php echo JText::_('COM_REDSHOP_RULES_SET_ALL_FOR_COLUMN') ?></option>
                                    <option value="inherit"><?php echo JText::_('JLIB_RULES_INHERITED') ?></option>
                                    <option value="allow"><?php echo JText::_('JLIB_RULES_ALLOWED') ?></option>
                                    <option value="denied"><?php echo JText::_('JLIB_RULES_DENIED') ?></option>
                                </select>
							<?php endif; ?>
                        </td>
                        <td style="text-align: center;">
							<?php if (!$isSuperUserGroup): ?>
                                <select class="input-sm input-medium disableBootstrapChosen select-permission-all-delete">
                                    <
                                    <option value="" selected><?php echo JText::_('COM_REDSHOP_RULES_SET_ALL_FOR_COLUMN') ?></option>
                                    <option value="inherit"><?php echo JText::_('JLIB_RULES_INHERITED') ?></option>
                                    <option value="allow"><?php echo JText::_('JLIB_RULES_ALLOWED') ?></option>
                                    <option value="denied"><?php echo JText::_('JLIB_RULES_DENIED') ?></option>
                                </select>
							<?php endif; ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $("label.label-permission").click(function (event) {
                var $select = $(this).parent().find("select.select-permission").first();
                $(this).animate({width: 'toggle'}, 'fast', 'swing', function () {
                    $select.animate({width: 'toggle'}, 'fast', 'swing');
                });
            });

            // Set permission for row
            $("select.select-permission-all-row").on("change", function () {
                var $row = $(this).parent().parent();
                var $labels = $row.find("td.cell-permission label.label-permission:visible");
                var $selects = $row.find("td.cell-permission select.select-permission");

                switch ($(this).val()) {
                    case "inherit":
                        $labels.click();
                        $selects.val("");
                        break;
                    case "allow":
                        $labels.click();
                        $selects.val("1");
                        break;
                    case "denied":
                        $labels.click();
                        $selects.val("0");
                        break;
                    default:
                        break;
                }
            });

            // All views select
            $(".select-permission-all-view").on("change", function () {
                var $table = $(this).parent().parent().parent().parent();
                var $labels = $table.find("td.cell-permission-view label.label-permission:visible");
                var $selects = $table.find("td.cell-permission-view select.select-permission");

                switch ($(this).val()) {
                    case "inherit":
                        $labels.click();
                        $selects.val("");
                        break;
                    case "allow":
                        $labels.click();
                        $selects.val("1");
                        break;
                    case "denied":
                        $labels.click();
                        $selects.val("0");
                        break;
                    default:
                        break;
                }
            });

            // All create select
            $(".select-permission-all-create").on("change", function () {
                var $table = $(this).parent().parent().parent().parent();
                var $labels = $table.find("td.cell-permission-create label.label-permission:visible");
                var $selects = $table.find("td.cell-permission-create select.select-permission");

                switch ($(this).val()) {
                    case "inherit":
                        $labels.click();
                        $selects.val("");
                        break;
                    case "allow":
                        $labels.click();
                        $selects.val("1");
                        break;
                    case "denied":
                        $labels.click();
                        $selects.val("0");
                        break;
                    default:
                        break;
                }
            });

            // All edit select
            $(".select-permission-all-edit").on("change", function () {
                var $table = $(this).parent().parent().parent().parent();
                var $labels = $table.find("td.cell-permission-edit label.label-permission:visible");
                var $selects = $table.find("td.cell-permission-edit select.select-permission");

                switch ($(this).val()) {
                    case "inherit":
                        $labels.click();
                        $selects.val("");
                        break;
                    case "allow":
                        $labels.click();
                        $selects.val("1");
                        break;
                    case "denied":
                        $labels.click();
                        $selects.val("0");
                        break;
                    default:
                        break;
                }
            });

            // All delete select
            $(".select-permission-all-delete").on("change", function () {
                var $table = $(this).parent().parent().parent().parent();
                var $labels = $table.find("td.cell-permission-delete label.label-permission:visible");
                var $selects = $table.find("td.cell-permission-delete select.select-permission");

                switch ($(this).val()) {
                    case "inherit":
                        $labels.click();
                        $selects.val("");
                        break;
                    case "allow":
                        $labels.click();
                        $selects.val("1");
                        break;
                    case "denied":
                        $labels.click();
                        $selects.val("0");
                        break;
                    default:
                        break;
                }
            });
        });
    })(jQuery);
</script>
