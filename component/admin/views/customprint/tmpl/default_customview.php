<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'printall') {
			//var w = window.open('','Popup_Window',"width=900,height=800,toolbar=1,scrollbars=1,resizable=1");
			//	form.target = 'Popup_Window';
			form.submit();
			return true;
		}
		return;
	}

	function printbutton(pressbutton) {
		var form = document.adminForm1;

		if (pressbutton == 'printall') {
			var w = window.open('', 'Popup_Window', "width=900,height=800,toolbar=1,scrollbars=1,resizable=1");
			form.target = 'Popup_Window';
			form.submit();

			return true;
		}
		return;
	}

</script>
<div id="editcell">
	<?php
	JPluginHelper::importPlugin('redshop_custom_views');
	$dispatcher  = JDispatcher::getInstance();
	$printoption = JRequest::getVar('printoption');
	$popup       = JRequest::getVar('popup');

	$tmplPath    = JPATH_SITE . '/plugins/redshop_custom_views/' . $printoption . '/tmpl';
	$this->addTemplatePath($tmplPath);

	echo $this->loadTemplate($printoption);
	?>
</div>
