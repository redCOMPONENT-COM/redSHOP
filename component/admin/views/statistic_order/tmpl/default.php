<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JFactory::getDocument()->addScript('//www.gstatic.com/charts/loader.js');
$producthelper = productHelper::getInstance();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<script type="text/javascript">
	//Load the Visualization API and the piechart package.
	google.charts.load("current", {packages:['corechart']});

	//Set a callback to run when the Google Visualization API is loaded.
	google.charts.setOnLoadCallback(drawChart);

	//Callback that creates and populates a data table,
	//instantiates the pie chart, passes in the data and
	//draws it.
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			['<?php echo JText::_('COM_REDSHOP_STATISTIC_DURATION');?>', '<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT');?>', {role: 'style'}, {role: 'annotation'}],
			<?php if (count($this->orders) > 0) :?>
				<?php foreach ($this->orders as $row) : ?>
	         		['<?php echo $row->viewdate ?>', <?php echo $row->order_total; ?>, '#0099ff', '<?php echo $producthelper->getProductFormattedPrice($row->order_total); ?>'],
	       	 	<?php endforeach; ?>
	       	 <?php else: ?>
	       	 	[0, 0, '#0099ff', 0],
	       	 <?php endif; ?>
	      ]);

		var options = {
			  chart: {
	            title: '<?php echo JText::_("COM_REDSHOP_STATISTIC_ORDER"); ?>',
	            subtitle: '<?php echo JText::_("COM_REDSHOP_STATISTIC_ORDER"); ?>',
	          },
		  annotations: {
				boxStyle: {
					// Color of the box outline.
					stroke: '#888',
					// Thickness of the box outline.
					strokeWidth: 1,
					// x-radius of the corner curvature.
					rx: 10,
					// y-radius of the corner curvature.
					ry: 10,
					// Attributes for linear gradient fill.
					gradient: {
						// Start color for gradient.
						color1: '#fbf6a7',
						// Finish color for gradient.
						color2: '#33b679',
						// Where on the boundary to start and
						// end the color1/color2 gradient,
						// relative to the upper left corner
						// of the boundary.
						x1: '0%', y1: '0%',
						x2: '100%', y2: '100%',
						// If true, the boundary for x1,
						// y1, x2, and y2 is the box. If
						// false, it's the entire chart.
						useObjectBoundingBoxUnits: true
					}
				}
			}
		};

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('order_statistic_chart'));
		chart.draw(data, options);
	}
</script>
<form action="index.php?option=com_redshop&view=statistic_order" method="post" name="adminForm" id="adminForm">
	<div class="filterTool">
		<div class="filterItem">
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('date_range', 'filter') ?>
			</div>
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('date_group', 'filter') ?>
			</div>
		</div>
	</div>
	<?php if (empty($this->orders)): ?>
	<hr />
	<div class="alert alert-info">
		<p><?php echo JText::_('COM_REDSHOP_NO_DATA') ?></p>
	</div>
	<?php else: ?>
	<div id="order_statistic_chart"></div>
	<hr />
	<table class="adminlist table table-striped" width="100%">
		<thead>
		<tr>

			<th align="center"><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_DATE'), 'cdate', $listDirn, $listOrder) ?></th>
			<th align="center"><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ORDER_COUNT'), 'count', $listDirn, $listOrder) ?></th>
			<th align="center"><?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_TOTAL_LBL'), 'order_total', $listDirn, $listOrder) ?></th>
		</tr>
		</thead>
		<?php foreach ($this->orders as $i => $row) : ?>
			<tr>
				<td align="center"><?php echo $row->viewdate; ?></td>
				<td align="center"><?php echo $row->count; ?></td>
				<td align="center"><?php  echo $producthelper->getProductFormattedPrice($row->order_total);?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
