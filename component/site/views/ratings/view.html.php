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

class ratingsViewratings extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');

		$detail     = $this->get('data');
		$pagination = $this->get('pagination');

		$this->assignRef('detail', $detail);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('params', $params);
		parent::display($tpl);
	}
}
