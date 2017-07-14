<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewRatings extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');

		$detail     = $this->get('data');
		$pagination = $this->get('pagination');

		$this->detail = $detail;
		$this->pagination = $pagination;
		$this->params = $params;
		parent::display($tpl);
	}
}
