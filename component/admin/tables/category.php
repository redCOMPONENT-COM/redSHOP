<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Category
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       2.0.6
 */
class RedshopTableCategory extends RedshopTableNested
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_category';

	/**
	 * @var  integer
	 */
	public $id;

	/**
	 * @var  string
	 */
	public $name;

	/**
	 * @var  string
	 */
	public $category_thumb_image;

	/**
	 * @var  integer
	 */
	public $template;

	/**
	 * Called delete().
	 *
	 * @param   integer  $pk        The primary key of the node to delete.
	 * @param   boolean  $children  True to delete child nodes, false to move them up a level.
	 *
	 * @return  boolean  True on success.
	 */
	protected function doDelete($pk = null, $children = true)
	{
		$db = $this->getDbo();

		// Check child category
		$query = $db->getQuery(true)
			->select('COUNT(*) AS ctotal')
			->select($db->qn('name'))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('parent_id') . ' = ' . (int) $this->id);

		$childCount = $db->setQuery($query)->loadResult();

		if ($childCount > 0)
		{
			$this->setError(JText::sprintf('COM_REDSHOP_CATEGORY_PARENT_ERROR_MSG', $this->name, $this->id));

			return false;
		}

		// Check products
		$productCount = RedshopEntityCategory::getInstance($this->id)->productCount();

		if ($productCount > 0)
		{
			$this->setError(JText::sprintf('COM_REDSHOP_CATEGORY_EXIST_PRODUCT', $this->name, $this->id));

			return false;
		}

		// Remove thumb images.
		if (!empty($this->category_thumb_image))
		{
			$thumbPath = REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $this->category_thumb_image;

			if (JFile::exists($thumbPath))
			{
				JFile::delete($thumbPath);
			}
		}

		// Remove full images.
		if (!empty($this->category_full_image))
		{
			$fullImagePath = REDSHOP_FRONT_IMAGES_RELPATH . 'category/thumb/' . $this->category_full_image;

			if (JFile::exists($fullImagePath))
			{
				JFile::delete($fullImagePath);
			}
		}

		// Remove reference with products
		$query = $db->getQuery(true)
			->delete($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('category_id') . ' = ' . $this->id);
		$db->setQuery($query)->execute();

		// Force do not delete child categories
		return parent::doDelete($pk, false);
	}
}
