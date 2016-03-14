<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminQuotation');
JLoader::load('RedshopHelperAdminProduct');

class RedshopControllerQuotation_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'quotation_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);
		parent::display();
	}

	public function save($send = 0)
	{
		$quotationHelper = new quotationHelper;
		$post = JRequest::get('post');


		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$post['quotation_id'] = $cid [0];
		$model = $this->getModel('quotation_detail');

		if ($post['quotation_id'] == 0)
		{
			$post['quotation_cdate'] = time();
			$post['quotation_encrkey'] = $quotationHelper->randomQuotationEncrkey();
		}

		if ($post['user_id'] == 0 && $post['quotation_email'] == "")
		{
			$msg = JText::_('COM_REDSHOP_CREATE_ACCOUNT_FOR_QUOTATION');
			$this->setRedirect('index.php?option=com_redshop&view=quotation_detail&task=edit&cid[]=' . $post['quotation_id'], $msg);
		}

		$quotation_item = array();
		$i = 0;

		foreach ($post as $key => $value)
		{
			if (!isset($quotation_item[$i]))
			{
				$quotation_item[$i] = new stdClass;
			}

			if (!strcmp("quotation_item_id", substr($key, 0, 17)))
			{
				$quotation_item[$i]->quotation_item_id = $value;
			}

			if (!strcmp("product_excl_price", substr($key, 0, 18)))
			{
				$quotation_item[$i]->product_excl_price = $value;
			}

			if (!strcmp("product_price", substr($key, 0, 13)))
			{
				$quotation_item[$i]->product_price = $value;
			}

			if (!strcmp("quantity", substr($key, 0, 8)) && strlen($key) < 12)
			{
				$quotation_item[$i]->product_quantity = $value;
				$quotation_item[$i]->product_final_price = $quotation_item[$i]->product_price * $quotation_item[$i]->product_quantity;
				$i++;
			}
		}

		$post['quotation_item'] = $quotation_item;
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
		}

		$quotation_status = $post['quotation_status'] > 0 ? $post['quotation_status'] : 2;

		$quotationHelper->updateQuotationStatus($row->quotation_id, $quotation_status);

		if ($send == 1)
		{
			if ($model->sendQuotationMail($row->quotation_id))
			{
				$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=quotation', $msg);
	}

	public function send()
	{
		$this->save(1);
	}

	public function remove()
	{

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('quotation_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=quotation', $msg);
	}

	public function deleteitem()
	{

		$qitemid = JRequest::getVar('qitemid', 0, 'request', 'int');
		$cid = JRequest::getVar('cid', array(0), 'request', 'array');

		$model = $this->getModel('quotation_detail');

		if (!$model->deleteitem($qitemid, $cid[0]))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_QUOTATION_ITEM_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=quotation_detail&task=edit&cid[]=' . $cid[0], $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=quotation', $msg);
	}

	public function newQuotationItem()
	{
		$adminproducthelper = new adminproducthelper;
		$post = JRequest::get('post');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		$model = $this->getModel('quotation_detail');

		$quotationItem = $adminproducthelper->redesignProductItem($post);

		$post['quotation_item'] = $quotationItem;

		if ($model->newQuotationItem($post))
		{
			$msg = JText::_('COM_REDSHOP_QUOTATION_ITEM_ADDED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_ADDING_QUOTATION_ITEM');
		}

		$this->setRedirect('index.php?option=com_redshop&view=quotation_detail&cid[]=' . $cid[0], $msg);
	}

	public function getQuotationPriceTax()
	{
		$producthelper = new producthelper;
		$get = JRequest::get('get');
		$product_id = $get['product_id'];
		$user_id = $get['user_id'];
		$newprice = $get['newprice'];
		$vatprice = 0;

		if ($newprice > 0)
		{
			$vatprice = $producthelper->getProductTax($product_id, $newprice, $user_id);
		}

		echo "<div id='newtax'>" . $vatprice . "</div>";
		exit;
	}
}
