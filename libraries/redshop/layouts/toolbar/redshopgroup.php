<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSEs
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Html\HtmlHelper;

HTMLHelper::_('bootstrap.dropdown', '.dropdown');

extract($displayData);

$items = $toolbar->getItems();
$title = $toolbar->getGroupTitle();

?>
<div id="toolbar-dropdown-save-group" class="btn-group dropdown-save-group" role="group">
    <button type="button" class="btn btn-success dropdown-toggle-split" data-bs-toggle="dropdown"
        data-bs-target=".dropdown-menu" data-bs-display="static" aria-haspopup="true" aria-expanded="true">
        <span class="visually-hidden">
            <?php echo Text::_('JGLOBAL_TOGGLE_DROPDOWN'); ?>
        </span>
        <span class="icon-chevron-down" aria-hidden="true"></span>
    </button>
    <button type="button" class="button-save btn btn-success">
        <span class=""></span>
        <?php echo Text::_($title); ?>
    </button>
    <div class="dropdown-menu data-bs-popper="static"">
        <?php foreach ($items as $item): ?>
            <span class="dropdown-item">
                <?php echo $toolbar->renderButton($item); ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>