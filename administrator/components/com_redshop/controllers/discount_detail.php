<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class discount_detailController extends RedshopCoreController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$layout = $this->input->get('layout');

		$this->input->set('view', 'discount_detail');

		if ($layout == 'product')
		{
			$this->input->set('layout', 'product');
		}
		else
		{
			$this->input->set('layout', 'default');
		}
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post   = $this->input->getArray($_POST);
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');
		$layout = $this->input->get('layout');

		$post ['start_date'] = strtotime($post ['start_date']);
		$post ['end_date']   = strtotime($post ['end_date']) + (23 * 59 * 59);

		$model = $this->getModel('discount_detail');

		if (isset($layout) && $layout == 'product')
		{
			$post ['discount_product_id'] = $cid[0];
			$row                          = $model->storeDiscountProduct($post);
			$did                          = $row->discount_product_id;
		}
		else
		{

			$post ['discount_id'] = $cid[0];
			$row                  = $model->store($post);
			$did                  = $row->discount_id;
		}
		if ($row)
		{
			$model->saveShoppers($did, $post['shopper_group_id']);
			$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_DISCOUNT_DETAIL');
		}
		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount_detail&task=edit&cid[]=' . $row->discount_id, $msg);
		}
		else
		{
			if (isset($layout) && $layout == 'product')
			{
				$this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
			}
			else
			{
				$this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
			}
		}
	}

	public function remove()
	{

		$option = $this->input->get('option');
		$layout = $this->input->get('layout');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('discount_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_DELETED_SUCCESSFULLY');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
		}
	}

	public function publish()
	{
		$layout = $this->input->get('layout');
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('discount_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_PUBLISHED_SUCCESSFULLY');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
		}
	}

	public function unpublish()
	{
		$layout = $this->input->get('layout');
		$option = $this->input->get('option');
		$cid    = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('discount_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_UNPUBLISHED_SUCCESSFULLY');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
		}
	}

	public function cancel()
	{
		$layout = $this->input->get('layout');
		$option = $this->input->get('option');

		$msg = JText::_('COM_REDSHOP_DISCOUNT_DETAIL_EDITING_CANCELLED');

		if (isset($layout) && $layout == 'product')
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount&layout=product', $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=discount', $msg);
		}
	}
}
