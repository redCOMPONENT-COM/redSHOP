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
			$new_arr        = RedShopHelperImages::geticonarray();
		?>
		<div class="row-fluid">
			<!-- Products -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['products']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['products'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['products'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['products'][$i],
							$new_arr['prodimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['prodtxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- Orders -->
			<div id="cpanel">
				<?php for ($i = 0; $i < count($new_arr['orders']); $i++) : ?>
					<?php if (('stockroom' == $new_arr['orders'][$i] && USE_STOCKROOM)
									|| 'stockroom' != $new_arr['orders'][$i]) : ?>
					<?php
						$isQuickLink = in_array($new_arr['orders'][$i], $quicklink_icon);

						$isBackendAccess = (
											$user->gid != 8
											&& ENABLE_BACKENDACCESS != 0
											&& in_array($new_arr['orders'][$i], $this->access_rslt)
											&& $isQuickLink
										);
						$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
					?>
						<?php if ($defaultAccess || $isBackendAccess) : ?>
							<?php
								redshopViewredshop::quickiconButton(
									'index.php?option=com_redshop&amp;view=' . $new_arr['orders'][$i],
									$new_arr['orderimages'][$i],
									JText::_("COM_REDSHOP_" . $new_arr['ordertxt'][$i])
								);
							?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endfor; ?>
			</div>

			<!-- Discounts -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['discounts']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['discounts'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['discounts'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['discounts'][$i],
							$new_arr['discountimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['discounttxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- Communications -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['communications']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['communications'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['communications'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['communications'][$i],
							$new_arr['commimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['commtxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- shippings -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['shippings']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['shippings'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['shippings'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<!-- Default shipping link -->
					<?php $link = 'index.php?option=com_redshop&amp;view=' . $new_arr['shippings'][$i]; ?>

					<?php if ($new_arr['shippings'][$i] == 'shipping_detail') : ?>
						<?php $link = 'index.php?option=com_installer'; ?>
					<?php endif; ?>

					<?php
						redshopViewredshop::quickiconButton(
							$link,
							$new_arr['shippingimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['shippingtxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- users -->
			<div id="cpanel">
				<?php for ($i = 0; $i < count($new_arr['users']); $i++) : ?>
					<?php if (('accessmanager' == $new_arr['users'][$i] && ENABLE_BACKENDACCESS)
									|| 'accessmanager' != $new_arr['users'][$i]) : ?>
					<?php
						$isQuickLink = in_array($new_arr['users'][$i], $quicklink_icon);

						$isBackendAccess = (
											$user->gid != 8
											&& ENABLE_BACKENDACCESS != 0
											&& in_array($new_arr['users'][$i], $this->access_rslt)
											&& $isQuickLink
										);
						$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
					?>
						<?php if ($defaultAccess || $isBackendAccess) : ?>
							<?php
								redshopViewredshop::quickiconButton(
									'index.php?option=com_redshop&amp;view=' . $new_arr['users'][$i],
									$new_arr['userimages'][$i],
									JText::_("COM_REDSHOP_" . $new_arr['usertxt'][$i])
								);
							?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endfor; ?>
			</div>

			<!-- vats -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['vats']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['vats'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['vats'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['vats'][$i],
							$new_arr['vatimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['vattxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- importexport -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['importexport']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['importexport'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['importexport'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['importexport'][$i],
							$new_arr['importimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['importtxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- altration -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['altration']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['altration'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['altration'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['altration'][$i],
							$new_arr['altrationimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['altrationtxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- customerinput -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['customerinput']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['customerinput'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['customerinput'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['customerinput'][$i],
							$new_arr['customerinputimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['customerinputtxt'][$i])
						);
					?>
				<?php endif; ?>
			<?php endfor; ?>
			</div>

			<!-- accountings -->
			<div id="cpanel">
			<?php for ($i = 0, $n = count($new_arr['accountings']); $i < $n; $i++) : ?>
				<?php
					$isQuickLink = in_array($new_arr['accountings'][$i], $quicklink_icon);

					$isBackendAccess = (
										$user->gid != 8
										&& ENABLE_BACKENDACCESS != 0
										&& in_array($new_arr['accountings'][$i], $this->access_rslt)
										&& $isQuickLink
									);
					$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
				?>
				<?php if ($defaultAccess || $isBackendAccess) : ?>
					<?php
						redshopViewredshop::quickiconButton(
							'index.php?option=com_redshop&amp;view=' . $new_arr['accountings'][$i],
							$new_arr['accimages'][$i],
							JText::_("COM_REDSHOP_" . $new_arr['acctxt'][$i])
						);
					?>
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
