<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

extract($displayData);

?>
<div role="tabpanel" class="tab-pane <?php echo ($row->active) ? 'active' : '' ?>" id="<?php echo $row->param ?>">
	<?php echo $view->loadTemplate($row->param); ?>
</div>
