<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Import from VirtueMart view.
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.0
 */
class RedshopViewImport_Vm extends RedshopViewAdmin
{
	/**
	 * @var    boolean
	 * @since  2.1.0
	 */
	protected $checkVirtuemart;

	/**
	 * @var    JModelLegacy
	 * @since  2.1.0
	 */
	protected $model;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 *
	 * @since   2.1.0
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
