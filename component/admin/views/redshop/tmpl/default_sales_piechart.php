<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('restricted access');


$user = JFactory::getUser();
$option = JRequest::getVar('option');
$model = $this->getModel('redshop');

$data = "[]";
$rowdata = "";
//print_r($this->turnover);

for ($p = 0; $p < count($this->turnover); $p++)
{
	$rowdata[] = "['" . $this->turnover[$p]->viewdate . "'," . $this->turnover[$p]->turnover . "]";

}





$title = addslashes(JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES'));
//print_r($rowdata);
if (is_array($rowdata))
{
	$rowdata = implode(",", $rowdata);
	$data = "[$rowdata]";
}
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
		data.addColumn('string', '<?php echo JText::_('COM_REDSHOP_LASTMONTHSALES');?>');
		data.addColumn('number', '<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT');?>');
		data.addRows(<?php echo $data;?>);

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('lastmonthsales_statistics_pie'));
		chart.draw(data, {width: 500, height: 300, is3D: true, title: '<?php echo $title;?>'});
	}
</script>


<form action="<?php echo 'index.php?option=' . $option; ?>" method="post" name="chartform" id="chartForm">
	<div id="editcell">

		<table class="adminlist" width="100%">


			<tr>
				<td><?php echo JText::_('COM_REDSHOP_FILTER') . ": " . $this->lists['filteroption'];?></td>
			</tr>

		</table>
		<table class="adminlist" width="100%" height="295">


			<div style="float:left;" id="lastmonthsales_statistics_pie"></div>

		</table>
	</div>
	<input type="hidden" name="view" value="redshop"/>


</form>
