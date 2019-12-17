<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
		/** @scrutinizer ignore-deprecated */ JHtml::stylesheet('com_redshop/redshop.search.min.css', array(), true);
		/** @scrutinizer ignore-deprecated */ JHtml::script('com_redshop/redshop.search.min.js', false, true);

		$this->detail = $this->get('data');

		parent::display($tpl);
	}
}
