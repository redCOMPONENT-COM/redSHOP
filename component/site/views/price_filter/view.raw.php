<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewPrice_filter extends RedshopView
{
	public function display($tpl = null)
	{
		$prdlist = $this->get('Data');

		$this->prdlist = $prdlist;

		parent::display($tpl);
	}
}
