<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'restricted access' );


$user = JFactory::getUser();
$option = JRequest::getVar('option');
$model = $this->getModel('redshop');

$data = "";
$rowdata = "";
//print_r($this->turnover);

for($p=0;$p<count($this->turnover);$p++)
{
  $rowdata[] = "['".$this->turnover[$p]->viewdate."',".$this->turnover[$p]->turnover."]";

}





$title=JText::_('PIE_CHART_FOR_LASTMONTH_SALES');
//print_r($rowdata);
if(is_array($rowdata))
{
	$rowdata = implode(",",$rowdata);
	$data .= "[$rowdata]";

}
?>

<script language="javascript" type="text/javascript">
//Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});

//Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

//Callback that creates and populates a data table,
//instantiates the pie chart, passes in the data and
//draws it.
function drawChart()
{
	//Create our data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', '<?php echo JText::_('LASTMONTHSALES');?>');
	data.addColumn('number', '<?php echo JText::_('SALES_AMOUNT');?>');
	data.addRows(<?php echo $data;?>);

	//Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.ColumnChart(document.getElementById('lastmonthsales_statistics_pie'));
	chart.draw(data, {width: 500, height: 300, is3D: true, title: '<?php echo $title;?>'});
}
</script>


<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="chartform" >
<div id="editcell">

    <table class="adminlist" width="100%">


       <tr><td><?php echo JText::_('FILTER').": ".$this->lists['filteroption'];?></td></tr>

	</table>
	<table class="adminlist" width="100%" height="295">


         <div style="float:left;" id="lastmonthsales_statistics_pie"></div>

	</table>
</div>
<input type="hidden" name="view" value="redshop" />



</form>
