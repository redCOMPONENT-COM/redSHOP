<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopModelQuotation_detail extends RedshopModel
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
		$query = "SELECT q.* FROM " . $this->_table_prefix . "quotation AS q "
			. "WHERE q.quotation_id='" . $this->_id . "' ";
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();

		return (boolean) $this->_data;
	}

	public function &getuserdata()
	{
		$producthelper = productHelper::getInstance();

		if ($this->_data->user_id)
		{
			$userdata = $producthelper->getUserInformation($this->_data->user_id);
			$this->_data->user_email = $userdata->user_email;
		}
		else
		{
			$detail = new stdClass;
			$detail->users_info_id = 0;
			$detail->user_id = 0;
			$detail->id = 0;
			$detail->gid = null;
			$detail->name = null;
			$detail->username = null;
			$detail->email = null;
			$detail->password = null;
			$detail->usertype = null;
			$detail->block = null;
			$detail->sendEmail = null;
			$detail->registerDate = null;
			$detail->lastvisitDate = null;
			$detail->activation = null;
			$detail->is_company = null;
			$detail->firstname = null;
			$detail->lastname = null;
			$detail->contact_info = null;
			$detail->address_type = null;
			$detail->company_name = null;
			$detail->vat_number = null;
			$detail->tax_exempt = 0;
			$detail->country_code = null;
			$detail->state_code = null;
			$detail->shopper_group_id = null;
			$detail->published = 1;
			$detail->address = null;
			$detail->city = null;
			$detail->zipcode = null;
			$detail->phone = null;
			$detail->requesting_tax_exempt = 0;
			$detail->tax_exempt_approved = 0;
			$detail->approved = 1;
			$userdata = $detail;
		}

		return $userdata;
	}

	public function _initData()
	{
		$quotationHelper = quotationHelper::getInstance();

		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->quotation_id = 0;
			$detail->user_id = 0;
			$detail->quotation_number = $quotationHelper->generateQuotationNumber();
			$detail->user_info_id = null;
			$detail->quotation_total = null;
			$detail->quotation_subtotal = null;
			$detail->quotation_status = null;
			$detail->quotation_cdate = null;
			$detail->quotation_mdate = null;
			$detail->quotation_note = null;
			$detail->quotation_ipaddress = $_SERVER ['REMOTE_ADDR'];
			$detail->firstname = null;
			$detail->lastname = null;
			$detail->address = null;
			$detail->zipcode = null;
			$detail->city = null;
			$detail->country_code = null;
			$detail->state_code = null;
			$detail->phone = null;
			$detail->user_email = null;
			$detail->is_company = null;
			$detail->vat_number = null;
			$detail->tax_exempt = null;
			$detail->quotation_encrkey = null;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$row = $this->getTable();

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

		$quotation_item = $data['quotation_item'];

		for ($i = 0, $in = count($quotation_item); $i < $in; $i++)
		{
			if (array_key_exists("quotation_item_id", $quotation_item[$i]))
			{
				$rowitem = $this->getTable('quotation_item_detail');
				$quotation_item[$i]->quotation_id = $row->quotation_id;

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

	public function delete($cid = array())
	{
		$quotationHelper = quotationHelper::getInstance();

		if (count($cid))
		{
			$cids = implode(',', $cid);
			$items = $quotationHelper->getQuotationProduct($cids);

			for ($i = 0, $in = count($items); $i < $in; $i++)
			{
				$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_accessory_item '
					. 'WHERE quotation_item_id = ' . $items[$i]->quotation_item_id . ' ';
				$this->_db->setQuery($query);

				if (!$this->_db->execute())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_attribute_item '
					. 'WHERE quotation_item_id = ' . $items[$i]->quotation_item_id . ' ';
				$this->_db->setQuery($query);

				if (!$this->_db->execute())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_fields_data '
					. 'WHERE quotation_item_id = ' . $items[$i]->quotation_item_id . ' ';
				$this->_db->setQuery($query);

				if (!$this->_db->execute())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_item '
				. 'WHERE quotation_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation WHERE quotation_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function deleteitem($cids = 0, $quotation_id = 0)
	{
		$quotationHelper = quotationHelper::getInstance();

		$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_fields_data '
			. 'WHERE quotation_item_id IN ( ' . $cids . ' ) ';
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_accessory_item '
			. 'WHERE quotation_item_id IN ( ' . $cids . ' )';
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_attribute_item '
			. 'WHERE quotation_item_id IN ( ' . $cids . ' )';
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_item '
			. 'WHERE quotation_item_id IN ( ' . $cids . ' )';
		$this->_db->setQuery($query);

		if (!$this->_db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		// Update Quotation Record
		$QuotationData = $this->getTable('quotation_detail');
		$QuotationData->load($quotation_id);

		$QuotationTotal       = 0;
		$QuotationSubTotal    = 0;
		$QuotationSpDiscount  = 0;
		$QuotationDiscount    = 0;
		$QuotationTotDiscount = 0;
		$QuotationTax         = 0;
		$quotationItems       = $quotationHelper->getQuotationProduct($QuotationData->quotation_id);

		for ($q = 0, $qn = count($quotationItems); $q < $qn; $q++)
		{
			$QuotationSubTotal += ($quotationItems[$q]->product_excl_price * $quotationItems[$q]->product_quantity);
			$QuotationTax += ($quotationItems[$q]->product_final_price - $quotationItems[$q]->product_excl_price) * $quotationItems[$q]->product_quantity;
		}

		// Count final Total
		$QuotationTotal = $QuotationTotal - $QuotationTotDiscount + $QuotationTax;

		// Deduct normal Discount
		$QuotationDiscount = $QuotationData->quotation_discount;

		// Special Discount
		$QuotationSpDiscount = ($QuotationData->quotation_special_discount * ($QuotationSubTotal + $QuotationTax)) / 100;

		// Total Discount
		$QuotationTotDiscount = $QuotationDiscount + $QuotationSpDiscount;

		// Count final Total
		$QuotationTotal = ($QuotationSubTotal + $QuotationTax) - $QuotationTotDiscount;

		$QuotationData->quotation_tax      = $QuotationTax;
		$QuotationData->quotation_total    = $QuotationTotal;
		$QuotationData->quotation_subtotal = $QuotationSubTotal;
		$QuotationData->quotation_mdate    = time();

		if (!$QuotationData->store())
		{
			return false;
		}

		// Update Quotation Record
		return true;
	}

	// Add new Quotation Item
	public function newQuotationItem($data)
	{
		$quotationHelper = quotationHelper::getInstance();
		$rsCarthelper = rsCarthelper::getInstance();
		$producthelper = productHelper::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$item = $data['quotation_item'];

		// Get Order Info
		$quotationdata = $this->getTable('quotation_detail');
		$quotationdata->load($this->_id);

		$user_id = $quotationdata->user_id;

		// Set Order Item Info
		$qitemdata = $this->getTable('quotation_item_detail');

		for ($i = 0, $in = count($item); $i < $in; $i++)
		{
			$product_id = $item[$i]->product_id;
			$quantity = $item[$i]->quantity;
			$product_excl_price = $item[$i]->prdexclprice;
			$product_price = $item[$i]->productprice;

			$product = Redshop::product((int) $product_id);

			$generateAttributeCart = $rsCarthelper->generateAttributeArray((array) $item[$i], $user_id);
			$retAttArr = $producthelper->makeAttributeCart($generateAttributeCart, $product_id, $user_id, 0, $quantity);
			$product_attribute = $retAttArr[0];

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

			$qitemdata = $this->getTable('quotation_item_detail');

			$qitemdata->quotation_item_id = 0;
			$qitemdata->quotation_id = $this->_id;
			$qitemdata->product_id = $product_id;
			$qitemdata->is_giftcard = 0;
			$qitemdata->product_name = $product->product_name;
			$qitemdata->actualitem_price = $product_price;
			$qitemdata->product_price = $product_price;
			$qitemdata->product_excl_price = $product_excl_price;
			$qitemdata->product_final_price = $product_price * $quantity;
			$qitemdata->product_attribute = $product_attribute;
			$qitemdata->product_accessory = $product_accessory;
			$qitemdata->product_wrapperid = $item[$i]->wrapper_data;
			$qitemdata->wrapper_price = $wrapper_price;
			$qitemdata->product_quantity = $quantity;

			if (!$qitemdata->store())
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
						$accessory_vat_price = $producthelper->getProductTax($qitemdata->product_id, $accessory_price, $user_id);
					}

					$attchildArr = $attArr[$a]['accessory_childs'];

					for ($j = 0, $jn = count($attchildArr); $j < $jn; $j++)
					{
						$attribute_id = $attchildArr[$j]['attribute_id'];
						$accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

						$rowattitem = $this->getTable('quotation_attribute_item');
						$rowattitem->quotation_att_item_id = 0;
						$rowattitem->quotation_item_id = $qitemdata->quotation_item_id;
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
								$section_vat = $producthelper->getProducttax($qitemdata->product_id, $propArr[$k]['property_price'], $user_id);
							}

							$property_id = $propArr[$k]['property_id'];
							$accessory_attribute .= urldecode($propArr[$k]['property_name']) . " (" . $propArr[$k]['property_oprand']
								. $producthelper->getProductFormattedPrice($propArr[$k]['property_price'] + $section_vat) . ")<br/>";
							$subpropArr = $propArr[$k]['property_childs'];

							$rowattitem = $this->getTable('quotation_attribute_item');
							$rowattitem->quotation_att_item_id = 0;
							$rowattitem->quotation_item_id = $qitemdata->quotation_item_id;
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
									$section_vat = $producthelper->getProducttax($qitemdata->product_id, $subpropArr[$l]['subproperty_price'], $user_id);
								}

								$subproperty_id = $subpropArr[$l]['subproperty_id'];
								$accessory_attribute .= urldecode($subpropArr[$l]['subproperty_name']) . " (" . $subpropArr[$l]['subproperty_oprand']
									. $producthelper->getProductFormattedPrice($subpropArr[$l]['subproperty_price'] + $section_vat) . ")<br/>";

								$rowattitem = $this->getTable('quotation_attribute_item');
								$rowattitem->quotation_att_item_id = 0;
								$rowattitem->quotation_item_id = $qitemdata->quotation_item_id;
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
					$rowaccitem->quotation_item_id = $qitemdata->quotation_item_id;
					$rowaccitem->accessory_id = $accessory_id;
					$rowaccitem->accessory_item_sku = $accProductinfo->product_number;
					$rowaccitem->accessory_item_name = $accessory_name;
					$rowaccitem->accessory_price = $accessory_org_price;
					$rowaccitem->accessory_vat = $accessory_vat_price;
					$rowaccitem->accessory_quantity = $qitemdata->product_quantity;
					$rowaccitem->accessory_item_price = $accessory_price;
					$rowaccitem->accessory_final_price = ($accessory_price * $qitemdata->product_quantity);
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
					$rowattitem->quotation_item_id = $qitemdata->quotation_item_id;
					$rowattitem->section_id = $attribute_id;
					$rowattitem->section = "attribute";
					$rowattitem->parent_section_id = $qitemdata->product_id;
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
							$section_vat = $producthelper->getProducttax($qitemdata->product_id, $propArr[$k]['property_price'], $user_id);
						}

						$property_id = $propArr[$k]['property_id'];

						/** product property STOCKROOM update start */
						$updatestock = $stockroomhelper->updateStockroomQuantity($property_id, $qitemdata->product_quantity, "property");

						$rowattitem = $this->getTable('quotation_attribute_item');
						$rowattitem->quotation_att_item_id = 0;
						$rowattitem->quotation_item_id = $qitemdata->quotation_item_id;
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
								$section_vat = $producthelper->getProducttax($qitemdata->product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							$subproperty_id = $subpropArr[$l]['subproperty_id'];

							/** product subproperty STOCKROOM update start */
							$updatestock = $stockroomhelper->updateStockroomQuantity($subproperty_id, $qitemdata->product_quantity, "subproperty");

							$rowattitem = $this->getTable('quotation_attribute_item');
							$rowattitem->quotation_att_item_id = 0;
							$rowattitem->quotation_item_id = $qitemdata->quotation_item_id;
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
			$userfields = JRequest::getVar('extrafields' . $qitemdata->product_id);
			$userfields_id = JRequest::getVar('extrafields_id_' . $qitemdata->product_id);

			for ($ui = 0, $countUserField = count($userfields); $ui < $countUserField; $ui++)
			{
				$quotationHelper->insertQuotationUserfield($userfields_id[$ui], $qitemdata->quotation_item_id, 12, $userfields[$ui]);
			}
		}

		// Update Quotation Record
		$QuotationData = $this->getTable('quotation_detail');
		$QuotationData->load($this->_id);

		$QuotationTotal       = 0;
		$QuotationSubTotal    = 0;
		$QuotationSpDiscount  = 0;
		$QuotationDiscount    = 0;
		$QuotationTotDiscount = 0;
		$QuotationTax         = 0;
		$quotationItems       = $quotationHelper->getQuotationProduct($QuotationData->quotation_id);

		for ($q = 0, $qn = count($quotationItems); $q < $qn; $q++)
		{
			$QuotationSubTotal += ($quotationItems[$q]->product_excl_price * $quotationItems[$q]->product_quantity);
			$QuotationTax += ($quotationItems[$q]->product_final_price - $quotationItems[$q]->product_excl_price) * $quotationItems[$q]->product_quantity;
		}

		// Deduct normal Discount
		$QuotationDiscount = $QuotationData->quotation_discount;

		// Special Discount
		$QuotationSpDiscount = ($QuotationData->quotation_special_discount * ($QuotationSubTotal + $QuotationTax)) / 100;

		// Total Discount
		$QuotationTotDiscount = $QuotationDiscount + $QuotationSpDiscount;

		// Count final Total
		$QuotationTotal = ($QuotationSubTotal + $QuotationTax) - $QuotationTotDiscount;

		$QuotationData->quotation_tax      = $QuotationTax;
		$QuotationData->quotation_total    = $QuotationTotal;
		$QuotationData->quotation_subtotal = $QuotationSubTotal;
		$QuotationData->quotation_mdate    = time();

		if (!$QuotationData->store())
		{
			return false;
		}
		else
		{
			return true;
		}

		// End
		return true;
	}

	public function storeOrder($data)
	{
		$orderFunctions = order_functions::getInstance();
		$producthelper  = productHelper::getInstance();
		$db             = $this->getDbo();
		$orderNumber    = $orderFunctions->generateOrderNumber();
		$encrKey        = $orderFunctions->random_gen_enc_key(35);

		$row                          = $this->getTable('order_detail');
		$row->user_id                 = (int) $data['user_id'];
		$row->order_number            = $orderNumber;
		$row->user_info_id            = (int) $data['user_info_id'];
		$row->order_total             = $data['quotation_total'];
		$row->order_subtotal          = $data['quotation_subtotal'];
		$row->order_tax               = $data['quotation_tax'];
		$row->order_discount          = $data['quotation_discount'];
		$row->special_discount_amount = $data['quotation_special_discount'];
		$row->order_status            = REDSHOP_ORDER_STATUS_PAID;
		$row->order_payment_status    = REDSHOP_ORDER_PAYMENT_STATUS_UNPAID;
		$row->cdate                   = time();
		$row->mdate                   = time();
		$row->ip_address              = $data['quotation_ipaddress'];
		$row->encr_key                = $encrKey;
		$row->special_discount        = $data['quotation_special_discount'];
		$row->order_discount_vat      = $data['Discountvat'];

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$orderId = $row->order_id;

		$rowOrderStatus                = $this->getTable('order_status_log');
		$rowOrderStatus->order_id      = $orderId;
		$rowOrderStatus->order_status  = REDSHOP_ORDER_STATUS_PAID;
		$rowOrderStatus->date_changed  = time();
		$rowOrderStatus->customer_note = '';
		$rowOrderStatus->store();

		foreach ($data['quotation_item'] as $key => $item)
		{
			$rowItem = $this->getTable('order_item_detail');

			if (!empty($item->quotation_item_id))
			{
				$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_quotation_item'))
					->where($db->qn('quotation_item_id') . ' = ' . $db->q((int) $item->quotation_item_id));
				$quotationItem = $db->setQuery($query)->loadObject();

				$product = $producthelper->getProductById($quotationItem->product_id);

				$rowItem->order_id                    = $orderId;
				$rowItem->user_info_id                = $data['user_info_id'];
				$rowItem->supplier_id                 = $product->supplier_id;
				$rowItem->product_id                  = $quotationItem->product_id;
				$rowItem->order_item_sku              = $product->product_number;
				$rowItem->order_item_name             = $product->product_name;
				$rowItem->product_quantity            = $quotationItem->product_quantity;
				$rowItem->product_item_price          = $quotationItem->product_price;
				$rowItem->product_item_price_excl_vat = $quotationItem->product_excl_price;
				$rowItem->product_final_price         = $quotationItem->product_final_price;
				$rowItem->order_item_currency         = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
				$rowItem->order_status                = REDSHOP_ORDER_STATUS_PAID;
				$rowItem->cdate                       = time();
				$rowItem->mdate                       = time();
				$rowItem->product_attribute           = $quotationItem->product_attribute;
				$rowItem->product_accessory           = $quotationItem->product_accessory;
				$rowItem->is_giftcard                 = $quotationItem->is_giftcard;
				$rowItem->wrapper_id                  = $quotationItem->product_wrapperid;
				$rowItem->wrapper_price               = $quotationItem->wrapper_price;

				if (!$rowItem->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		$userRow = $this->getTable('user_detail');
		$userRow->load($data['user_info_id']);
		$orderUser = $this->getTable('order_user_detail');

		if (!$orderUser->bind($userRow))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$orderUser->order_id     = $orderId;
		$orderUser->address_type = 'BT';

		if (!$orderUser->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$fields = array(
			$db->qn('order_id') . ' = ' . $db->q((int) $orderId)
		);

		$conditions = array(
			$db->qn('quotation_id') . ' = ' . $db->q((int) $data['quotation_id'])
		);

		$query = $db->getQuery(true)
				->clear()
				->update($db->qn('#__redshop_quotation'))
				->set($fields)
				->where($conditions);

		return $db->setQuery($query)->execute();
	}
}
