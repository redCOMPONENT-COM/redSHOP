<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('category', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');


/**
 * element for default product layout
 *
 * @package        Joomla
 * @subpackage     redshop
 * @since          1.5
 */
class JFormFieldcategory extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	public $type = 'category';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		$this->_cats = array();
		$name = $this->name;
		$control_name = $this->name;
		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$categories = $this->getCategoryListArray(0, 0, 0);
		array_unshift($categories, JHTML::_('select.option', '', '- ' . JText::_('COM_REDSHOP_SELECT_CATEGORY') . ' -', 'category_id', 'category_name'));

		return JHTML::_('select.genericlist', $categories, $name, 'class="inputbox"', 'category_id', 'category_name', $this->value, $name);
	}


	function getCategoryListArray($category_id = "", $cid = '0', $level = '0')
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$level++;

		$query->select('c.category_id, cx.category_child_id, cx.category_parent_id, c.category_name, c.category_description, c.published, ordering');
		$query->from('#__redshop_category AS c');
		$query->leftJoin('#__redshop_category_xref AS cx ON c.category_id=cx.category_child_id');
		$query->where('cx.category_parent_id=' . $cid);

		$db->setQuery($query);
		$cats = $db->loadObjectList();

		for ($x = 0; $x < count($cats); $x++)
		{
			$html = '';
			$cat = $cats[$x];
			$child_id = $cat->category_child_id;

			if ($child_id != $cid)
			{
				$catlist[] = $cat;

				for ($i = 0; $i < $level; $i++)
				{
					$html .= "-";
					if ($level != 1)
						$html .= "-";
				}

				$html .= $cat->category_name;
			}

			$cat->category_name = $html;
			$this->_cats[] = $cat;

			$this->getCategoryListArray($category_id, $child_id, $level);
		}

		return $this->_cats;
	}
}
