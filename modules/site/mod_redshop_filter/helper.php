<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redshop_filter
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_redshop_filter
 *
 * @since  2.0.0.4
 */
abstract class ModRedshopFilter
{
	/**
	 * This function will get range price of product from min to max
	 *
	 * @param   number  $cid              Default value is 0
	 * @param   number  $manufacturer_id  Default value is 0
	 *
	 * @return  array
	 */
	public static function getRangeMaxMin($cid = 0, $manufacturer_id = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$min = 0;
		$max = 0;

		$list = RedshopHelperCategory::getCategoryListArray($cid);
		$childCat = array($cid);

		foreach ($list as $key => $value)
		{
			$childCat[] = $value->id;
		}

		if (intval($cid) != 0)
		{
			$query->select($db->qn("cat.product_id"))
				->from($db->qn("#__redshop_product", "p"))
				->join("LEFT", $db->qn("#__redshop_product_category_xref", "cat") . " ON p.product_id = cat.product_id")
				->where($db->qn("cat.category_id") . " IN ( " . implode(',', $childCat) . ' )');

			// Filter by manufacture
			if (intval($manufacturer_id) !== 0)
			{
				$query->where($db->qn("p.manufacturer_id") . "=" . $db->q($manufacturer_id));
			}
		}
		else
		{
			$query->select($db->qn("product_id"))
				->from($db->qn("#__redshop_product", "p"));

			// Filter by manufacture
			if (intval($manufacturer_id) !== 0)
			{
				$query->where($db->qn("p.manufacturer_id") . "=" . $db->q($manufacturer_id));
			}
		}

		$pids = $db->setQuery($query)->loadAssocList("product_id");

		// Get only productid key
		$pids  = array_keys($pids);
		$range = self::getRange($pids);

		return $range;
	}

	/**
	 * This function will help get max and min value on list product price
	 *
	 * @param   array  $pids  default value is array
	 *
	 * @return array
	 */
	public static function getRange($pids = array())
	{
		$max = 0;
		$min = 0;
		$producthelper = new producthelper;
		$allProductPrices = array();

		if (!empty($pids))
		{
			// Get product price
			foreach ($pids as $k => $id)
			{
				$productprices = $producthelper->getProductNetPrice($id);
				$allProductPrices[] = $productprices['productPrice'];
			}

			// Get first value to make sure it won't zero value
			$max = $min = $allProductPrices[0];

			// Loop to check max min
			foreach ($allProductPrices as $k => $value)
			{
				// Check max
				if ($value >= $max)
				{
					$max = $value;
				}

				// Check min
				if ($value <= $min)
				{
					$min = $value;
				}
			}
		}

		$arrays = array(
			"max" => $max,
			"min" => $min
		);

		return $arrays;
	}

	/**
	 * Get all manufacturers based on category id
	 *
	 * @param   $catId  category id
	 *
	 * @return  object
	 */
	public static function getManufacturers($catId = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('m.media_name'))
			->select($db->qn('ma.manufacturer_name'))
			->select($db->qn('ma.manufacturer_id'))
			->from($db->qn('#__redshop_manufacturer', 'ma'))
			->leftjoin($db->qn('#__redshop_media', 'm') . ' ON ' . $db->qn('m.section_id')  . ' = ' . $db->qn('ma.manufacturer_id'))
			->where($db->qn('m.media_section') . ' = ' . $db->q('manufacturer'))
			->where($db->qn('m.published') . ' = 1')
			->where($db->qn('ma.published') . ' = 1');

		if (!empty($catId))
		{
			$manuList = self::getManufacturerIds($catId);

			if (!empty($manuList))
			{
				$query->where($db->qn('ma.manufacturer_id') . ' IN (' . implode(',', $manuList) . ')');

				return $db->setQuery($query)->loadObjectList();
			}
		}

