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
 * View Install
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.4
 */
class RedshopViewInstall extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $steps;

	/**
	 * @var  string
	 */
	public $installType;

	/**
	 * @var  array
	 */
	public $availableVersions;

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
	 * @since  2.0.4
	 */
	public function display($tpl = null)
	{
		$this->installType = JFactory::getApplication()->input->getString('install_type', 'install');

		JToolbarHelper::title(JText::_('COM_REDSHOP_INSTALL_TITLE'));

		/** @var RedshopModelInstall $model */
		$model = $this->getModel();

		$this->steps = $model->getSteps($this->installType);

		if ($this->installType == 'update')
		{
			$this->availableVersions = $model->getAvailableUpdate();
		}

		// Display the template
		parent::display($tpl);
	}
}
