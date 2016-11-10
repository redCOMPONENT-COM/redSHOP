<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1
 */
class RedshopTagsSectionsCategory extends RedshopTagsAbstract
{

	/**
	 * Init
	 *
	 * @return  void
	 *
	 * @since   2.1
	 */
	public function init()
	{
		if (isset($this->data['category']))
		{
			// TODO Allow to use alias tag instead duplicate tags
			$category = $this->data['category'];
			$this->_addReplace('{category_name}', $category->category_name);
			$this->_addReplace('{category_short_description}', $category->category_short_description);
			$this->_addReplace('{category_short_desc}', $category->category_short_description);
			$this->_addReplace('{categoryshortdesc}', $category->category_short_description);
			$this->_addReplace('{category_description}', $category->category_description);
			$this->_addReplace('{categorydesc}', $category->category_description);
		}
	}
}