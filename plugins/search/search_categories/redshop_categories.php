<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */


// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

//JPlugin::loadLanguage( 'plg_search_redshop_categories' );
require_once(JPATH_ROOT . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'helper.php');
class plgSearchredshop_categories extends JPlugin
{
	function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();

		$searchText = $text;

		// load plugin params info
		//$plugin =& JPluginHelper::getPlugin('search', 'redshop_categories');
		$pluginParams = $this->params;

		$limit = $pluginParams->def('search_limit', 50);

		$text = trim($text);
		if ($text == '')
		{
			return array();
		}
		$section = JText::_('COM_REDSHOP_Categories');

		$wheres = array();
		switch ($phrase)
		{
			case 'exact':
				$text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'a.category_name LIKE ' . $text;
				$wheres2[] = 'a.category_short_description LIKE ' . $text;
				$wheres2[] = 'a.category_description LIKE ' . $text;

				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word)
				{
					$word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = 'a.category_name LIKE ' . $word;
					$wheres2[] = 'a.category_short_description LIKE ' . $word;
					$wheres2[] = 'a.category_description LIKE ' . $word;

					$wheres[] = implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 'a.category_id ASC';
				break;

			case 'newest':

			default:
				$order = 'a.category_id DESC';
		}

		$query = 'SELECT a.category_id,a.category_name AS title, a.category_short_description, a.category_description AS text,'

			. ' "2" AS browsernav,"Redshop Categories" as section,"" as created'
			. ' FROM #__redshop_category AS a'

			. ' WHERE (' . $where . ')'
			. ' AND a.published = 1'


			. ' ORDER BY ' . $order;

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		$redhelper = new redhelper();
		foreach ($rows as $key => $row)
		{
			$Itemid = $redhelper->getItemid($row->category_id);
			$rows[$key]->href = "index.php?option=com_redshop&view=category&cid=" . $row->category_id . "&Itemid=" . $Itemid;
		}

		$return = array();
		foreach ($rows AS $key => $weblink)
		{
			if (searchHelper::checkNoHTML($weblink, $searchText, array('url', 'text', 'title')))
			{
				$return[] = $weblink;
			}
		}

		return $return;
	}
}	
