<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

/**
 * redSHOP Search Products
 *
 * @extends JPlugin
 *
 * @since   1.0.0
 */
class PlgSearchRedshop_Products extends JPlugin
{
	/**
	 * Auto load language
	 *
	 * @var  string
	 */
	protected $autoloadLanguage = true;

	/**
	 * Determine areas searchable by this plugin.
	 *
	 * @return  array  An array of search areas.
	 */
	public function onContentSearchAreas()
	{
		$areas = array(
			'redshop_products' => JText::_('PLG_SEARCH_REDSHOP_PRODUCTS_SECTION_NAME')
		);

		return $areas;
	}

	/**
	 * Search content (redSHOP Products).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search is to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 * @throws  Exception
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		$db = JFactory::getDbo();

		$section         = ($this->params->get('showSection')) ? JText::_('PLG_SEARCH_REDSHOP_PRODUCTS') : '';
		$searchShortDesc = $this->params->get('searchShortDesc', 0);
		$searchFullDesc  = $this->params->get('searchFullDesc', 0);

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
					'"' . $section . '" AS ' . $db->qn('section'),
					'"" AS ' . $db->qn('created'),
					'"2" AS ' . $db->qn('browsernav'),
					$db->qn('cat_in_sefurl')
				)
			)
			->from($db->qn('#__redshop_product'));

		// Building Where clause
		switch ($phrase)
		{
			case 'exact':
				$text = $db->quote('%' . $db->escape($text, true) . '%', false);

				// Also search in Extra Field Data
				$extraQuery->where($db->qn('data_txt') . ' LIKE ' . $text);
				$whereAppend = ' OR ' . $db->qn('product_id') . ' IN (' . $extraQuery . ')';

				$wheres = array(
					$db->qn('product_name') . ' LIKE ' . $text,
					$db->qn('product_number') . ' LIKE ' . $text
				);

				if ($searchShortDesc)
				{
					$wheres[] = $db->qn('product_s_desc') . ' LIKE ' . $text;
				}

				if ($searchFullDesc)
				{
					$wheres[] = $db->qn('product_desc') . ' LIKE ' . $text;
				}

				$where = '(('
					. implode(' OR ', $wheres)
					. $whereAppend
					. '))';

				$query->where($where);

				break;

			case 'all':
			case 'any':
			default:
				$words    = explode(' ', $text);
				$wheres   = array();
				$orsField = array();

				foreach ($words as $word)
				{
					$word = $db->quote('%' . $db->escape($word, true) . '%', false);

					$ors = array(
						$db->qn('product_name') . ' LIKE ' . $word,
						$db->qn('product_number') . ' LIKE ' . $word
					);

					if ($searchShortDesc)
					{
						$ors[] = $db->qn('product_s_desc') . ' LIKE ' . $word;
					}

					if ($searchFullDesc)
					{
						$ors[] = $db->qn('product_desc') . ' LIKE ' . $word;
					}

					$wheres[] = implode(' OR ', $ors);

					// Prepare extra field info where clause
					$orsField[] = $db->qn('data_txt') . ' LIKE ' . $word;
				}

				// Also search in Extra Field Data
				$extraQuery->where('(' . implode(' OR ', $orsField) . ')');
				$whereAppend = ' OR ' . $db->qn('product_id') . ' IN (' . $extraQuery->__toString() . ')';

				$where = '(('
						. implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres)
						. $whereAppend
						. '))';

				$query->where($where);

				break;
		}

		// Building Ordering
		switch ($ordering)
		{
			case 'oldest':
				$query->order($db->qn('product_id') . ' ASC');
				break;

			case 'popular':
				$query->order($db->qn('visited') . ' ASC');
				break;

			case 'alpha':
				$query->order($db->qn('product_name') . ' ASC');
				break;

			case 'newest':
			default:
				$query->order($db->qn('product_id') . ' DESC');

				break;
		}

		// Shopper group - choose from manufactures Start
		$shopperGroupManufacturers = RedshopHelperShopper_Group::getShopperGroupManufacturers();

		if (!empty($shopperGroupManufacturers))
		{
			$query->where($db->qn('manufacturer_id') . ' IN (' . $shopperGroupManufacturers . ')');
		}

		// Only published products
		$query->where($db->qn('published') . ' = 1');

		// Set the query and load the result.
		$db->setQuery($query, 0, $this->params->def('search_limit', 50));

		try
		{
			$rows = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage(), $e->getCode());
		}

		$return = array();

		foreach ($rows as $key => $row)
		{
			$itemId    = RedshopHelperRouter::getItemId($row->product_id, $row->cat_in_sefurl);
			$row->href = "index.php?option=com_redshop&view=product&pid=" . $row->product_id . "&cid=" . $row->cat_in_sefurl . "&Itemid=" . $itemId;
			$return[]  = $row;
		}

		return $return;
	}
}
