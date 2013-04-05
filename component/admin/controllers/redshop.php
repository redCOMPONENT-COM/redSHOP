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

class redshopController extends JController
{
	public function demoContentInsert()
	{
		$model = $this->getModel();

		$model->demoContentInsert();
		$msg = JText::_('COM_REDSHOP_SAMPLE_DATA_INSTALLED');

		$this->setRedirect('index.php?option=com_redshop', $msg);
	}
}
