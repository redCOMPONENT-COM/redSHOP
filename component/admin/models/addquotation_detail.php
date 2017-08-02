<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopModelAddquotation_detail extends RedshopModel
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

		$rowsh = $this->getTable('user_detail');

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
		$redshopMail = redshopMail::getInstance();
		$redshopMail->sendRegistrationMail($post);
	}

	public function store($data)
	{
		$extra_field = extra_field::getInstance();
		$quotationHelper = quotationHelper::getInstance();
		$producthelper = productHelper::getInstance();
		$rsCarthelper = rsCarthelper::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();

		$userRow = $this->getTable('user_detail');
		$userRow->load($data['user_info_id']);
		$userRow->firstname    = $data['firstname'];
		$userRow->lastname     = $data['lastname'];
		$userRow->address      = $data['address'];
		$userRow->zipcode      = $data['zipcode'];
		$userRow->country_code = $data['country_code'];
		$userRow->phone        = $data['phone'];
		$userRow->city         = $data['city'];
		$userRow->state_code   = $data['state_code'];

		if (!$userRow->store())
		{
			$this->setError($userRow->getErrorMsg());

			return false;
		}

		$extra_field->extra_field_save($data, 16, $data['user_info_id'], $data['user_email']);

		$row = $this->getTable('quotation_detail');

		if (isset($data['quotation_discount']) && $data['quotation_discount'] > 0)
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

		for ($i = 0, $in = count($item); $i < $in; $i++)
		{
			$product_id = $item[$i]->product_id;
			$quantity = $item[$i]->quantity;
			$product_excl_price = $item[$i]->prdexclprice;
			$product_price = $item[$i]->productprice;

			// Attribute price added
			$generateAttributeCart = $rsCarthelper->generateAttributeArray((array) $item[$i], $user_id);
			$retAttArr = $producthelper->makeAttributeCart($generateAttributeCart, $product_id, $user_id, 0, $quantity);
			$product_attribute = $retAttArr[0];

			// Accessory price
			$generateAccessoryCart = $rsCarthelper->generateAccessoryArray((array) $item[$i], $user_id);
			$retAccArr = $producthelper->makeAccessoryCart($generateAccessoryCart, $product_id, $user_id);
			$product_accessory = $retAccArr[0];

			$wrapper_price = 0;
			$wrapper_vat = 0;
			$wrapper = $producthelper->getWrapper($product_id, $item[$i]->wrapper_data);

			if (count($wrapper) > 0)
			{
				if ($wrapper[0]->wrapper_price > 0)
				{
					$wrapper_vat = $producthelper->getProducttax($product_id, $wrapper[0]->wrapper_price, $user_id);
				}

				$wrapper_price = $wrapper[0]->wrapper_price + $wrapper_vat;
			}

			$rowitem = $this->getTable('quotation_item_detail');

			$product = Redshop::product((int) $product_id);

			$quotation_item[$i] = new stdClass;
			$quotation_item[$i]->quotation_id = $row->quotation_id;
			$quotation_item[$i]->product_id = $product_id;
			$quotation_item[$i]->is_giftcard = 0;
			$quotation_item[$i]->product_name = $product->product_name;
			$quotation_item[$i]->actualitem_price = $product_price;
			$quotation_item[$i]->product_price = $product_price;
			$quotation_item[$i]->product_excl_price = $product_excl_price;
			$quotation_item[$i]->product_final_price = $product_price * $quantity;
			$quotation_item[$i]->product_attribute = $product_attribute;
			$quotation_item[$i]->product_accessory = $product_accessory;
			$quotation_item[$i]->product_wrapperid = $item[$i]->wrapper_data;
			$quotation_item[$i]->wrapper_price = $wrapper_price;
			$quotation_item[$i]->product_quantity = $quantity;

			if (!$rowitem->bind($quotation_item[$i]))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			if (!$rowitem->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// Store userfields
			$userfields = JRequest::getVar('extrafields' . $product_id);
			$userfields_id = JRequest::getVar('extrafields_id_' . $product_id);

			for ($ui = 0, $countUserField = count($userfields); $ui < $countUserField; $ui++)
			{
				$quotationHelper->insertQuotationUserfield($userfields_id[$ui], $rowitem->quotation_item_id, 12, $userfields[$ui]);
			}

			/** my accessory save in table start */
			if (count($generateAccessoryCart) > 0)
			{
				$attArr = $generateAccessoryCart;

				for ($a = 0, $an = count($attArr); $a < $an; $a++)
				{
					$accessory_vat_price = 0;
					$accessory_attribute = "";
					$accessory_id = $attArr[$a]['accessory_id'];
					$accessory_name = $attArr[$a]['accessory_name'];
					$accessory_price = $attArr[$a]['accessory_price'];
					$accessory_org_price = $accessory_price;

					if ($accessory_price > 0)
					{
						$accessory_vat_price = $producthelper->getProductTax($rowitem->product_id, $accessory_price, $user_id);
					}

					$attchildArr = $attArr[$a]['accessory_childs'];

					for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
					{
						$attribute_id = $attchildArr[$j]['attribute_id'];
						$accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

						$rowattitem = $this->getTable('quotation_attribute_item');
						$rowattitem->quotation_att_item_id = 0;
						$rowattitem->quotation_item_id = $rowitem->quotation_item_id;
						$rowattitem->section_id = $attribute_id;
						$rowattitem->section = "attribute";
						$rowattitem->parent_section_id = $accessory_id;
						$rowattitem->section_name = $attchildArr[$j]['attribute_name'];
						$rowattitem->is_accessory_att = 1;

						if ($attribute_id > 0)
						{
							if (!$rowattitem->store())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
						}

						$propArr = $attchildArr[$j]['attribute_childs'];

						for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
						{
							$section_vat = 0;

							if ($propArr[$k]['property_price'] > 0)
							{
								$section_vat = $producthelper->getProducttax($rowitem->product_id, $propArr[$k]['property_price'], $user_id);
							}

							$property_id = $propArr[$k]['property_id'];
							$accessory_attribute .= urldecode($propArr[$k]['property_name'])
								. " (" . $propArr[$k]['property_oprand']
								. $producthelper->getProductFormattedPrice($propArr[$k]['property_price'] + $section_vat) . ")<br/>";
							$subpropArr = $propArr[$k]['property_childs'];

							$rowattitem = $this->getTable('quotation_attribute_item');
							$rowattitem->quotation_att_item_id = 0;
							$rowattitem->quotation_item_id = $rowitem->quotation_item_id;
							$rowattitem->section_id = $property_id;
							$rowattitem->section = "property";
							$rowattitem->parent_section_id = $attribute_id;
							$rowattitem->section_name = $propArr[$k]['property_name'];
							$rowattitem->section_price = $propArr[$k]['property_price'];
							$rowattitem->section_vat = $section_vat;
							$rowattitem->section_oprand = $propArr[$k]['property_oprand'];
							$rowattitem->is_accessory_att = 1;

							if ($property_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}

							for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
							{
								$section_vat = 0;

								if ($subpropArr[$l]['subproperty_price'] > 0)
								{
									$section_vat = $producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price'], $user_id);
								}

								$subproperty_id = $subpropArr[$l]['subproperty_id'];
								$accessory_attribute .= urldecode($subpropArr[$l]['subproperty_name'])
									. " (" . $subpropArr[$l]['subproperty_oprand']
									. $producthelper->getProductFormattedPrice($subpropArr[$l]['subproperty_price'] + $section_vat) . ")<br/>";

								$rowattitem = $this->getTable('quotation_attribute_item');
								$rowattitem->quotation_att_item_id = 0;
								$rowattitem->quotation_item_id = $rowitem->quotation_item_id;
								$rowattitem->section_id = $subproperty_id;
								$rowattitem->section = "subproperty";
								$rowattitem->parent_section_id = $property_id;
								$rowattitem->section_name = $subpropArr[$l]['subproperty_name'];
								$rowattitem->section_price = $subpropArr[$l]['subproperty_price'];
								$rowattitem->section_vat = $section_vat;
								$rowattitem->section_oprand = $subpropArr[$l]['subproperty_oprand'];
								$rowattitem->is_accessory_att = 1;

								if ($subproperty_id > 0)
								{
									if (!$rowattitem->store())
									{
										$this->setError($this->_db->getErrorMsg());

										return false;
									}
								}
							}
						}
					}

					$accdata = $this->getTable('accessory_detail');

					if ($accessory_id > 0)
					{
						$accdata->load($accessory_id);
					}

					$accProductinfo = Redshop::product((int) $accdata->child_product_id);
					$rowaccitem = $this->getTable('quotation_accessory_item');
					$rowaccitem->quotation_item_acc_id = 0;
					$rowaccitem->quotation_item_id = $rowitem->quotation_item_id;
					$rowaccitem->accessory_id = $accessory_id;
					$rowaccitem->accessory_item_sku = $accProductinfo->product_number;
					$rowaccitem->accessory_item_name = $accessory_name;
					$rowaccitem->accessory_price = $accessory_org_price;
					$rowaccitem->accessory_vat = $accessory_vat_price;
					$rowaccitem->accessory_quantity = $rowitem->product_quantity;
					$rowaccitem->accessory_item_price = $accessory_price;
					$rowaccitem->accessory_final_price = ($accessory_price * $rowitem->product_quantity);
					$rowaccitem->accessory_attribute = $accessory_attribute;

					if ($accessory_id > 0)
					{
						if (!$rowaccitem->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}
				}
			}

			/** my attribute save in table start */
			if (count($generateAttributeCart) > 0)
			{
				$attArr = $generateAttributeCart;

				for ($j = 0, $jn = count($attArr); $j < $jn; $j++)
				{
					$attribute_id = $attArr[$j]['attribute_id'];

					$rowattitem = $this->getTable('quotation_attribute_item');
					$rowattitem->quotation_att_item_id = 0;
					$rowattitem->quotation_item_id = $rowitem->quotation_item_id;
					$rowattitem->section_id = $attribute_id;
					$rowattitem->section = "attribute";
					$rowattitem->parent_section_id = $rowitem->product_id;
					$rowattitem->section_name = $attArr[$j]['attribute_name'];
					$rowattitem->is_accessory_att = 0;

					if ($attribute_id > 0)
					{
						if (!$rowattitem->store())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}

					$propArr = $attArr[$j]['attribute_childs'];

					for ($k = 0, $kn = count($propArr); $k < $kn; $k++)
					{
						$section_vat = 0;

						if ($propArr[$k]['property_price'] > 0)
						{
							$section_vat = $producthelper->getProducttax($rowitem->product_id, $propArr[$k]['property_price'], $user_id);
						}

						$property_id = $propArr[$k]['property_id'];

						/** product property STOCKROOM update start */
						$stockroomhelper->updateStockroomQuantity($property_id, $rowitem->product_quantity, "property");

						$rowattitem = $this->getTable('quotation_attribute_item');
						$rowattitem->quotation_att_item_id = 0;
						$rowattitem->quotation_item_id = $rowitem->quotation_item_id;
						$rowattitem->section_id = $property_id;
						$rowattitem->section = "property";
						$rowattitem->parent_section_id = $attribute_id;
						$rowattitem->section_name = $propArr[$k]['property_name'];
						$rowattitem->section_price = $propArr[$k]['property_price'];
						$rowattitem->section_vat = $section_vat;
						$rowattitem->section_oprand = $propArr[$k]['property_oprand'];
						$rowattitem->is_accessory_att = 0;

						if ($property_id > 0)
						{
							if (!$rowattitem->store())
							{
								$this->setError($this->_db->getErrorMsg());

								return false;
							}
						}

						$subpropArr = $propArr[$k]['property_childs'];

						for ($l = 0, $ln = count($subpropArr); $l < $ln; $l++)
						{
							$section_vat = 0;

							if ($subpropArr[$l]['subproperty_price'] > 0)
							{
								$section_vat = $producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							$subproperty_id = $subpropArr[$l]['subproperty_id'];
							/** product subproperty STOCKROOM update start */
							$stockroomhelper->updateStockroomQuantity($subproperty_id, $rowitem->product_quantity, "subproperty");

							$rowattitem = $this->getTable('quotation_attribute_item');
							$rowattitem->quotation_att_item_id = 0;
							$rowattitem->quotation_item_id = $rowitem->quotation_item_id;
							$rowattitem->section_id = $subproperty_id;
							$rowattitem->section = "subproperty";
							$rowattitem->parent_section_id = $property_id;
							$rowattitem->section_name = $subpropArr[$l]['subproperty_name'];
							$rowattitem->section_price = $subpropArr[$l]['subproperty_price'];
							$rowattitem->section_vat = $section_vat;
							$rowattitem->section_oprand = $subpropArr[$l]['subproperty_oprand'];
							$rowattitem->is_accessory_att = 0;

							if ($subproperty_id > 0)
							{
								if (!$rowattitem->store())
								{
									$this->setError($this->_db->getErrorMsg());

									return false;
								}
							}
						}
					}
				}
			}
		}

		return $row;
	}

	public function sendQuotationMail($quotaion_id)
	{
		$redshopMail = redshopMail::getInstance();
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


	public function replaceSubPropertyData($product_id = 0, $accessory_id = 0, $attribute_id = 0, $property_id = 0, $uniqueid = "")
	{
		$producthelper = productHelper::getInstance();

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

			for ($i = 0, $in = count($subproperty); $i < $in; $i++)
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
			$tmp_array[0] = new stdClass;
			$tmp_array[0]->value = 0;
			$tmp_array[0]->text = JText::_('COM_REDSHOP_SELECT') . "&nbsp;" . urldecode($subproperty[0]->property_name);

			$new_subproperty = array_merge($tmp_array, $subproperty);
			$chklist = "";
			$display_type = 'radio';

			if (isset($subproperty[0]->setdisplay_type))
			{
				$display_type = $subproperty[0]->setdisplay_type;
			}

			if ($subproperty[0]->setmulti_selected)
			{
				$display_type = 'checkbox';
			}

			if ($display_type == 'checkbox' || $display_type == 'radio')
			{
				for ($chk = 0, $countSubProperty = count($subproperty); $chk < $countSubProperty; $chk++)
				{
					$chklist .= "<br /><input type='" . $display_type . "' value='" . $subproperty[$chk]->value
						. "' name='" . $subpropertyid . "[]'  id='" . $subpropertyid
						. "' class='inputbox' onchange='javascript:calculateOfflineTotalPrice(\""
						. $uniqueid . "\", true);' />&nbsp;" . $subproperty[$chk]->text;
				}
			}
			else
			{
				$chklist = JHTML::_('select.genericlist', $new_subproperty, $subpropertyid . '[]', ' id="'
					. $subpropertyid . '" class="inputbox" size="1" onchange="javascript:calculateOfflineTotalPrice(\''
					. $uniqueid . '\', true);" ', 'value', 'text', '');
			}

			$lists ['subproperty_id'] = $chklist;

			$attributelist .= "<tr><td>" . urldecode($subproperty[0]->property_name) . " : " . $lists ['subproperty_id'];
		}

		return $attributelist;
	}
}
