<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	<input type="hidden" name="selectedTabPosition" value="" />
	<?php echo $this->form->getInput('title') ?>
	<?php echo $this->form->getInput('id') ?>
	<?php echo JHtml::_('form.token') ?>
</form>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		// Find the position of selected tab
		var allTabsNames = document.querySelectorAll('#permissions-sliders a');
		var selectedTabName  = document.querySelectorAll('#permissions-sliders li.active a');
		for (var i=0; i < allTabsNames.length; i++) {
			if (selectedTabName[0].innerHTML === allTabsNames[i].innerHTML) {
				var selectedTabPosition =allTabsNames[i].getAttribute("aria-controls");
				break;
			}
		}

		var form = document.adminForm;

		if (pressbutton) {
			form.task.value = pressbutton;
		}

		if (pressbutton == 'access.save') {
			form.selectedTabPosition.value = selectedTabPosition;
		}

		try {
			form.onsubmit();
		}
		catch (e) {
		}

		form.submit();
	}
</script>