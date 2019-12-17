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
 * Import view.
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.0.3
 */
class RedshopViewImport extends RedshopViewAdmin
{
	/**
	 * @var   array
	 *
	 * @since  2.0.3
	 */
	protected $imports;

	/**
	 * Function display template
	 *
	 * @param   string  $tpl  name of template
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   2.0.3
	 */
	public function display($tpl = null)
	{
		$document = JFactory::getDocument();

		/** @var RedshopModelImport $model */
		$model = $this->getModel();

		$this->imports = $model->getImports();

		$document->setTitle(JText::_('COM_REDSHOP_DATA_IMPORT'));
		JToolBarHelper::title(JText::_('COM_REDSHOP_DATA_IMPORT'));

		parent::display($tpl);
	}
}
