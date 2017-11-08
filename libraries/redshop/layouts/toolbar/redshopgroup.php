<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSEs
 */

defined('_JEXEC') or die;

extract($displayData);

$items = $toolbar->getItems();
$title = $toolbar->getGroupTitle();

?>

<div class="btn-group">
    <button type="button" class="btn btn-small dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="icon-save"></span>
		<?php echo JText::_($title); ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
		<?php foreach ($items as $item) : ?>
            <li>
				<?php echo $toolbar->renderButton($item); ?>
            </li>
		<?php endforeach; ?>
    </ul>
</div>
