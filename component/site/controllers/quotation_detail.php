<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


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
		$post = $this->input->post->getArray();

		$Itemid = $this->input->get('Itemid');
		$encr   = $this->input->get('encr');
		$model  = $this->getModel('quotation_detail');

		// Update Status
		RedshopHelperQuotation::updateQuotationStatus($post['quotation_id'], $post['quotation_status']);

		// Add Customer Note
		$model->addQuotationCustomerNote($post);

		Redshop\Mail\Quotation::sendMail($post['quotation_id'], $post['quotation_status']);

		$msg = JText::_('COM_REDSHOP_QUOTATION_STATUS_UPDATED_SUCCESSFULLY');

		$this->setRedirect('index.php?option=com_redshop&view=quotation_detail&quoid=' . $post['quotation_id'] . '&encr=' . $encr . '&Itemid=' . $Itemid, $msg);
	}

	/**
	 * checkout function
	 *
	 * @access public
	 * @return void
	 * @throws  Exception
	 */
	public function checkout()
	{
		$Itemid = $this->input->get('Itemid');
		$post   = $this->input->post->getArray();
		$encr   = $this->input->get('encr');

		$model   = $this->getModel('quotation_detail');
		$session = JFactory::getSession();

		$cart        = array();
		$cart['idx'] = 0;
		\Redshop\Cart\Helper::setCart($cart);

		$quotationProducts = RedshopHelperQuotation::getQuotationProduct($post['quotation_id']);

		for ($q = 0, $qn = count($quotationProducts); $q < $qn; $q++)
		{
			$model->addtocart($quotationProducts[$q]);
		}

		$cart = $session->get('cart');

		$quotationDetail       = RedshopHelperQuotation::getQuotationDetail($post['quotation_id']);
		$cart['customer_note'] = $quotationDetail->quotation_note;
		$cart['quotation_id']  = $quotationDetail->quotation_id;
		$cart['cart_discount'] = $quotationDetail->quotation_discount;
		$cart['quotation']     = 1;
		\Redshop\Cart\Helper::setCart($cart);

		$model->modifyQuotation($quotationDetail->user_id);
		$Itemid = RedshopHelperRouter::getCheckoutItemId();
		$this->setRedirect('index.php?option=com_redshop&view=checkout&quotation=1&encr=' . $encr . '&Itemid=' . $Itemid);
	}
}
