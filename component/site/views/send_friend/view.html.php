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

JLoader::load('RedshopHelperAdminCategory');

class RedshopViewSend_friend extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$id     = JRequest::getInt('id');
		$Itemid = JRequest::getInt('Itemid');
		$pid    = JRequest::getInt('pid');

		$params = $app->getParams('com_redshop');

		$pathway  = $app->getPathway();
		$document = JFactory::getDocument();

		// Include Javascript

		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('json.js', 'components/com_redshop/assets/js/', false);

		JHTML::Stylesheet('scrollable-navig.css', 'components/com_redshop/assets/css/');
		$data = $this->get('data');

		$template = $this->get('template');

		// Next/Prev navigation end

		$this->data = $data;
		$this->template = $template;

		$this->params = $params;
		parent::display($tpl);
	}
}
