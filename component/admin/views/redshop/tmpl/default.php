<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$producthelper = productHelper::getInstance();

$statistic = $this->model->getStatisticDashboard();
?>

<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		if (pressbutton == 'configuration') {
			var link = 'index.php?option=com_redshop&view=configuration';
			window.location.href = 'index.php?option=com_redshop&view=configuration';
		}

		if (pressbutton == 'remote_update') {
			window.location.href = 'index.php?option=com_redshop&view=zip_import&layout=confirmupdate';
		}

		if (pressbutton == 'statistic') {
			window.location.href = 'index.php?option=com_redshop&view=statistic';
		}

		if (pressbutton == 'update') {
			window.location.href = 'index.php?option=com_redshop&view=update';
		}
	}
</script>
<?php
$user           = JFactory::getUser();
$userType       = array_keys($user->groups);
$user->usertype = $userType[0];
$user->gid      = $user->groups[$user->usertype];
?>

<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-green">
				<i class="fa fa-money" aria-hidden="true"></i>
			</span>

			<div class="info-box-content">
				<span class="info-box-text"><?php echo JText::_('COM_REDSHOP_STATISTIC_TOTAL_SALES');?></span>
				<span class="info-box-number"><?php echo $producthelper->getProductFormattedPrice($statistic[0]); ?></span>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-blue">
				<i class="fa fa-shopping-cart" aria-hidden="true"></i>
			</span>

			<div class="info-box-content">
				<span class="info-box-text"><?php echo JText::_('COM_REDSHOP_STATISTIC_ORDER_COUNT');?></span>
				<span class="info-box-number"><?php echo (int) $statistic[1] ?></span>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-aqua">
				<i class="fa fa-users" aria-hidden="true"></i>
			</span>

			<div class="info-box-content">
				<span class="info-box-text"><?php echo JText::_('COM_REDSHOP_STATISTIC_TOTAL_MEMBER');?></span>
				<span class="info-box-number"><?php echo (int) $statistic[2] ?></span>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-yellow">
				<i class="fa fa-area-chart" aria-hidden="true"></i>
			</span>

			<div class="info-box-content">
				<span class="info-box-text"><?php echo JText::_('COM_REDSHOP_STATISTIC_TOTAL_VISITOR');?></span>
				<span class="info-box-number"><?php echo (int) $statistic[3] ?></span>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					<?php echo JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');?>
				</h3>
			</div>

			<div class="box-body">
				<?php  echo $this->loadTemplate('sales_piechart');  ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					<?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS');?>
				</h3>
			</div>

			<div class="box-body">
				<?php echo $this->loadTemplate('newest_orders');  ?>
			</div>
		</div>
	</div>

	<div class="col-sm-6">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					<?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');?>
				</h3>
			</div>

			<div class="box-body">
				<?php echo $this->loadTemplate('newest_customers');  ?>
			</div>
		</div>
	</div>
</div>
