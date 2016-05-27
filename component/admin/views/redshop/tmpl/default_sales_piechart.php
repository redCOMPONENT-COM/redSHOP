<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addScript('//www.google.com/jsapi');

$turnover = RedshopModel::getInstance('Statistic', 'RedshopModel')->getTotalTurnoverCpanel();

$data = json_encode($turnover, JSON_NUMERIC_CHECK);
$title = addslashes(JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES'));
?>

<script language="javascript" type="text/javascript">
	//Load the Visualization API and the piechart package.
	google.load('visualization', '1.1', {'packages': ['corechart']});

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

		var options = {
			width: 600,
			height: 300,
			is3D: true,
			title: '<?php echo $title;?>',
			hAxis: {title: '<?php echo JText::_('COM_REDSHOP_LASTMONTHSALES');?>'},
			vAxis: {title: '<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT');?>'}
		};

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('lastmonthsales_statistics_pie'));
		chart.draw(data, options);
	}
</script>
<form action="index.php?option=com_redshop" method="post" name="chartform" id="chartForm">
	<div id="editcell">
		<table class="adminlist" width="100%">
			<tr>
				<td>
					<?php
						echo JText::_('COM_REDSHOP_FILTER') . ": ";
						$options = array();
						$options[] = JHTML::_('select.option', '0"selected"', JText::_('COM_REDSHOP_Select'));
						$options[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_DAILY'));
						$options[] = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_WEEKLY'));
						$options[] = JHTML::_('select.option', '3', JText::_('COM_REDSHOP_MONTHLY'));
						$options[] = JHTML::_('select.option', '4', JText::_('COM_REDSHOP_YEARLY'));

						echo JHTML::_(
							'select.genericlist',
							$options,
							'filteroption',
							'class="inputbox" size="1" onchange="document.chartform.submit();"',
							'value',
							'text',
							JFactory::getApplication()->input->getInt('filteroption', 4)
						);

					?>
				</td>
			</tr>
		</table>
		<table class="adminlist" width="100%" height="295">
			<div style="float:left;" id="lastmonthsales_statistics_pie"></div>
		</table>
	</div>
	<input type="hidden" name="view" value="redshop"/>
</form>
