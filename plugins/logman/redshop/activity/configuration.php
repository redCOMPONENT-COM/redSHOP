<?php
/**
 * @package     LOGman
 * @copyright   Copyright (C) 2011 - 2016 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */

/**
 * User/Users Activity Entity
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Joomlatools\Plugin\LOGman
 */
class PlgLogmanRedshopActivityConfiguration extends ComLogmanModelEntityActivity
{
	protected function _initialize(KObjectConfig $config)
	{
		$config->append(
			array('format' => '{actor} {action} {object.type} in {object}')
			);

		parent::_initialize($config);
	}

	protected function _objectConfig(KObjectConfig $config)
	{
		$config->append(array('url' => array('admin' => 'option=com_redshop&view=configuration')));
		parent::_objectConfig($config);
	}
}