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
JLoader::import('redshop.library');
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperHelper');
JLoader::load('RedshopHelperUser');

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

class plgSearchRedshop_products extends JPlugin
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
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		$db = JFactory::getDbo();

		// Load plugin params info
		$pluginParams = $this->params;

		$limit = $pluginParams->def('search_limit', 50);

		$text  = trim($text);

		if ($text == '')
		{
			return array();
		}

		$section    = '';

		if ($this->params->get('showSection'))
		{
			$section = JText::_('PLG_SEARCH_REDSHOP_PRODUCTS');
		}

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
						'"2" AS ' . $db->qn('browsernav')
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
