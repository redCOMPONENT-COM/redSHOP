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
		<div class="col-sm-6">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('analytics');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('gls');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('clicktell');?>
			</fieldset>

		</div>
		<div class="col-sm-6">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('postdk');?>
			</fieldset>
		</div>
	</div>
</fieldset>

<fieldset class="adminform">
	<div class="row">
		<div class="col-sm-12">
			<?php echo $this->loadTemplate('economic');?>
		</div>
	</div>
</fieldset>