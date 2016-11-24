<?php
/**
 * @package    LOGman
 * @copyright  Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * redSHOP LOGman plugin.
 *
 * @package  Joomlatools\Plugin\LOGman
 *
 * @since    1.0.0
 */
class PlgLogmanRedshop extends ComLogmanPluginJoomla
{
	/**
	 * Trigger after saved configuration
	 *
	 * @param   JRegistry  $config  Configuration
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onAfterAdminSaveConfiguration($config)
	{
		$name = JText::_('COM_REDSHOP_REDSHOP');
		$type = 'configuration';

		$user = JFactory::getUser();
		$this->log(
			array(
				'object' => array(
					'package' => $this->_getPackage(),
					'type'    => $type,
					'id'      => $this->_getUniqueId($config),
					'name'    => $name,
				),
				'verb'   => 'save',
				'actor'  => $user->id,
				'result' => 'changed'
			)
		);
	}

	/**
	 * Get package
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	private function _getPackage()
	{
		return 'redshop';
	}

	/**
	 * Get unique ID
	 *
	 * @param   mixed  $args  Args
	 *
	 * @return  string
	 *
	 * @since   1.0.0
	 */
	private function _getUniqueId ($args)
	{
		return md5(serialize($args) . serialize(JFactory::getUser()));
	}
}
