<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Class quotation_detailModelquotation_detail
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelQuotation_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	public function checkAuthorization($quoid, $encr)
	{
		$query = "SELECT COUNT(quotation_id) FROM " . $this->_table_prefix . "quotation "
			. "WHERE quotation_id = " . (int) $quoid . " "
			. "AND quotation_encrkey LIKE " . $this->_db->quote($encr);
		$this->_db->setQuery($query);
		$record = $this->_db->loadResult();

		return $record;
	}

	public function addtocart($data = array())
	{
		$app = JFactory::getApplication();

		$Itemid  = JRequest::getVar("Itemid");
		$session = JFactory::getSession();
		$db      = JFactory::getDbo();

		$carthelper      = rsCarthelper::getInstance();
		$producthelper   = productHelper::getInstance();
		$quotationHelper = quotationHelper::getInstance();

		$cart = $session->get('cart');

		$idx = (int) ($cart['idx']);

		$row_data           = $quotationHelper->getQuotationUserfield($data->quotation_item_id);
		$quotation_acc_data = $quotationHelper->getQuotationItemAccessoryDetail($data->quotation_item_id);
		$quotation_att_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 0, "attribute", $data->product_id);

		// Set session for giftcard
		if ($data->is_giftcard == 1)
		{
			if ($carthelper->rs_recursiveArraySearch($cart, $data->product_id))
			{
				$cart[$idx]['quantity'] += 1;
				RedshopHelperCartSession::setCart($cart);

				return;
			}
			else
			{
				$cart[$idx]['quantity'] = 1;
			}

			$cart[$idx]['quantity']      = $data->product_quantity;
			$cart[$idx]['giftcard_id']   = $data->product_id;
			$cart[$idx]['product_price'] = $data->product_price;
			$cart[$idx]['product_vat']   = 0;
			$cart[$idx]['product_id']    = '';
			$cart['discount_type']       = 0;
			$cart['discount']            = 0;
			$cart['discount2']           = 0;
			$cart['reciver_email']       = '';
			$cart['reciver_name']        = '';

			for ($i = 0, $in = count($row_data); $i < $in; $i++)
			{
				$field_name              = $row_data[$i]->field_name;
				$cart[$idx][$field_name] = $row_data[$i]->data_txt;
			}

			$cart['idx'] = $idx + 1;
			RedshopHelperCartSession::setCart($cart);

			return;
		}

		$cart[$idx]['product_id']    = $data->product_id;
		$cart[$idx]['product_price'] = $data->product_price;
		$cart[$idx]['quantity']      = $data->product_quantity;

		if ($data->product_excl_price)
		{
			$getprotax                   = $producthelper->getProductTax($cart[$idx]['product_id'], $data->product_excl_price);
			$cart[$idx]['product_price'] = $data->product_excl_price + $getprotax;
			$cart[$idx]['product_price'] += $data->wrapper_price;
			$cart[$idx]['product_subtotal'] = $cart[$idx]['quantity'] * $cart[$idx]['product_price'];
		}

		$generateAccessoryCart = array();

		for ($i = 0, $in = count($quotation_acc_data); $i < $in; $i++)
		{
			$generateAccessoryCart[$i]['accessory_id']     = $quotation_acc_data[$i]->accessory_id;
			$generateAccessoryCart[$i]['accessory_name']   = $quotation_acc_data[$i]->accessory_item_name;
			$generateAccessoryCart[$i]['accessory_oprand'] = "+";
			$generateAccessoryCart[$i]['accessory_price']  = $quotation_acc_data[$i]->accessory_price;

			$acc_att_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 1, "attribute", $quotation_acc_data[$i]->accessory_id);

			$accAttributeCart = array();

			for ($ia = 0, $countAccessoryAttribute = count($acc_att_data); $ia < $countAccessoryAttribute; $ia++)
			{
				$accPropertyCart                         = array();
				$accAttributeCart[$ia]['attribute_id']   = $acc_att_data[$ia]->section_id;
				$accAttributeCart[$ia]['attribute_name'] = $acc_att_data[$ia]->section_name;

				$acc_prop_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 1, "property", $acc_att_data[$ia]->section_id);

				for ($ip = 0, $countAccessoryProperty = count($acc_prop_data); $ip < $countAccessoryProperty; $ip++)
				{
					$accSubpropertyCart                      = array();
					$accPropertyCart[$ip]['property_id']     = $acc_prop_data[$ip]->section_id;
					$accPropertyCart[$ip]['property_name']   = $acc_prop_data[$ip]->section_name;
					$accPropertyCart[$ip]['property_oprand'] = $acc_prop_data[$ip]->section_oprand;

					$acc_subpro_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 1, "subproperty", $acc_prop_data[$ip]->section_id);
					$countAccessorySubroperty = count($acc_subpro_data);

					for ($isp = 0; $isp < $countAccessorySubroperty; $isp++)
					{
						$accSubpropertyCart[$isp]['subproperty_id']     = $acc_subpro_data[$isp]->section_id;
						$accSubpropertyCart[$isp]['subproperty_name']   = $acc_subpro_data[$isp]->section_name;
						$accSubpropertyCart[$isp]['subproperty_oprand'] = $acc_subpro_data[$isp]->section_oprand;
					}

					$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
				}

				$accAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
			}

			$generateAccessoryCart[$i]['accessory_childs'] = $accAttributeCart;
		}

		$generateAttributeCart = array();

		for ($ia = 0, $countQuotationAtrribute = count($quotation_att_data); $ia < $countQuotationAtrribute; $ia++)
		{
			$accPropertyCart                              = array();
			$generateAttributeCart[$ia]['attribute_id']   = $quotation_att_data[$ia]->section_id;
			$generateAttributeCart[$ia]['attribute_name'] = $quotation_att_data[$ia]->section_name;

			$acc_prop_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 0, "property", $quotation_att_data[$ia]->section_id);
			$countQuotationProperty = count($acc_prop_data);

			for ($ip = 0; $ip < $countQuotationProperty; $ip++)
			{
				$accSubpropertyCart                      = array();
				$accPropertyCart[$ip]['property_id']     = $acc_prop_data[$ip]->section_id;
				$accPropertyCart[$ip]['property_name']   = $acc_prop_data[$ip]->section_name;
				$accPropertyCart[$ip]['property_oprand'] = $acc_prop_data[$ip]->section_oprand;

				$acc_subpro_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 0, "subproperty", $acc_prop_data[$ip]->section_id);
				$countQuotationSubproperty = count($acc_subpro_data);

				for ($isp = 0; $isp < $countQuotationSubproperty; $isp++)
				{
					$accSubpropertyCart[$isp]['subproperty_id']     = $acc_subpro_data[$isp]->section_id;
					$accSubpropertyCart[$isp]['subproperty_name']   = $acc_subpro_data[$isp]->section_name;
					$accSubpropertyCart[$isp]['subproperty_oprand'] = $acc_subpro_data[$isp]->section_oprand;
				}

				$accPropertyCart[$ip]['property_childs'] = $accSubpropertyCart;
			}

			$generateAttributeCart[$ia]['attribute_childs'] = $accPropertyCart;
		}

		$cart[$idx]['cart_attribute'] = $generateAttributeCart;
		$cart[$idx]['cart_accessory'] = $generateAccessoryCart;
		$cart[$idx]['wrapper_id']             = $data->product_wrapperid;
		$cart[$idx]['wrapper_price']          = $data->wrapper_price;
		$cart[$idx]['product_price_excl_vat'] = $data->product_excl_price;

		$cart['idx'] = $idx + 1;

		for ($i = 0, $in = count($row_data); $i < $in; $i++)
		{
			$field_name              = $row_data[$i]->field_name;
			$cart[$idx][$field_name] = $row_data[$i]->data_txt;
		}

		RedshopHelperCartSession::setCart($cart);
	}

	public function modifyQuotation($user_id = 0)
	{
		$session    = JFactory::getSession();
		$carthelper = rsCarthelper::getInstance();
		$cart       = $session->get('cart');

		$cart = $carthelper->modifyCart($cart, $user_id);

		RedshopHelperCartSession::setCart($cart);
		$carthelper->cartFinalCalculation(false);
	}

	/**
	 * Add Quotation Detail Customer note
	 *
	 * @param   array  $data  Quotation Detail Post Data
	 *
	 * @return  void
	 */
	public function addQuotationCustomerNote($data)
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base update statement.
		$query->update($db->quoteName('#__redshop_quotation'))
			->set($db->quoteName('quotation_customer_note') . ' = ' . $db->quote($data['quotation_customer_note']))
			->where($db->quoteName('quotation_id') . ' = ' . (int) $data['quotation_id']);

		// Set the query and execute the update.
		$db->setQuery($query);

		try
		{
			$db->execute();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}
	}
}