		return array();
	}

	/**
	 * Get product id list
	 *
	 * @param   $catId  category id
	 *
	 * @return  mixed
	 */
	private static function getManufacturerIds($catId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product_category_xref'))
			->where($db->qn('category_id') . ' = ' . $db->q((int) $catId));

		$productIds = $db->setQuery($query)->loadColumn();

		if (!empty($productIds))
		{
			$query = $db->getQuery(true)
				->select($db->qn('manufacturer_id'))
				->from($db->qn('#__redshop_product'))
				->where($db->qn('product_id') . ' IN (' . implode(',', $productIds) . ')');

			return $db->setQuery($query)->loadColumn();
		}

		return array();
	}

	/**
	 * This method will get parent category redshop by category id
	 *
	 * @param   $cid  category id
	 *
	 * @return array
	 */
	public static function getParentCategory($cid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->select($db->qn('name'))
			->from($db->qn("#__redshop_category"))
			->where($db->qn("id") . ' = ' . $db->q((int) $cid))
			->where($db->qn("published") . " = 1");

		$data = $db->setQuery($query)->loadObjectList();

		foreach ($data as $key => $value)
		{
			if ($value->id != 0)
			{
				$child = self::getChildCategory($value->id);
				$data[$key]->child = $child;

				foreach ($child as $k => $subChild)
				{
					$sub = self::getChildCategory($subChild->id);
					$data[$key]->child[$k]->sub = $sub;
				}
			}
		}

		return $data;
	}

	/**
	 * This method will get child category redshop
	 *
	 * @param   $parentId  category parent id
	 *
	 * @return array
	 */
	public static function getChildCategory($parentId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->select($db->qn('name'))
			->from($db->qn("#__redshop_category"))
			->where($db->qn("parent_id") . ' = ' . $db->q((int) $parentId))
			->where($db->qn("published") . " = 1");

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Retrieve a list of article
	 *
	 * @param   $manuList  manufacturer ids
	 *
	 * @return  mixed
	 */
	public static function getManufacturerOnSale($manuList = NULL)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('m.media_name'))
			->select($db->qn('ma.manufacturer_name'))
			->select($db->qn('ma.manufacturer_id'))
			->from($db->qn('#__redshop_manufacturer', 'ma'))
			->leftJoin($db->qn('#__redshop_media', 'm') . ' ON ' . $db->qn('m.section_id') . ' = ' . $db->qn('ma.manufacturer_id'))
			->where('m.media_section = ' . $db->q('manufacturer'))
			->where($db->qn('m.published') . ' = 1')
			->where($db->qn('ma.published') . ' = 1');

		if (!empty($manuList))
		{
			$query->where($db->qn('ma.manufacturer_id') . ' IN (' . implode(',', $manuList) . ')');
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * This method will get parent category redshop
	 *
	 * @return array
	 */
	public static function getParentCategoryOnSale($catList = null, $rootCategory = 0, $saleCategory = null)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->select($db->qn('name'))
			->from($db->qn("#__redshop_category"))
			->where($db->qn("parent_id") . ' = ' . $db->q((int) $rootCategory));

		if (!empty($catList))
		{
			$query->where($db->qn('id') . ' IN (' . implode(',', $catList) . ')');

			if (!empty($saleCategory))
			{
				$query->where($db->qn('id') . ' != ' . $db->q((int) $saleCategory));
			}
		}

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * This method will get parent category redshop
	 *
	 * @return array
	 */
	public static function getCategorybyPids($pids = array(), $rootCategory = 0, $saleCategory = null)
	{
		$data = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		if (!empty($pids))
		{
			$query->select('category_id')
				->from($db->qn('#__redshop_product_category_xref'))
				->where($db->qn('product_id') . ' IN (' . implode(',', $pids) . ')');

			$cids = $db->setQuery($query)->loadColumn();
			$cids = array_merge(array(), array_unique($cids));

			$query = $db->getQuery(true)
				->clear()
				->select($db->qn('id'))
				->select($db->qn('name'))
				->from($db->qn("#__redshop_category"))
				->where($db->qn("parent_id") . ' = ' . $db->q((int) $rootCategory));

			if (!empty($cids))
			{
				$query->where($db->qn('id') . ' IN (' . implode(',', $cids) . ')');

				if (!empty($saleCategory))
				{
					$query->where($db->qn('id') . ' != ' . $db->q((int) $saleCategory));
				}
			}

			$data = $db->setQuery($query)->loadObjectList();

			foreach ($data as $key => $value)
			{
				if (!empty($value) && $value->id != 0)
				{
					$child = self::getChildCategory($value->id);
					$data[$key]->child = $child;

					foreach ($child as $k => $subChild)
					{
						$sub = self::getChildCategory($subChild->id);
						$data[$key]->child[$k]->sub = $sub;
					}
				}
			}

			return $data;
		}

		return $data;
	}

	/**
	 * get Manufacturer by id
	 *
	 * @param   $mid  manufacturer id
	 *
	 * @return  object
	 */
	public static function getManufacturerById($mid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('m.media_name, ma.manufacturer_name, ma.manufacturer_id')
			->from($db->qn('#__redshop_manufacturer', 'ma'))
			->leftJoin($db->qn('#__redshop_media', 'm') . ' ON m.section_id = ma.manufacturer_id')
			->where('m.media_section = ' . $db->q('manufacturer'))
			->where('m.published = 1')
			->where($db->qn('ma.manufacturer_id') . ' = ' . $db->q((int) $mid))
			->where('ma.published = 1');

		return $db->setQuery($query)->loadObjectList();
	}

	/**
	 * Get products by manufacturer id
	 *
	 * @param   integer  $mid  Manufacturer id
	 *
	 * @return  array
	 */
	public static function getProductByManufacturer($mid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('product_id'))
			->from($db->qn('#__redshop_product'))
			->where($db->qn('manufacturer_id') . ' = ' . $db->q((int) $mid));

		return $db->setQuery($query)->loadColumn();
	}
}
