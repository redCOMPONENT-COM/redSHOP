<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

//JPlugin::loadLanguage( 'plg_search_redshop_products' );
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/helper.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/user.php';
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

class plgSearchredshop_products extends JPlugin
{
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();
		$user = JFactory::getUser();

		$searchText = $text;

		// Load plugin params info
		$pluginParams = $this->params;

		$limit = $pluginParams->def('search_limit', 50);

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		$section = JText::_('COM_REDSHOP_Products');

		$wheres = array();

		switch ($phrase)
		{
			case 'exact':
				$text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'a.product_name LIKE ' . $text;
				$wheres2[] = 'a.product_number LIKE ' . $text;
				$wheres2[] = 'c.data_txt LIKE ' . $text;
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
					$wheres2[] = 'a.product_name LIKE ' . $word;
					$wheres2[] = 'a.product_number LIKE ' . $word;
					$wheres2[] = 'c.data_txt LIKE ' . $word;
					$wheres[] = implode(' OR ', $wheres2);
				}

				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 'a.product_id ASC';
				break;

			case 'newest':

			default:
				$order = 'a.product_id DESC';
		}

		// Shopper group - choose from manufactures Start

		$rsUserhelper = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		$whereaclProduct = "";

		if ($shopper_group_manufactures != "")
		{
			$whereaclProduct = " AND a.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		// Shopper group - choose from manufactures End

		$query = 'SELECT c.data_txt as customtxt,a.product_id,a.product_name AS title,a.product_number as number,a.product_s_desc AS text,'

			. ' "2" AS browsernav,"Redshop product" as section,"" as created'
			. ' FROM #__redshop_product AS a LEFT join #__redshop_fields_data As c on c.itemid = a.product_id'

			. ' WHERE (' . $where . ') ' . $whereaclProduct . ''
			. ' AND a.published = 1'

			. ' ORDER BY ' . $order;
		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		$redhelper = new redhelper;

		foreach ($rows as $key => $row)
		{
			$Itemid = $redhelper->getItemid($row->product_id);
			$rows[$key]->href = "index.php?option=com_redshop&view=product&pid=" . $row->product_id . "&Itemid=" . $Itemid;
		}

		$return = array();

		foreach ($rows AS $key => $weblink)
		{
			if (searchHelper::checkNoHTML($weblink, $searchText, array('url', 'text', 'title', 'number', 'customtxt')))
			{
				$return[] = $weblink;
			}
		}

		return $return;
	}
}
