<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Updates
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Update class
 *
 * @package     Redshob.Update
 *
 * @since       1.3.3.1
 */
class RedshopUpdate1331 extends RedshopInstallUpdate
{
	/**
	 * Method to update schema table if necessary.
	 *
	 * @return  void
	 *
	 * @since   1.3.3.1
	 */
	public function updateDatabaseSchema()
	{
		$db          = JFactory::getDbo();
		$query       = $db->getQuery(true)
			->select($db->qn('extension_id'))
			->from($db->qn('#__extensions'))
			->where($db->qn('element') . ' = ' . $db->quote('com_redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));
		$componentId = $db->setQuery($query)->loadResult();

		// Skip if there are no redshop install
		if (!$componentId)
		{
			return;
		}

		$query->clear()
			->select($db->qn('version_id'))
			->from($db->qn('#__schemas'))
			->where($db->qn('extension_id') . ' = ' . $componentId);
		$result = $db->setQuery($query)->loadResult();

		// Skip if there are already schema
		if ($result)
		{
			return;
		}

		$query->clear()
			->insert($db->qn('#__schemas'))
			->columns($db->qn(array('extension_id', 'version_id')))
			->values($componentId . ',' . $db->quote('1.1.10'));

		$db->setQuery($query)->execute();
	}
}
