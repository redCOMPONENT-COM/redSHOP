<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<div class="row">
	<div class="alert alert-info">
		<small><?php echo JText::_('COM_REDSHOP_VERSION');?></small>
		<span class="label label-info"><?php echo $this->redshopversion;?></span>
	</div>
	<div class="well">
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_POPULAR');?>
		</h2>
		<div class="row-fluid">
			<div id="cpanel" align="center">
				<?php
					redshopViewredshop::quickiconButton(
						'index.php?option=com_redshop&amp;wizard=1',
						'wizard_48.png',
						JText::_('COM_REDSHOP_WIZARD')
					);
				?>
			</div>
			<div id="cpanel" align="center">
				<?php
					redshopViewredshop::quickiconButton(
						'index.php?option=com_redshop&view=configuration&dashboard=1',
						'dashboard_48.png',
						JText::_('COM_REDSHOP_DASHBORAD_CONFIGURATION')
					);
				?>
			</div>
		</div>
	</div>
	<div class="well">
		<h2 class="module-title nav-header">
			<?php echo JText::_('COM_REDSHOP_QUICK_LINKS');?>
		</h2>
		<?php
			$user           = JFactory::getUser();
			$usertype       = array_keys($user->groups);
			$user->usertype = $usertype[0];
			$user->gid      = $user->groups[$user->usertype];
			$quicklink_icon = explode(",", QUICKLINK_ICON);
			$icons        = RedShopHelperImages::geticonarray();
			$sections = array(
				'products'       => 'prod',
				'discounts'      => 'discount',
				'communications' => 'comm',
				'shippings'      => 'shipping',
				'vats'           => 'vat',
				'importexport'   => 'import',
				'altration'      => 'altration',
				'customerinput'  => 'customerinput',
				'accountings'    => 'acc'
			);
		?>
		<div class="row-fluid">
		<?php foreach ($sections as $sectionName => $sectionAssets) : ?>
			<div id="cpanel">
				<?php for ($i = 0, $n = count($icons[$sectionName]); $i < $n; $i++) : ?>
					<?php
						$isQuickLink = in_array($icons[$sectionName][$i], $quicklink_icon);

						$isBackendAccess = (
											$user->gid != 8
											&& ENABLE_BACKENDACCESS != 0
											&& in_array($icons[$sectionName][$i], $this->access_rslt)
											&& $isQuickLink
										);
						$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
					?>
					<?php if ($defaultAccess || $isBackendAccess) : ?>
						<!-- Default shipping link -->
						<?php $link = 'index.php?option=com_redshop&amp;view=' . $icons[$sectionName][$i]; ?>

						<?php if ($icons[$sectionName][$i] == 'shipping_detail') : ?>
							<?php $link = 'index.php?option=com_installer'; ?>
						<?php endif; ?>

						<?php
							redshopViewredshop::quickiconButton(
								$link,
								$icons[$sectionAssets . 'images'][$i],
								JText::_("COM_REDSHOP_" . $icons[$sectionAssets . 'txt'][$i])
							);
						?>
					<?php endif; ?>
				<?php endfor; ?>
			</div>
		<?php endforeach; ?>

			<!-- Orders -->
			<div id="cpanel">
				<?php for ($i = 0, $n = count($icons['orders']); $i < $n; $i++) : ?>
					<?php if (('stockroom' == $icons['orders'][$i] && USE_STOCKROOM)
									|| 'stockroom' != $icons['orders'][$i]) : ?>
					<?php
						$isQuickLink = in_array($icons['orders'][$i], $quicklink_icon);

						$isBackendAccess = (
											$user->gid != 8
											&& ENABLE_BACKENDACCESS != 0
											&& in_array($icons['orders'][$i], $this->access_rslt)
											&& $isQuickLink
										);
						$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
					?>
						<?php if ($defaultAccess || $isBackendAccess) : ?>
							<?php
								redshopViewredshop::quickiconButton(
									'index.php?option=com_redshop&amp;view=' . $icons['orders'][$i],
									$icons['orderimages'][$i],
									JText::_("COM_REDSHOP_" . $icons['ordertxt'][$i])
								);
							?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endfor; ?>
			</div>

			<!-- users -->
			<div id="cpanel">
				<?php for ($i = 0, $n = count($icons['users']); $i < $n; $i++) : ?>
					<?php if (('accessmanager' == $icons['users'][$i] && ENABLE_BACKENDACCESS)
									|| 'accessmanager' != $icons['users'][$i]) : ?>
					<?php
						$isQuickLink = in_array($icons['users'][$i], $quicklink_icon);

						$isBackendAccess = (
											$user->gid != 8
											&& ENABLE_BACKENDACCESS != 0
											&& in_array($icons['users'][$i], $this->access_rslt)
											&& $isQuickLink
										);
						$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
					?>
						<?php if ($defaultAccess || $isBackendAccess) : ?>
							<?php
								redshopViewredshop::quickiconButton(
									'index.php?option=com_redshop&amp;view=' . $icons['users'][$i],
									$icons['userimages'][$i],
									JText::_("COM_REDSHOP_" . $icons['usertxt'][$i])
								);
							?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endfor; ?>
			</div>
		</div>
	</div>

	<?php if (DISPLAY_NEW_CUSTOMERS): ?>
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_NEWEST_CUSTOMERS');?>
			</h2>
			<div class="row-fluid">
				<?php  echo $this->loadTemplate('newest_customers');  ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if (DISPLAY_NEW_ORDERS): ?>
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_NEWEST_ORDERS');?>
			</h2>
			<div class="row-fluid">
				<?php  echo $this->loadTemplate('newest_orders');  ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if (DISPLAY_STATISTIC): ?>
		<div class="well">
			<h2 class="module-title nav-header">
				<?php echo JText::_('COM_REDSHOP_PIE_CHART_FOR_LASTMONTH_SALES');?>
			</h2>
			<div class="row-fluid">
				<?php  echo $this->loadTemplate('sales_piechart');  ?>
			</div>
		</div>
	<?php endif; ?>
</div>
