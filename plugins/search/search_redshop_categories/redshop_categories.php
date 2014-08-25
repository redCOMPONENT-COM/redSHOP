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

require_once JPATH_ROOT . '/components/com_redshop/helpers/helper.php';

class plgSearchredshop_categories extends JPlugin
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

	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db         = JFactory::getDbo();
		$user       = JFactory::getUser();
		$searchText = $text;
		$limit      = $this->params->def('search_limit', 50);
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

			. ' "2" AS browsernav,"' . $section . '" as section,"" as created'
			. ' FROM #__redshop_category AS a'

			. ' WHERE (' . $where . ')'
			. ' AND a.published = 1'

			. ' ORDER BY ' . $order;

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		$redhelper = new redhelper;

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
