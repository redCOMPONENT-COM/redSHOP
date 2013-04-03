<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class integrationController extends JController
{
	public function gbasedownload()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel("integration");

		if (!$model->gbasedownload())
		{
			$msg = JText::_("COM_REDSHOP_XML_DOESNOT_EXISTS");
			$app->redirect("index.php?option=com_redshop&view=integration&task=googlebase", $msg);
		}
	}
}
