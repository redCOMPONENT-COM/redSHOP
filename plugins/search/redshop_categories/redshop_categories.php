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
 * redSHOP Search Categories
 *
 * @extends JPlugin
 *
 * @since   1.0.0
 */
class PlgSearchRedshop_Categories extends JPlugin
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
			'redshop_categories' => JText::_('PLG_SEARCH_REDSHOP_CATEGORIES_SECTION_NAME')
		);

		return $areas;
	}

	/**
	 * Search content (redSHOP Categories).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav.
	 *
	 * @param   string $text     Target search string.
	 * @param   string $phrase   Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string $ordering Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed  $areas    An array if the search is to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 * @throws  Exception
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		if (is_array($areas) && !array_intersect($areas, array_keys($this->onContentSearchAreas())))
		{
			return array();
		}

		$text = trim($text);

		if (empty($text))
		{
			return array();
		}

		$section         = $this->params->get('showSection') ? JText::_('PLG_SEARCH_REDSHOP_CATEGORIES') : '';
		$searchShortDesc = $this->params->get('searchShortDesc', 1);
		$searchFullDesc  = $this->params->get('searchFullDesc', 1);

		// Init variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select(
				array(
					$db->qn('id'),
					$db->qn('name', 'title'),
					$db->qn('short_description'),
					$db->qn('description', 'text'),
					$db->quote($section) . ' AS ' . $db->qn('section'),
					$db->quote('') . ' AS ' . $db->qn('created'),
					$db->quote('2') . ' AS ' . $db->qn('browsernav')
				)
			)
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1');

		switch ($phrase)
		{
			case 'exact':
				$text  = $db->q('%' . $db->escape($text, true) . '%', false);
				$where = array(
					$db->qn('name') . ' LIKE ' . $text
				);

				if ($searchShortDesc)
				{
					$where[] = $db->qn('short_description') . ' LIKE ' . $text;
				}

				if ($searchFullDesc)
				{
					$where[] = $db->qn('description') . ' LIKE ' . $text;
				}

				$query->where('(' . implode(' OR ', $where) . ')');

				break;

			case 'all':
			case 'any':
			default:
				$words  = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word  = $db->q('%' . $db->escape($word, true) . '%', false);
					$where = array(
						$db->qn('name') . ' LIKE ' . $word
					);

					if ($searchShortDesc)
					{
						$where[] = $db->qn('short_description') . ' LIKE ' . $word;
					}

					if ($searchFullDesc)
					{
						$where[] = $db->qn('description') . ' LIKE ' . $word;
					}

					$wheres[] = implode(' OR ', $where);
				}

				$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');

				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$query->order($db->qn('id') . ' ASC');
				break;

			case 'alpha':
				$query->order($db->qn('name') . ' ASC');
				break;

			case 'newest':
			default:
				$query->order($db->qn('id') . ' DESC');
		}

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
			$itemId    = RedshopHelperRouter::getItemId(0, $row->id);
			$row->href = "index.php?option=com_redshop&view=category&cid=" . $row->id . "&Itemid=" . $itemId;
			$return[]  = $row;
		}

		return $return;
	}
}
