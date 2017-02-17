<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Shopper Group
 *
 * @since  2.0.3
 */
class RedshopHelperShopper_Group
{
	/**
	 * List all shopper group as dropdown list
	 *
	 * @param   string   $name            Name of dropdown list
	 * @param   integer  $shopperGroupId  ID of shopper group to display
	 * @param   array    $selectedGroups  Array of selected group
	 * @param   integer  $size            Size of dropdown list
	 * @param   boolean  $topLevel        Position align from top
	 * @param   boolean  $multiple        Is multiple select or not
	 * @param   array    $disabledFields  Disable some groups
	 *
	 * @return string    HTML of dropdown list to render
	 *
	 * @since  2.0.3
	 */
	public static function listAll($name, $shopperGroupId, $selectedGroups = array(), $size = 1, $topLevel = true, $multiple = false,
		$disabledFields = array())
	{
		$db   = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$html = '';

		$query->select($db->qn('parent_id'))
			->select($db->qn('shopper_group_id'))
			->from($db->qn('#__redshop_shopper_group'));

		if ($shopperGroupId)
		{
			$query->where($db->qn('shopper_group_id') . ' = ' . (int) $shopperGroupId);
		}

		$db->setQuery($query);
		$groups = $db->loadObjectList();

		if ($groups)
		{
			$selectedGroups[] = $groups[0]->parent_id;
		}

		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id       = str_replace('[]', '', $name);
		$html     .= "<select class=\"inputbox\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";

		if ($topLevel)
		{
			$html .= "<option value=\"0\"> -Top- </option>\n";
		}

		$html .= self::listTree($shopperGroupId, 0, 0, $selectedGroups, $disabledFields);
		$html .= "</select>\n";

		return $html;
	}

	/**
	 * List shopper group as option of dropdown list
	 *
	 * @param   integer  $shopperGroupId  Shopper group ID to display
	 * @param   integer  $cid             Parent ID
	 * @param   integer  $level           Position
	 * @param   array    $selectedGroups  Selected groups will be marked selected
	 * @param   array    $disabledFields  Disable groups
	 * @param   string   $html            Previous HTML
	 *
	 * @return  string  HTML to render <option></option>
	 *
	 * @since  2.0.3
	 */
	public static function listTree($shopperGroupId = 0, $cid = 0, $level = 0, $selectedGroups = array(), $disabledFields = array(), $html = '')
	{
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$level++;

		$query->select($db->qn(array('shopper_group_id', 'shopper_group_name', 'parent_id')))
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('parent_id') . ' = ' . (int) $cid)
			->where($db->qn('shopper_group_id') . ' != ' . (int) $shopperGroupId);

		$db->setQuery($query);
		$groups = $db->loadObjectList();

		for ($x = 0, $xn = count($groups); $x < $xn; $x++)
		{
			$group = $groups[$x];
			$childId = $group->shopper_group_id;

			$selected = "";

			if (is_array($selectedGroups))
			{
				if (in_array($childId, $selectedGroups))
				{
					$selected = "selected=\"selected\"";
				}

				$disabled = '';

				if (in_array($childId, $disabledFields))
				{
					$disabled = 'disabled="disabled"';
				}

				$html .= "<option $selected $disabled value=\"$childId\">\n";

				for ($i = 0; $i < $level; $i++)
				{
					$html .= "&#151;";
				}

				$html .= "|$level|";
				$html .= "&nbsp;" . $group->shopper_group_name . "</option>";
			}

			$html .= self::listTree($shopperGroupId, $childId, $level, $selectedGroups, $disabledFields);
		}

		return $html;
	}

	/**
	 * Get Shopper Group List as Array
	 *
	 * @param   integer  $shopperGroupId  Shopper Group ID to display
	 * @param   integer  $cid             Parent ID
	 * @param   integer  $level           Position
	 *
	 * @return array
	 *
	 * @since  2.0.3
	 */
	public static function getShopperGroupListArray($shopperGroupId = 0, $cid = 0, $level = 0)
	{
		$db  = JFactory::getDbo();
		$query = $db->getQuery(true);
		$level++;

		$query->select('*')
			->from($db->qn('#__redshop_shopper_group'))
			->where($db->qn('parent_id') . ' = ' . (int) $cid);

		$db->setQuery($query);
		$groups = $db->loadObjectList();

		for ($x = 0, $xn = count($groups); $x < $xn; $x++)
		{
			$html = '';
			$group = $groups[$x];
			$childId = $group->shopper_group_id;

			if ($childId != $cid)
			{
				for ($i = 0; $i < $level; $i++)
				{
					$html .= "&nbsp;&nbsp;&nbsp;";
				}

				$html .= "&nbsp;" . $group->shopper_group_name;
			}

			$group->shopper_group_name = $html;
			$GLOBALS['grouplist'][] = $group;
			self::getShopperGroupListArray($shopperGroupId, $childId, $level);
		}

		if (isset($GLOBALS['grouplist']))
		{
			return $GLOBALS['grouplist'];
		}

		return array();
	}

	/**
	 * Get Category List Reverce Array
	 *
	 * @param   integer  $cid  Parent ID
	 *
	 * @return  array
	 *
	 * @since  2.0.3
	 */
	public static function getCategoryListReverceArray($cid = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn(array('c.shopper_group_id', 'c.category_name', 'cx.shopper_group_id', 'cx.parent_id')))
			->from($db->qn('#__redshop_shopper_group', 'cx'))
			->leftJoin(
				$db->qn('#__redshop_shopper_group', 'c')
				. ' ON ' .
				$db->qn('c.shopper_group_id') . ' = ' . $db->qn('cx.parent_id')
			)
			->where($db->qn('cx.shopper_group_id') . ' = ' . (int) $cid);

		$db->setQuery($query);
		$groups = $db->loadObjectList();

		for ($x = 0, $xn = count($groups); $x < $xn; $x++)
		{
			$group = $groups[$x];
			$parent_id = $group->parent_id;
			$GLOBALS['catlist_reverse'][] = $group;
			self::getCategoryListReverceArray($parent_id);
		}

		return $GLOBALS['catlist_reverse'];
	}
}
