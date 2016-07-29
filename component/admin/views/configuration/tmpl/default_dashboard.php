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
		<div class="col-md-6">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('menuhide');?>
			</fieldset>
		</div>
		<div class="col-md-6">
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('new_customers');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('new_orders');?>
			</fieldset>
			<fieldset class="adminform">
				<?php echo $this->loadTemplate('statistic');?>
			</fieldset>
		</div>
	</div>
</fieldset>

