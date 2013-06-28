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
	$dispatcher = JDispatcher::getInstance();


	if (JRequest::getVar('printoption') != "" && JRequest::getVar('task') == "")
	{
		if (JRequest::getVar('printoption') == "rs_custom_views_date")
		{
			$results = $dispatcher->trigger('onSelectedDate');
		}

		if (JRequest::getVar('printoption') == "rs_custom_views_person")
		{
			$results = $dispatcher->trigger('onSelectedPerson');
		}

		if (JRequest::getVar('printoption') == "rs_custom_views_company")
		{
			$results = $dispatcher->trigger('onSelectedCompany');
		}
	}

	if (JRequest::getVar('task') != "")
	{
		if (JRequest::getVar('popup') == 0)
		{
			?>
			<script language="javascript">window.print();</script>
		<?php
		}

		if (JRequest::getVar('printoption') == "rs_custom_views_date")
		{
			if (JRequest::getVar('popup') != 0)
			{
				$results_date = $dispatcher->trigger('onSelectedDate');
			}

			$results = $dispatcher->trigger('onSelectedDateValue');

		}

		if (JRequest::getVar('printoption') == "rs_custom_views_person")
		{
			if (JRequest::getVar('popup') != 0)
			{
				$results_date = $dispatcher->trigger('onSelectedPerson');
			}

			$results = $dispatcher->trigger('onSelectedPersonValue');
		}

		if (JRequest::getVar('printoption') == "rs_custom_views_company")
		{
			if (JRequest::getVar('popup') != 0)
			{
				$results_date = $dispatcher->trigger('onSelectedCompany');
			}

			$results = $dispatcher->trigger('onSelectedCompanyValue');
		}
	}
	?>
</div>
