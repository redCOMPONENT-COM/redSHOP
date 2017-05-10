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
$productHelper = productHelper::getInstance();
$total = 0;

foreach ($this->productVariants as $itemId => $data)
{
	$total += $data['total_sale'];
}
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
			<?php if ($total > 0) :?>
				<?php foreach ($this->productVariants as $row) : ?>
					<?php $row = (object) $row; ?>
					<?php if (!empty($row->total_sale)) : ?>
	         		['<?php echo $row->product_name . '(' . $row->product_attribute . ')'; ?>', <?php echo $row->total_sale; ?>, 'blue', '<?php echo $productHelper->getProductFormattedPrice($row->total_sale); ?>'],
	         		<?php endif; ?>
	       	 	<?php endforeach; ?>
	       	 <?php else: ?>
	       	 	[0, 0, 'blue', 0],
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
		var chart = new google.visualization.ColumnChart(document.getElementById('product_variant_statistic_chart'));
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
		    if ($('input[name="filter_start_date"]').val().length)
		      options.startDate = $('input[name="filter_start_date').val();

		    if ($('input[name="filter_end_date"]').val().length)
		      options.endDate = $('input[name="filter_end_date"]').val();

		      options.autoApply = true;
		      options.ranges = {
		        'Today': [moment(), moment()],
		        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		        'Last 15 Days': [moment().subtract(14, 'days'), moment()],
		        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		        'Last 60 Days': [moment().subtract(59, 'days'), moment()],
		        'Last 90 Days': [moment().subtract(89, 'days'), moment()],
		        'This Month': [moment().startOf('month'), moment().endOf('month')],
		        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		        'This Year': [moment().startOf('year'), moment().endOf('year')],
		        'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
		      };

		      options.linkedCalendars = true;
		      options.autoUpdateInput = true;

		    $('#config-demo').daterangepicker(options, function(start, end, label) {
		    	$('input[name="filter_start_date"]').val(start.format('MM/DD/YYYY'));
		    	$('input[name="filter_end_date"]').val(end.format('MM/DD/YYYY'));
		    	$('input[name="filter_date_label"]').val(label);
		    	document.adminForm.submit();
		    });
		  }
	});
</script>
<form action="index.php?option=com_redshop&view=statistic_variant" method="post" name="adminForm" id="adminForm">
	<div id="editcell">
		<table width="100%">
			<tr>
				<td>
					<div class="col-md-4 col-md-offset-2 demo">
						<input type="text" id="config-demo" class="form-control">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
						<input type="hidden" name="filter_start_date" value="<?php echo $this->filterStartDate ?>"/>
						<input type="hidden" name="filter_end_date" value="<?php echo $this->filterEndDate ?>"/>
						<input type="hidden" name="filter_date_label" value="<?php echo $this->filterDateLabel ?>"/>
					</div>
				</td>
			</tr>
		</table>
		<div id="product_variant_statistic_chart"></div>
		<table class="adminlist table table-striped" width="100%">
			<thead>
			<tr>
				<th align="center"><?php echo JText::_('COM_REDSHOP_DATE'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_NAME'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTES'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_ATTRIBUTE_SKU'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_UNIT'); ?></th>
				<th align="center"><?php echo JText::_('COM_REDSHOP_PRODUCT_TOTAL_SALE'); ?></th>
			</tr>
			</thead>
			<?php if ($total > 0) :?>
				<?php
				foreach ($this->productVariants as $itemId => $data) :
					$row = (object) $data;
				?>
					<?php if (!empty($row->total_sale)) : ?>
					<tr>
						<td align="center"><?php echo $row->viewdate; ?></td>
						<td align="center"><a href="index.php?option=com_redshop&view=product_detail&task=edit&cid[]=<?php echo $row->product_id; ?>"><?php echo $row->product_name; ?></a></td>
						<td align="center"><?php echo $row->product_attribute; ?></td>
						<td align="center"><?php echo $row->product_attribute_sku; ?></td>
						<td align="center"><?php echo $row->unit_sold; ?></td>
						<td align="center"><?php echo $productHelper->getProductFormattedPrice($row->total_sale); ?></td>
					</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</table>
	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
</form>
