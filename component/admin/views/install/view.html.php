<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * View Install
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewInstall extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $steps;

	/**
	 * Do we have to disable a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $disableSidebar = true;

	/**
	 * Display template function
	 *
	 * @param   object  $tpl  template variable
	 *
	 * @return  void
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$installType = JFactory::getApplication()->input->getString('install_type', 'install');

		JToolbarHelper::title(JText::_('COM_REDSHOP_INSTALL_TITLE'));

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		$this->steps = $model->getSteps($installType);

		// Display the template
		parent::display($tpl);
	}
}
