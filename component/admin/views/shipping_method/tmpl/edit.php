<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');

echo RedshopLayoutHelper::render('view.edit.' . $this->formLayout, array('data' => $this));
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task) {

		var _form = document.adminForm;
		if (task == "shipping_rate") {
			_form.view.value = "shipping_rate";
		}

		Joomla.submitform(task);
	};
</script>
