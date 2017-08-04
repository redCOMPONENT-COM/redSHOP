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
			['', '<?php echo JText::_('COM_REDSHOP_SALES_AMOUNT') ?>', {role: 'annotation'}],
			<?php if (!empty($this->customers)): ?>
				<?php foreach ($this->customers as $row) : ?>
				[
					'<?php echo $row->customer_name ?>',
					<?php echo $row->total_sale ?>,
					"<?php echo $productHelper->getProductFormattedPrice($row->total_sale) ?>"
				],
				<?php endforeach; ?>
			<?php else: ?>
                [0, 0, 0],
			<?php endif; ?>
		]);

		var options = {
			title: '<?php echo JText::_("COM_REDSHOP_STATISTIC_CUSTOMER"); ?>',
			bars: 'vertical',
			height: 500,
			vAxis: {
				format: 'decimal'
			},
			legend: {
				position: 'top'
			}
		};

		//Instantiate and draw our chart, passing in some options.
		var chart = new google.visualization.ColumnChart(document.getElementById('customer_statistic_chart'));
		chart.draw(data, options);
	}
</script>
<form action="index.php?option=com_redshop&view=statistic_customer" method="post" name="adminForm" id="adminForm">
	<div class="filterTool row-fluid">
		<div class="filterItem col-md-3">
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('date_range', 'filter') ?>
			</div>
		</div>
		<div class="filterItem col-md-1 pull-right">
			<div class="js-stools clearfix">
				<?php echo $this->filterForm->getInput('limit', 'list') ?>
			</div>
		</div>
	</div>
	<?php if (empty($this->customers)): ?>
	<hr />
	<div class="alert alert-info">
		<p><?php echo JText::_('COM_REDSHOP_NO_DATA') ?></p>
	</div>
	<?php else: ?>
	<p></p>
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
		<tbody>
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
		</tbody>
		<tfoot>
			<td colspan="4">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tfoot>
	</table>
	<?php endif; ?>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn ?>" />
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="view" value="statistic_customer" />
	<?php echo JHtml::_('form.token'); ?>
</form>
