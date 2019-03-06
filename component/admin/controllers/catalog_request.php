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
 * Catalog request controller
 *
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 * @since       2.0.6
 */
class RedshopControllerCatalog_Request extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		/** @var RedshopModelCatalog_request $model */
		$model = $this->getModel('catalog_request');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(null, true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_REQUEST_BLOCK_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=catalog_request', $msg);
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelCatalog_request $model */
		$model = $this->getModel('catalog_request');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(null, true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_REQUEST_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=catalog_request', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		/** @var RedshopModelCatalog_request $model */
		$model = $this->getModel('catalog_request');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(null, true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_CATALOG_REQUEST_BLOCK_UNBLOCK_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=catalog_request', $msg);
	}
}
