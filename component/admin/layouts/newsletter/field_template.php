<?php

/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('bootstrap.tooltip', '.hasTooltip');

/**
 * Layout variables
 * ======================================
 *
 * @var  string $htmlField
 * @var  object $item
 * @var  array  $displayData
 */

extract($displayData);

echo $htmlField;

?>
<div class="control-group">
    <div class="control-label">
        <label id="jform_template_id-lbl" for="jform_template_id" class="hasPopover" data-bs-content="Template"
            data-bs-original-title="Template">
            <?php echo Text::_('COM_REDSHOP_EDIT_TEMPLATE') ?>
            <?php
            echo HTMLHelper::_(
                'redshop.tooltip',
                Text::_('COM_REDSHOP_TOOLTIP_TEMPLATE'),
                Text::_('COM_REDSHOP_EDIT_TEMPLATE')
            )
                ?>
        </label>
    </div>
    <div class="controls">
        <a href="index.php?option=com_redshop&task=template.edit&id=<?php echo $item->template_id ?>" target="_blank"
            class="btn btn-secondary btn-sm">
            <?php echo Text::_('COM_REDSHOP_EDIT') ?>
        </a>
    </div>
</div>