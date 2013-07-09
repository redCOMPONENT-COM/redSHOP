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

class newsletterViewnewsletter extends JView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$params = $app->getParams('com_redshop');

		$pathway  = $app->getPathway();
		$document = JFactory::getDocument();

		$pathway->addItem(JText::_('COM_REDSHOP_NEWSLETTER_SUBSCRIPTION'), '');

		$userdata = JRequest::getVar('userdata');
		$layout   = JRequest::getVar('layout');
		$user     = JFactory::getUser();

		$this->user = $user;
		$this->userdata = $userdata;
		$this->params = $params;

		if ($layout == 'thankyou')
		{
			$this->setLayout('thankyou');
		}

		parent::display($tpl);
	}
}
