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
 * Export view class
 *
 * @since  2.0.3
 */
class RedshopViewExport extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $exports;

	/**
	 * Method to display export view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		/** @var RedshopModelExport $model */
		$model = $this->getModel('export');
		$this->exports = $model->getExports();

		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_EXPORT'));

		JToolBarHelper::title(JText::_('COM_REDSHOP_EXPORT_MANAGEMENT'));

		parent::display($tpl);
	}
}
