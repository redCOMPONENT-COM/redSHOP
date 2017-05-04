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
 * @since       2.0.4
 */
class RedshopUpdate204 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/update.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshopupdate.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/update.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/tax_group_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/tax_group_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/tax_group_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/tax_group/tmpl/default.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.4
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/update',
			JPATH_ADMINISTRATOR . '/components/com_redshop/extras/sh404sef/sef_ext',
			JPATH_ADMINISTRATOR . '/components/com_redshop/extras/sh404sef/meta_ext',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/barcode',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/tax_group_detail'
		);
	}

	/**
	 * Change images file name
	 *
	 * @param   array   $files  List files in image folder
	 * @param   string  $path   Path to folder
	 *
	 * @return  void
	 */
	protected function changeImageFileName(&$files, &$path)
	{
		if (empty($files))
		{
			return;
		}

		foreach ($files as $file)
		{
			$fileName = str_replace(array('%20', ' '), '-', $file);

			JFile::move($path . $file, $path . $fileName);
		}
	}

	/**
	 * Method for check database structure when update.
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public function checkDatabase()
	{
		$installDatabase = new RedshopInstallDatabase;
		$installDatabase->install();
	}

	/**
	 * Method for rename image files name to correct format.
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public function updateImageFileNames()
	{
		$db = JFactory::getDbo();

		/** Update DB */
		$fields = array(
			$db->qn('product_full_image') . ' = REPLACE(' . $db->qn('product_full_image') . ", '%20', '-')",
			$db->qn('product_full_image') . ' = REPLACE(' . $db->qn('product_full_image') . ", ' ', '-')",
			$db->qn('product_thumb_image') . ' = REPLACE(' . $db->qn('product_thumb_image') . ", '%20', '-')",
			$db->qn('product_thumb_image') . ' = REPLACE(' . $db->qn('product_thumb_image') . ", ' ', '-')"
		);

		$query = $db->getQuery(true)
			->update($db->qn('#__redshop_product'))
			->set($fields);
		$db->setQuery($query)->execute();

		/** Update Image Name */
		$files = JFolder::files(JPATH_SITE . '/components/com_redshop/assets/images/product/');
		$this->changeImageFileName($files, $path);

		$files = JFolder::files(JPATH_SITE . '/components/com_redshop/assets/images/product/thumb/');
		$this->changeImageFileName($files, $path);
	}

	/**
	 * Method for update menu item id if necessary.
	 *
	 * @return  void
	 *
	 * @since   2.0.4
	 */
	public function updateMenuItem()
	{
		$db = JFactory::getDbo();

		// For Blank component id in menu table-admin menu error solution - Get redSHOP extension id from the table
		$query = $db->getQuery(true)
			->select('extension_id')
			->from($db->qn('#__extensions'))
			->where($db->qn('name') . ' LIKE ' . $db->quote('%redshop'))
			->where($db->qn('element') . ' = ' . $db->quote('com_redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));

		$extensionId = $db->setQuery($query)->loadResult();

		// Check for component menu item entry
		$query->clear()
			->select('id,component_id')
			->from($db->qn('#__menu'))
			->where($db->qn('menutype') . ' = ' . $db->quote('main'))
			->where($db->qn('path') . ' LIKE ' . $db->quote('%redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));

		$menuItem = $db->setQuery($query)->loadObject();

		// If component Entry found and component_id is same as extension id - no need to update menu item
		$isUpdate = ($menuItem && $menuItem->component_id == $extensionId) ? false : true;

		if (!$isUpdate)
		{
			return;
		}

		$query->clear()
			->update($db->qn('#__menu'))
			->set($db->qn('component_id') . ' = ' . (int) $extensionId)
			->where($db->qn('menutype') . ' = ' . $db->quote('main'))
			->where($db->qn('path') . ' LIKE ' . $db->quote('%redshop'))
			->where($db->qn('type') . ' = ' . $db->quote('component'));

		// Set the query and execute the update.
		$db->setQuery($query)->execute();
	}
}
