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
 * @var   array          $displayData  List of available data.
 * @var   array          $groups       List of available user groups.
 * @var   array          $actions      List of available actions.
 * @var   JFormField     $field        Field object data.
 * @var   boolean        $newItem      Is that new item.
 * @var   JAccessRules   $assetRules   Access Rules
 * @var   integer        $assetId      Asset ID
 * @var   string         $component    Component
 */

extract($displayData);
?>

<?php foreach ($groups as $group): ?>
	<?php
	// Initial Active Pane
	$active = ($group->value == 1) ? ' active' : '';
	?>
	<div class="tab-pane<?php echo $active ?>" id="permission-<?php echo $group->value ?>">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4">
					<div class="actions" id="actions-th<?php echo $group->value ?>">
						<h4 class="acl-action"><?php echo JText::_('JLIB_RULES_ACTION') ?></h4>
					</div>
				</div>
				<div class="col-md-4">
					<div class="settings" id="settings-th<?php echo $group->value ?>">
						<h4 class="acl-action"><?php echo JText::_('JLIB_RULES_SELECT_SETTING') ?></h4>
					</div>
				</div>
				<div class="col-md-4">
					<div id="aclactionth<?php echo $group->value ?>">
						<h4 class="acl-action"><?php echo JText::_('JLIB_RULES_CALCULATED_SETTING') ?></h4>
					</div>
				</div>
			</div>
			<?php
			// Check if this group has super user permissions
			$isSuperUserGroup = JAccess::checkGroup($group->value, 'core.admin');
			?>
			<div class="panel-group" role="tablist">
				<?php foreach ($actions as $actionGroupName => $groupActions): ?>
				<div class="panel panel-default">
					<div class="panel-heading" role="tab">
						<strong role="button" data-toggle="collapse" href="#group-<?php echo $actionGroupName ?>-<?php echo $group->value ?>"
								aria-expanded="true" aria-controls="group-<?php echo $actionGroupName ?>">
							<?php echo JText::_('COM_REDSHOP_ACTION_MANAGE_' . strtoupper($actionGroupName)) ?>
						</strong>
					</div>
					<div id="group-<?php echo $actionGroupName ?>-<?php echo $group->value ?>" class="panel-collapse collapse in" role="tabpanel">
						<div class="panel-body">
							<?php foreach ($groupActions as $action): ?>
								<?php
								$inheritedRule = JAccess::checkGroup($group->value, $action->name, $assetId);
								/**
								 * Possible values:
								 * null = not set means inherited
								 * false = denied
								 * true = allowed
								 */

								// Get the actual setting for the action for this group.
								$assetRule = ($newItem === false) ? $assetRules->allow($action->name, $group->value) : null;

								// Build the dropdowns for the permissions sliders
								// The parent group has "Not Set", all children can rightly "Inherit" from that.
								?>
								<div class="row">
									<div class="col-md-1">
									</div>
									<div class="col-md-3">
										<label for="<?php echo $field->id . '_' . $action->name . '_' . $group->value ?>" class="hasTooltip"
											title="<?php echo htmlspecialchars(JText::_($action->title) . ' ' . JText::_($action->description), ENT_COMPAT, 'UTF-8') ?>">
                                            <span class="level">–</span>&nbsp;<?php echo JText::_($action->title) ?>
										</label>
									</div>
									<div class="col-md-4">
										<label class="radio-inline" style="padding-top: 0px;">
											<input type="radio" name="<?php echo $field->name . '[' . $action->name . '][' . $group->value . ']' ?>"
												   id="option1" autocomplete="off" value="" <?php echo $assetRule === null ? 'checked' : '' ?>>
											<?php echo JText::_(empty($group->parent_id) && empty($component) ? 'JLIB_RULES_NOT_SET' : 'JLIB_RULES_INHERITED') ?>
										</label>
										<label class="radio-inline" style="padding-top: 0px;">
											<input type="radio" name="<?php echo $field->name . '[' . $action->name . '][' . $group->value . ']' ?>"
												   id="option2" autocomplete="off" value="1" <?php echo $assetRule === true ? 'checked' : '' ?>>
											<?php echo JText::_('JLIB_RULES_ALLOWED') ?>
										</label>
										<label class="radio-inline" style="padding-top: 0px;">
											<input type="radio" name="<?php echo $field->name . '[' . $action->name . '][' . $group->value . ']' ?>"
												   id="option3" autocomplete="off" value="0" <?php echo $assetRule === false ? 'checked' : '' ?>>
											<?php echo JText::_('JLIB_RULES_DENIED') ?>
										</label>
										<?php
										// If this asset's rule is allowed, but the inherited rule is deny, we have a conflict.
										if ($assetRule === true && $inheritedRule === false): ?>
											<?php echo JText::_('JLIB_RULES_CONFLICT') ?>
										<?php endif; ?>
									</div>
									<div class="col-md-4">
										<?php
										$result = array();

										// Get the group, group parent id, and group global config recursive calculated permission for the chosen action.
										$inheritedGroupRule            = JAccess::checkGroup((int) $group->value, $action->name, $assetId);
										$inheritedGroupParentAssetRule = !empty($parentAssetId) ? JAccess::checkGroup($group->value, $action->name, $parentAssetId) : null;
										$inheritedParentGroupRule      = !empty($group->parent_id) ? JAccess::checkGroup($group->parent_id, $action->name, $assetId) : null;

										// Current group is a Super User group, so calculated setting is "Allowed (Super User)".
										if ($isSuperUserGroup)
										{
											$result['class'] = 'badge badge-success';
											$result['text'] = '<span class="icon-lock icon-white"></span>' . JText::_('JLIB_RULES_ALLOWED_ADMIN');
										}
										// Not super user.
										else
										{
											// First get the real recursive calculated setting and add (Inherited) to it.

											// If recursive calculated setting is "Denied" or null. Calculated permission is "Not Allowed (Inherited)".
											if ($inheritedGroupRule === null || $inheritedGroupRule === false)
											{
												$result['class'] = 'badge badge-important';
												$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED_INHERITED');
											}
											// If recursive calculated setting is "Allowed". Calculated permission is "Allowed (Inherited)".
											else
											{
												$result['class'] = 'badge badge-success';
												$result['text']  = JText::_('JLIB_RULES_ALLOWED_INHERITED');
											}

											// Second part: Overwrite the calculated permissions labels if there is an explicit permission in the current group.

											/**
											 * @to do: incorrect info
											 * If a component as a permission that doesn't exists in global config (ex: frontend editing in com_modules) by default
											 * we get "Not Allowed (Inherited)" when we should get "Not Allowed (Default)".
											 */

											// If there is an explicit permission "Not Allowed". Calculated permission is "Not Allowed".
											if ($assetRule === false)
											{
												$result['class'] = 'badge badge-important';
												$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED');
											}
											// If there is an explicit permission is "Allowed". Calculated permission is "Allowed".
											elseif ($assetRule === true)
											{
												$result['class'] = 'badge badge-success';
												$result['text']  = JText::_('JLIB_RULES_ALLOWED');
											}

											// Third part: Overwrite the calculated permissions labels for special cases.

											// Global configuration with "Not Set" permission. Calculated permission is "Not Allowed (Default)".
											if (empty($group->parent_id) && $isGlobalConfig === true && $assetRule === null)
											{
												$result['class'] = 'badge badge-important';
												$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED_DEFAULT');
											}

											/**
											 * Component/Item with explicit "Denied" permission at parent Asset (Category, Component or Global config) configuration.
											 * Or some parent group has an explicit "Denied".
											 * Calculated permission is "Not Allowed (Locked)".
											 */
											elseif ($inheritedGroupParentAssetRule === false || $inheritedParentGroupRule === false)
											{
												$result['class'] = 'badge badge-important';
												$result['text']  = '<span class="icon-lock icon-white"></span>' . JText::_('JLIB_RULES_NOT_ALLOWED_LOCKED');
											}
										}
										?>
										<label class="<?php echo $result['class'] ?>"><?php echo $result['text'] ?></label>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
<?php endforeach; ?>
