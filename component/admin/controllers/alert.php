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
 * Class Redshop Controller Discount
 *
 * @since  1.5
 */
class RedshopControllerAlert extends RedshopController
{
	/**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  mixed            The model.
	 */
	public function getModel($name = 'Alert_detail', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Method for remove alert
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'), 500);
		}

		/** @var RedshopModelAlert_detail $model */
		$model = $this->getModel('alert_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ALERT_DELETED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=alert', $msg);
	}

	/**
	 * Method for publish
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'), 500);
		}

		/** @var RedshopModelAlert_detail $model */
		$model = $this->getModel('alert_detail');

		if (!$model->read($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ALERT_READ_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=alert', $msg);
	}

	/**
	 * Method for unpublish
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'), 500);
		}

		$model = $this->getModel('alert_detail');

		if (!$model->read($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_ALERT_UNREAD_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=alert', $msg);
	}
}
