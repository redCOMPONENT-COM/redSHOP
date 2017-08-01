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
<div class="callout callout-warning" style="background-color: #fff !important;">
	<h4 class="text-warning"><?php echo JText::_('COM_REDSHOP_ACCESS_HELP_INFORMATION') ?></h4>
	<p class="text-muted"><?php echo JText::_('COM_REDSHOP_ACCESS_HELP_INFORMATION_TEXT') ?></p>
</div>
<form action="index.php?option=com_redshop&view=access" method="post" id="adminForm" name="adminForm" class="form-validate form-horizontal"
	  enctype="multipart/form-data">
	<?php echo $this->form->getInput('rules') ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('title') ?>
	<?php echo $this->form->getInput('id') ?>
	<?php echo JHtml::_('form.token') ?>
</form>
