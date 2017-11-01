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
 * Class Redshop Controller Discount
 *
 * @since  1.5
 */
class RedshopControllerDiscount extends RedshopController
{
	/**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  object  The model.
	 */
	public function getModel($name = 'Discount_detail', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function remove()
	{
		$layout = $this->input->getCmd('layout', '');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('discount_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
		}
	}

	public function publish()
	{
		$layout = $this->input->getCmd('layout', '');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('discount_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_PUBLISHED_SUCCESSFULLY');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
		}
	}

	public function unpublish()
	{
		$layout = $this->input->getCmd('layout', '');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('discount_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_UNPUBLISHED_SUCCESSFULLY');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=discount', $msg);
		}
	}
}
