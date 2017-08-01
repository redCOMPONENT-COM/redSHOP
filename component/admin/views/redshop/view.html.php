<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP Dashboard view
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.0
 */
class RedshopViewRedshop extends RedshopViewAdmin
{
	/**
	 * @var  string
	 */
	public $layout;

	/**
	 * @var  RedshopModelRedshop
	 */
	public $model;

	/**
	 * @var  array
	 */
	public $newcustomers;

	/**
	 * @var  array
	 */
	public $neworders;

	/**
	 * Display the States view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function display($tpl = null)
	{
		$user = JFactory::getUser();

		$this->layout = JFactory::getApplication()->input->getCmd('layout', 'default');

		$menuHide = explode(",", Redshop::getConfig()->get('MENUHIDE'));

		JToolBarHelper::title(JText::sprintf('COM_REDSHOP_ADMIN_WELCOME', $user->name));

		if ($this->layout != "noconfig")
		{
			if (!in_array('COM_REDSHOP_STATISTIC', $menuHide))
			{
				JToolBarHelper::custom('statistic', 'redshop_statistic32', JText::_('COM_REDSHOP_STATISTIC'),
					JText::_('COM_REDSHOP_STATISTIC'), false
				);
			}

			if (!in_array('COM_REDSHOP_RESHOP_CONFIGURATION', $menuHide))
			{
				JToolBarHelper::custom('configuration', 'redshop_icon-32-settings', JText::_('COM_REDSHOP_CONFIG'),
					JText::_('COM_REDSHOP_CONFIG'), false
				);
			}
		}

		if (!$user->authorise('core.manage', 'com_redshop'))
		{
			throw new Exception('COM_REDSHOP_ACCESS_ERROR_NOT_HAVE_PERMISSION');
		}

		$this->model        = $this->getModel();
		$this->newcustomers = $this->model->getNewcustomers();
		$this->neworders    = $this->model->getNeworders();

		// Check PDF plugin
		if (!RedshopHelperPdf::isAvailablePdfPlugins())
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_WARNING_MISSING_PDF_PLUGIN'), 'warning');
		}

		parent::display($tpl);
	}
}
