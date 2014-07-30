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

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/helper.php';
require_once JPATH_ROOT . '/components/com_redshop/helpers/user.php';

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

/**
 * Product Search plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  Search.RedshopProduct
 * @since       1.6
 */
class PlgSearchRedshop_Products extends JPlugin
{
	/**
	 * Content Search method
	 * The sql must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 *
	 * @param   string  $text      Target search string
	 * @param   string  $phrase    mathcing option, exact|any|all
	 * @param   string  $ordering  ordering option, newest|oldest|popular|alpha|category
	 * @param   mixed   $areas     An array if the search it to be restricted to areas, null if search all
	 *
	 * @return  array   Search Result array
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();

		// Load plugin params info
		$pluginParams = $this->params;

		$limit = $pluginParams->def('search_limit', 50);

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		$section = JText::_('COM_REDSHOP_PRODUCTS');

		// Prepare Extra Field Query.
		$extraQuery = $db->getQuery(true)
						->select('DISTINCT(' . $db->qn('itemid') . ')')
						->from($db->qn('#__redshop_fields_data'))
						->where($db->qn('section') . ' = 1');

		// Create the base select statement.
		$query = $db->getQuery(true)
				->select(
					array(
						$db->qn('product_id'),
						$db->qn('product_name', 'title'),
						$db->qn('product_number', 'number'),
						$db->qn('product_s_desc', 'text'),
						'"2" AS ' . $db->qn('browsernav'),
						'"Redshop Products" AS ' . $db->qn('section'),
						'"" AS ' . $db->qn('created')
					)
				)
				->from($db->qn('#__redshop_product'));

		$wheres = array();

		// Building Where clause
		switch ($phrase)
		{
			case 'exact':
				$text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);

				// Also search in Extra Field Data
				$extraQuery->where($db->qn('data_txt') . ' LIKE ' . $text);
				$whereAppend = ' OR ' . $db->qn('product_id') . ' IN (' . $extraQuery->__toString() . ')';

				$wheres = array();
				$wheres[] = $db->qn('product_name') . ' LIKE ' . $text;
				$wheres[] = $db->qn('product_number') . ' LIKE ' . $text;

				$where = '('
					. implode(' OR ', $wheres)
					. $whereAppend
					. ')';

				$query->where($where);

				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				$orsField = array();

				foreach ($words as $word)
				{
					$word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);

					$ors = array();
					$ors[] = $db->qn('product_name') . ' LIKE ' . $word;
					$ors[] = $db->qn('product_number') . ' LIKE ' . $word;

					$wheres[] = implode(' OR ', $ors);

					// Prepare extra field info where clause
					$orsField[] = $db->qn('data_txt') . ' LIKE ' . $word;
				}

				// Also search in Extra Field Data
				$extraQuery->where('(' . implode(' OR ', $orsField) . ')');
				$whereAppend = ' OR ' . $db->qn('product_id') . ' IN (' . $extraQuery->__toString() . ')';

				$where = '('
						. implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres)
						. $whereAppend
						. ')';

				$query->where($where);

				break;
		}

		// Bulding Ordering
		switch ($ordering)
		{
			case 'oldest':
				$query->order($db->qn('product_id') . ' ASC');

				break;

			case 'newest':
			default:
				$query->order($db->qn('product_id') . ' DESC');

				break;
		}

		// Shopper group - choose from manufactures Start
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		$whereaclProduct = "";

		if ($shopper_group_manufactures != "")
		{
			$query->where($db->qn('manufacturer_id') . ' IN (' . $shopper_group_manufactures . ')');
		}

		// Only published products
		$query->where($db->qn('published') . ' = 1');

		// Set the query and load the result.
		$db->setQuery($query, 0, $limit);

		try
		{
			$rows = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		$redhelper = new redhelper;
		$return    = array();

		foreach ($rows as $key => $row)
		{
			$Itemid    = $redhelper->getItemid($row->product_id);
			$row->href = "index.php?option=com_redshop&view=product&pid=" . $row->product_id . "&Itemid=" . $Itemid;

			$return[]  = $row;
		}

		return $return;
	}
}
