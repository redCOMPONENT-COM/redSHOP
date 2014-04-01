<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

require_once JPATH_COMPONENT_SITE . '/helpers/product.php';
require_once JPATH_COMPONENT_SITE . '/helpers/helper.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/cart.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/product.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/quotation.php';

class addquotation_detailModeladdquotation_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function setBilling()
	{
		$detail = new stdClass;
		$detail->users_info_id = 0;
		$detail->address_type = "";
		$detail->company_name = null;
		$detail->firstname = null;
		$detail->lastname = null;
		$detail->country_code = null;
		$detail->state_code = null;
		$detail->zipcode = null;
		$detail->user_email = null;
		$detail->address = null;
		$detail->city = null;
		$detail->phone = null;

		return $detail;
	}

	public function storeShipping($data)
	{
		$data['address_type'] = 'BT';

		$row = $this->getTable('user_detail');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$data['address_type'] = 'ST';

		$rowsh = & $this->getTable('user_detail');

		if (!$rowsh->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$rowsh->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return 0;
		}

		return $row;
	}

	public function sendRegistrationMail($post)
	{
		$redshopMail = new redshopMail;
		$redshopMail->sendRegistrationMail($post);
	}

	public function store($data)
	{
		$extra_field     = new extra_field;
		$quotationHelper = new quotationHelper;
		$producthelper   = new producthelper;
		$rsCarthelper    = new rsCarthelper;
		$stockroomhelper = new rsstockroomhelper;

		$extra_field->extra_field_save($data, 16, $data['user_info_id'], $data['user_email']);

		$row = $this->getTable('quotation_detail');

		if ($data['quotation_discount'] > 0)
		{
			$data['order_total'] = $data['order_total'] - $data['quotation_discount'] - (($data['order_total'] * $data['quotation_special_discount']) / 100);
		}

		$data['quotation_number'] = $quotationHelper->generateQuotationNumber();
		$data['quotation_encrkey'] = $quotationHelper->randomQuotationEncrkey();
		$data['quotation_cdate'] = time();
		$data['quotation_mdate'] = time();
		$data['quotation_total'] = $data['order_total'];
		$data['quotation_subtotal'] = $data['order_subtotal'];
		$data['quotation_tax'] = $data['order_tax'];
		$data['quotation_ipaddress'] = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER ['REMOTE_ADDR'] : 'unknown';

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$row->quotation_status = 2;

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$quotation_item = array();

		$user_id = $row->user_id;
		$item = $data['order_item'];

		$adminproducthelper = new adminproducthelper;
		$quotationItem = $adminproducthelper->redesignProductItem($data);
		$data['quotation_item'] = $quotationItem;
		$data['quotation_id']   = $row->quotation_id;

		$quotationModel = JModel::getInstance('quotation_detail', 'quotation_detailModel');
		$quotationModel->newQuotationItem($data);

		return $row;
	}

	public function sendQuotationMail($quotaion_id)
	{
		$redshopMail = new redshopMail;
		$send = $redshopMail->sendQuotationMail($quotaion_id);

		return $send;
	}

	public function getUserData($user_id = 0, $billing = "", $user_info_id = 0)
	{
		$db = JFactory::getDbo();

		$and = '';

		if ($user_id != 0)
		{
			$and .= ' AND ui.user_id = ' . (int) $user_id . ' ';
		}

		if ($billing != "")
		{
			$and .= ' AND ui.address_type like ' . $db->quote($billing) . ' ';
		}

		if ($user_info_id != 0)
		{
			$and .= ' AND ui.users_info_id= ' . (int) $user_info_id . ' ';
		}

		$query = 'SELECT *,CONCAT(ui.firstname," ",ui.lastname) AS text FROM ' . $this->_table_prefix . 'users_info AS ui '
			. 'WHERE 1=1 '
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}


	public function replaceSubPropertyData($product_id = 0, $accessory_id = 0, $attribute_id = 0, $property_id = 0, $user_id, $uniqueid = "")
	{
		$producthelper = new producthelper;

		$subproperty = array();

		if ($property_id != 0 && $attribute_id != 0)
		{
			$attributes = $producthelper->getProductAttribute(0, 0, $attribute_id);
			$attributes = $attributes[0];
			$subproperty = $producthelper->getAttibuteSubProperty(0, $property_id);
		}

		if ($accessory_id != 0)
		{
			$prefix = $uniqueid . "acc_";
		}
		else
		{
			$prefix = $uniqueid . "prd_";
		}

		$attributelist = "";

		if (count($subproperty) > 0)
		{
			$commonid = $prefix . $product_id . '_' . $accessory_id . '_' . $attribute_id . '_' . $property_id;
			$subpropertyid = 'subproperty_id_' . $commonid;

			for ($i = 0; $i < count($subproperty); $i++)
			{
				$attributes_subproperty_vat = 0;

				if ($subproperty [$i]->subattribute_color_price > 0)
				{
					$attributes_subproperty_vat = $producthelper->getProducttax($product_id, $subproperty[$i]->subattribute_color_price);
					$subproperty [$i]->subattribute_color_price += $attributes_subproperty_vat;
					$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name)
						. " (" . $subproperty [$i]->oprand
						. $producthelper->getProductFormattedPrice($subproperty [$i]->subattribute_color_price) . ")";
				}
				else
				{
					$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
				}

				$attributelist .= '<input type="hidden" id="' . $subpropertyid . '_oprand'
					. $subproperty [$i]->value . '" value="' . $subproperty [$i]->oprand . '" />';
				$attributelist .= '<input type="hidden" id="' . $subpropertyid . '_protax'
					. $subproperty [$i]->value . '" value="' . $attributes_subproperty_vat . '" />';
				$attributelist .= '<input type="hidden" id="' . $subpropertyid . '_proprice'
					. $subproperty [$i]->value . '" value="' . $subproperty [$i]->subattribute_color_price . '" />';
			}

			$tmp_array = array();
			$tmp_array[0]->value = 0;
			$tmp_array[0]->text = JText::_('COM_REDSHOP_SELECT') . "&nbsp;" . urldecode($subproperty[0]->property_name);

			$new_subproperty = array_merge($tmp_array, $subproperty);
			$chklist = "";

			if ($attributes->allow_multiple_selection)
			{
				for ($chk = 0; $chk < count($subproperty); $chk++)
				{
					$chklist .= "<br /><input type='checkbox' value='" . $subproperty[$chk]->value
						. "' name='" . $subpropertyid . "[]'  id='" . $subpropertyid
						. "' class='inputbox' onchange='javascript:calculateOfflineTotalPrice(\""
						. $uniqueid . "\");' />&nbsp;" . $subproperty[$chk]->text;
				}
			}
			else
			{
				$chklist = JHTML::_('select.genericlist', $new_subproperty, $subpropertyid . '[]', ' id="'
					. $subpropertyid . '" class="inputbox" size="1" onchange="javascript:calculateOfflineTotalPrice(\''
					. $uniqueid . '\');" ', 'value', 'text', '');
			}

			$lists ['subproperty_id'] = $chklist;

			$attributelist .= "<tr><td>" . urldecode($subproperty[0]->property_name) . " : " . $lists ['subproperty_id'];
		}

		return $attributelist;
	}
}
