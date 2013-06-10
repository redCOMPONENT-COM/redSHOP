<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

include_once (JPATH_SITE . '/components/com_redshop/helpers/product.php');

class shipping
{
	public $_db;

	public function __construct()
	{
		$this->_db = JFactory::getDBO();

		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$this->producthelper = new producthelper;
	}

	public function getDeliveryTimeOfProduct($product_id)
	{
		$sql = "SELECT max_del_time FROM " . $this->_table_prefix . "container c," . $this->_table_prefix . "container_product_xref cx
	     		WHERE  cx.container_id = c.container_id AND cx.product_id = '$product_id' order by max_del_time desc";
		$this->_db->setQuery($sql);
		$delivery = $this->_db->loadResult();

		if (!$delivery)
		{
			$delivery = 13;
		}

		return $delivery;
	}

	public function getProductContainerId($product_id)
	{
		$sql = "SELECT c.container_id FROM " . $this->_table_prefix . "container c," . $this->_table_prefix . "container_product_xref cx
	     		WHERE  cx.container_id = c.container_id AND cx.product_id = '$product_id' order by max_del_time desc";
		$this->_db->setQuery($sql);

		return $this->_db->loadResult();
	}

	function getRegularDelivery()
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');

		if (!$cart)
		{
			$cart         = array();
			$cart ['idx'] = 0;
			$session->set('cart', $cart);
			$cart = $session->get('cart');
		}

		$idx    = (int) ($cart ['idx']);

		$delarr = array();

		for ($i = 0; $i < $idx; $i++)
		{
			$product_id           = $cart [$i] ['product_id'];

			$deliveryTime         = $this->getDeliveryTimeOfProduct($product_id);

			$delarr [$product_id] = $deliveryTime;
		}

		@asort($delarr);

		$first = $delarr [key($delarr)];

		$end   = end($delarr);

		$diff  = $end - $first;

		if ($diff > DELIVERY_RULE)
		{
			$deliveryTime = DELIVERY_RULE;
		}
		else
		{
			$deliveryTime = $end;
		}

		for ($i = 0; $i < $idx; $i++)
		{
			$product_id           = $cart [$i] ['product_id'];

			$delarr [$product_id] = $deliveryTime;
		}

