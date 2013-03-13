<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');

class pluginViewplugin extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $context;
		ob_clean();
		// flush();
		parent::display($tpl);
	}
}
