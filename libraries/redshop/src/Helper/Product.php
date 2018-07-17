<?php
/**
 * @package     RedShop
 * @subpackage  Libraries
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Helper;

defined('_JEXEC') or die;

class Product
{
	/**
	 * @param   integer $productId Product id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getProductMedias($productId)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__redshop_media')
			->where($db->quoteName('section_id') . ' = ' . (int) $productId)
			->where($db->quoteName('media_section') . ' = ' . $db->quote('product'));

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * @param   integer $pid Product id
	 *
	 * @return  mixed
	 *
	 * @since   2.1.0
	 */
	public static function getProductCategories($pid)
	{
		$db    = \JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('name'))
			->from($db->qn('#__redshop_product_category_xref', 'pcx'))
			->leftjoin($db->qn('#__redshop_category', 'c') . ' ON ' . $db->qn('c.id') . ' = ' . $db->qn('pcx.category_id'))
			->where($db->qn('pcx.product_id') . ' = ' . $db->q((int) $pid))
			->order($db->qn('c.name'));

		return $db->setQuery($query)->loadObjectlist();
	}

	/**
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 * @throws  \Exception
	 */
	public static function getTemplateList()
	{
		$templates      = \RedshopHelperTemplate::getTemplate('product');
		$temps          = array();
		$temps[0]       = new \stdClass;
		$temps[0]->id   = "0";
		$temps[0]->name = \JText::_('COM_REDSHOP_ASSIGN_TEMPLATE');
		$templates      = @array_merge($temps, $templates);

		return \JHtml::_('select.genericlist', $templates, 'product_template',
			'class="inputbox" size="1"  onchange="return AssignTemplate()" ', 'id', 'name', 0
		);
	}

	/**
	 * @param   integer $categoryId Category id
	 *
	 * @return  string
	 *
	 * @since   2.1.0
	 */
	public static function getCategoriesList($categoryId)
	{
		$categories  = Categories::getCategories();
		$categories1 = array();

		foreach ($categories as $key => $value)
		{
			$categories1[$key]            = new \stdClass;
			$categories1[$key]->id        = $categories[$key]->id;
			$categories1[$key]->parent_id = $categories[$key]->parent_id;
			$categories1[$key]->title     = $categories[$key]->title;
			$treename                     = str_replace("&#160;&#160;&#160;&#160;&#160;&#160;", " ", $categories[$key]->treename);
			$treename                     = str_replace("<sup>", " ", $treename);
			$treename                     = str_replace("</sup>&#160;", " ", $treename);
			$categories1[$key]->treename  = $treename;
			$categories1[$key]->children  = $categories[$key]->children;
		}

		$temps              = array();
		$temps[0]           = new \stdClass;
		$temps[0]->id       = "0";
		$temps[0]->treename = \JText::_('COM_REDSHOP_SELECT_CATEGORY');
		$categories1        = @array_merge($temps, $categories1);

		return \JHTML::_('select.genericlist', $categories1, 'category_id',
			'class="inputbox" onchange="document.adminForm.submit();" ', 'id', 'treename', $categoryId
		);
	}
}
