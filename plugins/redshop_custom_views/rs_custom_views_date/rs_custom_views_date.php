<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

class plgredshop_custom_viewsrs_custom_views_date extends JPlugin
{
	public function getMenuLink()
	{
		$values = array();
		$values['name'] = "rs_custom_views_date";
		$values['title'] = "COM_REDSHOP_CUSTOM_VIEWS_DATE";

		return $values;
	}
}
