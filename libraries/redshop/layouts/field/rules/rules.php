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
		<table class="table table-striped">
			<thead>
				<tr>
					<th class="actions" id="actions-th<?php echo $group->value ?>">
						<span class="acl-action"><?php echo JText::_('JLIB_RULES_ACTION') ?></span>
					</th>
					<th class="settings" id="settings-th<?php echo $group->value ?>">
						<span class="acl-action"><?php echo JText::_('JLIB_RULES_SELECT_SETTING') ?></span>
					</th>
					<?php // The calculated setting is not shown for the root group of global configuration. ?>
					<th id="aclactionth<?php echo $group->value ?>">
						<span class="acl-action"><?php echo JText::_('JLIB_RULES_CALCULATED_SETTING') ?></span>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Check if this group has super user permissions
				$isSuperUserGroup = JAccess::checkGroup($group->value, 'core.admin');
				?>
				<?php foreach ($actions as $action): ?>
					<tr>
						<td headers="actions-th<?php echo $group->value ?>">
							<label
								for="<?php echo $field->id . '_' . $action->name . '_' . $group->value ?>" class="hasTooltip"
								title="<?php echo htmlspecialchars(JText::_($action->title) . ' ' . JText::_($action->description), ENT_COMPAT, 'UTF-8') ?>">
								<?php echo JText::_($action->title) ?>
							</label>
						</td>
						<td headers="settings-th<?php echo $group->value ?>">
							<select
								class="input-small input-sm"
								name="<?php echo $field->name . '[' . $action->name . '][' . $group->value . ']' ?>"
								id="<?php echo $field->id . '_' . $action->name . '_' . $group->value ?>"
								title="<?php echo JText::sprintf('JLIB_RULES_SELECT_ALLOW_DENY_GROUP', JText::_($action->title), trim($group->text)) ?>">

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
								<option value="" <?php echo ($assetRule === null ? ' selected="selected"' : '') ?>>
								<?php echo JText::_(empty($group->parent_id) && empty($component) ? 'JLIB_RULES_NOT_SET' : 'JLIB_RULES_INHERITED') ?>
								</option>
								<option value="1"<?php echo ($assetRule === true ? ' selected="selected"' : '') ?>>
									<?php echo JText::_('JLIB_RULES_ALLOWED') ?>
								</option>
								<option value="0"<?php echo ($assetRule === false ? ' selected="selected"' : '') ?>>
									<?php echo JText::_('JLIB_RULES_DENIED') ?>
								</option>
							</select>
							<?php
							// If this asset's rule is allowed, but the inherited rule is deny, we have a conflict.
							if (($assetRule === true) && ($inheritedRule === false)): ?>
								<?php echo JText::_('JLIB_RULES_CONFLICT') ?>
							<?php endif; ?>
						</td>
						<?php // Build the Calculated Settings column. ?>
						<td headers="aclactionth<?php echo $group->value ?>">
							<?php
							$result = array();

							// Get the group, group parent id, and group global config recursive calculated permission for the chosen action.
							$inheritedGroupRule            = JAccess::checkGroup((int) $group->value, $action->name, $assetId);
							$inheritedGroupParentAssetRule = !empty($parentAssetId) ? JAccess::checkGroup($group->value, $action->name, $parentAssetId) : null;
							$inheritedParentGroupRule      = !empty($group->parent_id) ? JAccess::checkGroup($group->parent_id, $action->name, $assetId) : null;

							// Current group is a Super User group, so calculated setting is "Allowed (Super User)".
							if ($isSuperUserGroup)
							{
								$result['class'] = 'label label-success';
								$result['text'] = '<span class="icon-lock icon-white"></span>' . JText::_('JLIB_RULES_ALLOWED_ADMIN');
							}
							// Not super user.
							else
							{
								// First get the real recursive calculated setting and add (Inherited) to it.

								// If recursive calculated setting is "Denied" or null. Calculated permission is "Not Allowed (Inherited)".
								if ($inheritedGroupRule === null || $inheritedGroupRule === false)
								{
									$result['class'] = 'label label-important';
									$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED_INHERITED');
								}
								// If recursive calculated setting is "Allowed". Calculated permission is "Allowed (Inherited)".
								else
								{
									$result['class'] = 'label label-success';
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
									$result['class'] = 'label label-important';
									$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED');
								}
								// If there is an explicit permission is "Allowed". Calculated permission is "Allowed".
								elseif ($assetRule === true)
								{
									$result['class'] = 'label label-success';
									$result['text']  = JText::_('JLIB_RULES_ALLOWED');
								}

								// Third part: Overwrite the calculated permissions labels for special cases.

								// Global configuration with "Not Set" permission. Calculated permission is "Not Allowed (Default)".
								if (empty($group->parent_id) && $isGlobalConfig === true && $assetRule === null)
								{
									$result['class'] = 'label label-important';
									$result['text']  = JText::_('JLIB_RULES_NOT_ALLOWED_DEFAULT');
								}

								/**
								* Component/Item with explicit "Denied" permission at parent Asset (Category, Component or Global config) configuration.
								* Or some parent group has an explicit "Denied".
								* Calculated permission is "Not Allowed (Locked)".
								*/
								elseif ($inheritedGroupParentAssetRule === false || $inheritedParentGroupRule === false)
								{
									$result['class'] = 'label label-important';
									$result['text']  = '<span class="icon-lock icon-white"></span>' . JText::_('JLIB_RULES_NOT_ALLOWED_LOCKED');
								}
							}
							?>
							<span class="<?php echo $result['class'] ?>"><?php echo $result['text'] ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endforeach; ?>
