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
 * Import from VirtueMart view.
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       __DEPLOY_VERSION__
 */
class RedshopViewImport_Vm extends RedshopViewAdmin
{
	/**
	 * @var    boolean
	 * @since  __DEPLOY_VERSION__
	 */
	protected $checkVirtuemart;

	/**
	 * @var    JModelLegacy
	 * @since  __DEPLOY_VERSION__
	 */
	protected $model;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		$this->checkVirtuemart = (boolean) JComponentHelper::isEnabled('com_virtuemart');
		$this->model           = $this->getModel('Import_VM');

		$document->setTitle(JText::_('COM_REDSHOP_IMPORT_FROM_VM'));
		JToolBarHelper::title(JText::_('COM_REDSHOP_IMPORT_FROM_VM'), 'redshop_import48');

		parent::display($tpl);
	}
}
