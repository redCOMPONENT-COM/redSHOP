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

class catalogViewcatalog extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');
		$layout = JRequest::getVar('layout');

		if ($layout == "sample")
		{
			$this->setLayout('sample');
		}

		$this->params = $params;
		parent::display($tpl);
	}
}
