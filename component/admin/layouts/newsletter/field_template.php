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
<div class="form-group row-fluid">
    <div class="col-md-offset-2 col-md-10">
        <?php
        echo HTMLHelper::_(
            'redshop.tooltip',
            Text::_('COM_REDSHOP_TOOLTIP_TEMPLATE'),
            Text::_('COM_REDSHOP_TEMPLATE')
        )
            ?>
        <a href="index.php?option=com_redshop&task=template.edit&id=<?php echo $item->template_id ?>" target="_blank">
            <?php echo Text::_('COM_REDSHOP_EDIT_TEMPLATE') ?>
        </a>
    </div>
</div>