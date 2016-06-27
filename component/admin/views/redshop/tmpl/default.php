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
	<div class="col-sm-8">
	<?php if (DISPLAY_STATISTIC): ?>
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');?>
		</h2>
		<div class="row-fluid">
			<?php  echo $this->loadTemplate('sales_piechart');  ?>
		</div>
	<?php endif; ?>
	<?php if (DISPLAY_NEW_CUSTOMERS): ?>
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');?>
		</h2>
		<div class="row-fluid">
			<?php  echo $this->loadTemplate('newest_customers');  ?>
		</div>
	<?php endif; ?>

	<?php if (DISPLAY_NEW_ORDERS): ?>
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS');?>
		</h2>
		<div class="row-fluid">
			<?php  echo $this->loadTemplate('newest_orders');  ?>
		</div>
	<?php endif; ?>
	</div>

	<div class="col-sm-4">
		<?php  echo $this->loadTemplate('quickpanel');  ?>
	</div>
</div>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
<td valign="top" width="50%">

</td>
<td valign="top" width="50%">

</td>
</tr>
</table>
