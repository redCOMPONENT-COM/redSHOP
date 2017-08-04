<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewSearch extends RedshopViewAdmin
{
	/**
	 * @param   string  $tpl  Layout
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$doc->addStyleSheet('components/com_redshop/assets/css/search.css');
		$doc->addScript('components/com_redshop/assets/js/search.js');

		$this->detail = $this->get('data');

		parent::display($tpl);
	}
}
