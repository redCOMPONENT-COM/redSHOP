<?php
/**
 * @package     Redshop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

use Redshop\App;

/**
 * User helper
 *
 * @since  __DEPLOY_VERSION__
 */
class UserHelper
{
	/**
	 * Method for check if show price for current user or not.
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function isShowPrice()
	{
		$user           = \JFactory::getUser();
		$shopperGroupId = \RedshopHelperUser::getShopperGroup($user->id);
		$list           = \rsUserHelper::getInstance()->getShopperGroupList($shopperGroupId);

		if (empty($list))
		{
			return App::getConfig()->get('SHOW_PRICE_PRE');
		}

		$list = $list[0];

		if (($list->show_price == "yes") || ($list->show_price == "global" && App::getConfig()->get('SHOW_PRICE_PRE') == 1)
			|| ($list->show_price == "" && App::getConfig()->get('SHOW_PRICE_PRE') == 1))
		{
			return 1;
		}

		return 0;
	}

	/**
	 * Method for check if "Show As Catalog" is enable for current user
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function isUseCatalog()
	{
		$user           = \JFactory::getUser();
		$shopperGroupId = \RedshopHelperUser::getShopperGroup($user->id);
		$list           = \rsUserHelper::getInstance()->getShopperGroupList($shopperGroupId);

		if (empty($list))
		{
			return App::getConfig()->get('PRE_USE_AS_CATALOG');
		}

		$list = $list[0];

		if (($list->use_as_catalog == "yes") || ($list->use_as_catalog == "global" && App::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
			|| ($list->use_as_catalog == "" && App::getConfig()->get('PRE_USE_AS_CATALOG') == 1))
		{
			return 1;
		}

		return 0;
	}

	/**
	 * Method for check if "Show As Catalog" is enable for current user
	 *
	 * @return  int
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function setQuotationMode()
	{
		$db             = \JFactory::getDbo();
		$user           = \JFactory::getUser();
		$shopperGroupId = App::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

		if ($user->id)
		{
			$userShopperGroupId = \RedshopHelperUser::getShopperGroup($user->id);

			if ($userShopperGroupId)
			{
				$shopperGroupId = $userShopperGroupId;
			}
		}

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('shopper_group_id') . ' = ' . $shopperGroupId);

		$shopperGroup = $db->setQuery($query)->loadObject();

		if (empty($shopperGroup))
		{
			return App::getConfig()->get('DEFAULT_QUOTATION_MODE_PRE');
		}

		return (boolean) $shopperGroup->shopper_group_quotation_mode;
	}
}
