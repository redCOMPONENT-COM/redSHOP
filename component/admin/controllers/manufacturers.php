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
 * Manufacturers list controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller.Manufacturers
 * @since       2.0.0.3
 */
class RedshopControllerManufacturers extends RedshopControllerAdmin
{

	/**
	 * Batch copy manufacturers
	 *
	 * @since  2.0.0.3
	 *
	 * @return  void
	 */
	public function copy()
	{
		$model = $this->getModel();
		$cids = JFactory::getApplication()->input->get('cid', array(), 'ARRAY');

		if ($cids)
		{
			foreach ($cids as $cid)
			{
				if (!$model->copy($cid))
				{
					JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MANUFACTURER_COPY_FAILED'), 'warning');
				}
			}

			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_MANUFACTURER_COPY_SUCCESSFUL'));

			$this->setRedirect(JRoute::_('index.php?option=com_redshop&view=manufacturers', false));
		}
	}

	/**
	 * Proxy to get RedshopModelManufacturers
	 *
	 * @param   string  $name    Model name
	 * @param   string  $prefix  Model prefix
	 * @param   array   $config  Configuration
	 *
	 * @return  object  JControllerAdmin
	 */
	public function getModel($name = 'Manufacturer', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}

