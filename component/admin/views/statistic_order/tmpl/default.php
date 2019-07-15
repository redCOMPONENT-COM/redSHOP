<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JFactory::getDocument()->addScript('//www.gstatic.com/charts/loader.js');

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
			['', '<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT') ?>', {role: 'annotation'}],
			<?php if (count($this->orders) > 0) :?>
				<?php foreach ($this->orders as $row) : ?>
					[
						'<?php echo $row->viewdate ?>',
						<?php echo $row->order_total ?>,
						"<?php echo strip_tags(RedshopHelperProductPrice::formattedPrice($row->order_total)); ?>"
					],
				<?php endforeach; ?>
			 <?php else: ?>
				[0, 0, 0],
			 <?php endif; ?>
		  ]);

		var options = {
			title: "<?php echo JText::_('COM_REDSHOP_STATISTIC_ORDER') ?>",
			bars: 'vertical',
			height: 500,
			vAxis: {
				format: 'decimal',
				minValue: 0
			},
			legend: {
				position: 'top'
			}
		};

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('order_statistic_chart'));
		chart.draw(data, options);
	}
</script>
<form action="index.php?option=com_redshop&view=statistic_order" method="post" name="adminForm" id="adminForm">
	<div class="filterTool row-fluid">
		<div class="filterItem dateRange">
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('date_range', 'filter') ?>
			</div>
		</div>
		<div class="filterItem">
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('date_group', 'filter') ?>
			</div>
		</div>
		<div class="filterItem pull-right">
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('limit', 'list') ?>
			</div>
		</div>
	</div>
	<?php if (empty($this->orders)): ?>
	<hr />
	<div class="alert alert-info">
		<p><?php echo JText::_('COM_REDSHOP_NO_DATA') ?></p>
	</div>
	<?php else: ?>
		<hr />
	<div id="order_statistic_chart"></div>
	<hr />
	<table class="adminlist table table-striped" width="100%">
		<thead>
			<tr>
				<th align="center">
					<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_DATE'), 'orderdate', $listDirn, $listOrder) ?>
				</th>
				<th align="center" width="10%">
					<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ORDER_COUNT'), 'count', $listDirn, $listOrder) ?>
				</th>
				<th  style="text-align: right;" width="20%">
					<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_TOTAL_LBL'), 'order_total', $listDirn, $listOrder) ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($this->orders as $i => $row) : ?>
			<tr>
				<td align="center"><?php echo $row->viewdate; ?></td>
				<td align="center"><?php echo $row->count; ?></td>
				<td style="text-align: right;"><?php echo RedshopHelperProductPrice::formattedPrice($row->order_total) ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
		<tfoot>
			<td colspan="3">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tfoot>
	</table>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="view" value="statistic_order" />
	<?php echo JHtml::_('form.token'); ?>
</form>
