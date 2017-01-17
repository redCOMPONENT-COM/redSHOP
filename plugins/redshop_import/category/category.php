<?php
/**
 * @package     RedShop
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Plugin\AbstractImportPlugin;

JLoader::import('redshop.library');

/**
 * Plugin redSHOP Import Category
 *
 * @since  1.0
 */
class PlgRedshop_ImportCategory extends AbstractImportPlugin
{
	/**
	 * @var string
	 */
	protected $primaryKey = 'category_id';

	/**
	 * Event run when user load config for export this data.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Config()
	{
		RedshopHelperAjax::validateAjaxRequest();

		return '';
	}

	/**
	 * Event run when run importing.
	 *
	 * @return  string
	 *
	 * @since  1.0.0
	 */
	public function onAjaxCategory_Import()
	{
		RedshopHelperAjax::validateAjaxRequest();

		$input           = JFactory::getApplication()->input;
		$this->encoding  = $input->getString('encoding', 'UTF-8');
		$this->separator = $input->getString('separator', ',');
		$this->folder    = $input->getCmd('folder', '');

		return $this->importing();
	}

	/**
	 * Method for get table object.
	 *
	 * @return  \JTable
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTable()
	{
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		return JTable::getInstance('Category_Detail', 'Table');
	}

	/**
	 * Process import data.
	 *
	 * @param   \JTable  $table  Header array
	 * @param   array    $data   Data array
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function processImport($table, $data)
	{
		if (!parent::processImport($table, $data))
		{
			return false;
		}

		// Update category parent
		$db = $this->db;
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from($db->qn('#__redshop_category_xref'))
			->where($db->qn('category_parent_id') . ' = ' . $data['category_parent_id'])
			->where($db->qn('category_child_id') . ' = ' . $table->category_id);
		$result = $db->setQuery($query)->loadResult();

		if ($result)
		{
			return true;
		}

		// Remove existing
		$query->clear()
			->delete($db->qn('#__redshop_category_xref'))
			->where($db->qn('category_child_id') . ' = ' . $table->category_id);
		$db->setQuery($query)->execute();

		$query->clear()
			->insert($db->qn('#__redshop_category_xref'))
			->values($data['category_parent_id'] . ',' . $table->category_id);

		$db->setQuery($query)->execute();

		return true;
	}
}
