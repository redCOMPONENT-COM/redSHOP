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
	 * @var    array
	 *
	 * @since  2.1
	 */
	public $tags = array(
		'{category_name}',
		'{category_short_description}',
		'{category_short_desc}',
		'{categoryshortdesc}',
		'{category_description}',
		'{categorydesc}',
	);

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
			$this->addReplace('{category_name}', $category->category_name);
			$this->addReplace('{category_short_description}', $category->category_short_description);
			$this->addReplace('{category_short_desc}', $category->category_short_description);
			$this->addReplace('{categoryshortdesc}', $category->category_short_description);
			$this->addReplace('{category_description}', $category->category_description);
			$this->addReplace('{categorydesc}', $category->category_description);
		}
	}
}