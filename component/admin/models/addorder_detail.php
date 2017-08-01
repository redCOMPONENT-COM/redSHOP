<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

use Redshop\Economic\Economic;



class RedshopModelAddorder_detail extends RedshopModel
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
		$this->_order_functions = order_functions::getInstance();
		$this->_db = JFactory::getDbo();
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{

		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$order_functions = new order_functions;

		if (empty($this->_data))
		{
			$this->_data = $order_functions->getOrderDetails($this->_id);

			return (boolean) $this->_data;
		}

		return true;
	}

	public function setBilling()
	{
		$post = JRequest::get('post');

		$is_company = (Redshop::getConfig()->get('DEFAULT_CUSTOMER_REGISTER_TYPE') == 2) ? 1 : 0;
		$detail = new stdClass;
		$detail->users_info_id = (isset($post['users_info_id'])) ? $post['users_info_id'] : 0;
		$detail->address_type = (isset($post['address_type'])) ? $post['address_type'] : "";
		$detail->company_name = (isset($post['company_name'])) ? $post['company_name'] : null;
		$detail->firstname = (isset($post['firstname'])) ? $post['firstname'] : null;
		$detail->lastname = (isset($post['lastname'])) ? $post['lastname'] : null;
		$detail->country_code = (isset($post['country_code'])) ? $post['country_code'] : null;
		$detail->state_code = (isset($post['state_code'])) ? $post['state_code'] : null;
		$detail->zipcode = (isset($post['zipcode'])) ? $post['zipcode'] : null;
		$detail->user_email = (isset($post['user_email'])) ? $post['user_email'] : null;
		$detail->address = (isset($post['address'])) ? $post['address'] : null;
		$detail->is_company = (isset($post['is_company'])) ? $post['is_company'] : $is_company;
		$detail->city = (isset($post['city'])) ? $post['city'] : null;
		$detail->phone = (isset($post['phone'])) ? $post['phone'] : null;
		$detail->vat_number = (isset($post['vat_number'])) ? $post['vat_number'] : null;
		$detail->tax_exempt_approved = (isset($post['tax_exempt_approved'])) ? $post['tax_exempt_approved'] : null;
		$detail->requesting_tax_exempt = (isset($post['requesting_tax_exempt'])) ? $post['requesting_tax_exempt'] : null;
		$detail->ean_number = (isset($post['ean_number'])) ? $post['ean_number'] : null;
		$detail->tax_exempt = (isset($post['tax_exempt'])) ? $post['tax_exempt'] : null;

		return $detail;
	}

	public function setShipping()
	{
		$post = JRequest::get('post');

		$detail = new stdClass;
		$detail->billisship = (isset($post['billisship'])) ? $post['billisship'] : 1;
		$detail->users_info_id = (isset($post['users_info_id'])) ? $post['users_info_id'] : 0;
		$detail->firstname = (isset($post['firstname_ST'])) ? $post['firstname_ST'] : null;
		$detail->lastname = (isset($post['lastname_ST'])) ? $post['lastname_ST'] : null;
		$detail->country_code = (isset($post['country_code_ST'])) ? $post['country_code_ST'] : null;
		$detail->state_code = (isset($post['state_code_ST'])) ? $post['state_code_ST'] : null;
		$detail->zipcode = (isset($post['zipcode_ST'])) ? $post['zipcode_ST'] : null;
		$detail->address = (isset($post['address_ST'])) ? $post['address_ST'] : null;
		$detail->city = (isset($post['city_ST'])) ? $post['city_ST'] : null;
		$detail->phone = (isset($post['phone_ST'])) ? $post['phone_ST'] : null;

		return $detail;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->order_id = 0;
			$detail->user_id = null;
			$detail->order_number = null;
			$detail->user_info_id = null;
			$detail->order_total = null;
			$detail->order_subtotal = null;
			$detail->order_tax = null;
			$detail->order_tax_details = null;
			$detail->order_shipping = null;
			$detail->order_shipping_tax = null;
			$detail->coupon_discount = null;
			$detail->payment_discount = null;
			$detail->order_discount = null;
			$detail->order_status = null;
			$detail->cdate = null;
			$detail->mdate = null;
			$detail->ship_method_id = null;
			$detail->customer_note = null;
			$detail->ip_address = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}
		return true;
	}

	public function storeShipping($data)
	{
		$userhelper = rsUserHelper::getInstance();
		$data['address_type'] = 'BT';
		$data['createaccount'] = (isset($data['username']) && $data['username'] != "") ? 1 : 0;
		$data['user_email'] = $data['email1'] = $data['email'];
		$data['sameasbilling'] = (isset($data['billisship']) && $data['billisship'] == 1) ? 1 : 0;
		$data['billisship'] = 1;
		$data['groups'] = array("Registered" => "2");

		if ($data['guestuser'] && !$data['user_id'])
		{
			$joomlauser = $userhelper->updateJoomlaUser($data);

			if (!$joomlauser)
			{
				return false;
			}
		}

		$reduser = $userhelper->storeRedshopUser($data, $joomlauser->id, 1);

		if ($reduser)
		{
			if ($data['sameasbilling'] != 1)
			{
				$data['users_info_id'] = ($data['shipp_users_info_id'] != "") ? $data['shipp_users_info_id'] : 0;
				$data['user_email'] = $reduser->user_email;
				$data['user_id'] = $reduser->user_id;
				$data['tax_exempt'] = $reduser->tax_exempt;
				$data['requesting_tax_exempt'] = $reduser->requesting_tax_exempt;
				$data['shopper_group_id'] = $reduser->shopper_group_id;
				$data['tax_exempt_approved'] = $reduser->tax_exempt_approved;
				$data['company_name'] = $reduser->company_name;
				$data['vat_number'] = $reduser->vat_number;

				if ($data['firstname_ST'] == "")
				{
					$data['firstname_ST'] = $data['firstname'];
				}

				if ($data['lastname_ST'] == "")
				{
					$data['lastname_ST'] = $data['lastname'];
				}

				if ($data['address_ST'] == "")
				{
					$data['address_ST'] = $data['address'];
				}

				if ($data['city_ST'] == "")
				{
					$data['city_ST'] = $data['city'];
				}

				if ($data['state_code_ST'] == "0")
				{
					$data['state_code_ST'] = $data['state_code'];
				}

				if ($data['country_code_ST'] == "0")
				{
					$data['country_code_ST'] = $data['country_code'];
				}

				if ($data['zipcode_ST'] == "")
				{
					$data['zipcode_ST'] = $data['zipcode'];
				}

				if ($data['phone_ST'] == "")
				{
					$data['phone_ST'] = $data['phone'];
				}

				$rowsh = $userhelper->storeRedshopUserShipping($data);

				return $rowsh;
			}
			else
			{
				$reduser->users_info_id = 0;

				return $reduser;
			}
		}

		return $reduser;
	}

	public function store($postdata)
	{
		$order_functions = order_functions::getInstance();
		$producthelper = productHelper::getInstance();
		$rsCarthelper = rsCarthelper::getInstance();
		$adminproducthelper = RedshopAdminProduct::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();

		// For barcode generation
		$barcode_code = $order_functions->barcode_randon_number(12, 0);

		$postdata['barcode'] = $barcode_code;

		$row = $this->getTable('order_detail');

		if (!$row->bind($postdata))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->check())
		{
			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$rowOrderStatus = $this->getTable('order_status_log');
		$rowOrderStatus->order_id = $row->order_id;
		$rowOrderStatus->order_status = $row->order_status;
		$rowOrderStatus->date_changed = time();
		$rowOrderStatus->customer_note = $row->customer_note;
		$rowOrderStatus->store();

		$billingaddresses = $order_functions->getBillingAddress($row->user_id);

		if (isset($postdata['billisship']) && $postdata['billisship'] == 1)
		{
			$shippingaddresses = $billingaddresses;
		}
		else
		{
			$key = 0;
			$shippingaddresses = $order_functions->getShippingAddress($row->user_id);
			$shipp_users_info_id = (isset($postdata['shipp_users_info_id']) && $postdata['shipp_users_info_id'] != 0) ? $postdata['shipp_users_info_id'] : 0;

			if ($shipp_users_info_id != 0)
			{
				for ($o = 0, $on = count($shippingaddresses); $o < $on; $o++)
				{
					if ($shippingaddresses[$o]->users_info_id == $shipp_users_info_id)
					{
						$key = $o;
						break;
					}
				}
			}

			$shippingaddresses = $shippingaddresses[$key];
		}

		// ORDER DELIVERY TIME IS REMAINING

		$user_id = $row->user_id;
		$item = $postdata['order_item'];

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
			$accessory_vat_price = $retAccArr[2];

			$wrapper_price = 0;
			$wrapper_vat = 0;

			if ($item[$i]->wrapper_data != 0 && $item[$i]->wrapper_data != '')
			{
				$wrapper = $producthelper->getWrapper($product_id, $item[$i]->wrapper_data);

				if (count($wrapper) > 0)
				{
					if ($wrapper[0]->wrapper_price > 0)
					{
						$wrapper_vat = $producthelper->getProducttax($product_id, $wrapper[0]->wrapper_price, $user_id);
					}

					$wrapper_price = $wrapper[0]->wrapper_price + $wrapper_vat;
				}
			}

			$product = Redshop::product((int) $product_id);

			$rowitem = $this->getTable('order_item_detail');

			if (!$rowitem->bind($postdata))
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			// STOCKROOM update
			$updatestock = $stockroomhelper->updateStockroomQuantity($product_id, $quantity);
			$stockroom_id_list = $updatestock['stockroom_list'];
			$stockroom_quantity_list = $updatestock['stockroom_quantity_list'];
			$rowitem->stockroom_id = $stockroom_id_list;
			$rowitem->stockroom_quantity = $stockroom_quantity_list;
			$rowitem->order_item_id = 0;
			$rowitem->order_id = $row->order_id;
			$rowitem->user_info_id = $row->user_info_id;
			$rowitem->supplier_id = $product->manufacturer_id;
			$rowitem->product_id = $product_id;
			$rowitem->order_item_sku = $product->product_number;
			$rowitem->order_item_name = $product->product_name;
			$rowitem->product_quantity = $quantity;
			$rowitem->product_item_price = $product_price;
			$rowitem->product_item_price_excl_vat = $product_excl_price;
			$rowitem->product_final_price = $product_price * $quantity;
			$rowitem->order_item_currency = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
			$rowitem->order_status = $row->order_status;
			$rowitem->cdate = $row->cdate;
			$rowitem->mdate = $row->cdate;
			$rowitem->product_attribute = $product_attribute;
			$rowitem->product_accessory = $product_accessory;
			$rowitem->wrapper_id = $item[$i]->wrapper_data;
			$rowitem->wrapper_price = $wrapper_price;
			$rowitem->is_giftcard = 0;

			if (RedshopHelperProductDownload::checkDownload($product_id))
			{
				$medianame = $producthelper->getProductMediaName($product_id);

				for ($j = 0, $jn = count($medianame); $j < $jn; $j++)
				{
					$product_serial_number = $producthelper->getProdcutSerialNumber($product_id);
					$producthelper->insertProductDownload(
						$product_id, $user_id, $rowitem->order_id,
						$medianame[$j]->media_name, $product_serial_number->serial_number
					);
				}
			}

			if (!$rowitem->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
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
						$accessory_vat_price = $producthelper->getProductTax($product_id, $accessory_price, $user_id);
					}

					$attchildArr = $attArr[$a]['accessory_childs'];

					for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
					{
						$attribute_id = $attchildArr[$j]['attribute_id'];
						$accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

						$rowattitem = $this->getTable('order_attribute_item');
						$rowattitem->order_att_item_id = 0;
						$rowattitem->order_item_id = $rowitem->order_item_id;
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
								$section_vat = $producthelper->getProducttax($product_id, $propArr[$k]['property_price'], $user_id);
							}

							$property_id = $propArr[$k]['property_id'];
							$accessory_attribute .= urldecode($propArr[$k]['property_name']) . " ("
								. $propArr[$k]['property_oprand']
								. $producthelper->getProductFormattedPrice($propArr[$k]['property_price'] + $section_vat) . ")<br/>";
							$subpropArr = $propArr[$k]['property_childs'];

							$rowattitem = $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id = 0;
							$rowattitem->order_item_id = $rowitem->order_item_id;
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

								$rowattitem = $this->getTable('order_attribute_item');
								$rowattitem->order_att_item_id = 0;
								$rowattitem->order_item_id = $rowitem->order_item_id;
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
					$rowaccitem = $this->getTable('order_acc_item');
					$rowaccitem->order_item_acc_id = 0;
					$rowaccitem->order_item_id = $rowitem->order_item_id;
					$rowaccitem->product_id = $accessory_id;
					$rowaccitem->order_acc_item_sku = $accProductinfo->product_number;
					$rowaccitem->order_acc_item_name = $accessory_name;
					$rowaccitem->order_acc_price = $accessory_org_price;
					$rowaccitem->order_acc_vat = $accessory_vat_price;
					$rowaccitem->product_quantity = $quantity;
					$rowaccitem->product_acc_item_price = $accessory_price;
					$rowaccitem->product_acc_final_price = ($accessory_price * $quantity);
					$rowaccitem->product_attribute = $accessory_attribute;

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

					$rowattitem = $this->getTable('order_attribute_item');
					$rowattitem->order_att_item_id = 0;
					$rowattitem->order_item_id = $rowitem->order_item_id;
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
						$updatestock = $stockroomhelper->updateStockroomQuantity($property_id, $quantity, "property");

						$rowattitem = $this->getTable('order_attribute_item');
						$rowattitem->order_att_item_id = 0;
						$rowattitem->order_item_id = $rowitem->order_item_id;
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
								$section_vat = $producthelper->getProducttax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							$subproperty_id = $subpropArr[$l]['subproperty_id'];
							/** product subproperty STOCKROOM update start */
							$updatestock = $stockroomhelper->updateStockroomQuantity($subproperty_id, $quantity, "subproperty");

							$rowattitem = $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id = 0;
							$rowattitem->order_item_id = $rowitem->order_item_id;
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

			// Store userfields
			if (isset($item[$i]->extrafieldname) && isset($item[$i]->extrafieldId))
			{
				$userfields = $item[$i]->extrafieldname;
				$userfields_id = $item[$i]->extrafieldId;

				for ($ui = 0, $countUserField = count($userfields); $ui < $countUserField; $ui++)
				{
					$adminproducthelper->admin_insertProdcutUserfield($userfields_id[$ui], $rowitem->order_item_id, 12, $userfields[$ui]);
				}
			}
		}

		$rowpayment = $this->getTable('order_payment');

		if (!$rowpayment->bind($postdata))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$rowpayment->order_id = $row->order_id;
		$rowpayment->payment_method_id = $postdata['payment_method_class'];
		$rowpayment->order_payment_amount = $row->order_total;
		$rowpayment->order_payment_name = $postdata['order_payment_name'];
		$rowpayment->payment_method_class = $postdata['payment_method_class'];

		if (!$rowpayment->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Add billing Info
		$userrow = $this->getTable('user_detail');
		$userrow->load($billingaddresses->users_info_id);
		$orderuserrow = $this->getTable('order_user_detail');

		if (!$orderuserrow->bind($userrow))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$orderuserrow->order_id = $row->order_id;
		$orderuserrow->address_type = 'BT';

		if (!$orderuserrow->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Add shipping Info
		$userrow = $this->getTable('user_detail');

		if (isset($shippingaddresses->users_info_id))
		{
			$userrow->load($shippingaddresses->users_info_id);
		}

		$orderuserrow = $this->getTable('order_user_detail');

		if (!$orderuserrow->bind($userrow))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$orderuserrow->order_id = $row->order_id;
		$orderuserrow->address_type = 'ST';

		if (!$orderuserrow->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if ($row->order_status == Redshop::getConfig()->get('CLICKATELL_ORDER_STATUS'))
		{
			RedshopHelperClickatell::clickatellSMS($row->order_id);
		}

		// Economic Integration start for invoice generate and book current invoice
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') != 2)
		{
			$economicdata['economic_payment_terms_id'] = $postdata['economic_payment_terms_id'];
			$economicdata['economic_design_layout'] = $postdata['economic_design_layout'];
			$economicdata['economic_is_creditcard'] = $postdata['economic_is_creditcard'];
			$payment_name = $postdata['payment_method_class'];
			$paymentArr = explode("rs_payment_", $postdata['payment_method_class']);

			if (count($paymentArr) > 0)
			{
				$payment_name = $paymentArr[1];
			}

			$economicdata['economic_payment_method'] = $payment_name;

			Economic::createInvoiceInEconomic($row->order_id, $economicdata);

			if (Redshop::getConfig()->get('ECONOMIC_INVOICE_DRAFT') == 0)
			{
				// Check for bank transfer payment type plugin - `rs_payment_banktransfer` suffixed
				$isBankTransferPaymentType = RedshopHelperPayment::isPaymentType($postdata['payment_method_class']);

				$checkOrderStatus          = ($isBankTransferPaymentType) ? 0 : 1;

				$bookinvoicepdf = Economic::bookInvoiceInEconomic($row->order_id, $checkOrderStatus);

				if (JFile::exists($bookinvoicepdf))
				{
					RedshopHelperMail::sendEconomicBookInvoiceMail($row->order_id, $bookinvoicepdf);
				}
			}
		}

		// ORDER MAIL SEND
		if ($postdata['task'] != "addorder_detail.save_without_sendmail")
		{
			RedshopHelperMail::sendOrderMail($row->order_id);
		}

		return $row;
	}

	public function sendRegistrationMail($post)
	{
		$redshopMail = redshopMail::getInstance();
		$redshopMail->sendRegistrationMail($post);
	}

	public function changeshippingaddress($shippingadd_id, $user_id, $is_company)
	{
		$extra_field = extra_field::getInstance();

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info '
			. 'WHERE address_type like "ST" '
			. 'AND user_id = ' . (int) $user_id . ' '
			. 'AND users_info_id = ' . (int) $shippingadd_id;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		if (count($list) > 0)
		{
			$shipping = $list;
		}
		else
		{
			$shipping = $this->setShipping();
		}

		$allowCustomer = '';
		$allowCompany = '';

		if ($is_company)
		{
			$allowCustomer = 'style="display:none;"';
		}
		else
		{
			$allowCompany = 'style="display:none;"';
		}

		// Field_section 7 :Customer Address
		$lists['shipping_customer_field'] = $extra_field->list_all_field(14, $shipping->users_info_id);

		// Field_section 8 :Company Address
		$lists['shipping_company_field'] = $extra_field->list_all_field(15, $shipping->users_info_id);

		$countryarray = RedshopHelperWorld::getCountryList((array) $shipping, "country_code_ST", "ST", '', 'state_code_ST');
		$shipping->country_code_ST = $shipping->country_code = $countryarray['country_code_ST'];
		$lists['country_code_ST'] = $countryarray['country_dropdown'];

		$statearray = RedshopHelperWorld::getStateList((array) $shipping, "state_code_ST", "ST");
		$lists['state_code_ST'] = $statearray['state_dropdown'];

		$htmlshipping = '<table class="adminlist" border="0" width="100%">';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('FIRSTNAME') . ':</td>';
		$htmlshipping .= '<td><input class="inputbox" type="text" name="firstname_ST" maxlength="250" value="' . $shipping->firstname . '" /></td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('LASTNAME') . ':</td>';
		$htmlshipping .= '<td><input class="inputbox" type="text" name="lastname_ST" maxlength="250" value="' . $shipping->lastname . '" /></td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('ADDRESS') . ':</td>';
		$htmlshipping .= '<td><input class="inputbox" type="text" name="address_ST" maxlength="250" value="' . $shipping->address . '" /></td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('ZIP') . ':</td>';
		$htmlshipping .= '<td><input class="inputbox" type="text" name="zipcode_ST" maxlength="250" value="' . $shipping->zipcode . '" /></td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('CITY') . ':</td>';
		$htmlshipping .= '<td><input class="inputbox" type="text" name="city_ST" maxlength="250" value="' . $shipping->city . '" /></td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('COUNTRY') . ':</td>';
		$htmlshipping .= '<td>' . $lists['country_code_ST'] . '</td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('STATE') . ':</td>';
		$htmlshipping .= '<td>' . $lists['state_code_ST'] . '</td></tr>';
		$htmlshipping .= '<tr><td width="100" align="right">' . JText::_('PHONE') . ':</td>';
		$htmlshipping .= '<td><input class="inputbox" type="text" name="phone_ST" maxlength="250" value="' . $shipping->phone . '" /></td></tr>';
		$htmlshipping .= '<tr><td colspan="2"><div id="exCustomerFieldST" ' . $allowCustomer . '>' . $lists['shipping_customer_field'] . '</div>
							<div id="exCompanyFieldST" ' . $allowCompany . '>' . $lists['shipping_company_field'] . '</div></td></tr>';
		$htmlshipping .= '</table>';

		return $htmlshipping;
	}
}
