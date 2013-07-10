<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class sample_catalogVIEWsample_catalog extends JView
{
	public function display($tpl = null)
	{
		$option = JRequest::getVar('option');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$model = $this->getModel('sample_catalog');

		$sample = $model->getsample($detail->colour_id);

		$this->detail = $detail;
		$this->sample = $sample;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
