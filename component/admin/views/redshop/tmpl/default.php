<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
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

		if (pressbutton == 'wizard') {
			window.location.href = 'index.php?option=com_redshop&wizard=1';
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
$usertype       = array_keys($user->groups);
$user->usertype = $usertype[0];
$user->gid      = $user->groups[$user->usertype];
$quicklink_icon = explode(",", QUICKLINK_ICON);
$new_arr        = RedShopHelperImages::geticonarray();

?>

<div class="row">
	<div class="col-sm-12">
		<?php  echo $this->loadTemplate('quickpanel');  ?>
	</div>
</div>

<div class="row">
	<?php if (DISPLAY_STATISTIC): ?>
	<div class="col-sm-12">
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');?>
		</h2>
		<?php  echo $this->loadTemplate('sales_piechart');  ?>
	</div>
	<?php endif; ?>
</div>

<div class="row">
	<div class="col-sm-12">
		<?php if (DISPLAY_NEW_CUSTOMERS): ?>
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');?>
		</h2>
		<?php  echo $this->loadTemplate('newest_customers');  ?>
		<?php endif; ?>

		<?php if (DISPLAY_NEW_ORDERS): ?>
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS');?>
		</h2>
		<?php  echo $this->loadTemplate('newest_orders');  ?>
		<?php endif; ?>
	</div>
</div>
