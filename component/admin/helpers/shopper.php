<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

class shoppergroup
{

	function list_all($name, $shopper_group_id, $selected_groups = Array(), $size = 1, $toplevel = true, $multiple = false, $disabledFields = array())
	{

		$db = jFactory::getDBO();
		$html = '';
		$q = "SELECT parent_id,shopper_group_id FROM #__" . TABLE_PREFIX . "_shopper_group ";
		if ($shopper_group_id)
		{
			$q .= "WHERE shopper_group_id='$shopper_group_id'";
		}
		$db->setQuery($q);
		$groups = $db->loadObjectList();

		if ($groups)
			$selected_groups[] = $groups[0]->parent_id;

		$multiple = $multiple ? "multiple=\"multiple\"" : "";
		$id = str_replace('[]', '', $name);
		$html .= "<select class=\"inputbox\" size=\"$size\" $multiple name=\"$name\" id=\"$id\">\n";
		if ($toplevel)
		{
			$html .= "<option value=\"0\"> -Top- </option>\n";
		}
		$html .= $this->list_tree($shopper_group_id, '0', '0', $selected_groups, $disabledFields);
		$html .= "</select>\n";

		return $html;

	}

	function list_tree($shopper_group_id = "", $cid = '0', $level = '0', $selected_groups = Array(), $disabledFields = Array(), $html = '')
	{

		$db = jFactory::getDBO();
		$level++;

		$q = "SELECT shopper_group_id, shopper_group_id,shopper_group_name,parent_id FROM  #__" . TABLE_PREFIX . "_shopper_group ";
		$q .= "WHERE #__" . TABLE_PREFIX . "_shopper_group.parent_id='$cid' AND shopper_group_id !='$shopper_group_id' ";

		//    $q .= "ORDER BY   #__".TABLE_PREFIX."_shopper_group.category_name ASC";

		$db->setQuery($q);
		$groups = $db->loadObjectList();

		for ($x = 0; $x < count($groups); $x++)
		{
			$group = $groups[$x];
			$child_id = $group->shopper_group_id;


			$selected = "";

			if (is_array($selected_groups))
			{
				if (in_array($child_id, $selected_groups))
				{
					$selected = "selected=\"selected\"";
				}

				$disabled = '';
				if (in_array($child_id, $disabledFields))
				{
					$disabled = 'disabled="disabled"';
				}
				if ($disabled != '' && stristr($_SERVER['HTTP_USER_AGENT'], 'msie'))
				{
					// IE7 suffers from a bug, which makes disabled option fields selectable
				}
				else
				{
					$html .= "<option $selected $disabled value=\"$child_id\">\n";
					for ($i = 0; $i < $level; $i++)
					{
						$html .= "&#151;";
					}
					$html .= "|$level|";
					$html .= "&nbsp;" . $group->shopper_group_name . "</option>";
				}
			}
			$html .= $this->list_tree($shopper_group_id, $child_id, $level, $selected_groups, $disabledFields);
		}
		return $html;
	}

	function getshopperGroupListArray($shopper_group_id = "", $cid = '0', $level = '0')
	{


		$db = jFactory::getDBO();
		$html = '';
		$level++;

		$q = "SELECT * FROM  #__" . TABLE_PREFIX . "_shopper_group ";
		$q .= "WHERE #__" . TABLE_PREFIX . "_shopper_group.parent_id='$cid' ";

		//	$q .= "ORDER BY   #__".TABLE_PREFIX."_shopper_group.ordering ASC";

		$db->setQuery($q);
		$groups = $db->loadObjectList();

		for ($x = 0; $x < count($groups); $x++)
		{
			$html = '';
			$group = $groups[$x];
			$child_id = $group->shopper_group_id;
			if ($child_id != $cid)
			{
				$grouplist[] = $group;
				for ($i = 0; $i < $level; $i++)
				{
					$html .= "&nbsp;&nbsp;&nbsp;";
				}
				$html .= "&nbsp;" . $group->shopper_group_name;
			}
			$group->shopper_group_name = $html;
			$GLOBALS['grouplist'][] = $group;
			$this->getshopperGroupListArray($shopper_group_id, $child_id, $level);
		}

		return $GLOBALS['grouplist'];
	}

	function getCategoryListReverceArray($cid = '0')
	{

		$db = jFactory::getDBO();

		$q = "SELECT c.shopper_group_id,c.category_name,cx.shopper_group_id,cx.parent_id FROM  #__" . TABLE_PREFIX . "_shopper_group as cx, #__" . TABLE_PREFIX . "_shopper_group as c ";
		$q .= "WHERE cx.shopper_group_id='" . $cid . "'";
		$q .= "and c.shopper_group_id = cx.parent_id";

		$db->setQuery($q);
		$groups = $db->loadObjectList();

		for ($x = 0; $x < count($groups); $x++)
		{
			$group = $groups[$x];
			$parent_id = $group->parent_id;
			$GLOBALS['catlist_reverse'][] = $group;
			$this->getCategoryListReverceArray($parent_id);
		}
		return $GLOBALS['catlist_reverse'];
	}
}

?>
