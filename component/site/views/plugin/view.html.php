<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewPlugin extends RedshopView
{
	public function display($tpl = null)
	{
		ob_clean();

		// Flush();
		parent::display($tpl);
	}
}
