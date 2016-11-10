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

$productHelper = productHelper::getInstance();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>

<script type="text/javascript">
	//Load the Visualization API and the piechart package.
	google.charts.load('current', {'packages':['corechart']});

	//Set a callback to run when the Google Visualization API is loaded.
	google.charts.setOnLoadCallback(drawChart);

	//Callback that creates and populates a data table,
	//instantiates the pie chart, passes in the data and
	//draws it.
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
			[
				'<?php echo JText::_('COM_REDSHOP_STATISTIC_DURATION') ?>',
				'<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT') ?>',
				{role: 'style'},
				{role: 'annotation'}
			],
			<?php if (!empty($this->customers)): ?>
			<?php foreach ($this->customers as $row) : ?>
			[
				'<?php echo $row->customer_name ?>',
				<?php echo $row->total_sale ?>,
				'#0099ff',
				'<?php echo $productHelper->getProductFormattedPrice($row->total_sale) ?>'
			],
			<?php endforeach; ?>
			<?php endif; ?>
		]);

		var options = {
			  chart: {
				title: '<?php echo JText::_("COM_REDSHOP_STATISTIC_PRODUCT"); ?>',
				subtitle: '<?php echo JText::_("COM_REDSHOP_STATISTIC_PRODUCT"); ?>',
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
		var chart = new google.visualization.ColumnChart(document.getElementById('customer_statistic_chart'));
		chart.draw(data, options);
	}

	$(document).ready(function() {
		updateConfig();

		function updateConfig() {
			var options = {};

			options.locale = {
				format: 'MM/DD/YYYY',
				separator: ' - ',
				applyLabel: 'Apply',
				cancelLabel: 'Cancel',
				fromLabel: 'From',
				toLabel: 'To',
				customRangeLabel: 'Custom',
				daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
				monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
				firstDay: 1
			};

			<?php if (!empty($this->filterStartDate)): ?>
			options.startDate = $('input[name="filter[start_date]').val();
			<?php endif; ?>
			<?php if (!empty($this->filterEndDate)): ?>
			options.endDate = $('input[name="filter[end_date]"]').val();
			<?php endif; ?>

			options.autoApply = true;
			options.ranges = {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'This week': [moment().startOf('isoWeek'), moment().endOf('isoWeek')],
				'Last Week': [moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				'This Year': [moment().startOf('year'), moment().endOf('year')],
				'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 15 Days': [moment().subtract(14, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'Last 60 Days': [moment().subtract(59, 'days'), moment()],
				'Last 90 Days': [moment().subtract(89, 'days'), moment()]
			};

			options.linkedCalendars = true;
			options.autoUpdateInput = true;
			options.showDropdowns = true;
			options.alwaysShowCalendars = true;

			$('#filter_dates').daterangepicker(options, function(start, end, label) {
				$('input[name="filter[start_date]"]').val(start.format('MM/DD/YYYY'));
				$('input[name="filter[end_date]"]').val(end.format('MM/DD/YYYY'));
				document.adminForm.submit();
			});

			$('#btn-filter-date').click(function(e){
				e.preventDefault();
				$('#filter_dates').click();
			});
		  }
	});
</script>
<form action="index.php?option=com_redshop&view=statistic_customer" method="post" name="adminForm" id="adminForm">
	<div class="filterTool">
		<div class="filterItem">
			<div class="js-stools clearfix">
				<div class="input-append">
					<input type="text" id="filter_dates" class="form-control" autocomplete="false" />
					<button class="btn btn-success" id="btn-filter-date"><i class="fa fa-calendar"></i></button>
					<input type="hidden" name="filter[start_date]" value="<?php echo $this->filterStartDate ?>" />
					<input type="hidden" name="filter[end_date]" value="<?php echo $this->filterEndDate ?>" />
				</div>
			</div>
		</div>
	</div>
	<?php if (empty($this->customers)): ?>
	<hr />
	<div class="alert alert-info">
		<p><?php echo JText::_('COM_REDSHOP_NO_DATA') ?></p>
	</div>
	<?php else: ?>
	<div id="customer_statistic_chart"></div>
	<hr />
	<table class="adminlist table table-striped" width="100%">
		<thead>
		<tr>
			<th align="center">
				<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_USER_NAME'), 'customer_name', $listDirn, $listOrder) ?>
			</th>
			<th align="center">
				<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_USER_EMAIL'), 'ui.user_email', $listDirn, $listOrder) ?>
			</th>
			<th align="center">
				<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_ORDER_COUNT'), 'count', $listDirn, $listOrder) ?>
			</th>
			<th align="center">
				<?php echo JHTML::_('grid.sort', JText::_('COM_REDSHOP_PRODUCT_TOTAL_SALE'), 'total_sale', $listDirn, $listOrder) ?>
			</th>
		</tr>
		</thead>
		<?php foreach ($this->customers as $i => $row) : ?>
			<tr>
				<td align="center">
					<a href="index.php?option=com_redshop&view=user_detail&task=edit&user_id=<?php echo $row->user_id ?>&cid[]=<?php echo $row->users_info_id ?>">
						<?php echo $row->firstname . ' ' . $row->lastname; ?>
					</a>
				</td>
				<td align="center"><?php echo $row->user_email ?></td>
				<td align="center"><?php echo $row->count ?></td>
				<td align="center"><?php echo $productHelper->getProductFormattedPrice($row->total_sale) ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
