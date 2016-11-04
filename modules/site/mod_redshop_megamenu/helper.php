<?php
/**
 * @package     Redshop.Site
 * @subpackage  mod_redshop_megamenu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_menu
 *
 * @package     Redshopb.Site
 * @subpackage  mod_redshopb_megamenu
 * @since       1.6.21
 */
class ModRedshopMegaMenuHelper
{
	protected static $categories = array();

	/**
	 * Get a list of parents categories items.
	 *
	 * @param   int  $categoryId  ID of parent category.
	 *
	 * @return  array             Categories tree
	 */
	public static function getCategories($categoryId)
	{
		if (isset(static::$categories[$categoryId]))
		{
			return static::$categories[$categoryId];
		}

		$categories = RedshopHelperCategory::getCategoryListArray($categoryId);

		if (empty($categories))
		{
			static::$categories[$categoryId] = array();

			return static::$categories[$categoryId];
		}

		$subCategories = array();

		// Get first sub-categories of parent category
		foreach ($categories as $category)
		{
			if ($category->category_parent_id != $categoryId)
			{
				continue;
			}

			$category->category_name = str_replace('- ', '', $category->category_name);

			$subCategories[] = $category;
		}

		// Get 1 more sub-level of sub-categories
		foreach ($subCategories as $subCategory)
		{
			$subCategory->sub_cat = array();

			foreach ($categories as $category)
			{
				if ($category->category_parent_id != $subCategory->category_id)
				{
					continue;
				}

				$category->category_name = str_replace('- ', '', $category->category_name);
				$category->image = Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE');

				if (!empty($category->category_full_image)
					&& (strpos($category->category_full_image, '.jpg') == true
					|| strpos($category->category_full_image, '.png') == true
					|| strpos($category->category_full_image, '.jpeg') == true))
				{
					$category->image = $category->category_full_image;
				}

				$subCategory->sub_cat[] = $category;
			}
		}

		static::$categories[$categoryId] = $subCategories;

		return static::$categories[$categoryId];
	}
}
