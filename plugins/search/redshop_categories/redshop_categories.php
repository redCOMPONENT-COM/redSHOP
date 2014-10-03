<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomlplugin.plugin');

JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperHelper');


class plgSearchRedshop_categories extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Search content (redSHOP Categories).
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
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$section    = '';

		if ($this->params->get('showSection'))
		{
			$section = JText::_('PLG_SEARCH_REDSHOP_CATEGORIES');
		}

		$text = trim($text);

		if ($text == '')
		{
			return array();
		}

		// Initialiase variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select(array(
					$db->qn('category_id'),
					$db->qn('category_name', 'title'),
					$db->qn('category_short_description'),
					$db->qn('category_description', 'text'),
					'"' . $section . '" AS ' . $db->qn('section'),
					'"" AS ' . $db->qn('created'),
					'"2" AS ' . $db->qn('browsernav')
				))
			->from($db->qn('#__redshop_category'))
			->where($db->qn('published') . ' = 1');

		switch ($phrase)
		{
			case 'exact':

				$text = $db->q('%' . $db->getEscaped($text, true) . '%', false);

				$where = array();
				$where[] = $db->qn('category_name') . ' LIKE ' . $text;
				$where[] = $db->qn('category_short_description') . ' LIKE ' . $text;
				$where[] = $db->qn('category_description') . ' LIKE ' . $text;

				$query->where('(' . implode(' OR ', $where) . ')');

				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();

				foreach ($words as $word)
				{
					$word = $db->q('%' . $db->getEscaped($word, true) . '%', false);

					$where = array();
					$where[] = $db->qn('category_name') . ' LIKE ' . $word;
					$where[] = $db->qn('category_short_description') . ' LIKE ' . $word;
					$where[] = $db->qn('category_description') . ' LIKE ' . $word;

					$wheres[] = implode(' OR ', $where);
				}

				$query->where('(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')');

				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$query->order($db->qn('category_id') . ' ASC');
				break;

			case 'newest':
			default:
				$query->order($db->qn('category_id') . ' DESC');
		}

		// Set the query and load the result.
		$db->setQuery($query, 0, $this->params->def('search_limit', 50));

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
			$Itemid    = $redhelper->getItemid($row->category_id);
			$row->href = "index.php?option=com_redshop&view=category&cid=" . $row->category_id . "&Itemid=" . $Itemid;

			$return[]  = $row;
		}

		return $return;
	}
}
