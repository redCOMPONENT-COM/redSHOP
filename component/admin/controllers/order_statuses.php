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
 * Order statuses controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       2.0.3
 */
class RedshopControllerOrder_Statuses extends RedshopControllerAdmin
{
	/**
	 * Removes an item.
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function delete()
	{
		$cids = JFactory::getApplication()->input->get('cid', array(), 'array');
		$plugins = JPluginHelper::getPlugin('redshop_payment', '');

		if (!is_array($cids) || count($cids) < 1)
		{
			JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
		}
		else
		{
			$validateOrderStatus = array('C', 'PR', 'S', 'APP', 'P');
			$i                   = 0;

			foreach ($cids as $cid)
			{
				$orderStatusCode = RedshopEntityOrder_Status::getInstance($cid)->getItem()->order_status_code;
				foreach ($plugins as $plugin)
				{
					$params = json_decode($plugin->params);
					if ($params->verify_status == $orderStatusCode ||
						$params->invalid_status == $orderStatusCode ||
						in_array($orderStatusCode, $validateOrderStatus))
					{
						$this->setMessage(
							JText::sprintf('COM_REDSHOP_ORDER_STATUS_ERROR_DELETE_PLEASE_SET_PLUGIN', $plugin->name),
							'error');
						$i++;
					}
				}
			}
		}

		if ($i == 0)
		{
			parent::delete();
		}

		// Set redirect
		$this->setRedirect($this->getRedirectToListRoute());
	}
}
