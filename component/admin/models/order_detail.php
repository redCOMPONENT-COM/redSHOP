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

require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/cart.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/extra_field.php';
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/order.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/quotation.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/mail.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/product.php';

class order_detailModelorder_detail extends JModel
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
		$order_functions = new order_functions;

		if (empty($this->_data))
		{
			$this->_data = $order_functions->getOrderDetails($this->_id);

			return (boolean) $this->_data;
		}

		return true;
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

	public function store($data)
	{
		$row =& $this->getTable();

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

		return true;
	}

	public function delete($cid = array())
	{
		$producthelper = new producthelper;
		$order_functions = new order_functions;
		$quotationHelper = new quotationHelper;
		$stockroomhelper = new rsstockroomhelper;

		if (count($cid))
		{
			if (ECONOMIC_INTEGRATION == 1)
			{
				$economic = new economic;

				for ($i = 0; $i < count($cid); $i++)
				{
					$orderdata = $this->getTable('order_detail');
					$orderdata->load($cid[$i]);
					$invoiceHandle = $economic->deleteInvoiceInEconomic($orderdata);
				}
			}

			$cids = implode(',', $cid);
			$db = JFactory::getDBO();
			$order_item = $order_functions->getOrderItemDetail($cids);

			for ($i = 0; $i < count($order_item); $i++)
			{
				$quntity = $order_item[$i]->product_quantity;

				$order_id = $order_item[$i]->order_id;
				$order_detail = $order_functions->getOrderDetails($order_id);

				if ($order_detail->order_payment_status == "Unpaid")
				{
					// Update stock roommanageStockAmount
					$stockroomhelper->manageStockAmount($order_item[$i]->product_id, $quntity, $order_item[$i]->stockroom_id);
				}

				$producthelper->makeAttributeOrder($order_item[$i]->order_item_id, 0, $order_item[$i]->product_id, 1);
				$query = "DELETE FROM `" . $this->_table_prefix . "order_attribute_item` "
					. "WHERE `order_item_id` = " . $order_item[$i]->order_item_id;
				$this->_db->setQuery($query);
				$this->_db->query();


				$query = "DELETE FROM `" . $this->_table_prefix . "order_acc_item` "
					. "WHERE `order_item_id` = " . $order_item[$i]->order_item_id;
				$this->_db->setQuery($query);
				$this->_db->query();
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'orders WHERE order_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'order_item WHERE order_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
			$query = 'DELETE FROM ' . $this->_table_prefix . 'order_payment WHERE order_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'order_users_info WHERE order_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$quotation = $quotationHelper->getQuotationwithOrder($cids);

			for ($q = 0; $q < count($quotation); $q++)
			{
				$quotation_item = $quotationHelper->getQuotationProduct($quotation[$q]->quotation_id);

				for ($j = 0; $j < count($quotation_item); $j++)
				{
					$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_fields_data '
						. 'WHERE quotation_item_id=' . $quotation_item[$j]->quotation_item_id;
					$this->_db->setQuery($query);

					if (!$this->_db->query())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}

				$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_item '
					. 'WHERE quotation_id=' . $quotation[$q]->quotation_id;
				$this->_db->setQuery($query);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'quotation WHERE order_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function getProducts($order_id)
	{
		$query = "SELECT DISTINCT( p.product_id ) as value,p.product_name as text,oi.order_id FROM "
			. $this->_table_prefix . "product as p ," . $this->_table_prefix
			. "order_item as oi WHERE  oi.product_id != p.product_id AND oi.order_id = " . $order_id;
		$this->_db->setQuery($query);
		$products = $this->_db->loadObjectlist();

		return $products;
	}

	public function neworderitem($data, $quantity, $order_item_id)
	{
		$adminproducthelper = new adminproducthelper;
		$producthelper = new producthelper;
		$rsCarthelper = new rsCarthelper;
		$stockroomhelper = new rsstockroomhelper;

		// Get Order Info
		$orderdata = $this->getTable('order_detail');
		$orderdata->load($this->_id);

		$item = $data['order_item'];
		// Get product Info

		// Set Order Item Info
		$orderitemdata = $this->getTable('order_item_detail');
		$orderitemdata->load($order_item_id);

		$user_id = $orderdata->user_id;

		for ($i = 0; $i < count($item); $i++)
		{
			$product_id = $item[$i]->product_id;
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

			$product = $producthelper->getProductById($product_id);


			$updatestock = $stockroomhelper->updateStockroomQuantity($product_id, $quantity);
			$stockroom_id_list = $updatestock['stockroom_list'];
			$stockroom_quantity_list = $updatestock['stockroom_quantity_list'];

			$orderitemdata->stockroom_id = $stockroom_id_list;
			$orderitemdata->stockroom_quantity = $stockroom_quantity_list;
			$orderitemdata->order_item_id = 0;
			$orderitemdata->order_id = $this->_id;
			$orderitemdata->user_info_id = $orderdata->user_info_id;
			$orderitemdata->supplier_id = $product->manufacturer_id;
			$orderitemdata->product_id = $product_id;
			$orderitemdata->order_item_sku = $product->product_number;
			$orderitemdata->order_item_name = $product->product_name;
			$orderitemdata->product_quantity = $quantity;
			$orderitemdata->product_item_price = $product_price;
			$orderitemdata->product_item_price_excl_vat = $product_excl_price;
			$orderitemdata->product_final_price = $product_price * $quantity;
			$orderitemdata->order_item_currency = REDCURRENCY_SYMBOL;
			$orderitemdata->order_status = "P";
			$orderitemdata->cdate = time();
			$orderitemdata->mdate = time();
			$orderitemdata->product_attribute = $product_attribute;
			$orderitemdata->product_accessory = $product_accessory;
			$orderitemdata->wrapper_id = $item[$i]->wrapper_data;
			$orderitemdata->wrapper_price = $wrapper_price;

			if ($producthelper->checkProductDownload($product_id))
			{
				$medianame = $producthelper->getProductMediaName($product_id);

				for ($j = 0; $j < count($medianame); $j++)
				{
					$sql = "INSERT INTO " . $this->_table_prefix . "product_download "
						. "(product_id, user_id, order_id, end_date, download_max, download_id, file_name) "
						. "VALUES('" . $product_id . "', '" . $user_id . "', '" . $this->_id . "', "
						. "'" . (time() + (PRODUCT_DOWNLOAD_DAYS * 23 * 59 * 59)) . "', '" . PRODUCT_DOWNLOAD_LIMIT . "', "
						. "'" . md5(uniqid(mt_rand(), true)) . "', '" . $medianame[$j]->media_name . "')";
					$this->_db->setQuery($sql);
					$this->_db->query();
				}
			}

			if (!$orderitemdata->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			/** my accessory save in table start */
			if (count($generateAccessoryCart) > 0)
			{
				$attArr = $generateAccessoryCart;

				for ($a = 0; $a < count($attArr); $a++)
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

					for ($j = 0; $j < count($attchildArr); $j++)
					{
						$attribute_id = $attchildArr[$j]['attribute_id'];
						$accessory_attribute .= urldecode($attchildArr[$j]['attribute_name']) . ":<br/>";

						$rowattitem = & $this->getTable('order_attribute_item');
						$rowattitem->order_att_item_id = 0;
						$rowattitem->order_item_id = $orderitemdata->order_item_id;
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

						for ($k = 0; $k < count($propArr); $k++)
						{
							$section_vat = 0;

							if ($propArr[$k]['property_price'] > 0)
							{
								$section_vat = $producthelper->getProducttax($product_id, $propArr[$k]['property_price'], $user_id);
							}

							$property_id = $propArr[$k]['property_id'];
							$accessory_attribute .= urldecode($propArr[$k]['property_name']) . " (" . $propArr[$k]['property_oprand']
								. $producthelper->getProductFormattedPrice($propArr[$k]['property_price'] + $section_vat) . ")<br/>";
							$subpropArr = $propArr[$k]['property_childs'];

							$rowattitem = & $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id = 0;
							$rowattitem->order_item_id = $orderitemdata->order_item_id;
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

							for ($l = 0; $l < count($subpropArr); $l++)
							{
								$section_vat = 0;

								if ($subpropArr[$l]['subproperty_price'] > 0)
								{
									$section_vat = $producthelper->getProducttax($rowitem->product_id, $subpropArr[$l]['subproperty_price'], $user_id);
								}

								$subproperty_id = $subpropArr[$l]['subproperty_id'];
								$accessory_attribute .= urldecode($subpropArr[$l]['subproperty_name']) . " ("
									. $subpropArr[$l]['subproperty_oprand']
									. $producthelper->getProductFormattedPrice($subpropArr[$l]['subproperty_price'] + $section_vat)
									. ")<br/>";

								$rowattitem = & $this->getTable('order_attribute_item');
								$rowattitem->order_att_item_id = 0;
								$rowattitem->order_item_id = $orderitemdata->order_item_id;
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

					$accdata = & $this->getTable('accessory_detail');

					if ($accessory_id > 0)
					{
						$accdata->load($accessory_id);
					}

					$accessoryproduct = $producthelper->getProductById($accdata->child_product_id);
					$rowaccitem = & $this->getTable('order_acc_item');
					$rowaccitem->order_item_acc_id = 0;
					$rowaccitem->order_item_id = $orderitemdata->order_item_id;
					$rowaccitem->product_id = $accessory_id;
					$rowaccitem->order_acc_item_sku = $accessoryproduct->product_number;
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

				for ($j = 0; $j < count($attArr); $j++)
				{
					$attribute_id = $attArr[$j]['attribute_id'];

					$rowattitem = & $this->getTable('order_attribute_item');
					$rowattitem->order_att_item_id = 0;
					$rowattitem->order_item_id = $orderitemdata->order_item_id;
					$rowattitem->section_id = $attribute_id;
					$rowattitem->section = "attribute";
					$rowattitem->parent_section_id = $product_id;
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

					for ($k = 0; $k < count($propArr); $k++)
					{
						$section_vat = 0;

						if ($propArr[$k]['property_price'] > 0)
						{
							$section_vat = $producthelper->getProducttax($product_id, $propArr[$k]['property_price'], $usre_id);
						}

						$property_id = $propArr[$k]['property_id'];
						/** product property STOCKROOM update start */
						$updatestock = $stockroomhelper->updateStockroomQuantity($property_id, $quantity, "property");

						$rowattitem = & $this->getTable('order_attribute_item');
						$rowattitem->order_att_item_id = 0;
						$rowattitem->order_item_id = $orderitemdata->order_item_id;
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

						for ($l = 0; $l < count($subpropArr); $l++)
						{
							$section_vat = 0;

							if ($subpropArr[$l]['subproperty_price'] > 0)
							{
								$section_vat = $producthelper->getProducttax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							$subproperty_id = $subpropArr[$l]['subproperty_id'];
							/** product subproperty STOCKROOM update start */
							$updatestock = $stockroomhelper->updateStockroomQuantity($subproperty_id, $quantity, "subproperty");

							$rowattitem = & $this->getTable('order_attribute_item');
							$rowattitem->order_att_item_id = 0;
							$rowattitem->order_item_id = $orderitemdata->order_item_id;
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
			if (USE_CONTAINER)
			{
				$producthelper->updateContainerStock($product_id, $quantity, $orderitemdata->container_id);
			}

			// Store userfields
			$userfields = $item[$i]->extrafieldname;
			$userfields_id = $item[$i]->extrafieldId;

			for ($ui = 0; $ui < count($userfields); $ui++)
			{
				$adminproducthelper->admin_insertProdcutUserfield($userfields_id[$ui], $orderitemdata->order_item_id, 12, $userfields[$ui]);
			}
		}

		if ($orderitemdata->order_item_id > 0)
		{
			$totalItemVat = $orderitemdata->product_item_price - $orderitemdata->product_item_price_excl_vat;

			$orderdata->order_tax = $orderdata->order_tax + ($totalItemVat * $orderitemdata->product_quantity);
			$orderdata->order_total = $orderdata->order_total + $orderitemdata->product_final_price;
			$orderdata->order_subtotal = $orderdata->order_subtotal + $orderitemdata->product_final_price;
			$orderdata->mdate = time();

			// Update order detail
			if (!$orderdata->store())
			{
				return false;
			}

			if (ECONOMIC_INTEGRATION == 1)
			{
				$economic = new economic;
				$invoiceHandle = $economic->renewInvoiceInEconomic($orderdata);
			}

			// Send mail from template
			$redshopMail = new redshopMail;
			$redshopMail->sendOrderSpecialDiscountMail($this->_id);
		}

		else
		{
			return false;
		}

		return true;
	}

	public function delete_item($data)
	{
		$producthelper = new producthelper;
		$stockroomhelper = new rsstockroomhelper;

		$productid = $data['productid'];

		$order_item_id = $data['order_item_id'];

		// Get Order Item Info
		$orderitemdata = $this->getTable('order_item_detail');
		$orderitemdata->load($order_item_id);

		// Get Order Info
		$orderdata = $this->getTable('order_detail');
		$orderdata->load($this->_id);

		// Get order item price
		$product_vat = ($orderitemdata->product_item_price - $orderitemdata->product_item_price_excl_vat) * $orderitemdata->product_quantity;

		// Update stock room
		$stockroomhelper->manageStockAmount($productid, $orderitemdata->product_quantity, $orderitemdata->stockroom_id);

		$query = "DELETE FROM `" . $this->_table_prefix . "order_item` WHERE `order_item_id` = " . $order_item_id;
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
		else
		{
			$this->updateAttributeItem($order_item_id, $orderitemdata->product_quantity, $orderitemdata->stockroom_id);

			// Update Attribute stock room
			$query = "DELETE FROM `" . $this->_table_prefix . "order_attribute_item` "
				. "WHERE `order_item_id` = " . $order_item_id;
			$this->_db->setQuery($query);
			$this->_db->query();

			$query = "DELETE FROM `" . $this->_table_prefix . "order_acc_item` "
				. "WHERE `order_item_id` = " . $order_item_id;
			$this->_db->setQuery($query);
			$this->_db->query();

			$tmpArr['special_discount'] = $orderdata->special_discount;
			$this->special_discount($tmpArr, true);

			// Economic Integration start for invoice generate
			if (ECONOMIC_INTEGRATION == 1)
			{
				$economic = new economic;
				$invoiceHandle = $economic->renewInvoiceInEconomic($orderdata);
			}

			// Send mail from template ********************/
			$redshopMail = new redshopMail;
			$redshopMail->sendOrderSpecialDiscountMail($this->_id);
		}

		return true;
	}

	public function updateItem($data)
	{
		$producthelper = new producthelper;
		$order_functions = new order_functions;
		$stockroomhelper = new rsstockroomhelper;

		$order_item_id = $data['order_item_id'];
		$orderitemdata = $this->getTable('order_item_detail');
		$orderitemdata->load($order_item_id);
		$orderdata = $this->getTable('order_detail');
		$orderdata->load($this->_id);
		$order_id = $this->_id;
		$product_id = $orderitemdata->product_id;
		$currentStock = $stockroomhelper->getStockroomTotalAmount($product_id);
		$user_id = $orderdata->user_id;
		$productPrice = $data['update_price'];
		$productPrice_new = 0;

		if ($productPrice < 0)
		{
			$productPrice_new = $productPrice;
			$productPrice = $productPrice * -1;
		}

		$customer_note = $data['customer_note'];
		$product_tax = $producthelper->getProductTax($product_id, $productPrice, $user_id);

		if ($productPrice_new < 0)
		{
			$product_tax = $product_tax * -1;
			$productPrice = $productPrice_new;
		}

		$new_added_qty = $data['quantity'] - $orderitemdata->product_quantity;

		if ($currentStock >= $new_added_qty || USE_STOCKROOM == 0)
		{
			$quantity = (int) $data['quantity'];
		}
		else
		{
			$quantity = (int) $orderitemdata->product_quantity;
		}

		$product_item_price = $productPrice + $product_tax;
		$product_item_price_excl_vat = $productPrice;
		$product_final_price = $product_item_price * $quantity;
		$productTotalTax = $product_tax * $quantity;
		$subtotal = $product_item_price * $quantity;

		$OrderItems = $order_functions->getOrderItemDetail($order_id);
		$totalTax = $product_tax * $quantity;

		for ($i = 0; $i < count($OrderItems); $i++)
		{
			if ($order_item_id != $OrderItems[$i]->order_item_id)
			{
				$itemtax = $OrderItems[$i]->product_item_price - $OrderItems[$i]->product_item_price_excl_vat;
				$totalTax = $totalTax + ($itemtax * $OrderItems[$i]->product_quantity);
				$subtotal = $subtotal + ($OrderItems[$i]->product_item_price * $OrderItems[$i]->product_quantity);
			}

			if ($order_item_id == $OrderItems[$i]->order_item_id)
			{
				$newquantity = $OrderItems[$i]->product_quantity - $quantity;

				if ($newquantity > 0)
				{
					$stockroomhelper->manageStockAmount($product_id, $newquantity, $orderitemdata->stockroom_id);
				}
				elseif ($newquantity < 0)
				{
					$updatestock = $stockroomhelper->updateStockroomQuantity($product_id, (-$newquantity));

					$stockroom_id_list = $updatestock['stockroom_list'];
					$stockroom_quantity_list = $updatestock['stockroom_quantity_list'];
					$orderitemdata->stockroom_id = $stockroom_id_list;
					$orderitemdata->stockroom_quantity = $stockroom_quantity_list;
				}

				$this->updateAttributeItem($order_item_id, $newquantity, $orderitemdata->stockroom_id);
			}
		}

		$total = $subtotal + $orderdata->order_shipping - abs($orderdata->order_discount);
		$orderitemdata->product_item_price = $product_item_price;
		$orderitemdata->product_item_price_excl_vat = $product_item_price_excl_vat;
		$orderitemdata->product_final_price = $product_final_price;
		$orderitemdata->product_quantity = $quantity;
		$orderitemdata->customer_note = $customer_note;
		$orderdata->order_tax = $totalTax;
		$orderdata->order_total = $total;
		$orderdata->order_subtotal = $subtotal;

		if ($orderitemdata->store())
		{
			if (!$orderdata->store())
			{
				return false;
			}
			$tmpArr['special_discount'] = $orderdata->special_discount;
			$this->special_discount($tmpArr, true);
		}
		else
		{
			return false;
		}

		$order_functions->update_status();

		return true;
	}

	public function updateAttributeItem($order_item_id, $quantity = 0, $stockroom_id = 0)
	{
		$stockroomhelper = new rsstockroomhelper;
		$order_functions = new order_functions;
		$attArr = $order_functions->getOrderItemAttributeDetail($order_item_id, 0, "attribute");

		/** my attribute save in table start */
		for ($j = 0; $j < count($attArr); $j++)
		{
			$propArr = $order_functions->getOrderItemAttributeDetail($order_item_id, 0, "property", $attArr[$j]->section_id);

			for ($k = 0; $k < count($propArr); $k++)
			{
				$propitemdata = $this->getTable('order_attribute_item');
				$propitemdata->load($propArr[$k]->order_att_item_id);

				/** product property STOCKROOM update start */
				if ($quantity > 0)
				{
					$stockroomhelper->manageStockAmount($propitemdata->section_id, $quantity, $propArr[$k]->stockroom_id, "property");
				}
				elseif ($quantity < 0)
				{
					$updatestock = $stockroomhelper->updateStockroomQuantity($propitemdata->section_id, (-$quantity), "property");
				}

				$subpropArr = $order_functions->getOrderItemAttributeDetail($order_item_id, 0, "subproperty", $propitemdata->section_id);

				for ($l = 0; $l < count($subpropArr); $l++)
				{
					$subpropitemdata = $this->getTable('order_attribute_item');
					$subpropitemdata->load($subpropArr[$l]->order_att_item_id);

					if ($quantity > 0)
					{
						$stockroomhelper->manageStockAmount($subpropitemdata->section_id, $quantity, $subpropArr[$l]->stockroom_id, "subproperty");
					}
					elseif ($quantity < 0)
					{
						$updatestock = $stockroomhelper->updateStockroomQuantity($subpropitemdata->section_id, (-$quantity), "subproperty");
					}
				}
			}
		}

		return true;
	}

	public function update_discount($data)
	{
		// Get Order Info
		$orderdata = $this->getTable('order_detail');
		$orderdata->load($this->_id);
		$order_functions = new order_functions;
		$OrderItems = $order_functions->getOrderItemDetail($this->_id);
		$update_discount = abs($data['update_discount']);

		if ($update_discount == $orderdata->order_discount)
		{
			return false;
		}
		$subtotal = 0;

		for ($i = 0; $i < count($OrderItems); $i++)
		{
			if ($order_item_id != $OrderItems[$i]->order_item_id)
			{
				$subtotal = $subtotal + ($OrderItems[$i]->product_item_price * $OrderItems[$i]->product_quantity);
			}
		}

		$temporder_total = $subtotal + $orderdata->order_discount + $orderdata->special_discount_amount;

		if ($update_discount > $temporder_total)
		{
			$update_discount = $subtotal;
		}
		if (APPLY_VAT_ON_DISCOUNT == '0' && VAT_RATE_AFTER_DISCOUNT && $update_discount != "0.00" && $orderdata->order_tax && !empty($update_discount))
		{
			$Discountvat = (VAT_RATE_AFTER_DISCOUNT * $update_discount);
			$update_discount = $update_discount + $Discountvat;
		}
		if (abs($data['update_discount']) == 0)
		{
			$order_total = ($subtotal + $orderdata->order_shipping) - ($orderdata->special_discount_amount);
		}
		else
		{
			$order_total = ($subtotal + $orderdata->order_shipping) - ($update_discount) - ($orderdata->special_discount_amount);
		}

		$orderdata->order_total = $order_total;
		$orderdata->order_discount = $update_discount;
		$orderdata->mdate = time();

		if (!$orderdata->store())
		{
			return false;
		}
		// Economic Integration start for invoice generate
		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic;
			$invoiceHandle = $economic->renewInvoiceInEconomic($orderdata);
		}

		// Send mail from template
		$redshopMail = new redshopMail;
		$redshopMail->sendOrderSpecialDiscountMail($this->_id);

		return true;
	}

	public function special_discount($data, $chk = false)
	{
		$redshopMail = new redshopMail;

		$orderdata = $this->getTable('order_detail');
		$orderdata->load($this->_id);
		$order_functions = new order_functions;
		$OrderItems = $order_functions->getOrderItemDetail($this->_id);

		if (!$orderdata->special_discount)
		{
			$orderdata->special_discount = 0;
		}

		if (!$orderdata->special_discount_amount)
		{
			$orderdata->special_discount_amount = 0;
		}

		if ($data['special_discount'] == $orderdata->special_discount && $chk != true)
		{
			return false;
		}

		$special_discount = $data['special_discount'];

		$subtotal = 0;
		$subtotal_excl_vat = 0;

		for ($i = 0; $i < count($OrderItems); $i++)
		{
			if ($order_item_id != $OrderItems[$i]->order_item_id)
			{
				$subtotal_excl_vat = $subtotal_excl_vat + ($OrderItems[$i]->product_item_price_excl_vat * $OrderItems[$i]->product_quantity);
				$subtotal = $subtotal + ($OrderItems[$i]->product_item_price * $OrderItems[$i]->product_quantity);
			}
		}
		if (APPLY_VAT_ON_DISCOUNT)
		{
			$amt = $subtotal;
		}
		else
		{
			$amt = $subtotal_excl_vat;
		}

		$discount_price = ($amt * $special_discount) / 100;
		$orderdata->special_discount = $special_discount;
		$orderdata->special_discount_amount = $discount_price;

		$order_total = $subtotal + $orderdata->order_shipping - $discount_price - $orderdata->order_discount;
		$orderdata->order_total = $order_total;
		$orderdata->mdate = time();

		if (!$orderdata->store())
		{
			return false;
		}

		if (ECONOMIC_INTEGRATION == 1)
		{
			$economic = new economic;
			$invoiceHandle = $economic->renewInvoiceInEconomic($orderdata);
		}

		// Send mail from template
		$redshopMail->sendOrderSpecialDiscountMail($this->_id);

		return true;
	}

	public function update_shippingrates($data)
	{
		$redhelper = new redhelper;
		$shippinghelper = new shipping;

		// Get Order Info
		$orderdata = $this->getTable('order_detail');
		$orderdata->load($this->_id);

		if ($data['shipping_rate_id'] != "")
		{
			// Get Shipping rate info Info
			$decry = $shippinghelper->decryptShipping(str_replace(" ", "+", $data['shipping_rate_id']));
			$neworder_shipping = explode("|", $decry);

			if ($data['shipping_rate_id'] != $orderdata->ship_method_id || $neworder_shipping[0] == 'plgredshop_shippingdefault_shipping_GLS')
			{
				if (count($neworder_shipping) > 4)
				{
					// Shipping_rate_value
					$orderdata->order_total = $orderdata->order_total - $orderdata->order_shipping + $neworder_shipping[3];
					$orderdata->order_shipping = $neworder_shipping[3];
					$orderdata->ship_method_id = $data['shipping_rate_id'];
					$orderdata->order_shipping_tax = (isset($neworder_shipping[6]) && $neworder_shipping[6]) ? $neworder_shipping[6] : 0;
					$orderdata->mdate = time();
					$orderdata->shop_id = $data['shop_id'] . "###" . $data['gls_mobile'];

					if (!$orderdata->store())
					{
						return false;
					}

					// Economic Integration start for invoice generate
					if (ECONOMIC_INTEGRATION == 1)
					{
						$economic = new economic;
						$invoiceHandle = $economic->renewInvoiceInEconomic($orderdata);
					}
				}
			}
		}
		return true;
	}

	public function updateShippingAdd($data)
	{
		$row = $this->getTable('order_user_detail');
		$row->load($data['order_info_id']);

		$row->bind($data);

		if ($row->store())
		{

			if ($row->is_company == 1)
			{
				// Saving users extra fields information
				$field = new extra_field;

				// Field_section 8 :Company Address Section
				$list_field = $field->extra_field_save($data, 15, $row->users_info_id);
			}

			else
			{
				// Saving users extra fields information
				$field = new extra_field;

				// Field_section 7 :Customer Address Section
				$list_field = @$field->extra_field_save($data, 14, $row->users_info_id);
			}

			return true;
		}
		else
		{
			return false;
		}
	}

	public function updateBillingAdd($data)
	{
		$row = $this->getTable('order_user_detail');
		$row->load($data['order_info_id']);

		$row->bind($data);

		if ($row->store())
		{
			if ($row->is_company == 1)
			{
				// Saving users extra fields information
				$field = new extra_field;

				// Field_section 8 :Company Address Section
				$list_field = $field->extra_field_save($data, 8, $row->users_info_id);
			}
			else
			{
				// Saving users extra fields information
				$field = new extra_field;

				// Field_section 7 :Customer Address Section
				$list_field = @$field->extra_field_save($data, 7, $row->users_info_id);
			}

			return true;
		}
		else
		{
			return false;
		}
	}

	// Get order stats log
	public function getOrderLog($order_id)
	{
		$database = JFactory::getDBO();
		$sql = "SELECT log.*,order_status_name "
			. " FROM " . $this->_table_prefix . "order_status_log AS log , " . $this->_table_prefix . "order_status ros"
			. " WHERE log.order_id=" . $order_id . " AND log.order_status=ros.order_status_code";
		$database->setQuery($sql);

		return $database->loadObjectList();
	}

	// Get Product subscription price
	public function getProductSubscriptionDetail($product_id, $subscription_id)
	{
		$db = JFactory::getDBO();

		$query = "SELECT * "
			. " FROM " . $this->_table_prefix . "product_subscription"
			. " WHERE "
			. " product_id = " . $product_id . " And subscription_id = " . $subscription_id;
		$db->setQuery($query);

		return $db->loadObject();
	}

	// Get User Product subscription detail
	public function getUserProductSubscriptionDetail($order_item_id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT * "
			. " FROM " . $this->_table_prefix . "product_subscribe_detail"
			. " WHERE "
			. " order_item_id = " . $order_item_id;
		$db->setQuery($query);

		return $db->loadObject();
	}

	// Get credit card detail
	public function getccdetail($order_id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT * "
			. " FROM " . $this->_table_prefix . "order_payment  "
			. " WHERE "
			. " order_id = " . $order_id
			. " AND  payment_method_class='rs_payment_localcreditcard'";
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function send_downloadmail($oid)
	{
		$order_functions = new order_functions;

		if ($order_functions->SendDownload($oid))
		{
			return true;
		}

		else
		{
			return false;
		}
	}

	public function getvar($name)
	{
		global $_GET, $_POST;

		if (isset($_GET[$name]))
		{
			return $_GET[$name];
		}

		elseif (isset($_POST[$name]))
		{
			return $_POST[$name];
		}

		else
		{
			return false;
		}
	}

	public function update_ccdata($order_id, $payment_transaction_id)
	{
		$db = JFactory::getDBO();

		$session = JFactory::getSession();
		$ccdata = $session->get('ccdata');

		$order_payment_code = $ccdata['creditcard_code'];
		$order_payment_cardname = base64_encode($ccdata['order_payment_name']);
		$order_payment_number = base64_encode($ccdata['order_payment_number']);
		$order_payment_ccv = base64_encode($ccdata['credit_card_code']);
		$order_payment_expire = $ccdata['order_payment_expire_month'] . $ccdata['order_payment_expire_year'];
		$order_payment_trans_id = $payment_transaction_id;

		$payment_update = "UPDATE " . $this->_table_prefix . "order_payment "
			. " SET order_payment_code  = '" . $order_payment_code . "' ,"
			. " order_payment_cardname  = '" . $order_payment_cardname . "' ,"
			. " order_payment_number  = '" . $order_payment_number . "' ,"
			. " order_payment_ccv  = '" . $order_payment_ccv . "' ,"
			. " order_payment_expire  = '" . $order_payment_expire . "' ,"
			. " order_payment_trans_id  = '" . $payment_transaction_id . "' "
			. " WHERE order_id  = '" . $order_id . "'";

		$db->setQuery($payment_update);

		if (!$db->Query())
		{
			return false;
		}
	}

	public function getStockNoteTemplate()
	{
		$redTemplate = new Redtemplate;

		if (empty ($this->_template))
		{
			$this->_template = $redTemplate->getTemplate("stock_note", $this->_data->product_template);
			$this->_template = $this->_template[0];
		}

		return $this->_template;
	}
}
