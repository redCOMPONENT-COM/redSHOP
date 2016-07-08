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
<div class="alert alert-info">
		<small><?php echo JText::_('COM_REDSHOP_VERSION');?></small>
		<span class="label label-info"><?php echo $this->redshopversion;?></span>
	</div>

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
			'orders'         => 'order',
			'discounts'      => 'discount',
			'communications' => 'comm',
			'shippings'      => 'shipping',
			'users'          => 'user',
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

					<?php // Don't show some icons based on conditions ?>
					<?php if ((ENABLE_BACKENDACCESS && 'accessmanager' == $icons[$sectionName][$i])
								|| ('stockroom' == $icons[$sectionName][$i] && USE_STOCKROOM)
								|| 'accessmanager' != $icons[$sectionName][$i]
								|| 'stockroom' != $icons[$sectionName][$i]) : ?>
						<?php
							$isQuickLink = in_array($icons[$sectionName][$i], $quicklink_icon);

							// Prepare check for regular use - not super admin
							$isBackendAccessReg = (
													$user->gid != 8
													&& ENABLE_BACKENDACCESS != 0
													&& in_array($icons[$sectionName][$i], $this->access_rslt)
												);

							// Allow super admin access by default
							$isBackendAccess = (8 == $user->gid || $isBackendAccessReg) && $isQuickLink;

							// Prepare condition for disabled config backend access.
							$defaultAccess = (ENABLE_BACKENDACCESS == 0 && $isQuickLink);
						?>

						<?php // Handles Backend Access in below conditions ?>
						<?php if ($defaultAccess || $isBackendAccess) : ?>
							<?php // Default shipping link ?>
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

					<?php endif; ?>

				<?php endfor; ?>
			</div>
		<?php endforeach; ?>
	</div>