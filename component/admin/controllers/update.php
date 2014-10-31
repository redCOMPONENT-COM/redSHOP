<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopControllerUpdate
 *
 * @since  1.4
 */
class RedshopControllerUpdate extends RedshopControllerLegacy
{
	/**
	 * Refresh page
	 *
	 * @return void
	 */
	public function refresh()
	{
		JFactory::getApplication()->redirect('index.php?option=com_redshop&view=update');
	}

	/**
	 * Update
	 *
	 * @return  void
	 */
	public function update()
	{
		// Set 300 seconds for execute script if tables very huge
		ini_set('max_execution_time', 300);
		$app = JFactory::getApplication();
		$model = $this->getModel('Update');

		ob_start();
		$return = $model->update();
		$syncOutput = ob_get_contents();
		ob_end_clean();

		if (empty($return['success']) || $return['success'] === false)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_UPDATE_FAILED'), 'error');
		}

		if (!empty($layoutOutput))
		{
			JFactory::getApplication()->enqueueMessage($syncOutput, 'message');
		}

		$messages = $app->getMessageQueue();
		$return['messages'] = array();

		if (is_array($messages))
		{
			foreach ($messages as $msg)
			{
				switch ($msg['type'])
				{
					case 'message':
						$typeMessage = 'success';
						break;
					case 'notice':
						$typeMessage = 'info';
						break;
					case 'error':
						$typeMessage = 'important';
						break;
					case 'warning':
						$typeMessage = 'warning';
						break;
					default:
						$typeMessage = $msg['type'];
				}

				$return['messages'][] = array('message' => $msg['message'], 'type_message' => $typeMessage);
			}
		}

		echo json_encode($return);

		$app->close();
	}
}
