<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewRedshop extends RedshopViewAdmin
{
	public $layout;

	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		$this->layout = JFactory::getApplication()->input->getCmd('layout', 'default');

		$menuhide = explode(",", Redshop::getConfig()->get('MENUHIDE'));

		JToolBarHelper::title(JText::sprintf('COM_REDSHOP_ADMIN_WELCOME', $user->name));

		if ($this->layout != "noconfig")
		{
			if (!in_array('COM_REDSHOP_STATISTIC', $menuhide))
			{
				JToolBarHelper::custom('statistic', 'redshop_statistic32', JText::_('COM_REDSHOP_STATISTIC'),
					JText::_('COM_REDSHOP_STATISTIC'), false, false
				);
			}

			if (!in_array('COM_REDSHOP_RESHOP_CONFIGURATION', $menuhide))
			{
				JToolBarHelper::custom('configuration', 'redshop_icon-32-settings', JText::_('COM_REDSHOP_CONFIG'),
					JText::_('COM_REDSHOP_CONFIG'), false, false
				);
			}
		}

		if ($user->authorise('core.manage', 'com_redshop'))
		{
			$this->access_rslt = new Redaccesslevel;
			$this->access_rslt = $this->access_rslt->checkaccessofuser($user->gid);
		}

		$model = $this->getModel();

		$this->newcustomers = $model->getNewcustomers();

		$this->neworders = $model->getNeworders();

		parent::display($tpl);
	}
}
