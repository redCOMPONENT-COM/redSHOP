<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class integrationController extends JController
{
	function __construct($default = array())
	{
		parent::__construct($default);
	}

	function display()
	{

		parent::display();
	}

	function gbasedownload()
	{
		global $mainframe;
		$model = $this->getModel("integration");
		if (!$model->gbasedownload())
		{
			$msg = JText::_("COM_REDSHOP_XML_DOESNOT_EXISTS");
			$mainframe->redirect("index.php?option=com_redshop&view=integration&task=googlebase", $msg);
		}
	}
}
