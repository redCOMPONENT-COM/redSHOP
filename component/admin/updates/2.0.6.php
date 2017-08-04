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
 * @since       2.0.6
 */
class RedshopUpdate206 extends RedshopInstallUpdate
{
	/**
	 * Return list of old files for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	protected function getOldFiles()
	{
		return array(
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redaccesslevel.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/thumbnail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/accessmanager_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/category_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/category_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/controllers/fields_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/models/fields_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/tables/fields_detail.php',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/configuration/tmpl/default_analytics.php'
		);
	}

	/**
	 * Return list of old folders for clean
	 *
	 * @return  array
	 *
	 * @since   2.0.6
	 */
	protected function getOldFolders()
	{
		return array(
			JPATH_LIBRARIES . '/redshop/economic',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/accessmanager_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/category_detail',
			JPATH_ADMINISTRATOR . '/components/com_redshop/views/fields_detail'
		);
	}

	/**
	 * Method to update new structure for Category
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public function updateCategory()
	{
		$db    = JFactory::getDbo();
		$check = RedshopHelperCategory::getRootId();

		if (!empty($check))
		{
			return;
		}

		$root            = new stdClass;
		$root->name      = 'ROOT';
		$root->parent_id = 0;
		$root->level     = 0;
		$root->lft       = 0;
		$root->rgt       = 1;
		$root->published = 1;
		$db->insertObject('#__redshop_category', $root);

		$rootId = $db->insertid();

		$query      = $db->getQuery(true)
			->select('c.*')
			->select($db->qn('cx.category_parent_id', 'parent_id'))
			->from($db->qn('#__redshop_category', 'c'))
			->leftJoin($db->qn('#__redshop_category_xref', 'cx') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('cx.category_child_id'));
		$categories = $db->setQuery($query)->loadObjectList();

		foreach ($categories as $key => $category)
		{
			if ($category->name == 'ROOT')
			{
				continue;
			}

			$parentId = ($category->parent_id == 0) ? $rootId : $category->parent_id;
			$alias    = JFilterOutput::stringUrlUnicodeSlug($category->name);

			$fields = array(
				$db->qn('parent_id') . ' = ' . $db->q((int) $parentId),
				$db->qn('alias') . ' = ' . $db->q($alias)
			);

			$conditions = array(
				$db->qn('id') . ' = ' . $db->q((int) $category->id)
			);

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_category'))
				->set($fields)
				->where($conditions);

			$db->setQuery($query)->execute();
		}

		if ($this->processRebuildCategory($rootId))
		{
			$this->processDeleteCategoryXrefTable();
		}
	}

	/**
	 * Method to update new structure for Category
	 *
	 * @param   int  $rootId  Root ID
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	protected function processRebuildCategory($rootId)
	{
		/** @var RedshopTableCategory $table */
		$table = RedshopTable::getInstance('Category', 'RedshopTable');

		return $table->rebuild($rootId);
	}

	/**
	 * Method to update new structure for Category
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 */
	protected function processDeleteCategoryXrefTable()
	{
		$db = JFactory::getDbo();

		return $db->setQuery('DROP TABLE IF EXISTS ' . $db->qn('#__redshop_category_xref'))
			->execute();
	}
}
