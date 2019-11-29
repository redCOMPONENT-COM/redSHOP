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
 * View Tool
 *
 * @package     RedSHOP.Backend
 * @subpackage  View
 * @since       2.1.0
 */
class RedshopViewTool_Update extends RedshopViewAdmin
{
	/**
	 * @var  array
	 */
	public $availableVersions;

	/**
	 * Display template function
	 *
	 * @param   string  $tpl  template variable
	 *
	 * @return  mixed
	 * @throws  Exception
	 *
	 * @since   2.1.0
	 */
	public function display($tpl = null)
	{
		/** @var RedshopModelTool $model */
		$model = $this->getModel();

		$this->availableVersions = $model->getAvailableUpdate();

		JFactory::getDocument()->setTitle(JText::_('COM_REDSHOP_TOOL'));
		JToolbarHelper::title(JText::_('COM_REDSHOP_TOOL'));

		// Display the template
		parent::display($tpl);
	}
}
