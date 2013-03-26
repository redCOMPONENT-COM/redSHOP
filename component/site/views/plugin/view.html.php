<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.view');

class pluginViewplugin extends JView
{
	public function display($tpl = null)
	{
		ob_clean();

		// Flush();
		parent::display($tpl);
	}
}
