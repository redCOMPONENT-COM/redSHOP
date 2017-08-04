<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerWrapper extends RedshopController
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
	public function getModel($name = 'Wrapper_detail', $prefix = 'RedshopModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function remove()
	{
		$showall = $this->input->get('showall', '0');
		$tmpl    = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$product_id = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('wrapper_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_WRAPPER_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
	}

	/**
	 * logic for publish
	 *
	 * @access public
	 * @return void
	 */
	public function publish()
	{
		$showall = $this->input->get('showall', '0');
		$tmpl    = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$product_id = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('wrapper_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_WRAPPER_PUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
	}

	/**
	 * logic for unpublish
	 *
	 * @access public
	 * @return void
	 */
	public function unpublish()
	{
		$showall = $this->input->get('showall', '0');
		$tmpl    = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$product_id = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('wrapper_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_WRAPPER_UNPUBLISHED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
	}

	public function enable_defaultpublish()
	{
		$showall = $this->input->get('showall', '0');
		$tmpl    = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$product_id = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('wrapper_detail');

		if (!$model->enable_defaultpublish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USE_TO_ALL_ENABLE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
	}

	public function enable_defaultunpublish()
	{
		$showall = $this->input->get('showall', '0');
		$tmpl    = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$product_id = $this->input->get('product_id');
		$cid        = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('wrapper_detail');

		if (!$model->enable_defaultpublish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USE_TO_ALL_DISABLE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrapper&showall=' . $showall . $tmpl . '&product_id=' . $product_id, $msg);
	}
}
