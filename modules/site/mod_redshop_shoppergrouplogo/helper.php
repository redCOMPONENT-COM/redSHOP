<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_shoppergrouplogo
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * Helper for mod_redshop_shoppergrouplogo
 *
 * @since  1.7.0
 */
abstract class ModRedshopShopperGroupLogoHelper
{
	/**
	 * getPortalLogo
	 *
	 * @param   \Joomla\Registry\Registry  &$params  Module parameters
	 *
	 * @return  mixed
	 */
	public static function getPortalLogo(&$params)
	{
		$user = JFactory::getUser();
		$portalLogo = '';
		$thumbWidth  = (int) $params->get('thumbwidth', 100);
		$thumbHeight = (int) $params->get('thumbheight', 100);

		if (!$user->id)
		{
			if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO')))
			{
				$portalLogo = RedShopHelperImages::getImagePath(
					Redshop::getConfig()->get('DEFAULT_PORTAL_LOGO'),
					'',
					'thumb',
					'shopperlogo',
					$thumbWidth,
					$thumbHeight
				);
			}
		}
		elseif ($userInfo = RedshopHelperUser::getUserInformation($user->id))
		{
			if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'shopperlogo/' . $userInfo->shopper_group_logo))
			{
				$portalLogo = RedShopHelperImages::getImagePath(
					$userInfo->shopper_group_logo,
					'',
					'thumb',
					'shopperlogo',
					$thumbWidth,
					$thumbHeight
				);
			}
		}

		return $portalLogo;
	}
}
