<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  User.cart
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Cart User plugin for redSHOP
 *
 * @since  1.5
 */
class PlgUserCart extends JPlugin
{
	/**
	 * This method should handle any login logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data
	 * @param   array  $options  Array holding options (remember, autoregister, group)
	 *
	 * @return	boolean	True on success
	 *
	 * @since	1.5
	 */
	public function onUserLogin($user, $options = array())
	{
		$this->_removeRedshopCart();
	}

	/**
	 * This method should handle any logout logic and report back to the subject
	 *
	 * @param   array  $user     Holds the user data.
	 * @param   array  $options  Array holding options(client, ...)
	 *
	 * @return  object  True on success
	 *
	 * @since	1.5
	 */
	public function onUserLogout($user, $options = array())
	{
		$this->_removeRedshopCart();
	}

	/**
	 * Common function to remove cart from DB for specific shopper groups
	 *
	 * @return  boolean  True on success
	 */
	protected function _removeRedshopCart()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
		JLoader::import('configuration', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
		JLoader::import('cart', JPATH_SITE . '/components/com_redshop/helpers');

		$Redconfiguration = new Redconfiguration;
		$Redconfiguration->defineDynamicVars();

		$rsCarthelper = new rsCarthelper;

		// Only unselected user can remove cart from DB
		if (!$this->getJoomlaUserUsingShopperGroups())
		{
			return $rsCarthelper->removecartfromdb();
		}
	}

	/**
	 * Get shopper group Id to Keep Cart alive
	 *
	 * @return  boolean  True for Valid user
	 */
	public function getJoomlaUserUsingShopperGroups()
	{
		// Lookup for Valid Shopper Group - Only allow shopper groups which are selected in param.
		$shopperGroupArray = $this->params->get('sgId');
		JArrayHelper::toInteger($shopperGroupArray);
		$shopperGroupIds = implode(',', $shopperGroupArray);

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
				->select($db->qn('user_id'))
				->from($db->qn('#__redshop_users_info'))
				->where($db->qn('shopper_group_id') . ' IN (' . $shopperGroupIds . ')')
				->where($db->qn('user_id') . '=' . (int) JFactory::getUser()->id);

		// Set the query and load the result.
		$db->setQuery($query);

		try
		{
			$isUserAllowed = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		return (boolean) $isUserAllowed;
	}
}
