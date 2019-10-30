<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Fields list controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Fields
 * @since       2.0.6
 */
class RedshopControllerFields extends RedshopControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   2.1.0
	 */
	public function getModel($name = 'Field', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Method for mass assign group to multiple fields
	 *
	 * @return  void
	 * @throws  Exception
	 */
	public function massAssignGroup()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();

		// Get items to remove from the request.
		$cid     = $app->input->get('cid', array(), 'array');
		$groupId = $app->input->getInt('field_assign_group', 0);

		if (!is_array($cid) || count($cid) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			/** @var RedshopModelField $model */
			$model = $this->getModel();

			if (!$model->massAssignGroup($cid, $groupId))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_FIELDS_ERROR_MASS_ASSIGN_GROUP'), 'error');
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_FIELDS_SUCCESS_MASS_ASSIGN_GROUP'));
			}
		}

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute());
	}
}
