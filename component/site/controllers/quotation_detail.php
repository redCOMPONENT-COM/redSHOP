<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminQuotation');
JLoader::load('RedshopHelperAdminMail');
JLoader::load('RedshopHelperHelper');

/**
 * Quotation Detail Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerQuotation_detail extends RedshopController
{
	/**
	 * update status function
	 *
	 * @access public
	 * @return void
	 */
	public function updatestatus()
	{
		$post   = JRequest::get('post');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$encr   = JRequest::getVar('encr');
		$model = $this->getModel('quotation_detail');

		$quotationHelper = new quotationHelper;
		$redshopMail     = new redshopMail;

		// Update Status
		$quotationHelper->updateQuotationStatus($post['quotation_id'], $post['quotation_status']);

		// Add Customer Note
		$model->addQuotationCustomerNote($post);

		$mailbool = $redshopMail->sendQuotationMail($post['quotation_id'], $post['quotation_status']);

		$msg = JText::_('COM_REDSHOP_QUOTATION_STATUS_UPDATED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=quotation_detail&quoid=' . $post['quotation_id'] . '&encr=' . $encr . '&Itemid=' . $Itemid, $msg);
	}

	/**
	 * checkout function
	 *
	 * @access public
	 * @return void
	 */
	public function checkout()
	{
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$post   = JRequest::get('post');
		$encr   = JRequest::getVar('encr');

		$quotationHelper = new quotationHelper;
		$model           = $this->getModel('quotation_detail');
		$session         = JFactory::getSession();
		$redhelper       = new redhelper;

		$cart = array();
		$cart['idx'] = 0;
		$session->set('cart', $cart);

		$quotationProducts = $quotationHelper->getQuotationProduct($post['quotation_id']);

		for ($q = 0; $q < count($quotationProducts); $q++)
		{
			$model->addtocart($quotationProducts[$q]);
		}

		$cart = $session->get('cart');

		$quotationDetail = $quotationHelper->getQuotationDetail($post['quotation_id']);
		$cart['customer_note'] = $quotationDetail->quotation_note;
		$cart['quotation_id']  = $quotationDetail->quotation_id;
		$cart['cart_discount'] = $quotationDetail->quotation_discount;
		$cart['quotation']     = 1;
		$session->set('cart', $cart);

		$model->modifyQuotation($quotationDetail->user_id);
		$Itemid = $redhelper->getCheckoutItemid();
		$this->setRedirect('index.php?option=com_redshop&view=checkout&quotation=1&encr=' . $encr . '&Itemid=' . $Itemid);
	}
}