		return $delarr;
	}

	function getSplitDelivery()
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');

		if (!$cart)
		{
			$cart         = array();
			$cart ['idx'] = 0;

			$session->set('cart', $cart);
			$cart = $session->get('cart');
		}

		$idx    = (int) ($cart ['idx']);
		$delarr = array();
		$isDiff = 0;

		for ($i = 0; $i < $idx; $i++)
		{
			$product_id           = $cart [$i] ['product_id'];
			$deliveryTime         = $this->getDeliveryTimeOfProduct($product_id);
			$delarr [$product_id] = $deliveryTime;
		}

		@asort($delarr);

		$diffarr = array();

		$result = array_unique($delarr);

		if (count($result) == 1)
		{
			$split [0] = $this->getRegularDelivery();

			return $split;
		}

		$tmp = 0;

		for ($i = 0; $i < count($delarr); $i++)
		{
			$value = current($delarr);
			$key   = key($delarr);

			if ($nextval = next($delarr))
			{
				$diffarr [$key] = $nextval - $value;
				$tmp            = $nextval - $value;
			}
		}

		@arsort($diffarr);

		reset($diffarr);

		$splittokey = key($diffarr);
		$split1     = array();
		$split2     = array();
		$s          = 0;

		reset($delarr);

		for ($i = 0; $i < count($delarr); $i++)
		{
			$value = current($delarr);
			$key   = key($delarr);

			if ($s == 0)
			{
				$split1 [$key] = $value;
			}
			else
			{
				$split2 [$key] = $value;
			}

			if ($key == $splittokey)
			{
				$s = 1;
			}

			next($delarr);
		}

		$return    = array();
		$return[0] = $split1;

		if (count($split2) > 0)
		{
			$return [1] = $split2;
		}

		return ($return);
	}

	public function getProductDeliveryArray($shipping_rate_id)
	{
		if (strstr($shipping_rate_id, "regular"))
		{
			$deliveryArray = $this->getRegularDelivery();
		}
		else
		{
			$splitArray = $this->getSplitDelivery();

			if (count($splitArray) > 1)
			{
				$deliveryArray = $splitArray [0] + $splitArray [1];
			}
			else
			{
				$deliveryArray = $splitArray [0];
			}
		}

		return ($deliveryArray);
	}

	/**
	 *  function ******** To get Shipping rate for cart
	 */
	public function getDefaultShipping($d)
	{
		$userhelper      = new rsUserhelper;
		$session         = JFactory::getSession();
		$order_subtotal  = $d ['order_subtotal'];
		$user            = JFactory::getUser();
		$user_id         = $user->id;

		$totaldimention  = $this->getCartItemDimention();
		$weighttotal     = $totaldimention['totalweight'];
		$volume          = $totaldimention['totalvolume'];

		$order_functions = new order_functions;
		$userInfo        = $order_functions->getBillingAddress($user_id);
		$country         = '';
		$state           = '';
		$is_company      = '';
		$newpwhere       = '';
		$newcwhere       = '';
		$wherestate      = '';
		$whereshopper    = '';

		if ($userInfo)
		{
			$country    = $userInfo->country_code;
			$is_company = $userInfo->is_company;
			$user_id    = $userInfo->user_id;
			$state      = $userInfo->state_code;
		}

		$shoppergroup = $userhelper->getShoppergroupData($user_id);

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper     = ' AND (FIND_IN_SET( "' . $shopper_group_id . '", shipping_rate_on_shopper_group ) OR shipping_rate_on_shopper_group="") ';
		}

		if ($country)
		{
			$wherecountry = '(FIND_IN_SET( "' . $country . '", shipping_rate_country ) OR shipping_rate_country="0" OR shipping_rate_country="")';
		}
		else
		{
			$wherecountry = '(FIND_IN_SET( "' . DEFAULT_SHIPPING_COUNTRY . '", shipping_rate_country ) OR shipping_rate_country="0"
			OR shipping_rate_country="")';
		}

		if ($state)
		{
			$wherestate = ' AND (FIND_IN_SET( "' . $state . '", shipping_rate_state ) OR shipping_rate_state="0" OR shipping_rate_state="")';
		}

		if (!$is_company)
		{
			$iswhere = " AND ( company_only = 2 or company_only = 0) ";
		}

		else
		{
			$iswhere = " AND ( company_only = 1 or company_only = 0) ";
		}

		$shippingArr = $this->getShopperGroupDefaultShipping();

		$shopper_shipping = 0;

		if (empty($shippingArr))
		{
			$cart        = $session->get('cart');
			$idx         = (int) ($cart ['idx']);
			$totalVolume = 0;

			$shippingrate = array();

			if ($idx)
			{
				$pwhere = 'AND ( ';

				for ($i = 0; $i < $idx; $i++)
				{
					$product_id = $cart [$i] ['product_id'];
					$pwhere     .= 'FIND_IN_SET("' . $product_id . '", shipping_rate_on_product)';

					if ($i != $idx - 1)
					{
						$pwhere .= " or ";
					}
				}

				$pwhere    .= ")";
				$newpwhere = str_replace("AND (", "OR (", $pwhere);
				$sql       = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr "
							. "LEFT JOIN #__extensions AS s ON sr.shipping_class = s.element
		 	     				 WHERE s.folder='redshop_shipping' and s.enabled =1  and  $wherecountry
								 $iswhere
								 AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '$volume') OR (shipping_rate_volume_end = 0) )
								 AND ((shipping_rate_ordertotal_start <= '$order_subtotal' AND  shipping_rate_ordertotal_end >= '
								 $order_subtotal')  OR (shipping_rate_ordertotal_end = 0))
								 AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
								 $weighttotal')  OR (shipping_rate_weight_end = 0))
								 $pwhere $wherestate $whereshopper
								   ORDER BY s.ordering, sr.shipping_rate_priority  LIMIT 0,1";

				$this->_db->setQuery($sql);
				$shippingrate = $this->_db->loadObject();
			}

			if (!$shippingrate)
			{
				for ($i = 0; $i < $idx; $i++)
				{
					$product_id = $cart [$i] ['product_id'];
					$sel = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id = ' . $product_id;
					$this->_db->setQuery($sel);
					$categorydata = $this->_db->loadObjectList();
					$where = ' ';

					if ($categorydata)
					{
						$where = 'AND ( ';

						for ($c = 0; $c < count($categorydata); $c++)
						{
							$where .= " FIND_IN_SET('" . $categorydata [$c]->category_id . "', shipping_rate_on_category) ";

							if ($c != count($categorydata) - 1)
							{
								$where .= " or ";
							}
						}

						$where .= ")";
						$newcwhere = str_replace("AND (", "OR (", $where);
						$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr
									 LEFT JOIN #__extensions AS s
									 ON
									 sr.shipping_class = s.element
			 	     				 WHERE  s.folder='redshop_shipping' and s.enabled =1 and $wherecountry $whereshopper
									 $iswhere
									 AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '
									 $volume') OR (shipping_rate_volume_end = 0) )
									 AND ((shipping_rate_ordertotal_start <= '$order_subtotal' AND  shipping_rate_ordertotal_end >= '
									 $order_subtotal')  OR (shipping_rate_ordertotal_end = 0))
									 AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
									 $weighttotal')  OR (shipping_rate_weight_end = 0))
									 $where $wherestate
									ORDER BY s.ordering, sr.shipping_rate_priority  LIMIT 0,1";

						$this->_db->setQuery($sql);
						$shippingrate = $this->_db->loadObject();
					}
				}
			}

			if (!$shippingrate)
			{
				$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr
								 LEFT JOIN #__extensions AS s
								 ON
								 sr.shipping_class = s.element
		 	     		WHERE s.folder='redshop_shipping' and s.enabled =1  and $wherecountry $whereshopper
						$iswhere $wherestate
						AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '
						$volume') OR (shipping_rate_volume_end = 0) )
						AND ((shipping_rate_ordertotal_start <= '$order_subtotal' AND  shipping_rate_ordertotal_end >= '
						$order_subtotal')  OR (shipping_rate_ordertotal_end = 0))
						AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
						$weighttotal')  OR (shipping_rate_weight_end = 0))
						AND (shipping_rate_on_product = '' $newpwhere) AND (shipping_rate_on_category = '' $newcwhere )
						ORDER BY s.ordering, sr.shipping_rate_priority  LIMIT 0,1";

				$this->_db->setQuery($sql);
				$shippingrate = $this->_db->loadObject();
			}

			$total = 0;
			$shipping_vat = 0;

			if ($shippingrate)
			{
				$total = $shippingrate->shipping_rate_value;

				if ($shippingrate->apply_vat == 1)
				{
					$result = $this->getShippingVatRates($shippingrate->shipping_tax_group_id, $user_id);
					$chk    = $this->producthelper->taxexempt_addtocart($user_id);

					if (!empty($result) && !empty($chk))
					{
						if ($result->tax_rate > 0)
						{
							$shipping_vat = $total * $result->tax_rate;
							$total        = $shipping_vat + $total;
						}
					}
				}
			}

			$shipArr['shipping_rate'] = $total;
			$shipArr['shipping_vat']  = $shipping_vat;

			return $shipArr;
		}
		else
		{
			return $shippingArr;
		}
	}

	/**
	 *  function ******** To get Shipping rate for xmlexport
	 */
	public function getDefaultShipping_xmlexport($d)
	{
		$userhelper     = new rsUserhelper;
		$session        = JFactory::getSession();
		$order_subtotal = $d ['order_subtotal'];
		$user           = JFactory::getUser();
		$user_id        = $user->id;

		$data           = $this->producthelper->getProductById($d['product_id']);

		$totalQnt       = '';
		$weighttotal    = $data->weight;
		$volume         = $data->product_volume;
		$totalLength    = $data->product_length;
		$totalheight    = $data->product_height;
		$totalwidth     = $data->product_width;

		$userInfo       = $this->getShippingAddress($d['users_info_id']);
		$country        = '';
		$state          = '';
		$is_company     = '';
		$newpwhere      = '';
		$newcwhere      = '';
		$wherestate     = '';
		$whereshopper   = '';

		if ($userInfo)
		{
			$country    = $userInfo->country_code;
			$is_company = $userInfo->is_company;
			$user_id    = $userInfo->user_id;
			$state      = $userInfo->state_code;
		}

		$shoppergroup = $userhelper->getShoppergroupData($user_id);

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper = ' AND (FIND_IN_SET( "' . $shopper_group_id . '", shipping_rate_on_shopper_group )
			OR shipping_rate_on_shopper_group="") ';
		}

		if ($country)
		{
			$wherecountry = '(FIND_IN_SET( "' . $country . '", shipping_rate_country ) OR shipping_rate_country="0"
			OR shipping_rate_country="")';
		}
		else
		{
			$wherecountry = '(FIND_IN_SET( "' . DEFAULT_SHIPPING_COUNTRY . '", shipping_rate_country ) OR shipping_rate_country="0"
			OR shipping_rate_country="")';
		}

		if ($state)
		{
			$wherestate = ' AND (FIND_IN_SET( "' . $state . '", shipping_rate_state ) OR shipping_rate_state="0"
			OR shipping_rate_state="")';
		}

		if (!$is_company)
		{
			$iswhere = " AND ( company_only = 2 or company_only = 0) ";
		}
		else
		{
			$iswhere = " AND ( company_only = 1 or company_only = 0) ";
		}

		$shippingArr = $this->getShopperGroupDefaultShipping();

		$shopper_shipping = 0;

		if (empty($shippingArr))
		{
			$cart         = $session->get('cart');
			$idx          = (int) ($cart ['idx']);
			$totalVolume  = 0;

			$shippingrate = array();

			$pwhere = 'AND ( FIND_IN_SET("' . $product_id . '", shipping_rate_on_product) )';
			$newpwhere = str_replace("AND (", "OR (", $pwhere);

			$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr "
				. "LEFT JOIN #__extensions AS s ON sr.shipping_class = s.element
 	     				 WHERE s.folder='redshop_shipping' and  $wherecountry
						 $iswhere
						 AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '
						 $volume') OR (shipping_rate_volume_end = 0) )
						 AND ((shipping_rate_ordertotal_start <= '$order_subtotal' AND  shipping_rate_ordertotal_end >= '
						 $order_subtotal')  OR (shipping_rate_ordertotal_end = 0))
						 AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
						 $weighttotal')  OR (shipping_rate_weight_end = 0))
						 $pwhere $wherestate $whereshopper
						   ORDER BY sr.shipping_rate_priority  LIMIT 0,1";

			$this->_db->setQuery($sql);
			$shippingrate = $this->_db->loadObject();

			if (!$shippingrate)
			{
				$product_id = $cart ['product_id'];
				$sel = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id = ' . $product_id;
				$this->_db->setQuery($sel);
				$categorydata = $this->_db->loadObjectList();
				$where = ' ';

				if ($categorydata)
				{
					$where = 'AND ( ';

					for ($c = 0; $c < count($categorydata); $c++)
					{
						$where .= " FIND_IN_SET('" . $categorydata [$c]->category_id . "', shipping_rate_on_category) ";

						if ($c != count($categorydata) - 1)
						{
							$where .= " or ";
						}
					}

					$where .= ")";
					$newcwhere = str_replace("AND (", "OR (", $where);
					$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr
									 LEFT JOIN #__extensions AS s
									 ON
									 sr.shipping_class = s.element
			 	     				 WHERE  s.folder='redshop_shipping' and $wherecountry $whereshopper
									 $iswhere
									 AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '
									 $volume') OR (shipping_rate_volume_end = 0) )
									 AND ((shipping_rate_ordertotal_start <= '$order_subtotal' AND  shipping_rate_ordertotal_end >= '
									 $order_subtotal')  OR (shipping_rate_ordertotal_end = 0))
									 AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
									 $weighttotal')  OR (shipping_rate_weight_end = 0))
									 $where $wherestate
									ORDER BY sr.shipping_rate_priority  LIMIT 0,1";

					$this->_db->setQuery($sql);
					$shippingrate = $this->_db->loadObject();
				}
			}

			if (!$shippingrate)
			{
				$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr
								 LEFT JOIN #__extensions AS s
								 ON
								 sr.shipping_class = s.element
		 	     		WHERE s.folder='redshop_shipping' and $wherecountry $whereshopper
						$iswhere $wherestate
						AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '
						$volume') OR (shipping_rate_volume_end = 0) )
						AND ((shipping_rate_ordertotal_start <= '$order_subtotal' AND  shipping_rate_ordertotal_end >= '
						$order_subtotal')  OR (shipping_rate_ordertotal_end = 0))
						AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
						$weighttotal')  OR (shipping_rate_weight_end = 0))
						AND (shipping_rate_on_product = '' $newpwhere) AND (shipping_rate_on_category = '' $newcwhere )
						ORDER BY sr.shipping_rate_priority  LIMIT 0,1";

				$this->_db->setQuery($sql);
				$shippingrate = $this->_db->loadObject();
			}

			$total = 0;
			$shipping_vat = 0;

			if ($shippingrate)
			{
				$total = $shippingrate->shipping_rate_value;

				if ($shippingrate->apply_vat == 1)
				{
					$result = $this->getShippingVatRates($shippingrate->shipping_tax_group_id, $user_id);
					$chk    = $this->producthelper->taxexempt_addtocart($user_id);

					if (!empty($result) && !empty($chk))
					{
						if ($result->tax_rate > 0)
						{
							$shipping_vat = $total * $result->tax_rate;
							$total        = $shipping_vat + $total;
						}
					}
				}
			}

			$shipArr['shipping_rate'] = $total;
			$shipArr['shipping_vat']  = $shipping_vat;

			return $shipArr;
		}
		else
		{
			return $shippingArr;
		}
	}

	/**
	 * Return only one shipping rate on cart page...
	 * this function is called by ajax
	 */
	public function getShippingrate_calc()
	{
		$country    = JRequest::getVar('country_code');
		$state      = JRequest::getVar('state_code');
		$zip        = JRequest::getVar('zip_code');
		$ordertotal = 0;
		$rate       = 0;
		$session    = JFactory::getSession();
		$cart       = $session->get('cart');

		$idx        = (int) ($cart ['idx']);

		$pwhere     = "";
		$cwhere     = "";

		for ($i = 0; $i < $idx; $i++)
		{
			$ordertotal += ($cart[$i]['product_price'] * $cart[$i]['quantity']);

			$product_id = $cart [$i] ['product_id'];
			$pwhere     .= 'FIND_IN_SET("' . $product_id . '", shipping_rate_on_product)';

			if ($i != $idx - 1)
			{
				$pwhere .= " or ";
			}

			$sel = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id = ' . $product_id;
			$this->_db->setQuery($sel);
			$categorydata = $this->_db->loadObjectList();

			if ($categorydata)
			{
				$cwhere = ' ( ';

				for ($c = 0; $c < count($categorydata); $c++)
				{
					$cwhere .= " FIND_IN_SET('" . $categorydata [$c]->category_id . "', shipping_rate_on_category) ";

					if ($c != count($categorydata) - 1)
					{
						$cwhere .= " or ";
					}
				}

				$cwhere .= ")";
			}
		}

		if ($pwhere != "")
		{
			$pwhere = " OR (" . $pwhere . ")";
		}

		if ($cwhere != "")
		{
			$cwhere = " OR (" . $cwhere . ")";
		}

		$totaldimention = $this->getCartItemDimention();
		$weighttotal    = $totaldimention['totalweight'];
		$volume         = $totaldimention['totalvolume'];

		// Product volume based shipping
		$volumeShipping = $this->getProductVolumeShipping();

		$whereShippingVolume = "";

		for ($g = 0; $g < count($volumeShipping); $g++)
		{
			$length = $volumeShipping[$g]['length'];
			$width  = $volumeShipping[$g]['width'];
			$height = $volumeShipping[$g]['height'];

			if ($g == 0)
			{
				$whereShippingVolume .= "AND (";
			}

			$whereShippingVolume .= "((shipping_rate_length_start <= '$length' AND  shipping_rate_length_end >= '$length')
			AND (shipping_rate_width_start <= '$width' AND  shipping_rate_width_end >= '$width') AND (shipping_rate_height_start <= '
			$height' AND  shipping_rate_height_end >= '$height')) ";

			if ($g != count($volumeShipping) - 1)
			{
				$whereShippingVolume .= " OR ";
			}

			if ($g == count($volumeShipping) - 1)
			{
				$whereShippingVolume .= ")";
			}
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");

		$zipCond = "";
		$zip = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = ' AND ( ( shipping_rate_zip_start <= "' . $zip . '" AND shipping_rate_zip_end >= "' . $zip . '" )
			OR (shipping_rate_zip_start = "0" AND shipping_rate_zip_end = "0")
			OR (shipping_rate_zip_start = "" AND shipping_rate_zip_end = "") ) ';
		}

		$wherecountry = "";
		$wherestate = '';

		if ($country)
		{
			$wherecountry = ' AND (FIND_IN_SET( "' . $country . '", shipping_rate_country ) OR (shipping_rate_country ="0"
			OR shipping_rate_country ="") )';
		}

		if ($state)
		{
			$wherestate = ' AND (FIND_IN_SET( "' . $state . '", shipping_rate_state ) OR shipping_rate_state="0" OR shipping_rate_state="")';
		}

		$sql = "SELECT shipping_rate_value,shipping_rate_zip_start,shipping_rate_zip_end FROM " . $this->_table_prefix . "shipping_rate as sr
				LEFT JOIN #__extensions AS s
				ON
				sr.shipping_class = s.element WHERE 1=1 and s.folder='redshop_shipping'  and
				$wherecountry $wherestate
				$zipCond
				AND ((shipping_rate_volume_start <= '$volume' AND  shipping_rate_volume_end >= '$volume') OR (shipping_rate_volume_end = 0) )
				AND ((shipping_rate_ordertotal_start <= '$ordertotal' AND  shipping_rate_ordertotal_end >= '
				$ordertotal')  OR (shipping_rate_ordertotal_end = 0))
				AND ((shipping_rate_weight_start <= '$weighttotal' AND  shipping_rate_weight_end >= '
				$weighttotal')  OR (shipping_rate_weight_end = 0))
				$whereShippingVolume
				AND (shipping_rate_on_product = '' $pwhere) AND (shipping_rate_on_category = '' $cwhere )

				ORDER BY shipping_rate_priority ,shipping_rate_value, sr.shipping_rate_id ";

		$this->_db->setQuery($sql);

		$shippingrate = $this->_db->loadObjectlist();

		/**
		 * rearrange shipping rates array
		 * after filtering zipcode
		 * check character condition for zip code..
		 */
		$shipping = array();

		if (strlen(str_replace($numbers, '', $zip)) != 0 && $zip != "")
		{
			$k = 0;

			$userzip_len = ($this->strposa($zip, $numbers) !== false) ? ($this->strposa($zip, $numbers)) : strlen($zip);

			for ($i = 0; $i < count($shippingrate); $i++)
			{
				$flag             = false;
				$tmp_shippingrate = $shippingrate[$i];
				$start            = $tmp_shippingrate->shipping_rate_zip_start;
				$end              = $tmp_shippingrate->shipping_rate_zip_end;


				$startzip_len = ($this->strposa($start, $numbers) !== false) ? ($this->strposa($start, $numbers)) : strlen($start);
				$endzip_len = ($this->strposa($end, $numbers) !== false) ? ($this->strposa($end, $numbers)) : strlen($end);

				if ($startzip_len != $endzip_len || $userzip_len != $endzip_len)
				{
					continue;
				}

				$len = $userzip_len;

				for ($j = 0; $j < $len; $j++)
				{
					if (ord(strtoupper($zip[$j])) >= ord(strtoupper($start[$j])) && ord(strtoupper($zip[$j])) <= ord(strtoupper($end[$j])))
					{
						$flag = true;
					}

					else
					{
						$flag = false;
						break;
					}
				}

				if ($flag)
				{
					$shipping[$k++] = $tmp_shippingrate;
				}
			}

			if (count($shipping) > 0)
			{
				$rate = $shipping[0]->shipping_rate_value;
			}

			else
			{
				if (count($shippingrate) > 0)
				{
					$rate = $shippingrate[0]->shipping_rate_value;
				}
			}
		}
		else
		{
			if (count($shippingrate) > 0)
			{
				$rate = $shippingrate[0]->shipping_rate_value;
			}
		}

		$total = $cart['total'] - $cart['shipping'] + $rate;

		$rate  = $this->producthelper->getProductFormattedPrice($rate, true);
		$total = $this->producthelper->getProductFormattedPrice($total, true);

		return $rate . "`" . $total;
	}

	/******************New Function used in redshop 1.1**********************/
	public function encryptShipping($Str_Message)
	{
		$Len_Str_Message       = strlen($Str_Message);
		$Str_Encrypted_Message = "";

		for ($Position = 0; $Position < $Len_Str_Message; $Position++)
		{
			$Key_To_Use                = (($Len_Str_Message + $Position) + 1);

			$Key_To_Use                = (255 + $Key_To_Use) % 255;
			$Byte_To_Be_Encrypted      = SUBSTR($Str_Message, $Position, 1);
			$Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted);
			$Xored_Byte                = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;
			$Encrypted_Byte            = CHR($Xored_Byte);
			$Str_Encrypted_Message     .= $Encrypted_Byte;
		}

		$result = base64_encode($Str_Encrypted_Message);
		$result = str_replace("+", " ", $result);

		return $result;
	}

	public function decryptShipping($Str_Message)
	{
		$Str_Message           = base64_decode($Str_Message);
		$Len_Str_Message       = strlen($Str_Message);
		$Str_Encrypted_Message = "";

		for ($Position = 0; $Position < $Len_Str_Message; $Position++)
		{
			$Key_To_Use                = (($Len_Str_Message + $Position) + 1);

			$Key_To_Use                = (255 + $Key_To_Use) % 255;
			$Byte_To_Be_Encrypted      = SUBSTR($Str_Message, $Position, 1);
			$Ascii_Num_Byte_To_Encrypt = ORD($Byte_To_Be_Encrypted);

			// Xor operation
			$Xored_Byte                = $Ascii_Num_Byte_To_Encrypt ^ $Key_To_Use;
			$Encrypted_Byte            = CHR($Xored_Byte);
			$Str_Encrypted_Message     .= $Encrypted_Byte;
		}

		return $Str_Encrypted_Message;
	}

	public function getShippingAddress($user_info_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info '
			. 'WHERE users_info_id="' . $user_info_id . '" ';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function getShippingMethodByClass($shipping_class = '')
	{
		$folder = strtolower('redshop_shipping');
		$query = "SELECT * FROM #__extensions "
			. "WHERE LOWER(`folder`) = '{$folder}' "
			. "AND element='" . $shipping_class . "' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function getShippingMethodById($id = 0)
	{
		$folder = strtolower('redshop_shipping');

		$query = "SELECT *,extension_id as id FROM #__extensions "
			. "WHERE LOWER(`folder`) = '{$folder}' "
			. "AND `extension_id`='" . $id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function getShippingRates($shipping_class)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "shipping_rate "
			. "WHERE shipping_class='" . $shipping_class . "' ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectlist();

		return $result;
	}

	public function applyVatOnShippingRate($shippingrate = array(), $user_id)
	{
		$shipping_rate_vat = $shippingrate->shipping_rate_value;

		if ($shippingrate->apply_vat == 1)
		{
			$result = $this->getShippingVatRates($shippingrate->shipping_tax_group_id, $user_id);
			$chk = $this->producthelper->taxexempt_addtocart($user_id);

			if (!empty($result) && !empty($chk))
			{
				if ($result->tax_rate > 0)
				{
					$shipping_rate_vat = ($shipping_rate_vat * $result->tax_rate) + $shipping_rate_vat;
				}
			}
		}

		return $shipping_rate_vat;
	}

	public function listshippingrates($shipping_class, $users_info_id, &$d)
	{
		$userhelper     = new rsUserhelper;
		$order_subtotal = $d['order_subtotal'];

		$totaldimention = $this->getCartItemDimention();
		$weighttotal    = $totaldimention['totalweight'];
		$volume         = $totaldimention['totalvolume'];
		$session        = JFactory::getSession();

		$cart           = $session->get('cart');
		$idx            = (int) ($cart ['idx']);

		// Product volume based shipping
		$volumeShipping      = $this->getProductVolumeShipping();

		$whereShippingVolume = "";

		if (count($volumeShipping) > 0)
		{
			$whereShippingVolume .= " AND ( ";

			for ($g = 0; $g < count($volumeShipping); $g++)
			{
				$length = $volumeShipping[$g]['length'];
				$width  = $volumeShipping[$g]['width'];
				$height = $volumeShipping[$g]['height'];

				if ($g != 0)
				{
					$whereShippingVolume .= " OR ";
				}

				$whereShippingVolume .= "(
						(	('$length' BETWEEN shipping_rate_length_start AND shipping_rate_length_end)
							OR (shipping_rate_length_start = '0' AND shipping_rate_length_end = '0'))
						AND (('$width' BETWEEN shipping_rate_width_start AND shipping_rate_width_end)
							OR (shipping_rate_width_start = '0' AND shipping_rate_width_end = '0'))
						AND (('$height' BETWEEN shipping_rate_height_start AND shipping_rate_height_end)
							OR (shipping_rate_height_start = '0' AND shipping_rate_height_end = '0'))
						) ";
			}

			$whereShippingVolume .= " ) ";
		}

		$userInfo     = $this->getShippingAddress($users_info_id);
		$country      = $userInfo->country_code;
		$state        = $userInfo->state_code;
		$zip          = $userInfo->zipcode;
		$is_company   = $userInfo->is_company;
		$where        = '';
		$wherestate   = '';
		$whereshopper = '';

		if (!$is_company)
		{
			$where = " AND ( company_only = 2 or company_only = 0) ";
		}
		else
		{
			$where = " AND ( company_only = 1 or company_only = 0) ";
		}

		$shoppergroup = $userhelper->getShoppergroupData($userInfo->user_id);

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper     = ' AND (FIND_IN_SET( "' . $shopper_group_id . '", shipping_rate_on_shopper_group )
			OR shipping_rate_on_shopper_group="") ';
		}

		$shippingrate = array();

		if ($country)
		{
			$wherecountry = 'AND (FIND_IN_SET( "' . $country . '", shipping_rate_country ) OR shipping_rate_country="0"
			OR shipping_rate_country="" )';
		}
		else
		{
			$wherecountry = 'AND (FIND_IN_SET( "' . DEFAULT_SHIPPING_COUNTRY . '", shipping_rate_country ) )';
		}

		if ($state)
		{
			$wherestate = ' AND (FIND_IN_SET( "' . $state . '", shipping_rate_state ) OR shipping_rate_state="0" OR shipping_rate_state="")';
		}

		$pwhere = "";
		$cwhere = "";

		if ($idx)
		{
			$pwhere = 'OR ( ';

			for ($i = 0; $i < $idx; $i++)
			{
				$product_id = $cart [$i] ['product_id'];
				$pwhere .= 'FIND_IN_SET("' . $product_id . '", shipping_rate_on_product)';

				if ($i != $idx - 1)
				{
					$pwhere .= " OR ";
				}
			}

			$pwhere .= ")";
		}

		$app      = JFactory::getApplication();
		$is_admin = $app->isAdmin();

		if (!$shippingrate)
		{
			for ($i = 0; $i < $idx; $i++)
			{
				$product_id = $cart [$i] ['product_id'];
				$sel = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id = ' . $product_id;
				$this->_db->setQuery($sel);
				$categorydata = $this->_db->loadObjectList();

				if ($categorydata)
				{
					for ($c = 0; $c < count($categorydata); $c++)
					{
						$acwhere[] = " FIND_IN_SET('" . $categorydata [$c]->category_id . "', shipping_rate_on_category) ";
					}
				}
			}

			if (isset($acwhere) && count($acwhere) > 0)
			{
				$acwhere = implode(' OR ', $acwhere);
				$cwhere  = ' OR (' . $acwhere . ')';
			}
		}

		if (!$shippingrate)
		{
			$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z","A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", " ");

			$zipCond = "";
			$zip     = trim($zip);

			if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
			{
				$zipCond = ' AND ( ( shipping_rate_zip_start <= "' . $zip . '" AND shipping_rate_zip_end >= "' . $zip . '" )
				OR (shipping_rate_zip_start = "0" AND shipping_rate_zip_end = "0")
				OR (shipping_rate_zip_start = "" AND shipping_rate_zip_end = "") ) ';
			}

			$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate WHERE shipping_class = '" . $shipping_class . "'
				$wherecountry $wherestate $whereshopper
				$zipCond
				AND (( '$volume' BETWEEN shipping_rate_volume_start AND shipping_rate_volume_end) OR (shipping_rate_volume_end = 0) )
				AND (( '$order_subtotal' BETWEEN shipping_rate_ordertotal_start AND shipping_rate_ordertotal_end)  OR (shipping_rate_ordertotal_end = 0))
				AND (( '$weighttotal' BETWEEN shipping_rate_weight_start AND shipping_rate_weight_end)  OR (shipping_rate_weight_end = 0))
				$whereShippingVolume
				AND (shipping_rate_on_product = '' $pwhere) AND (shipping_rate_on_category = '' $cwhere )
				$where
				ORDER BY shipping_rate_priority";

			$this->_db->setQuery($sql);
			$shippingrate = $this->_db->loadObjectList();
		}

		/*
		 * rearrange shipping rates array
		 * after filtering zipcode
		 * check character condition for zip code..
		 */
		$shipping = array();

		if (strlen(str_replace($numbers, '', $zip)) != 0 && $zip != "")
		{
			$k = 0;
			$userzip_len = ($this->strposa($zip, $numbers) !== false) ? ($this->strposa($zip, $numbers)) : strlen($zip);

			for ($i = 0; $i < count($shippingrate); $i++)
			{
				$flag             = false;
				$tmp_shippingrate = $shippingrate[$i];
				$start            = $tmp_shippingrate->shipping_rate_zip_start;
				$end              = $tmp_shippingrate->shipping_rate_zip_end;

				if (trim($start) == "" && trim($end) == "")
				{
					$shipping[$k++] = $tmp_shippingrate;
				}

				else
				{
					$startzip_len = ($this->strposa($start, $numbers) !== false) ? ($this->strposa($start, $numbers)) : strlen($start);
					$endzip_len   = ($this->strposa($end, $numbers) !== false) ? ($this->strposa($end, $numbers)) : strlen($end);

					if ($startzip_len != $endzip_len || $userzip_len != $endzip_len)
					{
						continue;
					}

					$len = $userzip_len;

					for ($j = 0; $j < $len; $j++)
					{
						if (ord(strtoupper($zip[$j])) >= ord(strtoupper($start[$j])) && ord(strtoupper($zip[$j])) <= ord(strtoupper($end[$j])))
						{
							$flag = true;
						}
						else
						{
							$flag = false;
							break;
						}
					}

					if ($flag)
					{
						$shipping[$k++] = $tmp_shippingrate;
					}
				}
			}

			if ($is_admin == false)
			{
				$shipping = $this->filter_by_priority($shipping);
			}

			return $shipping;
		}
		else
		{
			if ($is_admin == false)
			{
				$shippingrate = $this->filter_by_priority($shippingrate);
			}

			return $shippingrate;
		}
	}

	public function getShippingVatRates($shipping_tax_group_id, $user_id = 0)
	{
		$user    = JFactory::getUser();
		$session = JFactory::getSession();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$and = '';
		$q2 = '';

		if ($user_id)
		{
			$userdata = $this->producthelper->getUserInformation($user_id);

			if (count($userdata) > 0)
			{
				if (!$userdata->country_code)
				{
					$userdata->country_code = DEFAULT_VAT_COUNTRY;
				}

				if (!$userdata->state_code)
				{
					$userdata->state_code = DEFAULT_VAT_STATE;
				}

				/*
				 *  VAT_BASED_ON = 0 // webshop mode
				 *  VAT_BASED_ON = 1 // customer mode
				 *  VAT_BASED_ON = 2 // EU mode
				 */
				if (VAT_BASED_ON != 2 && VAT_BASED_ON != 1)
				{
					$userdata->country_code = DEFAULT_VAT_COUNTRY;
					$userdata->state_code   = DEFAULT_VAT_STATE;
				}
			}

			if (VAT_BASED_ON == 2)
			{
				$and .= ' AND tr.is_eu_country=1 ';
			}
		}
		else
		{
			$auth                   = $session->get('auth');
			$users_info_id          = $auth['users_info_id'];
			$userdata->country_code = DEFAULT_VAT_COUNTRY;
			$userdata->state_code   = DEFAULT_VAT_STATE;

			if ($users_info_id && (REGISTER_METHOD == 1 || REGISTER_METHOD == 2) && (VAT_BASED_ON == 2 || VAT_BASED_ON == 1))
			{
				$query = "SELECT country_code,state_code FROM " . $this->_table_prefix . "users_info AS u "
					. "LEFT JOIN " . $this->_table_prefix . "shopper_group AS sh ON sh.shopper_group_id=u.shopper_group_id "
					. "WHERE u.users_info_id='" . $users_info_id . "' "
					. "order by u.users_info_id ASC LIMIT 0,1";
				$this->_db->setQuery($query);
				$userdata = $this->_db->loadObject();
			}
		}

		if ($shipping_tax_group_id == 0)
		{
			$and .= 'AND tr.tax_group_id = "' . DEFAULT_VAT_GROUP . '" ';
		}
		elseif ($shipping_tax_group_id > 0)
		{
			$q2 = 'LEFT JOIN ' . $this->_table_prefix . 'shipping_rate as s on tr.tax_group_id=s.shipping_tax_group_id ';
			$and .= 'AND s.shipping_tax_group_id = "' . $shipping_tax_group_id . '" ';
		}
		else
		{
			$and .= 'AND tr.tax_group_id=' . DEFAULT_VAT_GROUP . ' ';
		}

		$query = 'SELECT tr.* FROM ' . $this->_table_prefix . 'tax_rate as tr '
			. $q2
			. 'WHERE ( tr.tax_country="' . $userdata->country_code . '" or tr.tax_country = "") '
			. 'AND ( tr.tax_state = "' . $userdata->state_code . '" or tr.tax_state = "")'
			. $and
			. ' ORDER BY `tax_rate` DESC';
		$this->_db->setQuery($query);
		$taxdata = $this->_db->loadObject();

		return $taxdata;
	}

	public function getShopperGroupDefaultShipping($user_id = 0)
	{
		$shippingArr = array();
		$user        = JFactory::getUser();

		// FOR OFFLINE ORDER
		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id)
		{
			$result = $this->producthelper->getUserInformation($user_id);

			if (count($result) > 0 && $result->default_shipping == 1)
			{
				$shippingArr['shipping_rate'] = 0;
				$shippingArr['shipping_vat']  = 0;

				$row = $this->getShippingVatRates(0);

				if (!empty($row))
				{
					$total        = 0;
					$shipping_vat = 0;

					if ($row->tax_rate > 0)
					{
						$shipping_vat = ($result->default_shipping_rate * $row->tax_rate) . "<br>";
						$total        += $shipping_vat + $result->default_shipping_rate;
					}

					$shippingArr['shipping_vat']  = $shipping_vat;
					$shippingArr['shipping_rate'] = $total;

					return $shippingArr;
				}

				$shippingArr['shipping_rate'] = $result->default_shipping_rate;

				return $shippingArr;
			}
		}

		return $shippingArr;
	}

	// function to find first number position.
	public function strposa($haystack, $needles = array(), $offset = 0)
	{
		$chr = array();

		foreach ($needles as $needle)
		{
			if (strpos($haystack, $needle, $offset) !== false)
			{
				$chr[] = strpos($haystack, $needle, $offset);
			}
		}

		if (empty($chr))
		{
			return false;
		}
		else
		{
			return min($chr);
		}
	}

	public function filter_by_priority($shippingrate)
	{
		$tmp_shippingrates = array();

		for ($i = 0, $j = 0; $i < count($shippingrate); $i++)
		{
			if ($shippingrate[0]->shipping_rate_priority == $shippingrate[$i]->shipping_rate_priority)
			{
				$tmp_shippingrates[$j] = $shippingrate[$i];
				$j++;
			}
		}

		return $tmp_shippingrates;
	}

	/*
	 * function to get product volume shipping
	 *
	 * @return: array $cases , 3cases of shipping
	 */
	public function getProductVolumeShipping()
	{
		$session  = JFactory::getSession();
		$cart     = $session->get('cart');
		$idx      = (int) ($cart ['idx']);

		$length   = array();
		$width    = array();
		$height   = array();
		$length_q = array();
		$width_q  = array();
		$height_q = array();
		$Lmax     = 0;
		$Ltotal   = 0;
		$Wmax     = 0;
		$Wtotal   = 0;
		$Hmax     = 0;
		$Htotal   = 0;

		// Cart loop
		for ($i = 0; $i < $idx; $i++)
		{
			$data       = $this->producthelper->getProductById($cart [$i] ['product_id']);

			$length[$i] = $data->product_length;
			$width[$i]  = $data->product_width;
			$height[$i] = $data->product_height;

			$tmparr     = array($length[$i], $width[$i], $height[$i]);
			$switch     = array_search(min($tmparr), $tmparr);

			switch ($switch)
			{
				case 0:
					$length_q[$i] = $data->product_length * $cart [$i] ['quantity'];
					$width_q[$i]  = $data->product_width;
					$height_q[$i] = $data->product_height;
				break;
				case 1:
					$length_q[$i] = $data->product_length;
					$width_q[$i]  = $data->product_width * $cart [$i] ['quantity'];
					$height_q[$i] = $data->product_height;
				break;
				case 2:
					$length_q[$i] = $data->product_length;
					$width_q[$i]  = $data->product_width;
					$height_q[$i] = $data->product_height * $cart [$i] ['quantity'];
				break;
			}
		}

		// Get maximum length
		if (count($length) > 0)
		{
			$Lmax = max($length);
		}

		// Get total length
		if (count($length_q) > 0)
		{
			$Ltotal = array_sum($length_q);
		}

		// Get maximum width
		if (count($width) > 0)
		{
			$Wmax = max($width);
		}

		// Get total width
		if (count($width_q) > 0)
		{
			$Wtotal = array_sum($width_q);
		}

		// Get maximum height
		if (count($height) > 0)
		{
			$Hmax = max($height);
		}

		// Get total height
		if (count($height_q) > 0)
		{
			$Htotal = array_sum($height_q);
		}

		// 3 cases are available for shipping boxes
		$cases              = array();
		$cases[0]['length'] = $Lmax;
		$cases[0]['width']  = $Wmax;
		$cases[0]['height'] = $Htotal;

		$cases[1]['length'] = $Lmax;
		$cases[1]['width']  = $Wtotal;
		$cases[1]['height'] = $Hmax;

		$cases[2]['length'] = $Ltotal;
		$cases[2]['width']  = $Wmax;
		$cases[2]['height'] = $Hmax;

		return $cases;
	}

	public function getCartItemDimention()
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$idx     = (int) ($cart ['idx']);

		$totalQnt    = 0;
		$totalWeight = 0;
		$totalVolume = 0;
		$totalLength = 0;
		$totalheight = 0;
		$totalwidth  = 0;

		for ($i = 0; $i < $idx; $i++)
		{
			$data       = $this->producthelper->getProductById($cart [$i] ['product_id']);
			$acc_weight = 0;

			if (isset($cart[$i]['cart_accessory']) && count($cart[$i]['cart_accessory']) > 0)
			{
				for ($a = 0; $a < count($cart[$i]['cart_accessory']); $a++)
				{
					$acc_id     = $cart[$i]['cart_accessory'][$a]['accessory_id'];
					$acc_qty    = $cart[$i]['cart_accessory'][$a]['accessory_quantity'];
					$acc_data   = $this->producthelper->getProductById($acc_id);

					$acc_weight += ($acc_data->weight * $acc_qty);
				}
			}

			$totalQnt    += $cart [$i] ['quantity'];
			$totalWeight += (($data->weight * $cart [$i] ['quantity']) + $acc_weight);
			$totalVolume += ($data->product_volume * $cart [$i] ['quantity']);
			$totalLength += ($data->product_length * $cart [$i] ['quantity']);
			$totalheight += ($data->product_height * $cart [$i] ['quantity']);
			$totalwidth  += ($data->product_width * $cart [$i] ['quantity']);
		}

		$ret = array(
			"totalquantity" => $totalQnt,
			"totalweight"   => $totalWeight,
			"totalvolume"   => $totalVolume,
			"totallength"   => $totalLength,
			"totalheight"   => $totalheight,
			"totalwidth"    => $totalwidth
		);

		return $ret;
	}

	/*
	 * get available shipping boxes according to cart items
	 *
	 * @return: string	, html (radio button table row )
	 */
	public function getShippingBox()
	{
		$volumeShipping      = $this->getProductVolumeShipping();

		$whereShippingVolume = "";

		if (count($volumeShipping) > 0)
		{
			$whereShippingVolume .= " AND ( ";

			for ($g = 0; $g < count($volumeShipping); $g++)
			{
				$length = $volumeShipping[$g]['length'];
				$width  = $volumeShipping[$g]['width'];
				$height = $volumeShipping[$g]['height'];

				if ($g != 0)
				{
					$whereShippingVolume .= " OR ";
				}

				$whereShippingVolume .= " (shipping_box_length >= '$length' AND shipping_box_width >= '$width' AND shipping_box_height >= '$height') ";
			}

			$whereShippingVolume .= " ) ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "shipping_boxes "
			. "WHERE published = 1 "
			. $whereShippingVolume
			. " ORDER BY shipping_box_priority ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	/*
	 * get Selected shipping BOX dimensions
	 *
	 * @params: $boxid	, Shipping Box id
	 *
	 * @return: array , box dimensions
	 */
	public function getBoxDimensions($boxid = 0)
	{
		$whereShippingBoxes = array();

		if ($boxid)
		{
			$query = "SELECT * FROM " . $this->_table_prefix . "shipping_boxes "
				. "WHERE published = 1 "
				. "AND shipping_box_id ='" . $boxid . "' ";
			$this->_db->setQuery($query);
			$box_detail = $this->_db->loadObject();

			if (count($box_detail) > 0)
			{
				$whereShippingBoxes['box_length'] = $box_detail->shipping_box_length;
				$whereShippingBoxes['box_width']  = $box_detail->shipping_box_width;
				$whereShippingBoxes['box_height'] = $box_detail->shipping_box_height;
			}
		}

		return $whereShippingBoxes;
	}

	public function getShippingRateError(&$d)
	{
		$bool = $this->isCartDimentionMatch($d);

		if ($bool)
		{
			$bool = $this->isUserInfoMatch($d);

			if ($bool)
			{
				$bool = $this->isProductDetailMatch($d);

				if ($bool)
				{
					return true;
				}

				else
				{
					return JText::_("COM_REDSHOP_PRODUCT_DETAIL_NOT_MATCH");
				}
			}

			else
			{
				return JText::_("COM_REDSHOP_USER_INFORMATION_NOT_MATCH");
			}
		}
		else
		{
			return JText::_("COM_REDSHOP_CART_DIMENTION_NOT_MATCH");
		}
	}

	public function isCartDimentionMatch(&$d)
	{
		$order_subtotal      = $d['order_subtotal'];

		$totaldimention      = $this->getCartItemDimention();
		$weighttotal         = $totaldimention['totalweight'];
		$volume              = $totaldimention['totalvolume'];

		// Product volume based shipping
		$volumeShipping      = $this->getProductVolumeShipping();

		$whereShippingVolume = "";

		if (count($volumeShipping) > 0)
		{
			$whereShippingVolume .= " AND ( ";

			for ($g = 0; $g < count($volumeShipping); $g++)
			{
				$length = $volumeShipping[$g]['length'];
				$width  = $volumeShipping[$g]['width'];
				$height = $volumeShipping[$g]['height'];

				if ($g != 0)
				{
					$whereShippingVolume .= " OR ";
				}

				$whereShippingVolume .= "(
						(	('$length' BETWEEN shipping_rate_length_start AND shipping_rate_length_end)
							OR (shipping_rate_length_start = '0' AND shipping_rate_length_end = '0'))
						AND (('$width' BETWEEN shipping_rate_width_start AND shipping_rate_width_end)
							OR (shipping_rate_width_start = '0' AND shipping_rate_width_end = '0'))
						AND (('$height' BETWEEN shipping_rate_height_start AND shipping_rate_height_end)
							OR (shipping_rate_height_start = '0' AND shipping_rate_height_end = '0'))
						) ";
			}

			$whereShippingVolume .= " ) ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "shipping_rate "
			. "WHERE (shipping_class = 'default_shipping' OR shipping_class = 'shipper') "
			. "AND (( '$volume' BETWEEN shipping_rate_volume_start AND shipping_rate_volume_end) OR (shipping_rate_volume_end = 0) ) "
			. "AND (( '$order_subtotal' BETWEEN shipping_rate_ordertotal_start AND shipping_rate_ordertotal_end)  OR (shipping_rate_ordertotal_end = 0)) "
			. "AND (( '$weighttotal' BETWEEN shipping_rate_weight_start AND shipping_rate_weight_end)  OR (shipping_rate_weight_end = 0)) "
			. $whereShippingVolume
			. " ORDER BY shipping_rate_priority ";
		$this->_db->setQuery($query);
		$shippingrate = $this->_db->loadObjectList();

		if (count($shippingrate) > 0)
		{
			return true;
		}

		return false;
	}

	public function isUserInfoMatch(&$d)
	{
		$userhelper   = new rsUserhelper;
		$shippingrate = array();

		$userInfo     = $this->getShippingAddress($d['users_info_id']);
		$country      = $userInfo->country_code;
		$state        = $userInfo->state_code;
		$zip          = $userInfo->zipcode;
		$is_company   = $userInfo->is_company;

		$whereshopper = '';
		$wherestate   = '';

		if ($is_company)
		{
			$where = "AND ( company_only = 1 or company_only = 0) ";
		}
		else
		{
			$where = "AND ( company_only = 2 or company_only = 0) ";
		}

		if ($country)
		{
			$wherecountry = "AND (FIND_IN_SET( '" . $country . "', shipping_rate_country ) OR shipping_rate_country='0' OR shipping_rate_country='') ";
		}
		else
		{
			$wherecountry = "AND (FIND_IN_SET( '" . DEFAULT_SHIPPING_COUNTRY . "', shipping_rate_country)) ";
		}

		$shoppergroup = $userhelper->getShoppergroupData($userInfo->user_id);

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper = ' AND (FIND_IN_SET( "' . $shopper_group_id . '", shipping_rate_on_shopper_group ) OR shipping_rate_on_shopper_group="") ';
		}

		if ($state)
		{
			$wherestate = "AND (FIND_IN_SET( '" . $state . "', shipping_rate_state ) OR shipping_rate_state='0' OR shipping_rate_state='') ";
		}

		$numbers = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", " ");
		$zipCond = "";
		$zip     = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = "AND ( ( shipping_rate_zip_start <= '" . $zip . "' AND shipping_rate_zip_end >= '" . $zip . "' ) "
				. "OR (shipping_rate_zip_start='0' AND shipping_rate_zip_end='0') "
				. "OR (shipping_rate_zip_start='' AND shipping_rate_zip_end='') ) ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "shipping_rate "
			. "WHERE (shipping_class = 'default_shipping' OR shipping_class = 'shipper') "
			. $wherecountry
			. $wherestate
			. $whereshopper
			. $zipCond
			. $where
			. " ORDER BY shipping_rate_priority ";
		$this->_db->setQuery($query);
		$shippingrate = $this->_db->loadObjectList();

		if (count($shippingrate) > 0)
		{
			return true;
		}

		return false;
	}

	public function isProductDetailMatch(&$d)
	{
		$session = JFactory::getSession();
		$cart    = $session->get('cart');
		$idx     = (int) ($cart['idx']);

		$pwhere  = "";
		$cwhere  = "";

		if ($idx)
		{
			$pwhere = 'OR ( ';

			for ($i = 0; $i < $idx; $i++)
			{
				$product_id = $cart [$i] ['product_id'];
				$pwhere .= 'FIND_IN_SET("' . $product_id . '", shipping_rate_on_product)';

				if ($i != $idx - 1)
				{
					$pwhere .= " OR ";
				}
			}

			$pwhere .= ")";
		}

		$acwhere = array();

		for ($i = 0; $i < $idx; $i++)
		{
			$product_id = $cart[$i]['product_id'];
			$sel = 'SELECT category_id FROM ' . $this->_table_prefix . 'product_category_xref WHERE product_id="' . $product_id . '" ';
			$this->_db->setQuery($sel);
			$categorydata = $this->_db->loadObjectList();

			for ($c = 0; $c < count($categorydata); $c++)
			{
				$acwhere[] = " FIND_IN_SET('" . $categorydata [$c]->category_id . "', shipping_rate_on_category) ";
			}
		}

		if (isset($acwhere) && count($acwhere) > 0)
		{
			$acwhere = implode(' OR ', $acwhere);
			$cwhere = ' OR (' . $acwhere . ')';
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "shipping_rate "
			. "WHERE (shipping_class = 'default_shipping' OR shipping_class = 'shipper') "
			. "AND (shipping_rate_on_product = '' $pwhere) AND (shipping_rate_on_category = '' $cwhere ) "
			. "ORDER BY shipping_rate_priority ";
		$this->_db->setQuery($query);
		$shippingrate = $this->_db->loadObjectList();

		if (count($shippingrate) > 0)
		{
			return true;
		}

		return false;
	}

	public function getfreeshippingRate($shipping_rate_id = 0)
	{
		$userhelper = new rsUserhelper;
		$session    = JFactory::getSession();
		$cart       = $session->get('cart', null);

		$idx = 0;

		if (isset($cart ['idx']) === true)
		{
			$idx = (int) ($cart ['idx']);
		}

		$order_subtotal  = isset($cart['product_subtotal']) ? $cart['product_subtotal'] : null;
		$order_functions = new order_functions;
		$user            = JFactory::getUser();
		$user_id         = $user->id;

		if (!empty($idx))
		{
			$text = JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
		}
		else
		{
			return JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE_WHEN_NOPRODUCT_IN_CART');
		}

		$users_info_id = JRequest::getVar('users_info_id');

		if ($user_id)
		{
			if ($users_info_id)
			{
				$userInfo = $this->getShippingAddress($users_info_id);
			}
			else
			{
				$userInfo = $order_functions->getShippingAddress($user_id);
				$userInfo = $userInfo[0];
			}
		}

		$country      = $userInfo->country_code;
		$state        = $userInfo->state_code;
		$is_company   = $userInfo->is_company;

		$where        = '';
		$wherestate   = '';
		$whereshopper = '';

		if (!$is_company)
		{
			$where = " AND ( company_only = 2 or company_only = 0) ";
		}
		else
		{
			$where = " AND ( company_only = 1 or company_only = 0) ";
		}

		$shoppergroup = $userhelper->getShoppergroupData($userInfo->user_id);

		if (count($shoppergroup) > 0)
		{
			$shopper_group_id = $shoppergroup->shopper_group_id;
			$whereshopper = ' AND (FIND_IN_SET( "' . $shopper_group_id . '", shipping_rate_on_shopper_group )
			OR shipping_rate_on_shopper_group="") ';
		}

		$shippingrate = array();

		if ($country)
		{
			$wherecountry = 'AND (FIND_IN_SET( "' . $country . '", shipping_rate_country ) OR shipping_rate_country="0"
			OR shipping_rate_country="" )';
		}
		else
		{
			$wherecountry = 'AND (FIND_IN_SET( "' . DEFAULT_SHIPPING_COUNTRY . '", shipping_rate_country )
			OR shipping_rate_country="0" OR shipping_rate_country="")';
		}

		if ($state)
		{
			$wherestate = ' AND (FIND_IN_SET( "' . $state . '", shipping_rate_state ) OR shipping_rate_state="0" OR shipping_rate_state="")';
		}

		$zipCond = "";
		$zip = trim($zip);

		if (strlen(str_replace($numbers, '', $zip)) == 0 && $zip != "")
		{
			$zipCond = ' AND ( ( shipping_rate_zip_start <= "' . $zip . '" AND shipping_rate_zip_end >= "' . $zip . '" )
				OR (shipping_rate_zip_start = "0" AND shipping_rate_zip_end = "0")
				OR (shipping_rate_zip_start = "" AND shipping_rate_zip_end = "") ) ';
		}

		if ($shipping_rate_id)
		{
			$where .= ' AND sr.shipping_rate_id = "' . $shipping_rate_id . '"';
		}

		$sql = "SELECT * FROM " . $this->_table_prefix . "shipping_rate as sr
								 LEFT JOIN #__extensions AS s
								 ON
								 sr.shipping_class = s.element
								 WHERE (shipping_rate_value =0 OR shipping_rate_value ='0')

				$wherecountry $wherestate $whereshopper $zipCond $where
				ORDER BY s.ordering,sr.shipping_rate_priority limit 0,1";

		$this->_db->setQuery($sql);
		$shippingrate = $this->_db->loadObject();

		if ($shippingrate)
		{
			if ($shippingrate->shipping_rate_ordertotal_start > $order_subtotal)
			{
				$diff = $shippingrate->shipping_rate_ordertotal_start - $order_subtotal;
				$text = sprintf(JText::_('COM_REDSHOP_SHIPPING_TEXT_LBL'), $this->producthelper->getProductFormattedPrice($diff));
			}

			elseif ($shippingrate->shipping_rate_ordertotal_start < $order_subtotal && $shippingrate->shipping_rate_ordertotal_end > $order_subtotal)
			{
				$text = JText::_('COM_REDSHOP_FREE_SHIPPING_RATE_IS_IN_USED');
			}

			else
			{
				$text = JText::_('COM_REDSHOP_NO_SHIPPING_RATE_AVAILABLE');
			}
		}

		return $text;
	}
}
