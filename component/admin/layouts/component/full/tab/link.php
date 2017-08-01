<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


?>
<li role="presentation" class="<?php echo ($displayData->active) ? 'active' : '' ?>">
	<a href="<?php echo $displayData->link ?>" aria-controls="<?php echo $displayData->param ?>" role="tab" data-toggle="tab">
		<?php echo JText::_($displayData->title, true) ?>
	</a>
</li>
