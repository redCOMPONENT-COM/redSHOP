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
 * Controller Import VirtueMart
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       __DEPLOY_VERSION__
 */
class RedshopControllerImport_Vm extends RedshopControllerAdmin
{
	/**
	 * @var string
	 */
	protected $logName = 'vm_sync.name';

	/**
	 * Sync category
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncCategory()
	{
		$this->sync('syncCategory');
	}

	/**
	 * Sync manufacturer
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncManufacturer()
	{
		$this->sync('syncManufacturer');
	}

	/**
	 * Sync shopper group
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncShopperGroup()
	{
		$this->sync('syncShopperGroup');
	}

	/**
	 * Sync user
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncUser()
	{
		$this->sync('syncUser');
	}

	/**
	 * Sync order status
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncOrderStatus()
	{
		$this->sync('syncOrderStatus');
	}

	/**
	 * Sync product
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncProduct()
	{
		$this->sync('syncProduct');
	}

	/**
	 * Sync order
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function syncOrder()
	{
		$this->sync('syncOrder');
	}

	/**
	 * Base method for sync data
	 *
	 * @param   string   $method  Method of model for execute the sync
	 *
	 * @return  void
	 * @throws  Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	private function sync($method = '')
	{
		RedshopHelperAjax::validateAjaxRequest();
		$app = JFactory::getApplication();

		/** @var \RedshopModelImport_Vm $model */
		$model = $this->getModel('Import_Vm');

		$index = $this->input->getInt('index');

		// Check method exist.
		if (!method_exists($model, $method))
		{
			$app->setHeader('status', 500);
			echo JText::sprintf('COM_REDSHOP_IMPORT_VM_ERROR_METHOD_NOT_EXIST', $method);
			$app->sendHeaders();
		}

		// Start sync process
		if (!$model->$method($index))
		{
			$app->setHeader('status', 500);
			echo json_encode(array('name' => $model->getState($this->logName), 'msg' => $model->getError()));
			$app->sendHeaders();
		}

		echo json_encode(array('name' => $model->getState($this->logName), 'msg' => ''));

		$app->close();
	}
}
