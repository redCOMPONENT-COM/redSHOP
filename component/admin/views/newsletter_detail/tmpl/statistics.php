<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;


$newsid = JFactory::getApplication()->input->getInt('newsid', 0);
$model = $this->getModel('newsletter_detail');

$newsletter_list = $model->getNewsletterList();

$sel = array();
$sel[0] = new stdClass;
$sel[0]->newsletter_id = 0;
$sel[0]->text = JText::_('COM_REDSHOP_SELECT');
$newsletter_list = array_merge($sel, $newsletter_list);
$selnewsletter = JHTML::_('select.genericlist', $newsletter_list, 'newsid', 'class="inputbox" size="1" onchange="document.adminForm.submit();" ', 'newsletter_id', 'text', $newsid);

$returnstring = $model->getNewsletterTracker($newsid);

$querystring = $returnstring[0];
$title = $returnstring[1];

$data = "";
$rowdata = array();
for ($i = 0, $in = count($querystring); $i < $in; $i++)
{
	$rowdata[] = "['" . $querystring[$i]->xdata . "'," . $querystring[$i]->ydata . "]";
}
$rowdata = implode(",", $rowdata);
$data .= "[$rowdata]";

?>
<script language="javascript" type="text/javascript">
	//Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages': ['corechart']});

	//Set a callback to run when the Google Visualization API is loaded.
	google.setOnLoadCallback(drawChart);

	//Callback that creates and populates a data table,
	//instantiates the pie chart, passes in the data and
	//draws it.
	function drawChart() {
		//Create our data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', '<?php echo JText::_('COM_REDSHOP_NEWSLETTER');?>');
		data.addColumn('number', '<?php echo JText::_('COM_REDSHOP_NO_OF_READ_NEWSLETTER');?>');
		data.addRows(<?php echo $data;?>);

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.PieChart(document.getElementById('newsletter_statistics_pie'));
		chart.draw(data, {width: 800, height: 640, is3D: true, title: '<?php echo $title;?>'});
	}
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}
	}
</script>
<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_STATISTICS_FILTER'); ?></legend>
		<table class="admintable table">
			<tr>
				<td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_NEWSLETTER'); ?>:</td>
				<td><?php echo $selnewsletter;?></td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<legend><?php echo JText::_('COM_REDSHOP_NEWSLETTER_STATISTICS'); ?></legend>
		<div style="float:left;" id="newsletter_statistics_pie"></div>
		<div id="newsletter_statistics_column"></div>
	</fieldset>
	<input type="hidden" name="view" value="newsletter_detail"/>
	<input type="hidden" name="layout" value="statistics"/>
	<input type="hidden" name="option" value="com_redshop"/>
	<input type="hidden" name="task" value=""/>
</form>
