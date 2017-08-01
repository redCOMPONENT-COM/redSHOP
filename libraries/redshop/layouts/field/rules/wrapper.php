<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
 * @var   boolean      $showGroup      Show groups permissions
 * @var   string       $section        Section
 */
extract($displayData);

$layout = ($showGroup === true) ? 'field.rules.group' : 'field.rules.rules';
?>

<p class="rule-desc"><?php echo JText::_('JLIB_RULES_SETTINGS_DESC') ?></p>
<div id="permissions-sliders">
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-stacked nav-pills">
				<?php foreach ($groups as $group): ?>
					<?php $active = ($group->value == 1) ? 'active' : ''; ?>
                    <li class="<?php echo $active ?>">
                        <a href="#permission-<?php echo $group->value ?>" data-toggle="tab">
                            <?php if ($group->level == 1): ?>
                                <span class="level">&vdash;</span>
                            <?php elseif ($group->level > 1): ?>
                                <span class="level">&vdash;</span><?php echo str_repeat('&ndash;&ndash;', $group->level - 1) ?>
                            <?php endif; ?>
							<?php echo $group->text ?>
                        </a>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
				<?php
				echo RedshopLayoutHelper::render(
					$layout,
					array(
						'groups'         => $groups,
						'actions'        => $actions,
						'field'          => $field,
						'newItem'        => $newItem,
						'assetRules'     => $assetRules,
						'assetId'        => $assetId,
						'isGlobalConfig' => $isGlobalConfig,
						'component'      => $component
					)
				);
				?>
            </div>
        </div>
    </div>
    <div class="row">
        <div clas="col-md-12">
            <div class="alert">
				<?php
				if ($section === 'component' || $section === null):
					echo JText::_('JLIB_RULES_SETTING_NOTES');
				else:
					echo JText::_('JLIB_RULES_SETTING_NOTES_ITEM');
				endif;
				?>
            </div>
        </div>
    </div>
</div>
