<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<fieldset class="adminform">
	<div class="row">
		<div class="col-sm-4">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('settings');?>
			</fieldset>
		</div>

		<div class="col-sm-4">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('modules');?>
			</fieldset>
		</div>

		<div class="col-sm-4">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('general_layout_settings');?>
			</fieldset>
		</div>
	</div>
</fieldset>
