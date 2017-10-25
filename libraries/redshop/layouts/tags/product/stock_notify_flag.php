<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   int   $productId          Product id
 * @param   int   $propertyId         Property id
 * @param   int   $subPropertyId      Sub property id
 * @param   bool  $isAjax             Layout use for ajax request
 * @param   array $productStockStatus Product status array
 */
extract($displayData);

$session = JFactory::getSession();
$userArr = RedshopHelperUser::createUserSession();
$user_id = isset($userArr['rs_userid']) ? $userArr['rs_userid'] : '';
$is_login = isset($userArr['rs_is_user_login']) ? $userArr['rs_is_user_login'] : '';
$users_info_id = isset($userArr['rs_user_info_id']) ? $userArr['rs_user_info_id'] : 0;

if (!isset($isAjax))
{
	$isAjax = false;
}

if (!$isAjax):
	?>
	<div id="notify_stock<?php echo $productId; ?>" class="notifyStock">
<?php
endif;

if ((!isset($productStockStatus['regular_stock']) || !$productStockStatus['regular_stock']) && $is_login && $users_info_id && $user_id)
{
	if (($productStockStatus['preorder'] && !$productStockStatus['preorder_stock']) || !$productStockStatus['preorder'])
	{
		if (RedshopHelperStockroom::isAlreadyNotifiedUser($user_id, $productId, $propertyId, $subPropertyId)): ?>
			<span><?php echo JText::_('COM_REDSHOP_ALREADY_REQUESTED_FOR_NOTIFICATION'); ?></span>
		<?php else: ?>
			<span><?php echo JText::_('COM_REDSHOP_NOTIFY_STOCK_LBL'); ?></span>
			<input type="button" name="" value="<?php echo JText::_('COM_REDSHOP_NOTIFY_STOCK_BUTTON'); ?>"
				   class="notifystockbtn btn" onclick=" getStocknotify('<?php
					echo $productId; ?>','<?php
					echo $propertyId; ?>','<?php
					echo $subPropertyId; ?>');" />
		<?php endif;
	}
}

if (!$isAjax):
	?>
	</div>
<?php endif;
