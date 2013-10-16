<?php
/**
 * $ModDesc
 *
 * @version        $Id: helper.php $Revision
 * @package        modules
 * @subpackage     $Subpackage
 * @copyright      Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website    htt://landofcoder.com
 * @license        GNU General Public License version 2
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');

class JFormFieldRedshopcategory extends JFormField
{

	/**
	 * @access private
	 */
	var $_name = 'redshopcategory';

	protected function getInput()
	{
		$db           = JFactory::getDbo();
		$this->_cats  = array();
		$name         = $this->name;
		$control_name = $this->name;
		// This might get a conflict with the dynamic translation - TODO: search for better solution
		$categories = $this->getCategoryListArray(0, 0, 0);
		array_unshift($categories, JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_CATEGORY') . ' -', 'category_id', 'category_name'));
		ob_start();
		$output = JHTML::_('select.genericlist', $categories, $name, 'class="inputbox"', 'category_id', 'category_name', $this->value, $name);
		ob_end_clean();

		return $output;
	}

	function getCategoryListArray($category_id = "", $cid = '0', $level = '0')
	{

		$db = JFactory::getDbo();
		$level++;

		$and = " AND cx.category_parent_id=". (int) $cid;

		$q = "SELECT c.category_id, cx.category_child_id, cx.category_parent_id "
			. ",c.category_name,c.category_description,c.published,ordering "
			. "FROM #__redshop_category AS c "
			. " ,#__redshop_category_xref AS cx "
			. "WHERE c.category_id=cx.category_child_id "
			. $and
			. " ORDER BY c.category_name ASC";

		$db->setQuery($q);
		$cats = $db->loadObjectList();

		for ($x = 0; $x < count($cats); $x++)
		{
			$html     = '';
			$cat      = $cats[$x];
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
			$this->_cats[]      = $cat;

			$this->getCategoryListArray($category_id, $child_id, $level);
		}

		return $this->_cats;
	}

}
