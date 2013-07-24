<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quotation.php';
require_once JPATH_COMPONENT . '/helpers/extra_field.php';
require_once JPATH_COMPONENT . '/helpers/product.php';
include_once JPATH_COMPONENT . '/helpers/cart.php';

/**
 * Class quotation_detailModelquotation_detail
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class quotation_detailModelquotation_detail extends JModel
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
			. "WHERE quotation_id='" . $quoid . "' "
			. "AND quotation_encrkey LIKE '" . $encr . "' ";
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

		$carthelper      = new rsCarthelper;
		$producthelper   = new producthelper;
		$quotationHelper = new quotationHelper;

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
				$session->set('cart', $cart);

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

			for ($i = 0; $i < count($row_data); $i++)
			{
				$field_name              = $row_data[$i]->field_name;
				$cart[$idx][$field_name] = $row_data[$i]->data_txt;
			}

			$cart['idx'] = $idx + 1;
			$session->set('cart', $cart);

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

		for ($i = 0; $i < count($quotation_acc_data); $i++)
		{
			$generateAccessoryCart[$i]['accessory_id']     = $quotation_acc_data[$i]->accessory_id;
			$generateAccessoryCart[$i]['accessory_name']   = $quotation_acc_data[$i]->accessory_item_name;
			$generateAccessoryCart[$i]['accessory_oprand'] = "+";
			$generateAccessoryCart[$i]['accessory_price']  = $quotation_acc_data[$i]->accessory_price;

			$acc_att_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 1, "attribute", $quotation_acc_data[$i]->accessory_id);

			$accAttributeCart = array();

			for ($ia = 0; $ia < count($acc_att_data); $ia++)
			{
				$accPropertyCart                         = array();
				$accAttributeCart[$ia]['attribute_id']   = $acc_att_data[$ia]->section_id;
				$accAttributeCart[$ia]['attribute_name'] = $acc_att_data[$ia]->section_name;

				$acc_prop_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 1, "property", $acc_att_data[$ia]->section_id);

				for ($ip = 0; $ip < count($acc_prop_data); $ip++)
				{
					$accSubpropertyCart                      = array();
					$accPropertyCart[$ip]['property_id']     = $acc_prop_data[$ip]->section_id;
					$accPropertyCart[$ip]['property_name']   = $acc_prop_data[$ip]->section_name;
					$accPropertyCart[$ip]['property_oprand'] = $acc_prop_data[$ip]->section_oprand;

					$acc_subpro_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 1, "subproperty", $acc_prop_data[$ip]->section_id);

					for ($isp = 0; $isp < count($acc_subpro_data); $isp++)
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

		for ($ia = 0; $ia < count($quotation_att_data); $ia++)
		{
			$accPropertyCart                              = array();
			$generateAttributeCart[$ia]['attribute_id']   = $quotation_att_data[$ia]->section_id;
			$generateAttributeCart[$ia]['attribute_name'] = $quotation_att_data[$ia]->section_name;

			$acc_prop_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 0, "property", $quotation_att_data[$ia]->section_id);

			for ($ip = 0; $ip < count($acc_prop_data); $ip++)
			{
				$accSubpropertyCart                      = array();
				$accPropertyCart[$ip]['property_id']     = $acc_prop_data[$ip]->section_id;
				$accPropertyCart[$ip]['property_name']   = $acc_prop_data[$ip]->section_name;
				$accPropertyCart[$ip]['property_oprand'] = $acc_prop_data[$ip]->section_oprand;

				$acc_subpro_data = $quotationHelper->getQuotationItemAttributeDetail($data->quotation_item_id, 0, "subproperty", $acc_prop_data[$ip]->section_id);

				for ($isp = 0; $isp < count($acc_subpro_data); $isp++)
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

		for ($i = 0; $i < count($row_data); $i++)
		{
			$field_name              = $row_data[$i]->field_name;
			$cart[$idx][$field_name] = $row_data[$i]->data_txt;
		}

		$session->set('cart', $cart);
	}

	public function modifyQuotation($user_id = 0)
	{
		$session    = JFactory::getSession();
		$carthelper = new rsCarthelper;
		$cart       = $session->get('cart');

		$cart = $carthelper->modifyCart($cart, $user_id);

		$session->set('cart', $cart);
		$carthelper->cartFinalCalculation(false);
	}
}
