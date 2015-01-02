<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_lettersearch
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');
JLoader::load('RedshopHelperHelper');

class modlettersearchHelper
{

	function getDefaultModulecharacters($selected_field)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT DISTINCT LEFT(fd.data_txt, 1) AS chars FROM #__redshop_fields AS f";
		$query .= " LEFT JOIN #__redshop_fields_data AS fd ON fd.fieldid = f.field_id";
		$query .= " WHERE f.field_id = " . (int) $selected_field . " AND  fd.section=1 ";
		$query .= " AND fd.data_txt IS NOT NULL  ORDER BY fd.data_txt";
		$db->setQuery($query);
		$characterlist = $db->loadObjectlist();

		return $characterlist;
	}
}
