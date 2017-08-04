<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addScript('//www.gstatic.com/charts/loader.js');

$producthelper = productHelper::getInstance();

$turnover = RedshopModel::getInstance('Statistic', 'RedshopModel')->getTotalTurnOverCpanel();

$sales = RedshopModel::getInstance('Statistic', 'RedshopModel')->getTotalSalesCpanel();

?>

<script language="javascript" type="text/javascript">
	//Load the Visualization API and the piechart package.
	google.charts.load("current", {packages:['corechart']});

	//Set a callback to run when the Google Visualization API is loaded.
	google.charts.setOnLoadCallback(drawChart);

	//Callback that creates and populates a data table,
	//instantiates the pie chart, passes in the data and
	//draws it.
	function drawChart() {
		//Create our data table.
		var data = new google.visualization.DataTable();
		data.addColumn('string', '<?php echo JText::_('COM_REDSHOP_STATISTIC_DURATION');?>');
		data.addColumn('number', '<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT');?>');
		data.addColumn({type: 'string', role: 'annotation'});
		data.addColumn({type: 'string', role: 'tooltip'});

		<?php if (count($turnover) > 0) {?>
			<?php $turnover = array_reverse($turnover); ?>
			<?php foreach ($turnover as $row) { ?>
				data.addRow(['<?php echo $row[0] ?>', <?php echo $row[1] ?>, '<?php echo $producthelper->getProductFormattedPrice($row[1]); ?>', '<?php echo $producthelper->getProductFormattedPrice($row[1]) . ' in ' . $row[0]; ?>']);
			<?php } ?>
		<?php } ?>

		var options = {
			height: 400,
			colors: ['#1ab395'],
			legend: { position: "none" },
			chartArea: {'width': '90%', 'height': '90%'},
			curveType: 'function'
		};

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.LineChart(document.getElementById('lastmonthsales_statistics_pie'));
		chart.draw(data, options);
	}
</script>

<div class="row">
	<div class="col-sm-8">
		<form action="index.php?option=com_redshop" method="post" name="chartform" id="chartForm">
			<div id="editcell">
				<?php
					echo JText::_('COM_REDSHOP_FILTER') . ": ";
					$options = array();
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
						JFactory::getApplication()->input->getInt('filteroption', 1)
					);

				?>
				<div style="float:left;" id="lastmonthsales_statistics_pie"></div>
			</div>
			<input type="hidden" name="view" value="redshop"/>
		</form>
	</div>

	<div class="col-sm-4">
		<table class="adminlist table table-striped" width="100%">
			<thead>
				<tr>
					<th></th>
					<th><?php echo JText::_('COM_REDSHOP_STATISTIC_TOTAL_SALES') ?></th>
					<th><?php echo JText::_('COM_REDSHOP_STATISTIC_ORDER_COUNT') ?></th>
				</tr>
			</thead>
			<?php foreach($sales as $sale) : ?>
				<tr>
					<td><?php echo $sale[2]; ?></td>
					<td><?php echo $producthelper->getProductFormattedPrice($sale[0]); ?></td>
					<td><?php echo $sale[1]; ?></td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
</div>
