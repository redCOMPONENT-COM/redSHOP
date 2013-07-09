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

class price_filterViewprice_filter extends JView
{
	public function display($tpl = null)
	{
		$prdlist = $this->get('Data');

		$this->prdlist = $prdlist;

		parent::display($tpl);
	}
}
