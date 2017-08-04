<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Access detail
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.6
 */
class RedshopControllerAccess extends RedshopControllerForm
{
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app  = JFactory::getApplication();
		$data = $app->input->get('jform', array(), 'Array');

		/** @var RedshopModelAccess $model */
		$model = $this->getModel();

		if ($model->save($data))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ACCESS_SAVE_SUCCESS', 'success'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ACCESS_SAVE_FAIL', 'error'));
		}

		$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=access', false));

		return true;
	}
}
