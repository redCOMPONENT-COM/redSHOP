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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/category.php';

class send_friendViewsend_friend extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		// Request variables
		$id     = JRequest::getVar('id', null, '', 'int');
		$option = JRequest::getVar('option', 'com_redshop');
		$Itemid = JRequest::getVar('Itemid');
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
