<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewSearch extends RedshopViewAdmin
{
	public function display($tpl = null)
	{
		$doc = JFactory::getDocument();

		$doc->addStyleSheet('components/com_redshop/assets/css/search.css');
		$doc->addScript('components/com_redshop/assets/js/search.js');

		$search_detail = $this->get('data');

		$this->detail = $search_detail;

		parent::display($tpl);
	}
}
