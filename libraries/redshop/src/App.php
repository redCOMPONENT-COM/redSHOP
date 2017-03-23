<?php
/**
 * @package     RedSHOP
 * @subpackage  Library
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop;

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redshop App class
 *
 * @since  __DEPLOY_VERSION__
 */
class App
{
	/**
	 * Component option.
	 *
	 * @var  string
	 */
	protected static $component = 'com_redshop';

	/**
	 * @var    Registry
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $config;

	/**
	 * Method for get redSHOP Configuration
	 *
	 * @return   Registry  redSHOP Configuration in Registry format.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getConfig()
	{
		if (null === static::$config)
		{
			$db = \JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->qn(array('config', 'value')))
				->from($db->qn('#__redshop_configuration'))
				->order($db->qn('config'));

			static::$config = new Registry($db->setQuery($query)->loadAssocList('config', 'value'));
		}

		return static::$config;
	}
}
