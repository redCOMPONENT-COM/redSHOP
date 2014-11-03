<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewPassword extends RedshopView
{
	public function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$layout = JRequest::getVar('layout');
		$uid    = JRequest::getInt('uid', 0);
		$params = $app->getParams('com_redshop');

		if ($uid != 0)
		{
			$this->setLayout('setpassword');
		}
		else
		{
			if ($layout == 'token')
			{
				$this->setLayout('token');
			}
			else
			{
				$this->setLayout('default');
			}
		}

		parent::display($tpl);
	}
}
