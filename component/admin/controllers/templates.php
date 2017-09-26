<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Templates controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       2.0.7
 */
class RedshopControllerTemplates extends RedshopControllerAdmin
{
	/**
	 * Method to clone an existing supplier.
	 *
	 * @return  void
	 *
	 * @since   2.0.0.6
	 */
	public function duplicate()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$pks = $this->input->post->get('cid', array(), 'array');
		$pks = \Joomla\Utilities\ArrayHelper::toInteger($pks);

		try
		{
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(JText::plural('COM_REDSHOP_N_SUPPLIERS_DUPLICATED', count($pks)));
		}
		catch (Exception $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

		$this->setRedirect('index.php?option=com_redshop&view=templates');
	}
}
