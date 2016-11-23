<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * redSHOP LOGman plugin.
 *
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanRedshop extends ComLogmanPluginJoomla
{

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

	private function _getPackage()
	{
		return 'redshop';
	}

	private function _getUniqueId ($args)
	{
		return md5(serialize($args) . serialize(JFactory::getUser()));
	}
}
