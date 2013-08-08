<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('currency', JPATH_SITE . '/components/com_redshop/helpers');
JLoader::import('helper', JPATH_SITE . '/components/com_redshop/helpers');
JLoader::import('extra_field', JPATH_SITE . '/components/com_redshop/helpers');
JLoader::import('user', JPATH_SITE . '/components/com_redshop/helpers');
JLoader::import('order', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('quotation', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('template', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');
JLoader::import('stockroom', JPATH_ADMINISTRATOR . '/components/com_redshop/helpers');

class producthelper
{
	public $_id = null;

	public $_data = null;

	public $_db = null;

	public $_userdata = null;

	public $_table_prefix = null;

	public $_product_level = 0;

	public $_userhelper = null;

	public $_session = null;

	public $_cartTemplateData = null;

	public $_ajaxdetail_templatedata = null;

	public $_vatCountry = null;

	public $_vatState = null;

	public $_vatGroup = null;

	public $_taxData = array();

	public $_cart_template = null;

	public $_acc_template = null;

	public $_attribute_template = null;

	public $_attributewithcart_template = null;

	public $_shopper_group_id = null;

	public $_discount_product_data = null;

	function __construct()
	{
		$this->_db           = JFactory::getDBO();
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$this->_userhelper   = new rsUserhelper;
		$this->_session      = JFactory::getSession();
	}

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function getWishlistmodule($menu_id)
	{
		$query = 'SELECT * FROM #__extensions WHERE element = "' . $menu_id . '" ';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function getwishlistuserfieldata($wishlistid, $productid)
	{
		$query = 'SELECT * FROM #__redshop_wishlist_userfielddata  WHERE wishlist_id = "'
			. $wishlistid . '"  and product_id="' . $productid . '" order by fieldid ASC';
		$this->_db->setQuery($query);
		$result = $this->_db->loadObjectList();

		return $result;
	}

	public function getProductById($product_id, $field_name = "", $test = '')
	{
		$query = $this->_db->getQuery(true);

		$query->select(' * ');
		$query->from($this->_table_prefix . 'product');
		$query->where('product_id = "' . $product_id . '"');

		if ($this->_id != $product_id)
		{
			$this->_db->setQuery($query);
			$this->setId($product_id);
			$result = $this->_data = $this->_db->loadObject();
		}
		else
		{
			$result = $this->_data;
		}

		return $result;
	}

	public function country_in_eu_common_vat_zone($country)
	{
		$eu_countries = array('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST',
			'FIN', 'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU',
			'LUX', 'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');

		return in_array($country, $eu_countries);
	}

	public function getProductPrices($product_id, $userid, $quantity = 1)
	{
		$leftjoin = "";
		$userArr  = $this->_session->get('rs_user');
		$helper = new redhelper;

		if (empty($userArr))
		{
			$userArr = $this->_userhelper->createUserSession($userid);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		if ($helper->isredCRM())
		{
			if ($this->_session->get('isredcrmuser'))
			{
				$crmDebitorHelper = new crmDebitorHelper;
				$debitor_id_tot   = $crmDebitorHelper->getContactPersons(0, 0, 0, $userid);
				$debitor_id       = $debitor_id_tot[0]->section_id;
				$details          = $crmDebitorHelper->getDebitor($debitor_id);
				$userid           = $details[0]->user_id;
			}
		}

		$query = $this->_db->getQuery(true);
		$query->select(' p.price_id,p.product_price,p.product_currency,p.discount_price, p.discount_start_date, p.discount_end_date ');

		if ($userid)
		{
			$query->join('LEFT', $this->_table_prefix . 'users_info AS u ON u.shopper_group_id = p.shopper_group_id');
			$and = " u.user_id='" . $userid . "' AND u.address_type='BT' ";
		}
		else
		{
			$and = " p.shopper_group_id = '" . $shopperGroupId . "' ";
		}

		$query->from($this->_table_prefix . 'product_price AS p');
		$query->where('p.product_id = "' . $product_id . '"');
		$query->where($and);
		$query->where(' (( p.price_quantity_start <= "' . $quantity . '" AND p.price_quantity_end >= "'
			. $quantity . '" ) OR (p.price_quantity_start = "0" AND p.price_quantity_end = "0"))');
		$query->order('price_quantity_start ASC LIMIT 0,1 ');

		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		if (count($result) > 0)
		{
			if ($result->discount_price != 0
				&& $result->discount_start_date != 0 && $result->discount_end_date != 0
				&& $result->discount_start_date <= time()
				&& $result->discount_end_date >= time()
				&& $result->discount_price < $result->product_price)
			{
				$result->product_price = $result->discount_price;
			}
		}

		return $result;
	}

	public function getProductSpecialPrice($product_price, $discount_product_id, $product_id = 0)
	{
		$result          = array();
		$categoryProduct = '';

		if ($product_id)
		{
			$categoryProduct = $this->getCategoryProduct($product_id);
		}

		// Get shopper group Id
		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$user    = JFactory::getUser();
			$userArr = $this->_userhelper->createUserSession($user->id);
		}

		// Shopper Group Id from user session
		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		$query = $this->_db->getQuery(true);

		// Prepare query.
		$query->select('*');
		$query->from('#__redshop_discount_product');
		$query->where('published = 1');
		$query->where('(discount_product_id IN ("' . $discount_product_id . '") OR FIND_IN_SET("' . $categoryProduct . '",category_ids) )');
		$query->where('`start_date` <= ' . time());
		$query->where('`end_date` >= ' . time());
		$query->where('`discount_product_id` IN (SELECT `discount_product_id` FROM `#__redshop_discount_product_shoppers` WHERE `shopper_group_id` = "' . $shopperGroupId . '")');
		$query->order('`amount` DESC');

		// Inject the query and load the result.
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return array();
		}

		// Result is empty then return blank
		if (count($result) <= 0)
		{
			return array();
		}

		switch ($result->condition)
		{
			case 1:
				$query->where('`amount` >= "' . $product_price . '"');
				break;
			case 2:
				$query->where('`amount` = "' . $product_price . '"');
				break;
			case 3:
				$query->where('`amount` <= "' . $product_price . '"');
				break;
		}

		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		// Check for a database error.
		if ($this->_db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());

			return array();
		}

		return $result;
	}

	public function getProductSpecialId($userid)
	{
		if ($userid)
		{
			$sql = "SELECT ps.discount_product_id FROM " . $this->_table_prefix . "users_info AS ui "
				. " LEFT JOIN " . $this->_table_prefix . "discount_product_shoppers AS ps ON ui.shopper_group_id = ps.shopper_group_id "
				. " WHERE user_id = '" . $userid . "' AND address_type='BT'";
			$this->_db->setQuery($sql);
			$res = $this->_db->loadObjectList();
		}
		else
		{
			$userArr = $this->_session->get('rs_user');

			if (empty($userArr))
			{
				$userArr = $this->_userhelper->createUserSession($userid);
			}

			$shopperGroupId = $userArr['rs_user_shopperGroup'];

			if ($this->_shopper_group_id != $shopperGroupId)
			{
				$query = "SELECT * FROM " . $this->_table_prefix . "discount_product_shoppers AS ps "
					. "WHERE ps.shopper_group_id ='" . $shopperGroupId . "'";
				$this->_db->setQuery($query);
				$this->_shopper_group_id = $shopperGroupId;
				$res                     = $this->_discount_product_data = $this->_db->loadObjectList();
			}
			else
			{
				$res = $this->_discount_product_data;
			}
		}

		$discount_product_id = '0';

		for ($i = 0; $i < count($res); $i++)
		{
			if ($res[$i]->discount_product_id != "" && $res[$i]->discount_product_id != 0)
			{
				$discount_product_id .= "," . $res[$i]->discount_product_id;
			}
		}

		return $discount_product_id;
	}

	public function getProductTax($product_id = 0, $product_price = 0, $user_id = 0, $tax_exempt = 0)
	{
		$userArr = $this->_session->get('rs_user');

		if ($user_id == 0)
		{
			$user    = JFactory::getUser();
			$user_id = $user->id;
		}

		$proinfo = array();

		if ($product_id != 0)
		{
			$proinfo = $this->getProductById($product_id);
		}

		$protax   = 0;
		$tax_rate = 0;

		if (empty($userArr))
		{
			$userArr                     = array();
			$userArr['rs_is_user_login'] = 0;
		}

		if ($userArr['rs_is_user_login'] == 0 && $user_id != 0)
		{
			$userArr = $this->_userhelper->createUserSession($user_id);
		}

		$vatrates_data = $this->getVatRates($product_id, $user_id);

		if ($vatrates_data)
		{
			$tax_rate = $vatrates_data->tax_rate;
		}

		if ($product_price <= 0 && isset($proinfo->product_price))
		{
			$product_price = $proinfo->product_price;
		}

		$product_price = $this->productPriceRound($product_price);

		if ($tax_exempt)
		{
			$protax = $product_price * $tax_rate;

			return $protax;
		}

		if ($tax_rate)
		{
			if ($user_id)
			{
				$userinfo = $this->getUserInformation($user_id);

				if (count($userinfo) > 0)
				{
					if ($userinfo->requesting_tax_exempt == 1 && $userinfo->tax_exempt_approved)
					{
						$protax = $protax;
					}
					else
					{
						$protax = $product_price * $tax_rate;
					}
				}
				else
				{
					$protax = $product_price * $tax_rate;
				}
			}
			else
			{
				$protax = $product_price * $tax_rate;
			}
		}

		$protax = $this->productPriceRound($protax);

		return $protax;
	}

	public function replaceVatinfo($data_add)
	{
		if (strstr($data_add, "{vat_info}"))
		{
			$strVat       = '';
			$chktaxExempt = $this->getApplyVatOrNot($data_add);

			if ($chktaxExempt)
			{
				$strVat = WITH_VAT_TEXT_INFO;
			}
			else
			{
				$strVat = WITHOUT_VAT_TEXT_INFO;
			}

			$data_add = str_replace("{vat_info}", $strVat, $data_add);
		}

		return $data_add;
	}

	public function taxexempt_addtocart($user_id = 0, $btn_show_addto_cart = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$userinfo = $this->getUserInformation($user_id);

		if ($user_id)
		{
			if (count($userinfo) > 0)
			{
				if ($userinfo->requesting_tax_exempt == 0)
				{
					return true;
				}
				elseif ($userinfo->requesting_tax_exempt == 1 && $userinfo->tax_exempt_approved == 0)
				{
					if ($btn_show_addto_cart)
					{
						return false;
					}

					return true;
				}
				elseif ($userinfo->requesting_tax_exempt == 1 && $userinfo->tax_exempt_approved == 1)
				{
					if ($btn_show_addto_cart)
					{
						return true;
					}

					return false;
				}
			}
		}

		return true;
	}

	public function getVatUserinfo($user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id)
		{
			$userArr         = $this->_session->get('rs_user');
			$rs_user_info_id = (isset($userArr['rs_user_info_id'])) ? $userArr['rs_user_info_id'] : 0;
			$userdata        = $this->getUserInformation($user_id, CALCULATE_VAT_ON, $rs_user_info_id);

			if (count($userdata) > 0)
			{
				$userArr['rs_user_info_id'] = $userdata->users_info_id;
				$userArr                    = $this->_session->set('rs_user', $userArr);

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
				 *  VAT_BASED_ON = 1 // Customer mode
				 *  VAT_BASED_ON = 2 // EU mode
				 */
				if (VAT_BASED_ON != 2 && VAT_BASED_ON != 1)
				{
					$userdata->country_code = DEFAULT_VAT_COUNTRY;
					$userdata->state_code   = DEFAULT_VAT_STATE;
				}
			}
			else
			{
				$userdata->country_code = DEFAULT_VAT_COUNTRY;
				$userdata->state_code   = DEFAULT_VAT_STATE;
			}
		}
		else
		{
			$auth                   = $this->_session->get('auth');
			$users_info_id          = $auth['users_info_id'];

			$userdata = new stdClass;
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

		return $userdata;
	}

	public function getVatRates($product_id = 0, $user_id = 0, $vat_rate_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$proinfo         = (object) $this->getProductById($product_id);
		$tax_group_id    = 0;
		$rs_user_info_id = 0;
		$and             = 'AND tg.published= "1" ';
		$q2              = 'LEFT JOIN ' . $this->_table_prefix . 'tax_group as tg on tg.tax_group_id=tr.tax_group_id ';
		$userdata        = $this->getVatUserinfo($user_id);

		$userArr = $this->_session->get('rs_user');
		$chkflg  = true;

		if (!empty($userArr))
		{
			if (array_key_exists('vatCountry', $userArr) && !empty($userArr['taxData']))
			{
				if (empty($proinfo->product_tax_group_id))
				{
					$proinfo->product_tax_group_id = DEFAULT_VAT_GROUP;
				}

				if ($userArr['vatCountry'] == $userdata->country_code
					&& $userArr['vatState'] == $userdata->state_code
					&& @$userArr['vatGroup'] == $proinfo->product_tax_group_id)
				{
					return $userArr['taxData'];
				}
			}
		}

		if (VAT_BASED_ON == 2)
		{
			$and .= ' AND tr.is_eu_country=1 ';
		}

		if ($product_id == 0)
		{
			$and .= 'AND tr.tax_group_id = "' . DEFAULT_VAT_GROUP . '" ';
		}
		elseif ($proinfo->product_tax_group_id > 0)
		{
			$q2 .= 'LEFT JOIN ' . $this->_table_prefix . 'product as p on tr.tax_group_id=p.product_tax_group_id ';
			$and .= 'AND p.product_id = "' . $product_id . '" ';
		}
		else
		{
			$and .= 'AND tr.tax_group_id=' . DEFAULT_VAT_GROUP . ' ';
		}

		$query = 'SELECT tr.* FROM ' . $this->_table_prefix . 'tax_rate as tr '
			. $q2
			. 'WHERE tr.tax_country="' . $userdata->country_code . '" '
			. 'AND (tr.tax_state = "' . $userdata->state_code . '" OR tr.tax_state = "") '
			. $and
			. ' ORDER BY `tax_rate` DESC LIMIT 0,1';
		$this->_db->setQuery($query);

		$userArr['taxData']    = $this->_taxData = $this->_db->loadObject();
		$userArr['vatCountry'] = $userdata->country_code;
		$userArr['vatState']   = $userdata->state_code;

		if (!empty($userArr['taxData']))
		{
			$tax_group_id = $userArr['taxData']->tax_group_id;
		}

		$userArr['vatGroup'] = $tax_group_id;
		$this->_session->set('rs_user', $userArr);

		return $this->_taxData;
	}

	// Get Vat for Googlebase xml
	public function getGoogleVatRates($product_id = 0, $product_price = 0, $tax_exempt = 0)
	{
		$proinfo         = $this->getProductById($product_id);
		$tax_group_id    = 0;
		$rs_user_info_id = 0;

		$country_code = DEFAULT_VAT_COUNTRY;
		$state_code   = DEFAULT_VAT_STATE;
		$and          = 'AND tg.published= "1" ';
		$q2           = 'LEFT JOIN ' . $this->_table_prefix . 'tax_group as tg on tg.tax_group_id=tr.tax_group_id ';

		$chkflg = true;

		if (VAT_BASED_ON == 2)
		{
			$and .= ' AND tr.is_eu_country=1 ';
		}

		if ($product_id == 0)
		{
			$and .= 'AND tr.tax_group_id = "' . DEFAULT_VAT_GROUP . '" ';
		}
		elseif ($proinfo->product_tax_group_id > 0)
		{
			$q2 .= 'LEFT JOIN ' . $this->_table_prefix . 'product as p on tr.tax_group_id=p.product_tax_group_id ';
			$and .= 'AND p.product_id = "' . $product_id . '" ';
		}
		else
		{
			$and .= 'AND tr.tax_group_id=' . DEFAULT_VAT_GROUP . ' ';
		}

		$where = $q2
			. 'WHERE tr.tax_country="' . $country_code . '" '
			. 'AND (tr.tax_state = "' . $state_code . '" OR tr.tax_state = "") '
			. $and;

		$query = 'SELECT tr.* FROM ' . $this->_table_prefix . 'tax_rate as tr '
			. $where
			. ' ORDER BY `tax_rate` DESC';
		$this->_db->setQuery($query);
		$this->_taxData = $this->_db->loadObject();

		$tax_rate      = $this->_taxData->tax_rate;
		$product_price = $product_price;
		$product_price = $this->productPriceRound($product_price);

		if ($tax_exempt)
		{
			$protax = $product_price * $tax_rate;

			return $protax;
		}

		if ($tax_rate)
		{
			$protax = $product_price * $tax_rate;
		}

		$protax = $this->productPriceRound($protax);

		return $protax;
	}

	/*
	 * parse extra fields for tempplate for according to section.
	 * $categorypage aregument for product section extra field for category page
	 *
	 */
	public function getExtraSectionTag($filedname = array(), $product_id, $section, $template_data, $categorypage = 0)
	{
		$extraField = new extraField;

		$str = array();

		for ($i = 0; $i < count($filedname); $i++)
		{
			if ($categorypage == 1)
			{
				if (strstr($template_data, "{producttag:" . $filedname[$i] . "}"))
				{
					$str[] = $filedname[$i];
				}
			}
			else
			{
				if (strstr($template_data, "{" . $filedname[$i] . "}"))
				{
					$str[] = $filedname[$i];
				}
			}
		}

		$dbname = "";

		if (count($str) > 0)
		{
			$dbname = "'" . implode("','", $str) . "'";
		}

		$template_data = $extraField->extra_field_display($section, $product_id, $dbname, $template_data, $categorypage);

		return $template_data;
	}

	public function getPriceReplacement($product_price)
	{
		$return = "";

		if ($product_price)
		{
			$return = $this->getProductFormattedPrice($product_price);
		}
		else
		{
			if (!SHOW_PRICE || (DEFAULT_QUOTATION_MODE == '1' && SHOW_QUOTATION_PRICE != '1')) // && DEFAULT_QUOTATION_MODE==1)
			{
				$return = PRICE_REPLACE_URL ? "<a href='http://" . PRICE_REPLACE_URL . "' target='_blank'>"
					. PRICE_REPLACE . "</a>" : PRICE_REPLACE;
			}

			if (SHOW_PRICE && trim($product_price) != "")
			{
				if ((DEFAULT_QUOTATION_MODE == '0') || (DEFAULT_QUOTATION_MODE == '1' && SHOW_QUOTATION_PRICE == '1'))
				{
					$return = ZERO_PRICE_REPLACE_URL ? "<a href='http://" . ZERO_PRICE_REPLACE_URL . "' target='_blank'>" . ZERO_PRICE_REPLACE . "</a>" : ZERO_PRICE_REPLACE;
				}
			}
		}

		return $return;
	}

	/**
	 * Format Product Price
	 *
	 * @param   float    $product_price    Product price
	 * @param   boolean  $convert          Decide to conver price in Multi Currency
	 * @param   float    $currency_symbol  Product Formatted Price
	 *
	 * @return  float                      Formatted Product Price
	 */
	public function getProductFormattedPrice($product_price, $convert = true, $currency_symbol = REDCURRENCY_SYMBOL)
	{
		// Get Current Currency of SHOP
		$session = JFactory::getSession('product_currency');
		/*
		 * if convert set true than use conversation
		 */
		if ($convert && $session->get('product_currency'))
		{
			$CurrencyHelper  = new CurrencyHelper;
			$product_price = $CurrencyHelper->convert($product_price);

			$currency_symbol = $session->get('product_currency');
		}

		$price = '';

		if (is_numeric($product_price))
		{
			if (CURRENCY_SYMBOL_POSITION == 'front')
			{
				$price = $currency_symbol
					. number_format((double) $product_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
			}
			elseif (CURRENCY_SYMBOL_POSITION == 'behind')
			{
				$price = number_format((double) $product_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR)
					. $currency_symbol;
			}
			elseif (CURRENCY_SYMBOL_POSITION == 'none')
			{
				$price = number_format((double) $product_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
			}
			else
			{
				$price = $currency_symbol . number_format((double) $product_price, PRICE_DECIMAL, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
			}
		}

		return $price;
	}

	public function productPriceRound($product_price)
	{
		$cal_no = 4;

		if (defined('CALCULATION_PRICE_DECIMAL') && CALCULATION_PRICE_DECIMAL != "")
		{
			$cal_no = CALCULATION_PRICE_DECIMAL;
		}

		$product_price = round($product_price, $cal_no);

		return $product_price;
	}

	public function getProductparentImage($product_parent_id)
	{
		$result = $this->getProductById($product_parent_id);

		if ($result->product_full_image == '' && $result->product_parent_id > 0)
		{
			$result = $this->getProductparentImage($result->product_parent_id);
		}

		return $result;
	}

	public function getProductImage($product_id = 0, $link = '', $width, $height, $Product_detail_is_light = 2, $enableHover = 0, $suffixid = 0)
	{
		$thum_image      = '';
		$stockroomhelper = new rsstockroomhelper;
		$result          = $this->getProductById($product_id);

		$isStockExists = $stockroomhelper->isStockExists($product_id);

		$middlepath = REDSHOP_FRONT_IMAGES_RELPATH . "product/";

		if ($result->product_full_image == '' && $result->product_parent_id > 0)
		{
			$result = $this->getProductparentImage($result->product_parent_id);
		}

		$cat_product_hover = false;

		if ($enableHover && PRODUCT_HOVER_IMAGE_ENABLE)
		{
			$cat_product_hover = true;
		}

		$product_image = $result->product_full_image;

		if ($Product_detail_is_light != 2)
		{
			if ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image))
			{
				$product_image = $result->product_thumb_image;
			}
		}

		if (!$isStockExists && USE_PRODUCT_OUTOFSTOCK_IMAGE == 1)
		{
			if (PRODUCT_OUTOFSTOCK_IMAGE && file_exists($middlepath . PRODUCT_OUTOFSTOCK_IMAGE))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					PRODUCT_OUTOFSTOCK_IMAGE,
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					array(),
					$suffixid
				);
			}
			elseif ($product_image && file_exists($middlepath . $product_image))
			{
				if ($result->product_full_image && file_exists($middlepath . $result->product_full_image)
					&& ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image)))
				{
					$thum_image = $this->replaceProductImage(
						$result,
						$product_image,
						$result->product_thumb_image,
						$link,
						$width,
						$height,
						$Product_detail_is_light,
						$enableHover,
						array(),
						$suffixid
					);
				}
				elseif ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image))
				{
					$thum_image = $this->replaceProductImage(
						$result,
						$product_image,
						"",
						$link,
						$width,
						$height,
						$Product_detail_is_light,
						$enableHover,
						array(),
						$suffixid
					);
				}
				elseif ($result->product_full_image && file_exists($middlepath . $result->product_full_image))
				{
					$thum_image = $this->replaceProductImage(
						$result,
						$product_image,
						"",
						$link,
						$width,
						$height,
						$Product_detail_is_light,
						$enableHover,
						array(),
						$suffixid
					);
				}
			}
			else
			{
				$thum_image = $this->replaceProductImage(
					$result,
					"",
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					array(),
					$suffixid
				);
			}
		}
		elseif ($product_image && file_exists($middlepath . $product_image))
		{
			if ($result->product_full_image && file_exists($middlepath . $result->product_full_image)
				&& ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image)))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					$product_image,
					$result->product_thumb_image,
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					array(),
					$suffixid
				);
			}
			elseif ($result->product_thumb_image && file_exists($middlepath . $result->product_thumb_image))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					$product_image,
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					array(),
					$suffixid
				);
			}
			elseif ($result->product_full_image && file_exists($middlepath . $result->product_full_image))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					$product_image,
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					array(),
					$suffixid
				);
			}
		}
		else
		{
			if (PRODUCT_DEFAULT_IMAGE && file_exists($middlepath . PRODUCT_DEFAULT_IMAGE))
			{
				$thum_image = $this->replaceProductImage(
					$result,
					PRODUCT_DEFAULT_IMAGE,
					"",
					$link,
					$width,
					$height,
					$Product_detail_is_light,
					$enableHover,
					array(),
					$suffixid
				);
			}
		}

		return $thum_image;
	}

	public function replaceProductImage($product, $imagename = "", $linkimagename = "", $link = "", $width, $height, $Product_detail_is_light = 2, $enableHover = 0, $preselectedResult = array(), $suffixid = 0)
	{
		$url           = JURI::root();
		$imagename     = trim($imagename);
		$linkimagename = trim($linkimagename);
		$product_id    = $product->product_id;
		$redhelper     = new redhelper;

		$middlepath    = REDSHOP_FRONT_IMAGES_RELPATH . "product/";
		$product_image = $product->product_full_image;

		if ($Product_detail_is_light != 2)
		{
			if ($product->product_thumb_image && file_exists($middlepath . $product->product_thumb_image))
			{
				$product_image = $product->product_thumb_image;
			}
		}

		$altText = $this->getAltText('product', $product_id, $product_image);

		if ($altText)
		{
			$product->product_name = $altText;
		}

		$title = " title='" . $product->product_name . "' ";
		$alt   = " alt='" . $product->product_name . "' ";

		$cat_product_hover = false;

		if ($enableHover && PRODUCT_HOVER_IMAGE_ENABLE)
		{
			$cat_product_hover = true;
		}

		$noimage = "noimage.jpg";

		$product_img = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;

		$product_hover_img = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;
		$linkimage         = REDSHOP_FRONT_IMAGES_ABSPATH . $noimage;

		if ($imagename != "")
		{
			$product_img = $redhelper->watermark('product', $imagename, $width, $height, WATERMARK_PRODUCT_THUMB_IMAGE, '0');

			if ($cat_product_hover)
				$product_hover_img = $redhelper->watermark('product',
					$imagename,
					PRODUCT_HOVER_IMAGE_WIDTH,
					PRODUCT_HOVER_IMAGE_HEIGHT,
					WATERMARK_PRODUCT_THUMB_IMAGE,
					'2');

			if ($linkimagename != "")
			{
				$linkimage = $redhelper->watermark('product', $linkimagename, '', '', WATERMARK_PRODUCT_IMAGE, '0');
			}
			else
			{
				$linkimage = $redhelper->watermark('product', $imagename, '', '', WATERMARK_PRODUCT_IMAGE, '0');
			}
		}

		if (count($preselectedResult) > 0)
		{
			$product_img = $preselectedResult['product_mainimg'];
			$title       = " title='" . $preselectedResult['aTitleImageResponse'] . "' ";
			$linkimage   = $preselectedResult['aHrefImageResponse'];
		}

		$commonid = ($suffixid) ? $product_id . '_' . $suffixid : $product_id;

		if ($Product_detail_is_light != 2 && $Product_detail_is_light != 1 && !MAGIC_MAGNIFYPLUS)
		{
			$thum_image = "<img id='main_image" . $commonid . "' src='" . $product_img . "' " . $title . $alt . " />";
		}
		else
		{
			if ($Product_detail_is_light == 1)
			{
				$thum_image = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $linkimage . "' rel=\"myallimg\">";
			}
			elseif (MAGIC_MAGNIFYPLUS)
			{
				$cat_product_hover = false;
				$thum_image        = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $linkimage
					. "' class='MagicMagnifyPlus'>";
			}
			elseif (PRODUCT_IS_LIGHTBOX == 1)
			{
				$thum_image = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $linkimage
					. "' class=\"modal\" rel=\"{handler: 'image', size: {}}\">";
			}
			else
			{
				$thum_image = "<a id='a_main_image" . $commonid . "' " . $title . " href='" . $link . "'>";
			}

			$thum_image .= "<img id='main_image" . $commonid . "' src='" . $product_img . "' " . $title . $alt . " />";

			if ($cat_product_hover)
			{
				$thum_image .= "<img id='main_image" . $commonid . "' src='" . $product_hover_img . "' "
					. $title . $alt . " class='redImagepreview' />";
			}

			$thum_image .= "</a>";
		}

		if ($cat_product_hover)
		{
			$thum_image = "<div class='redhoverImagebox'>" . $thum_image . "</div>";
		}
		else
		{
			$thum_image = "<div>" . $thum_image . "</div>";
		}

		return $thum_image;
	}

	public function getProductCategoryImage($product_id = 0, $category_img = '', $link = '', $width, $height)
	{
		$redhelper  = new redhelper;
		$result     = $this->getProductById($product_id);
		$thum_image = "";
		$title      = " title='" . $result->product_name . "' ";
		$alt        = " alt='" . $result->product_name . "' ";

		if ($category_img && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "category/" . $category_img))
		{
			if (PRODUCT_IS_LIGHTBOX == 1)
			{
				$product_img       = $redhelper->watermark('category', $category_img, $width, $height, WATERMARK_PRODUCT_IMAGE, '0');
				$product_hover_img = $redhelper->watermark('product', $category_img, PRODUCT_HOVER_IMAGE_WIDTH, PRODUCT_HOVER_IMAGE_HEIGHT, WATERMARK_PRODUCT_IMAGE, '0');
				$linkimage         = $redhelper->watermark('category', $category_img, '', '', WATERMARK_PRODUCT_IMAGE, '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $linkimage . "' " . $title . "  rel=\"myallimg\">";
				$thum_image .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";

				$thum_image .= "</a>";
			}
			else
			{
				$product_img       = $redhelper->watermark('category', $category_img, $width, $height, WATERMARK_PRODUCT_IMAGE, '0');
				$product_hover_img = $redhelper->watermark('category', $category_img, PRODUCT_HOVER_IMAGE_WIDTH, PRODUCT_HOVER_IMAGE_HEIGHT, WATERMARK_PRODUCT_IMAGE, '0');
				$thum_image        = "<a id='a_main_image" . $product_id . "' href='" . $link . "' " . $title . ">";
				$thum_image .= "<img id='main_image" . $product_id . "' src='" . $product_img . "' " . $title . $alt . " />";
				$thum_image .= "</a>";
			}
		}

		return $thum_image;
	}

	public function getProductMinDeliveryTime($product_id = 0, $section_id = 0, $section = '', $loadDiv = 1)
	{
		$helper = new redhelper;

		if (!$section_id && !$section)
		{
			$query = "SELECT  min_del_time as deltime, s.max_del_time, s.delivery_time "
				. " FROM " . $this->_table_prefix . "product_stockroom_xref AS ps , "
				. $this->_table_prefix . "stockroom as s "
				. " WHERE "
				. " ps.product_id = '" . $product_id . "' AND ps.stockroom_id = s.stockroom_id and ps.quantity >0 ORDER BY min_del_time ASC LIMIT 0,1";
		}
		else
		{
			$query = "SELECT  min_del_time as deltime, s.max_del_time, s.delivery_time "
				. " FROM " . $this->_table_prefix . "product_attribute_stockroom_xref AS pas , "
				. $this->_table_prefix . "stockroom as s "
				. " WHERE "
				. " pas.section_id = '" . $section_id . "' AND pas.section = '" . $section
				. "' AND pas.stockroom_id = s.stockroom_id and pas.quantity >0 ORDER BY min_del_time ASC LIMIT 0,1";
		}

		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		$product_delivery_time = '';

		if ($result)
		{
			if (!$section_id && !$section)
			{
				$sql = "SELECT  min_del_time as deltime, s.max_del_time,s.delivery_time "
					. " FROM " . $this->_table_prefix . "stockroom as s, "
					. $this->_table_prefix . "product_stockroom_xref AS ps  "
					. " WHERE "
					. " ps.product_id = '" . $product_id . "' AND ps.stockroom_id = s.stockroom_id AND s.min_del_time = "
					. $result->deltime . " and ps.quantity >=0 ORDER BY max_del_time ASC LIMIT 0,1";
			}
			else
			{
				$sql = "SELECT  min_del_time as deltime, s.max_del_time,s.delivery_time "
					. " FROM " . $this->_table_prefix . "stockroom as s, "
					. $this->_table_prefix . "product_attribute_stockroom_xref AS pas  "
					. " WHERE "
					. " pas.section_id = '" . $section_id . "' AND pas.section = '" . $section
					. "' AND pas.stockroom_id = s.stockroom_id AND s.min_del_time = " . $result->deltime
					. " AND pas.quantity >=0 ORDER BY max_del_time ASC LIMIT 0,1";
			}

			$this->_db->setQuery($sql);
			$row = $this->_db->loadObject();

			if ($row->deltime == 0 || $row->deltime == ' ')
			{
				$product_delivery_time = '';
			}
			else
			{
				if ($row->delivery_time == "Days")
				{
					$duration = JText::_('COM_REDSHOP_DAYS');
				}
				else
				{
					$row->deltime      = $row->deltime / 7;
					$row->max_del_time = $row->max_del_time / 7;
					$duration          = JText::_('COM_REDSHOP_WEEKS');
				}

				$product_delivery_time = (int) $row->deltime . "-" . (int) $row->max_del_time . " " . $duration;
			}
		}

		/**
		 * redCRM includes
		 */
		if ($helper->isredCRM())
		{
			if (ENABLE_ITEM_TRACKING_SYSTEM)
			{
				// Supplier order helper object
				$crmSupplierOrderHelper   = new crmSupplierOrderHelper;
				$sendData                 = new stdClass;
				$sendData->product_id     = $product_id;
				$sendData->property_id    = 0;
				$sendData->subproperty_id = 0;

				if ($section == 'property')
				{
					$sendData->property_id = $section_id;
				}
				elseif ($section == 'subproperty')
				{
					// Get data for property id
					$subattribute_data        = $this->getAttibuteSubProperty($section_id);
					$sendData->property_id    = $subattribute_data[0]->subattribute_id;
					$sendData->subproperty_id = $section_id;
				}

				$product_delivery_time = $crmSupplierOrderHelper->getProductDeliveryTime($sendData);
				$dayLanguage           = (strlen($product_delivery_time) == 1 && $product_delivery_time == 1) ? JText::_('COM_REDSHOP_DAY') : JText::_('COM_REDSHOP_DAYS');
				$product_delivery_time = $product_delivery_time . " " . $dayLanguage;
			}
		}

		if ($product_delivery_time && $loadDiv)
			$product_delivery_time = '<div id="ProductAttributeMinDelivery' . $product_id . '">' . $product_delivery_time . '</div>';

		return $product_delivery_time;
	}

	public function GetDefaultQuantity($product_id = 0, $data_add = "")
	{
		$cart_template = $this->getAddtoCartTemplate($data_add);
		$cartform      = (count($cart_template) > 0) ? $cart_template->template_desc : "";
		$qunselect     = 1;

		if (strstr($cartform, "{addtocart_quantity_selectbox}"))
		{
			$product = $this->getProductById($product_id);

			if ((DEFAULT_QUANTITY_SELECTBOX_VALUE != "" && $product->quantity_selectbox_value == '')
				|| $product->quantity_selectbox_value != '')
			{
				$selectbox_value = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : DEFAULT_QUANTITY_SELECTBOX_VALUE;
				$quaboxarr       = explode(",", $selectbox_value);
				$quaboxarr       = array_merge(array(), array_unique($quaboxarr));
				sort($quaboxarr);

				for ($q = 0; $q < count($quaboxarr); $q++)
				{
					if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
					{
						$qunselect = intVal($quaboxarr[$q]);
						break;
					}
				}
			}
		}

		return $qunselect;
	}

	public function GetProductShowPrice($product_id, $data_add, $seoTemplate = "", $user_id = 0, $isrel = 0, $attributes = array())
	{
		$product_price                  = '';
		$price_excluding_vat            = '';
		$display_product_discount_price = '';
		$display_product_old_price      = '';
		$display_product_price_saving   = '';
		$display_product_price_novat    = '';
		$display_product_price_incl_vat = '';
		$product_price_saving_lbl       = '';
		$product_old_price_lbl          = '';
		$product_vat_lbl                = '';
		$product_price_lbl              = '';
		$seoProductPrice                = '';
		$seoProductSavingPrice          = '';

		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$qunselect = $this->GetDefaultQuantity($product_id, $data_add);

		$ProductPriceArr = $this->getProductNetPrice($product_id, $user_id, $qunselect, $data_add, $attributes);

		$relPrefix = '';

		if ($isrel)
		{
			$relPrefix = 'rel';
		}

		$stockroomhelper = new rsstockroomhelper;

		if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
		{
			$product_price          = $this->getPriceReplacement($ProductPriceArr['product_price'] * $qunselect);
			$product_main_price     = $this->getPriceReplacement($ProductPriceArr['product_main_price'] * $qunselect);
			$product_old_price      = $this->getPriceReplacement($ProductPriceArr['product_old_price'] * $qunselect);
			$product_price_saving   = $this->getPriceReplacement($ProductPriceArr['product_price_saving'] * $qunselect);
			$product_discount_price = $this->getPriceReplacement($ProductPriceArr['product_discount_price'] * $qunselect);
			$product_price_novat    = $this->getPriceReplacement($ProductPriceArr['product_price_novat'] * $qunselect);
			$product_price_incl_vat = $this->getPriceReplacement($ProductPriceArr['product_price_incl_vat'] * $qunselect);

			$isStockExists = $stockroomhelper->isStockExists($product_id);

			if ($isStockExists && strstr($data_add, "{" . $relPrefix . "product_price_table}"))
			{
				$product_price_table = $this->getProductQuantityPrice($product_id, $user_id);
				$data_add            = str_replace("{" . $relPrefix . "product_price_table}", $product_price_table, $data_add);
			}

			$price_excluding_vat   = $ProductPriceArr['price_excluding_vat'];
			$seoProductPrice       = $this->getPriceReplacement($ProductPriceArr['seoProductPrice'] * $qunselect);
			$seoProductSavingPrice = $this->getPriceReplacement($ProductPriceArr['seoProductSavingPrice'] * $qunselect);

			$product_old_price_lbl    = $ProductPriceArr['product_old_price_lbl'];
			$product_price_saving_lbl = $ProductPriceArr['product_price_saving_lbl'];
			$product_price_lbl        = $ProductPriceArr['product_price_lbl'];
			$product_vat_lbl          = $ProductPriceArr['product_vat_lbl'];

			$display_product_old_price      = $product_old_price;
			$display_product_discount_price = $product_discount_price;
			$display_product_price_saving   = $product_price_saving;
			$display_product_price_novat    = $product_price_novat;

			if ($ProductPriceArr['product_discount_price'])
			{
				$display_product_discount_price = '<span id="display_product_discount_price' . $product_id . '">' . $product_discount_price . '</span>';
			}

			if ($ProductPriceArr['product_old_price'])
			{
				$display_product_old_price = '<span id="display_product_old_price' . $product_id . '">' . $product_old_price . '</span>';
			}

			if ($ProductPriceArr['product_price_saving'])
			{
				$display_product_price_saving = '<span id="display_product_saving_price' . $product_id . '">' . $product_price_saving . '</span>';
			}

			if ($ProductPriceArr['product_price_novat'] != "")
			{
				$display_product_price_novat = '<span id="display_product_price_no_vat' . $product_id . '">' . $product_price_novat . '</span>';
			}

			if ($ProductPriceArr['product_price_incl_vat'] != "")
			{
				$display_product_price_incl_vat = '<span id="product_price_incl_vat' . $product_id . '">' . $product_price_incl_vat . '</span>';
			}
		}

		if (strstr($data_add, "{" . $relPrefix . "product_price_table}"))
		{
			$data_add = str_replace("{" . $relPrefix . "product_price_table}", '', $data_add);
		}

		$data_add = str_replace("{" . $relPrefix . "product_price}", '<span id="produkt_kasse_hoejre_pris_indre' . $product_id . '">' . $product_price . '</span>', $data_add);
		$data_add = str_replace("{" . $relPrefix . "price_excluding_vat}", $price_excluding_vat, $data_add);
		$data_add = str_replace("{" . $relPrefix . "product_discount_price}", $display_product_discount_price, $data_add);

		if ($ProductPriceArr['product_price_saving'])
		{
			$data_add = str_replace("{" . $relPrefix . "product_price_saving}", $display_product_price_saving, $data_add);
			$data_add = str_replace("{" . $relPrefix . "product_price_saving_lbl}", $product_price_saving_lbl, $data_add);
		}
		else
		{
			$data_add = str_replace("{" . $relPrefix . "product_price_saving}", '', $data_add);
			$data_add = str_replace("{" . $relPrefix . "product_price_saving_lbl}", '', $data_add);
		}

		if ($ProductPriceArr['product_old_price'])
		{
			$data_add = str_replace("{" . $relPrefix . "product_old_price}", $display_product_old_price, $data_add);
			$data_add = str_replace("{" . $relPrefix . "product_old_price_lbl}", $product_old_price_lbl, $data_add);
		}
		else
		{
			$data_add = str_replace("{" . $relPrefix . "product_old_price}", '', $data_add);
			$data_add = str_replace("{" . $relPrefix . "product_old_price_lbl}", '', $data_add);
		}

		$data_add = str_replace("{" . $relPrefix . "product_price_novat}", $display_product_price_novat, $data_add);
		$data_add = str_replace("{" . $relPrefix . "product_price_incl_vat}", $display_product_price_incl_vat, $data_add);
		$data_add = str_replace("{" . $relPrefix . "product_vat_lbl}", $product_vat_lbl, $data_add);
		$data_add = str_replace("{" . $relPrefix . "product_price_lbl}", $product_price_lbl, $data_add);

		if ($seoTemplate != "")
		{
			$seoTemplate = str_replace("{" . $relPrefix . "saleprice}", $seoProductPrice, $seoTemplate);
			$seoTemplate = str_replace("{" . $relPrefix . "saving}", $seoProductSavingPrice, $seoTemplate);

			return $seoTemplate;
		}

		return $data_add;
	}

	public function getProductNetPrice($product_id, $user_id = 0, $quantity = 1, $data_add = '', $attributes = array())
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$ProductPriceArr = array();
		$stockroomhelper = new rsstockroomhelper;

		$row = $this->getProductById($product_id);

		$product_id                 = $row->product_id;
		$price_text                 = JText::_('COM_REDSHOP_REGULAR_PRICE') . "";
		$result                     = $this->getProductPrices($product_id, $user_id, $quantity);
		$product_price              = '';
		$product_vat_lbl            = '';
		$product_price_lbl          = '';
		$product_price_table        = '';
		$product_old_price_lbl      = '';
		$product_price_saving_lbl   = '';
		$product_old_price_excl_vat = '';
		$newproductprice            = $row->product_price;

		if (!empty($result))
		{
			$temp_Product_price = $result->product_price;
			$newproductprice    = $temp_Product_price;
		}

		// Set Product Custom Price through product plugin
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('redshop_product');
		$results = $dispatcher->trigger('setProductCustomPrice', array($product_id));

		if (count($results) > 0 && $results[0])
		{
			$newproductprice = $results[0];
		}

		$applytax            = $this->getApplyVatOrNot($data_add, $user_id);
		$discount_product_id = $this->getProductSpecialId($user_id);
		$res                 = $this->getProductSpecialPrice($newproductprice, $discount_product_id, $product_id);

		if (!empty($res))
		{
			$discount_amount = 0;

			if (count($res) > 0)
			{
				if ($res->discount_type == 0)
				{
					$discount_amount = $res->discount_amount;
				}
				else
				{
					$discount_amount = ($newproductprice * $res->discount_amount) / (100);
				}
			}

			if ($newproductprice < 0)
			{
				$newproductprice = 0;
			}

			$reg_price_tax = $this->getProductTax($row->product_id, $newproductprice, $user_id);

			if ($applytax)
				$reg_price = $row->product_price + $reg_price_tax;
			else
				$reg_price = $row->product_price;

			$reg_price_tax   = $this->getProductTax($product_id, $row->product_price, $user_id);
			$reg_price       = $row->product_price;
			$formatted_price = $this->getProductFormattedPrice($reg_price);

			$product_price = $newproductprice - $discount_amount;

			if ($product_price < 0)
			{
				$product_price = 0;
			}

			$price_text = $price_text . "<span class='redPriceLineThrough'>" . $formatted_price . "</span><br />"
				. JText::_('COM_REDSHOP_SPECIAL_PRICE');
		}
		else
		{
			$product_price = $newproductprice;
		}

		$excludingvat    = $this->defaultAttributeDataPrice($product_id, $product_price, $data_add, $user_id, 0, $attributes);
		$formatted_price = $this->getProductFormattedPrice($excludingvat);
		$price_text      = $price_text . '<span id="display_product_price_without_vat' . $product_id . '">'
			. $formatted_price . '</span><input type="hidden" name="product_price_excluding_price" id="product_price_excluding_price'
			. $product_id . '" value="' . $product_price . '" />';

		$default_tax_amount         = $this->getProductTax($product_id, $product_price, $user_id, 1);
		$tax_amount                 = $this->getProductTax($product_id, $product_price, $user_id);
		$product_price_exluding_vat = $product_price;
		$product_price_incl_vat     = $default_tax_amount + $product_price_exluding_vat;

		if ($applytax)
		{
			$product_price = $tax_amount + $product_price;
		}

		if ($product_price < 0)
		{
			$product_price = 0;
		}

		if (SHOW_PRICE) // && !DEFAULT_QUOTATION_MODE)
		{
			$price_excluding_vat        = $price_text;
			$product_discount_price_tmp = $this->checkDiscountDate($product_id);
			$product_old_price_excl_vat = $product_price_exluding_vat;

			if ($row->product_on_sale && $product_discount_price_tmp > 0)
			{
				$dicount_price_exluding_vat = $product_discount_price_tmp;
				$tax_amount                 = $this->getProductTax($product_id, $product_discount_price_tmp, $user_id);

				if (intval($applytax) && $product_discount_price_tmp)
				{
					$dis_tax_amount             = $tax_amount;
					$product_discount_price_tmp = $product_discount_price_tmp + $dis_tax_amount;
				}

				if ($product_price < $product_discount_price_tmp)
				{
					$product_price          = $this->defaultAttributeDataPrice(
																				$product_id,
																				$product_price,
																				$data_add,
																				$user_id,
																				intval($applytax),
																				$attributes
																			);

					$product_main_price     = $product_price;
					$product_discount_price = '';
					$product_old_price      = '';
					$product_price_saving   = '';
					$product_price_novat    = $product_price_exluding_vat;
					$seoProductSavingPrice  = '';
					$seoProductPrice        = $product_price;
					$tax_amount             = $this->getProductTax($product_id, $product_price, $user_id);

				}
				else
				{
					$product_price_saving = $product_price_exluding_vat - $dicount_price_exluding_vat;

					if (intval($applytax) && $product_price_saving)
					{
						$dis_save_tax_amount  = $this->getProductTax($product_id, $product_price_saving, $user_id);
						$product_price_saving = $product_price_saving + $dis_save_tax_amount;
					}

					$product_price_incl_vat     = $product_discount_price_tmp + $tax_amount;
					$product_old_price          = $this->defaultAttributeDataPrice(
						$product_id,
						$product_price,
						$data_add,
						$user_id,
						intval($applytax),
						$attributes
					);

					$product_discount_price_tmp = $this->defaultAttributeDataPrice(
						$product_id,
						$product_discount_price_tmp,
						$data_add,
						$user_id,
						intval($applytax),
						$attributes
					);

					$product_discount_price     = $product_discount_price_tmp;
					$product_main_price         = $product_discount_price_tmp;
					$product_price              = $product_discount_price_tmp;

					$product_price_novat        = $this->defaultAttributeDataPrice(
						$product_id,
						$dicount_price_exluding_vat,
						$data_add,
						$user_id,
						0,
						$attributes
					);

					$seoProductPrice            = $product_discount_price_tmp;
					$seoProductSavingPrice      = $product_price_saving;

					$product_price_saving_lbl = JText::_('COM_REDSHOP_PRODUCT_PRICE_SAVING_LBL');
					$product_old_price_lbl    = JText::_('COM_REDSHOP_PRODUCT_OLD_PRICE_LBL');
				}
			}
			else
			{
				$product_main_price     = $product_price;
				$product_price          = $this->defaultAttributeDataPrice(
					$product_id,
					$product_price,
					$data_add,
					$user_id,
					intval($applytax),
					$attributes
				);

				$product_discount_price = '';
				$product_price_saving   = '';
				$product_old_price      = '';
				$product_price_novat    = $product_price_exluding_vat;
				$seoProductPrice        = $product_price;
				$seoProductSavingPrice  = '';
			}

			if ($tax_amount && intval($applytax))
			{
				$product_vat_lbl = ' ' . JText::_('COM_REDSHOP_PRICE_INCLUDING_TAX');
			}
			else
			{
				$product_vat_lbl = ' ' . JText::_('COM_REDSHOP_PRICE_EXCLUDING_TAX');
			}

			$product_price_lbl = JText::_('COM_REDSHOP_PRODUCT_PRICE');
		}
		else
		{
			$seoProductPrice        = '';
			$seoProductSavingPrice  = '';
			$product_discount_price = '';
			$product_old_price      = '';
			$product_price_saving   = '';
			$product_price_novat    = '';
			$product_main_price     = '';
			$product_price          = '';
			$price_excluding_vat    = '';

		}

		$ProductPriceArr['productPrice']               = $product_price_novat;
		$ProductPriceArr['product_price']              = $product_price;
		$ProductPriceArr['price_excluding_vat']        = $price_excluding_vat;
		$ProductPriceArr['product_main_price']         = $product_main_price;
		$ProductPriceArr['product_price_novat']        = $product_price_novat;
		$ProductPriceArr['product_price_saving']       = $product_price_saving;
		$ProductPriceArr['product_old_price']          = $product_old_price;
		$ProductPriceArr['product_discount_price']     = $product_discount_price;
		$ProductPriceArr['seoProductSavingPrice']      = $seoProductSavingPrice;
		$ProductPriceArr['seoProductPrice']            = $seoProductPrice;
		$ProductPriceArr['product_old_price_lbl']      = $product_old_price_lbl;
		$ProductPriceArr['product_price_saving_lbl']   = $product_price_saving_lbl;
		$ProductPriceArr['product_price_lbl']          = $product_price_lbl;
		$ProductPriceArr['product_vat_lbl']            = $product_vat_lbl;
		$ProductPriceArr['productVat']                 = $tax_amount;
		$ProductPriceArr['product_old_price_excl_vat'] = $product_old_price_excl_vat;
		$ProductPriceArr['product_price_incl_vat']     = $product_price_incl_vat;

		return $ProductPriceArr;
	}

	public function getProductQuantityPrice($product_id, $userid)
	{
		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$userArr = $this->_userhelper->createUserSession($userid);
		}

		$shopperGroupId = $this->_userhelper->getShopperGroup($userid);

		if ($userid)
		{
			$query = "SELECT p.* FROM " . $this->_table_prefix . "users_info AS u "
				. "LEFT JOIN " . $this->_table_prefix . "product_price AS p ON u.shopper_group_id = p.shopper_group_id "
				. "WHERE p.product_id = '" . $product_id . "' "
				. "AND u.user_id='" . $userid . "' AND u.address_type='BT' "
				. "ORDER BY price_quantity_start ASC ";
		}
		else
		{
			$query = "SELECT p.* FROM " . $this->_table_prefix . "product_price AS p "
				. "WHERE p.product_id = '" . $product_id . "' "
				. "AND p.shopper_group_id = '" . $shopperGroupId . "' "
				. "ORDER BY price_quantity_start ASC ";
		}

		$this->_db->setQuery($query);
		$result        = $this->_db->loadObjectList();
		$quantitytable = '';

		if ($result)
		{
			$quantitytable = "<table>";
			$quantitytable .= "<tr><th>" . JText::_('COM_REDSHOP_QUANTITY') . "</th><th>" . JText::_('COM_REDSHOP_PRICE')
				. "</th></tr>";

			foreach ($result as $r)
			{
				if ($r->discount_price != 0
					&& $r->discount_start_date != 0
					&& $r->discount_end_date != 0
					&& $r->discount_start_date <= time()
					&& $r->discount_end_date >= time())
				{
					$r->product_price = $r->discount_price;
				}

				$price = $this->getProductFormattedPrice($r->product_price);
				$quantitytable .= "<tr><td>" . $r->price_quantity_start . " - " . $r->price_quantity_end
					. "</td><td>" . $price . "</td></tr>";
			}

			$quantitytable .= "</table>";
		}

		return $quantitytable;
	}

	public function getDiscountId($subtotal = 0, $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$userArr = $this->_userhelper->createUserSession($userid);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		$sql = "SELECT ds.discount_id FROM " . $this->_table_prefix . "discount_shoppers AS ds "
			. " WHERE ds.shopper_group_id = '" . $shopperGroupId . "' ";

		$this->_db->setQuery($sql);
		$list       = $this->_db->loadResultArray();
		$list       = array_merge(array(0 => '0'), $list);
		$discountid = implode(',', $list);

		if ($discountid)
		{
			$query   = "SELECT * FROM " . $this->_table_prefix . "discount "
				. "WHERE published =1 "
				. "AND discount_id IN (" . $discountid . ") "
				. "AND `start_date`<='" . time() . "' "
				. "AND `end_date` >='" . time() . "' ";
			$orderby = " ORDER BY `amount` DESC LIMIT 0,1";

			if (!$subtotal)
			{
				$query1 = $query . $orderby;
				$this->_db->setQuery($query1);
				$result = $this->_db->loadObject();

				return $result;
			}

			$query1 = $query . "AND `condition`=2 AND amount='" . $subtotal . "' " . $orderby;
			$this->_db->setQuery($query1);
			$result = $this->_db->loadObject();

			if (count($result) <= 0)
			{
				$query1 = $query . "AND `condition`=1 AND amount > '" . $subtotal . "' " . $orderby;
				$this->_db->setQuery($query1);
				$result = $this->_db->loadObject();

				if (count($result) <= 0)
				{
					$query1 = $query . "AND `condition`=3 AND amount < '" . $subtotal . "' " . $orderby;
					$this->_db->setQuery($query1);
					$result = $this->_db->loadObject();
				}
			}

			return $result;
		}

		return;
	}

	public function getDiscountAmount($cart = array(), $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if (count($cart) <= 0)
			$cart = $this->_session->get('cart');

		$discount = $this->getDiscountId($cart['product_subtotal'], $user_id);

		$discount_amount = 0;
		$discount_vat    = 0;

		if (count($discount) > 0)
		{
			$product_subtotal = $cart['product_subtotal'] + $cart['shipping'];

			if ($discount->discount_type == 0)
			{
				if ($discount->discount_amount > $product_subtotal)
				{
					$discount_amount = $product_subtotal;
				}
				else
				{
					$discount_amount = $discount->discount_amount;
				}
			}
			else
			{
				$discount_amount = $product_subtotal * $discount->discount_amount / 100;
			}

			// Added specific vat amount for useage for e-conomic
			$cart['discount_vat_amount'] = $discount_vat;
			// take vatrates when there is discount on ex price of products
			// formula is used discount amount / 1 + (vat rate).

// 			$vatData = $this->getVatRates(0,$user_id);
			$vatrate = 0;

			/*if(isset($vatData->tax_rate)){
				$vatrate = $vatData->tax_rate;
			}*/

			$discount_amount = round($discount_amount, 2);
			$discount_amt    = $discount_amount;
			//$discount_amt = $discount_amount / ( 1 + ($vatrate)); // exluding vat on discount

			if (APPLY_VAT_ON_DISCOUNT)
			{
				$discount_vat = $this->getProductTax(1, $discount_amount, $user_id);

				// Temp fix to solve issues with difference in amount of digits between e-conomic and redSHOP make them calculate on the same
				$discount_vat = round($discount_vat, 2);

				// Temp fix to show right VAT of cart content subtotal
				$cart['tax'] = $cart['tax'];

				// Temp fix to solve issues with difference in amount of digits between e-conomic and redSHOP make them calculate on the same
				$discount_amount = round($discount_amount, 2);

				// Temp fix to add in new var with discount vat to fix e-conomic discount and calculated based on the
				// actual VAT rate for the user retracted from the discount amount incl. VAT
				$discount_vat_fix = 0;

				if ($discount_amount)
				{
					$discount_vat_fix = ($discount_amount - ($discount_amount / (1 + (1 - (($discount_amount - $discount_vat) / $discount_amount)))));
				}

				$cart['discount_vat_amount'] = $discount_vat_fix;

				// End of temp fix
			}
			else
			{
				$discount_amt    = $discount_amount / (1 + ($vatrate));
				$discount_amount = $discount_amt;
			}

			// Added specific discount amount for useage for e-conomic
			$cart['discount_ex_vat'] = $discount_amt;
			$this->_session->set('cart', $cart);
		}

		return $discount_amount;
	}

	public function getProductPrice($product_id, $show_price_with_vat = 1, $user_id = 0)
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$row    = $this->getProductById($product_id);
		$result = $this->getProductPrices($product_id, $user_id);

		if (!empty($result))
		{
			$temp_Product_price = $result->product_price;
			$row->product_price = $temp_Product_price;
		}

		$discount_product_id = $this->getProductSpecialId($user_id);
		$res                 = $this->getProductSpecialPrice($row->product_price, $discount_product_id);

		if (!empty($res))
		{
			$discount_amount = 0;

			if (count($res) > 0)
			{
				if ($res->discount_type == 0)
				{
					$discount_amount = $res->discount_amount;
				}
				else
				{
					$discount_amount = ($row->product_price * $res->discount_amount) / (100);
				}
			}

			$row->product_price = $row->product_price - $discount_amount;

			if ($row->product_price < 0)
			{
				$row->product_price = 0;
			}
		}

		$tax_amount = 0;

		if ($show_price_with_vat && $row->product_price != 0)
		{
			$tax_amount = $this->getProductTax($row->product_id, $row->product_price, $user_id);
		}

		$product_price = $tax_amount + $row->product_price;

		return $product_price;
	}

	public function getAdditionMediaImage($section_id = 0, $section = "", $mediaType = "images")
	{
		$left = "";

		if ($section == "product")
		{
			$left = "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = m.section_id ";
		}
		elseif ($section == "property")
		{
			$left = "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS p ON p.property_id = m.section_id ";
		}
		elseif ($section == "subproperty")
		{
			$left = "LEFT JOIN " . $this->_table_prefix . "product_subattribute_color AS p ON p.subattribute_color_id = m.section_id ";
		}
		elseif ($section == "manufacturer")
		{
			$left = "LEFT JOIN " . $this->_table_prefix . "manufacturer AS p ON p.manufacturer_id = m.section_id ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "media AS m "
			. $left
			. "WHERE m.media_section='" . $section . "' "
			. "AND m.media_type='" . $mediaType . "' "
			. "AND m.section_id='" . $section_id . "' "
			. "AND m.published=1 "
			. "ORDER BY m.ordering,m.media_id ASC";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getAltText($media_section, $section_id, $media_name = '', $media_id = 0, $mediaType = "images")
	{
		$and = '';

		if ($media_name != '')
		{
			$and .= ' AND media_name="' . $media_name . '" ';
		}

		if ($media_id)
		{
			$and .= ' AND media_id="' . $media_id . '" ';
		}

		$query = 'SELECT * FROM ' . $this->_table_prefix . 'media '
			. 'WHERE media_section = "' . $media_section . '" '
			. 'AND section_id ="' . $section_id . '" '
			. 'AND media_type="' . $mediaType . '" '
			. $and;
		$this->_db->setQuery($query);
		$mediadata = $this->_db->loadObject();

		if (!$mediadata)
		{
			return false;
		}

		return $mediadata->media_alternate_text;
	}

	public function getUserInformation($userid = 0, $address_type = 'BT', $rs_user_info_id = 0)
	{
		$list = array();
		$user = JFactory::getUser();
		$and  = '';

		if (!$userid)
		{
			$userid = $user->id;
		}

		if (empty($address_type))
		{
			$address_type = 'BT';
		}

		if ($rs_user_info_id && $address_type == 'ST')
		{
			$and = "AND u.users_info_id = '" . $rs_user_info_id . "'";
		}

		if ($userid)
		{
			$query = "SELECT sh.*,u.* FROM " . $this->_table_prefix . "users_info AS u "
				. "LEFT JOIN " . $this->_table_prefix . "shopper_group AS sh ON sh.shopper_group_id=u.shopper_group_id "
				. "WHERE u.user_id='" . $userid . "' "
				. $and
				. "order by u.users_info_id ASC LIMIT 0,1";
			$this->_db->setQuery($query);
			$list = $this->_db->loadObject();
		}

		return $list;
	}

	public function getApplyVatOrNot($data_add = "", $user_id = 0)
	{
		$user            = JFactory::getUser();
		$userInformation = array();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id != 0)
		{
			$userInformation = $this->getUserInformation($user_id);
		}

		if (count($userInformation) <= 0)
		{
			$userInformation = $this->GetdefaultshopperGroupData();
		}

		if (!empty($userInformation))
		{
			if (isset($userInformation->show_price_without_vat) && $userInformation->show_price_without_vat)
			{
				return false;
			}
		}

		if (strstr($data_add, "{without_vat}"))
		{
			return false;
		}
		else
		{
			return $this->taxexempt_addtocart($user_id);
		}

		return true;
	}

	public function getApplyattributeVatOrNot($data_add = "", $user_id = 0)
	{
		$user            = JFactory::getUser();
		$userInformation = array();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		if ($user_id != 0)
		{
			$userInformation = $this->getUserInformation($user_id);
		}

		if (count($userInformation) <= 0)
		{
			$userInformation = $this->GetdefaultshopperGroupData();
		}

		if (!empty($userInformation))
		{
			if (isset($userInformation->show_price_without_vat) && $userInformation->show_price_without_vat)
			{
				return false;
			}
		}

		if (strstr($data_add, "{attribute_price_without_vat}"))
		{
			return 0;
		}
		elseif (strstr($data_add, "{attribute_price_with_vat}"))
		{
			return 1;
		}
		else
		{
			return $this->taxexempt_addtocart($user_id);
		}

		return true;
	}

	public function GetdefaultshopperGroupData()
	{
		$list           = array();
		$shopperGroupId = $this->_userhelper->getShopperGroup();
		$result         = $this->_userhelper->getShopperGroupList($shopperGroupId);

		if (count($result) > 0)
		{
			$list = $result[0];
		}

		return $list;
	}

	public function checkDiscountDate($productid = 0)
	{
		$discountprice = 0;
		$today         = time();
		$list          = array();

		// Initialiase variables.
		$query = $this->_db->getQuery(true)
		    ->select('*')
			->from($this->_db->quoteName('#__redshop_product'))
			->where($this->_db->quoteName('product_id') . ' = ' . $this->_db->quote($productid))
			->where('
				(
				(' . $this->_db->quoteName('discount_enddate') . ' = "" AND ' . $this->_db->quoteName('discount_stratdate') . ' = "")
				OR
				(' . $this->_db->quoteName('discount_enddate') . ' >= ' . $this->_db->quote($today) . ' AND ' . $this->_db->quoteName('discount_stratdate') . ' <= ' . $this->_db->quote($today) . ')
				)');

		// Set the query and load the result.
		$this->_db->setQuery($query);

		try
		{
			$list = $this->_db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			throw new RuntimeException($e->getMessage(), $e->getCode());
		}

		if (count($list) > 0)
		{
			$discountprice = $list[0]->discount_price;
		}

		return $discountprice;
	}

	public function getPropertyPrice($section_id = '', $quantity = '', $section = '', $user_id = 0)
	{
		$leftjoin = "";
		$and      = "";
		$user     = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$userArr = $this->_userhelper->createUserSession($userid);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];

		if ($user_id)
		{
			$leftjoin = " LEFT JOIN " . $this->_table_prefix . "users_info AS u ON u.shopper_group_id=p.shopper_group_id ";
			$and      = " AND u.user_id='" . $user_id . "' AND u.address_type='BT' ";
		}
		else
		{
			$and = " AND p.shopper_group_id = '" . $shopperGroupId . "' ";
		}

		$query = "SELECT p.price_id,p.product_price,p.product_currency,p.discount_price, p.discount_start_date, p.discount_end_date  "
			. "FROM " . $this->_table_prefix . "product_attribute_price AS p "
			. $leftjoin
			. " WHERE p.section_id = '" . $section_id . "' AND section = '" . $section . "' "
			. $and
			. " AND ( (p.price_quantity_start <= '" . $quantity . "' and p.price_quantity_end >= '"
			. $quantity . "') OR (p.price_quantity_start = '0' AND p.price_quantity_end = '0')) "
			. "ORDER BY price_quantity_start ASC LIMIT 0,1 ";
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		if (count($result) > 0 && $result->discount_price != 0
			&& $result->discount_start_date != 0
			&& $result->discount_end_date != 0
			&& $result->discount_start_date <= time()
			&& $result->discount_end_date >= time()
			&& $result->discount_price < $result->product_price)
		{
			$result->product_price = $result->discount_price;
		}

		return $result;
	}

	public function getProperty($section_id, $section)
	{
		if ($section == 'property')
		{
			$query = "SELECT p.*,property_price as product_price  "
				. " FROM " . $this->_table_prefix . "product_attribute_property AS p "
				. " WHERE "
				. " p.property_id = '" . $section_id . "' AND p.property_published = 1 ";
		}

		if ($section == 'subproperty')
		{
			$query = "SELECT p.*,subattribute_color_price as product_price  "
				. " FROM " . $this->_table_prefix . "product_subattribute_color AS p "
				. " WHERE "
				. " p.subattribute_color_id = '" . $section_id . "' AND p.subattribute_published = 1 ";
		}

		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		return $result;
	}

	public function getWrapper($product_id, $wrapper_id = 0, $default = 1)
	{
		$usetoall = "";
		$and      = "";

		if ($wrapper_id != 0)
		{
			$and .= " AND wrapper_id='" . $wrapper_id . "' ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "product_category_xref "
			. "WHERE product_id = '" . $product_id . "' ";
		$this->_db->setQuery($query);
		$cat = $this->_db->loadObjectList();

		for ($i = 0; $i < count($cat); $i++)
		{
			$usetoall .= " OR FIND_IN_SET(" . $cat[$i]->category_id . ",category_id) ";
		}

		if ($default != 0)
		{
			$usetoall .= " OR wrapper_use_to_all = 1 ";
		}

		$query = "SELECT * FROM " . $this->_table_prefix . "wrapper "
			. "WHERE published = 1 "
			. "AND (FIND_IN_SET(" . $product_id . ",product_id) "
			. $usetoall . " )"
			. $and;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectList();

		return $list;
	}

	public function getBreadcrumbPathway($category = array())
	{
		$pathway_items = array();

		for ($i = 0; $i < count($category); $i++)
		{
			$item            = new stdClass;
			$item->name      = $category[$i]['category_name'];
			$item->link      = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $category[$i]['category_id'] . '&Itemid=' . $category[$i]['catItemid']);
			$pathway_items[] = $item;
		}

		return $pathway_items;
	}

	public function getCategoryNavigationlist($category_id)
	{
		$redhelper = new redhelper;
		static $i = 0;
		static $category_list = array();

		$categorylist       = $this->getSection("category", $category_id);
		$category_parent_id = $this->getParentCategory($category_id);

		if (count($categorylist) > 0)
		{
			$cItemid = $redhelper->getCategoryItemid($categorylist->category_id);

			if ($cItemid != "")
			{
				$tmpItemid = $cItemid;
			}
			else
			{
				$tmpItemid = JRequest::getVar('Itemid');
			}

			$category_list[$i]['category_id']   = $categorylist->category_id;
			$category_list[$i]['category_name'] = $categorylist->category_name;
			$category_list[$i]['catItemid']     = $tmpItemid;
		}

		if ($category_parent_id)
		{
			$i++;
			array_merge($category_list, $this->getCategoryNavigationlist($category_parent_id));
		}

		return $category_list;
	}

	public function generateBreadcrumb($sectionid = 0)
	{
		$app     = JFactory::getApplication();
		$pathway       = $app->getPathway();
		$view          = JRequest::getVar('view');
		$layout        = JRequest::getVar('layout');
		$Itemid        = JRequest::getInt('Itemid');
		$catid         = JRequest::getInt('cid');
		$custompathway = array();

		$patharr = $pathway->getPathWay();

		$totalcount = count($patharr);

		for ($j = 0; $j < $totalcount; $j++)
		{
			unset($patharr[$j]);
		}

		$pathway->setPathWay($patharr);

		switch ($view)
		{
			case "category":
				$custompathway = array();
				$newlink       = "index.php?option=com_redshop&view=category";

				if ($layout == "categoryproduct")
				{
					$newlink = "index.php?option=com_redshop&view=category&layout=" . $layout;
				}

				$res = $this->getMenuDetail($newlink);

				if (count($res) > 0 && $res->home != 1)
				{
					$main            = new stdClass;
					$main->name      = $res->title;
					$main->link      = JRoute::_($newlink . '&Itemid=' . $res->id);
					$custompathway[] = $main;
				}

				if ($sectionid != 0)
				{
					$category_list = array_reverse($this->getCategoryNavigationlist($sectionid));
					$custompathway = array_merge($custompathway, $this->getBreadcrumbPathway($category_list));
				}
				break;
			case "product":
				$res = $this->getMenuInformation($Itemid);

				if (count($res) > 0 && (strstr($res->params, "manufacturer") && !strstr($res->params, '"manufacturer_id":"0"')))
				{
					$custompathway = array();
					$res           = $this->getMenuDetail("index.php?option=com_redshop&view=manufacturers");

					if (count($res) > 0 && $res->home != 1)
					{
						if ($res->parent)
						{
							$parentres = $this->getMenuInformation($res->parent);

							if (count($parentres) > 0)
							{
								$main            = new stdClass;
								$main->name      = $parentres->name;
								$main->link      = JRoute::_($parentres->link . '&Itemid=' . $parentres->id);
								$custompathway[] = $main;
							}
						}

						$main            = new stdClass;
						$main->name      = $res->title;
						$main->link      = JRoute::_('index.php?option=com_redshop&view=manufacturers&Itemid=' . $res->id);
						$custompathway[] = $main;
					}

					if ($sectionid != 0)
					{
						$prd = $this->getSection("product", $sectionid);
						$res = $this->getSection("manufacturer", $prd->manufacturer_id);

						if (count($res) > 0)
						{
							$main            = new stdClass;
							$main->name      = $res->manufacturer_name;
							$main->link      = JRoute::_('index.php?option=com_redshop&view=manufacturers&layout=products&mid='	. $prd->manufacturer_id . '&Itemid=' . $Itemid);
							$custompathway[] = $main;
						}

						$main            = new stdClass;
						$main->name      = $prd->product_name;
						$main->link      = "";
						$custompathway[] = $main;
					}
				}
				else
				{
					$custompathway = array();
					$res           = $this->getMenuDetail("index.php?option=com_redshop&view=category");

					if (count($res) > 0 && $res->home != 1)
					{
						$main            = new stdClass;
						$main->name      = $res->title;
						$main->link      = JRoute::_('index.php?option=com_redshop&view=category&Itemid=' . $res->id);
						$custompathway[] = $main;
					}
					else
					{
						$res = $this->getMenuDetail("index.php?option=com_redshop&view=product&pid=" . $sectionid);

						if (count($res) > 0 && $res->home != 1 && $res->parent)
						{
							$parentres = $this->getMenuInformation($res->parent);

							if (count($parentres) > 0)
							{
								$main            = new stdClass;
								$main->name      = $parentres->name;
								$main->link      = JRoute::_($parentres->link . '&Itemid=' . $parentres->id);
								$custompathway[] = $main;
							}
						}
					}

					if ($sectionid != 0)
					{
						$prd = $this->getSection("product", $sectionid);

						if ($catid != 0)
						{
						}
						else
						{
							$catid = $this->getCategoryProduct($sectionid);
						}

						if ($catid != 0)
						{
							$category_list = array_reverse($this->getCategoryNavigationlist($catid));
							$custompathway = array_merge($custompathway, $this->getBreadcrumbPathway($category_list));
						}

						$main            = new stdClass;
						$main->name      = $prd->product_name;
						$main->link      = "";
						$custompathway[] = $main;
					}
				}
				break;
			case "manufacturers":

				$custompathway = array();
				$res           = $this->getMenuDetail("index.php?option=com_redshop&view=manufacturers");

				if (count($res) > 0 && $res->home != 1)
				{
					if ($res->parent)
					{
						$parentres = $this->getMenuInformation($res->parent);

						if (count($parentres) > 0)
						{
							$main            = new stdClass;
							$main->name      = $parentres->name;
							$main->link      = JRoute::_($parentres->link . '&Itemid=' . $parentres->id);
							$custompathway[] = $main;
						}
					}

					$main            = new stdClass;
					$main->name      = $res->title;
					$main->link      = JRoute::_('index.php?option=com_redshop&view=manufacturers&Itemid=' . $res->id);
					$custompathway[] = $main;
				}

				if ($sectionid != 0)
				{
					$res = $this->getMenuInformation(0, $sectionid, "manufacturerid", "manufacturers");

					if (count($res) > 0)
					{
						$main            = new stdClass;
						$main->name      = $res->title;
						$main->link      = "";
						$custompathway[] = $main;
					}
					else
					{
						$res = $this->getSection("manufacturer", $sectionid);

						if (count($res) > 0)
						{
							$main            = new stdClass;
							$main->name      = $res->manufacturer_name;
							$main->link      = "";
							$custompathway[] = $main;
						}
					}
				}
				break;
			case "account":
				$custompathway = array();
				$res           = $this->getMenuInformation($Itemid);

				if (count($res) > 0)
				{
					$main       = new stdClass;
					$main->name = $res->title;
					$main->link = "";
				}
				else
				{
					$main       = new stdClass;
					$main->name = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
					$main->link = "";
				}

				$custompathway[] = $main;
				break;
			case "order_detail":
				$custompathway = array();
				$res           = $this->getMenuInformation(0, 0, "", "account");

				if (count($res) > 0)
				{
					$main            = new stdClass;
					$main->name      = $res->title;
					$main->link      = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $res->id);
					$custompathway[] = $main;
				}
				else
				{
					$main            = new stdClass;
					$main->name      = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
					$main->link      = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid);
					$custompathway[] = $main;
				}

				$main            = new stdClass;
				$main->name      = JText::_('COM_REDSHOP_ORDER_DETAILS');
				$main->link      = "";
				$custompathway[] = $main;
				break;
			case "orders":
			case "account_billto":
			case "account_shipto":
				$custompathway = array();
				$res           = $this->getMenuInformation(0, 0, "", "account");

				if (count($res) > 0)
				{
					$main            = new stdClass;
					$main->name      = $res->title;
					$main->link      = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $res->id);
					$custompathway[] = $main;
				}
				else
				{
					$main            = new stdClass;
					$main->name      = JText::_('COM_REDSHOP_ACCOUNT_MAINTAINANCE');
					$main->link      = JRoute::_('index.php?option=com_redshop&view=account&Itemid=' . $Itemid);
					$custompathway[] = $main;
				}

				if ($view == 'orders')
				{
					$lastlink = JText::_('COM_REDSHOP_ORDER_LIST');
				}
				elseif ($view == 'account_billto')
				{
					$lastlink = JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL');
				}
				elseif ($view == 'account_shipto')
				{
					$lastlink = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL');
				}

				$main            = new stdClass;
				$main->name      = $lastlink;
				$main->link      = "";
				$custompathway[] = $main;
				break;
		}

		if (count($custompathway) > 0)
		{
			$custompathway[count($custompathway) - 1]->link = '';

			for ($j = 0; $j < count($custompathway); $j++)
			{
				$pathway->addItem($custompathway[$j]->name, $custompathway[$j]->link);
			}
		}
	}

	public function getSection($section = "", $id = 0)
	{
		$and = "";

		if ($id != 0)
		{
			$and = " WHERE " . $section . "_id = '" . $id . "' ";
		}

		$query = " SELECT * FROM " . $this->_table_prefix . $section
			. $and;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return $res;
	}

	public function getMenuDetail($link = "")
	{
		$and = "";

		if ($link != "")
		{
			$and .= " AND link = '" . $link . "' ";
		}

		$query = "SELECT * FROM #__menu "
			. "WHERE published=1 "
			. $and;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return $res;
	}

	public function getMenuInformation($Itemid = 0, $sectionid = 0, $sectioname = "", $menuview = "", $isRedshop = true)
	{
		$and = "";

		if ($menuview != "")
		{
			$and .= " AND link LIKE '%view=$menuview%' ";
		}

		if ($sectionid != 0)
		{
			$sid = "=" . $sectionid . "\n";
			$not = "";
		}
		else
		{
			$sid = "";
			$not = "NOT";
		}

		if ($sectioname != "")
		{
			$and .= " AND params " . $not . " LIKE '%$sectioname" . "$sid%' ";
		}

		if ($Itemid != 0)
		{
			$and .= " AND id = '" . $Itemid . "' ";
		}

		if ($isRedshop)
		{
			$and .= " AND link LIKE '%com_redshop%' ";
		}

		$query = "SELECT * FROM #__menu "
			. "WHERE published=1 "
			. $and
			. " ORDER BY id ";
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return $res;
	}

	public function getParentCategory($id = 0)
	{
		$query = "SELECT category_parent_id FROM " . $this->_table_prefix . "category_xref WHERE category_child_id='" . $id . "' ";
		$this->_db->setQuery($query);
		$res = $this->_db->loadResult();

		return $res;
	}

	public function getCategoryProduct($id = 0)
	{
		$query = "SELECT category_id FROM " . $this->_table_prefix . "product_category_xref WHERE product_id='" . $id . "' ";
		$this->_db->setQuery($query);
		$res = $this->_db->loadResult();

		return $res;
	}

	public function getProductCategory($id = 0)
	{
		$rsUserhelper               = new rsUserhelper;
		$shopper_group_manufactures = $rsUserhelper->getShopperGroupManufacturers();

		if ($shopper_group_manufactures != "")
		{
			$and .= " AND p.manufacturer_id IN (" . $shopper_group_manufactures . ") ";
		}

		$query = "SELECT p.product_id FROM " . $this->_table_prefix . "product_category_xref pc"
			. " LEFT JOIN " . $this->_table_prefix . "product AS p ON pc.product_id=p.product_id "
			. " WHERE category_id='" . $id . "' "
			. $and;
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectlist();

		return $res;
	}

	/*
	 * function to check product is downloadable or else
	 * @param: $pid : product Id
	 * @param: $return : return variable to change return type
	 * if $return = true
	 * @return: std class array
	 * else
	 *  @return: boolean
	 *
	 */
	public function checkProductDownload($pid, $return = false)
	{
		$query = 'SELECT product_download,product_download_days,product_download_limit,product_download_clock,product_download_clock_min,product_download_infinite FROM '
			. $this->_table_prefix . 'product '
			. 'WHERE product_id ="' . $pid . '" ';

		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		if ($return)
			return $res;
		else
			return $res->product_download;
	}

	public function getProductMediaName($product_id)
	{
		$query = 'SELECT media_name FROM ' . $this->_table_prefix . 'media '
			. 'WHERE media_section = "product" '
			. 'AND media_type="download" '
			. 'AND published=1 AND section_id="' . $product_id . '" ';
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectList();

		return $res;
	}

	public function updateContainerStock($product_id, $quantity, $container_id)
	{
		// Subtracting the products from the container. means decreasing stock
		$query = "SELECT quantity FROM " . $this->_table_prefix . "container_product_xref "
			. "WHERE container_id='" . $container_id . "' AND product_id='" . $product_id . "' ";
		$this->_db->setQuery($query);
		$con_product_qun = $this->_db->loadResult();
		$con_product_qun = $con_product_qun - $quantity;

		if ($con_product_qun > 0)
		{
			$query = 'UPDATE ' . $this->_table_prefix . 'container_product_xref '
				. 'SET quantity = ' . $con_product_qun
				. ' WHERE container_id="' . $container_id . '" AND product_id="' . $product_id . '" ';
			$this->_db->setQuery($query);
			$this->_db->query();
		}

		// Subtracting the products from the container. means decreasing stock end
	}

	public function getGiftcardData($gid)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "giftcard "
			. "WHERE giftcard_id='" . $gid . "' ";
		$this->_db->setQuery($query);
		$res = $this->_db->loadObject();

		return $res;
	}

	public function getValidityDate($period, $data)
	{
		$todate = mktime(0, 0, 0, date('m'), date('d') + $period, date('Y'));
		$config = new Redconfiguration;

		$todate   = $config->convertDateFormat($todate);
		$fromdate = $config->convertDateFormat(strtotime(date('d M Y')));

		$data = str_replace("{giftcard_validity_from}", JText::_('COM_REDSHOP_FROM') . " " . $fromdate, $data);
		$data = str_replace("{giftcard_validity_to}", JText::_('COM_REDSHOP_TO') . " " . $todate, $data);

		return $data;
	}

	public function getAccessoryPrice($product_id = 0, $accessory_price = 0, $accessory_main_price = 0, $vat = 0, $user_id = 0)
	{
		$return = array();
		$saved  = 0;

		/*
		 * $vat = 0 (add vat to accessory price)
		 * $vat = 1 (Do not add vat to accessory price)
		 */
		if ($vat != 1)
		{
			$accessory_price_vat      = 0;
			$accessory_main_price_vat = 0;

			// Get vat for accessory price
			if ($accessory_price > 0)
			{
				$accessory_price_vat = $this->getProductTax($product_id, $accessory_price, $user_id);
			}

			if ($accessory_main_price > 0)
			{
				$accessory_main_price_vat = $this->getProductTax($product_id, $accessory_main_price, $user_id);
			}

			// Add VAT to accessory prices
			$accessory_price += $accessory_price_vat;
			$accessory_main_price += $accessory_main_price_vat;
		}

		$saved = $accessory_main_price - $accessory_price;

		if ($saved < 0)
		{
			$saved = 0;
		}

		//accessory Price
		$return[0] = $accessory_price;

		//accessory main price
		$return[1] = $accessory_main_price;

		//accessory saving price
		$return[2] = $saved;

		return $return;
	}

	public function getuserfield($orderitemid = 0, $section_id = 12)
	{
		$redTemplate     = new Redtemplate;
		$order_functions = new order_functions;
		$live_site       = JURI::root();
		$resultArr       = array();

		$userfield = $order_functions->getOrderUserfieldData($orderitemid, $section_id);

		if (count($userfield) > 0)
		{
			$orderItem  = $order_functions->getOrderItemDetail(0, 0, $orderitemid);
			$product_id = $orderItem[0]->product_id;

			$productdetail   = $this->getProductById($product_id);
			$productTemplate = $redTemplate->getTemplate("product", $productdetail->product_template);

			$returnArr    = $this->getProductUserfieldFromTemplate($productTemplate[0]->template_desc);
			$userFieldTag = $returnArr[1];

			for ($i = 0; $i < count($userFieldTag); $i++)
			{
				for ($j = 0; $j < count($userfield); $j++)
				{
					if ($userfield[$j]->field_name == $userFieldTag[$i])
					{
						if ($userfield[$j]->field_type == 10)
						{
							$files    = explode(",", $userfield[$j]->data_txt);
							$data_txt = "";

							for ($f = 0; $f < count($files); $f++)
							{
								$u_link = REDSHOP_FRONT_DOCUMENT_ABSPATH . "product/" . $files[$f];
								$data_txt .= "<a href='" . $u_link . "' target='_blank'>" . $files[$f] . "</a> ";
							}

							if (trim($data_txt) != "")
							{
								$resultArr[] = $userfield[$j]->field_title . " : " . $data_txt;
							}
						}
						else
						{
							if (trim($userfield[$j]->data_txt) != "")
							{
								$resultArr[] = $userfield[$j]->field_title . " : " . $userfield[$j]->data_txt;
							}
						}
					}
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
		}

		return $resultstr;
	}

	public function getProductUserfieldFromTemplate($templatedata = "", $giftcard = 0)
	{
		$userfields      = array();
		$userfields_lbl  = array();
		$retArr          = array();
		$template_middle = "";

		if ($giftcard)
		{
			$template_start = explode("{if giftcard_userfield}", $templatedata);

			if (count($template_start) > 1)
			{
				$template_end = explode("{giftcard_userfield end if}", $template_start[1]);

				if (count($template_end) > 0)
				{
					$template_middle = $template_end[0];
				}
			}
		}
		else
		{
			$template_start = explode("{if product_userfield}", $templatedata);

			if (count($template_start) > 1)
			{
				$template_end = explode("{product_userfield end if}", $template_start[1]);

				if (count($template_end) > 0)
				{
					$template_middle = $template_end[0];
				}
			}
		}

		if ($template_middle != "")
		{
			$tmpArr = explode('}', $template_middle);

			for ($i = 0; $i < count($tmpArr); $i++)
			{
				$val   = strpbrk($tmpArr[$i], "{");
				$value = str_replace("{", "", $val);

				if ($value != "")
				{
					if (strstr($template_middle, '{' . $value . '_lbl}'))
					{
						$userfields_lbl[] = $value . '_lbl';
						$userfields[]     = $value;
					}
					else
					{
						$userfields_lbl[] = '';
						$userfields[]     = $value;
					}
				}
			}
		}

		$tmp = array();

		for ($i = 0; $i < count($userfields); $i++)
		{
			if (!in_array($userfields[$i], $userfields_lbl))
			{
				$tmp[] = $userfields[$i];
			}
		}

		$userfields = $tmp;
		$retArr[0]  = $template_middle;
		$retArr[1]  = $userfields;

		return $retArr;
	}

	public function GetProdcutUserfield($id = 'NULL', $section_id = 12)
	{
		$extraField  = new extraField;
		$redTemplate = new Redtemplate;
		$cart        = $this->_session->get('cart');

		$row_data = $extraField->getSectionFieldList($section_id, 1, 0);

		$product_id = $cart[$id]['product_id'];

		$productdetail = $this->getProductById($product_id);

		if ($section_id == 12)
		{
			$temp_name = "product";
			$temp_id   = $productdetail->product_template;
			$giftcard  = 0;
		}
		else
		{
			$temp_name = "giftcard";
			$temp_id   = 0;
			$giftcard  = 1;
		}

		$productTemplate = $redTemplate->getTemplate($temp_name, $temp_id);

		$returnArr    = $this->getProductUserfieldFromTemplate($productTemplate[0]->template_desc, $giftcard);
		$userFieldTag = $returnArr[1];

		$resultArr = array();

		for ($i = 0; $i < count($userFieldTag); $i++)
		{
			for ($j = 0; $j < count($row_data); $j++)
			{
				if (array_key_exists($userFieldTag[$i], $cart[$id]) && $cart[$id][$userFieldTag[$i]])
				{
					if ($row_data[$j]->field_name == $userFieldTag[$i])
					{
						$strtitle = '';

						if ($row_data[$j]->field_title)
						{
							$strtitle = $row_data[$j]->field_title . ' : ';
						}

						$resultArr[] = $strtitle . $cart[$id][$userFieldTag[$i]];
					}
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = "<div>" . JText::_("COM_REDSHOP_PRODUCT_USERFIELD") . "</div><div>" . implode("<br/>", $resultArr) . "</div>";
		}

		return $resultstr;
	}

	public function GetProdcutfield($id = 'NULL', $section_id = 1)
	{
		$extraField = new extraField;
		$cart       = $this->_session->get('cart');
		$product_id = $cart[$id]['product_id'];
		$row_data   = $extraField->getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0; $j < count($row_data); $j++)
		{
			$main_result = $extraField->getSectionFieldDataList($row_data[$j]->field_id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = $main_result->field_title . " : " . $main_result->data_txt;
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	public function GetProdcutfield_order($orderitemid = 'NULL', $section_id = 1)
	{
		$extraField      = new extraField;
		$order_functions = new order_functions;
		$orderItem       = $order_functions->getOrderItemDetail(0, 0, $orderitemid);

		$product_id = $orderItem[0]->product_id;

		$row_data = $extraField->getSectionFieldList($section_id, 1, 0);

		$resultArr = array();

		for ($j = 0; $j < count($row_data); $j++)
		{
			$main_result = $extraField->getSectionFieldDataList($row_data[$j]->field_id, $section_id, $product_id);

			if (isset($main_result->data_txt) && isset($row_data[$j]->display_in_checkout))
			{
				if ($main_result->data_txt != "" && 1 == $row_data[$j]->display_in_checkout)
				{
					$resultArr[] = $main_result->field_title . " : " . $main_result->data_txt;
				}
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}

	public function insertProdcutUserfield($id = 'NULL', $cart = array(), $order_item_id = 0, $section_id = 12)
	{
		$extraField = new extraField;
		$row_data   = $extraField->getSectionFieldList($section_id, 1);

		for ($i = 0; $i < count($row_data); $i++)
		{
			if (array_key_exists($row_data[$i]->field_name, $cart[$id]) && $cart[$id][$row_data[$i]->field_name])
			{
				$user_fields = $cart[$id][$row_data[$i]->field_name];

				if (trim($user_fields) != '')
				{
					$sql = "INSERT INTO " . $this->_table_prefix . "fields_data "
						. "(fieldid,data_txt,itemid,section) "
						. "value ('" . $row_data[$i]->field_id . "','" . addslashes($user_fields) . "','"
						. $order_item_id . "','" . $section_id . "')";
					$this->_db->setQuery($sql);
					$this->_db->query();
				}
			}
		}

		return;
	}

	public function getProductAttribute($product_id = 0, $attribute_set_id = 0, $attribute_id = 0, $published = 0, $attribute_required = 0, $notAttributeId = 0)
	{
		$and          = "";
		$astpublished = "";

		if ($product_id != 0)
		{
			$and .= "AND a.product_id IN (" . $product_id . ") ";
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id='" . $attribute_set_id . "' ";
		}

		if ($attribute_id != 0)
		{
			$and .= "AND a.attribute_id='" . $attribute_id . "' ";
		}

		if ($published != 0)
		{
			$astpublished = " AND ast.published='" . $published . "' ";
		}

		if ($attribute_required != 0)
		{
			$and .= "AND a.attribute_required='" . $attribute_required . "' ";
		}

		if ($notAttributeId != 0)
		{
			$and .= "AND a.attribute_id NOT IN (" . $notAttributeId . ") ";
		}

		$query = "SELECT a.attribute_id AS value,a.attribute_name AS text,a.*,ast.attribute_set_name "
			. "FROM " . $this->_table_prefix . "product_attribute AS a "
			. "LEFT JOIN " . $this->_table_prefix . "attribute_set AS ast ON ast.attribute_set_id=a.attribute_set_id "
			. $astpublished
			. "WHERE a.attribute_name!='' "
			. $and
			. " and attribute_published=1 ORDER BY a.ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAttibuteProperty($property_id = 0, $attribute_id = 0, $product_id = 0, $attribute_set_id = 0, $required = 0, $notPropertyId = 0)
	{
		$and = "";

		if ($attribute_id != 0)
		{
			$and .= "AND ap.attribute_id IN (" . $attribute_id . ") ";
		}

		if ($attribute_set_id != 0)
		{
			$and .= "AND a.attribute_set_id IN (" . $attribute_set_id . ") ";
		}

		if ($property_id != 0)
		{
			$and .= "AND ap.property_id IN (" . $property_id . ") ";
		}

		if ($product_id != 0)
		{
			$and .= "AND a.product_id IN (" . $product_id . ") ";
		}

		if ($required != 0)
		{
			$and .= "AND ap.setrequire_selected='" . $required . "' ";
		}

		if ($notPropertyId != 0)
		{
			$and .= "AND ap.property_id NOT IN (" . $notPropertyId . ") ";
		}

		$query = "SELECT ap.property_id AS value,ap.property_name AS text,ap.*, a.attribute_name "
			. "FROM " . $this->_table_prefix . "product_attribute_property AS ap "
			. "LEFT JOIN " . $this->_table_prefix . "product_attribute AS a ON a.attribute_id = ap.attribute_id "
			. "WHERE 1=1 AND ap.property_published = 1 "
			. $and
			. "ORDER BY ap.ordering ASC ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAttibutePropertyWithStock($property)
	{
		$stockroomhelper     = new rsstockroomhelper;
		$property_with_stock = array();

		for ($p = 0; $p < count($property); $p++)
		{
			$isStock = $stockroomhelper->isStockExists($property[$p]->property_id, $section = "property");

			if ($isStock)
			{
				$property_with_stock[] = $property[$p];
			}
		}

		return $property_with_stock;
	}

	public function getAttibuteSubPropertyWithStock($subproperty)
	{
		$stockroomhelper        = new rsstockroomhelper;
		$subproperty_with_stock = array();

		for ($p = 0; $p < count($subproperty); $p++)
		{
			$isStock = $stockroomhelper->isStockExists($subproperty[$p]->subattribute_color_id, $section = "subproperty");

			if ($isStock)
			{
				$subproperty_with_stock[] = $subproperty[$p];
			}
		}

		return $subproperty_with_stock;
	}

	public function getAttibuteSubProperty($subproperty_id = 0, $property_id = 0)
	{
		$and = "";

		if ($subproperty_id != 0)
		{
			$and .= "AND sp.subattribute_color_id='" . $subproperty_id . "' ";
		}

		if ($property_id != 0)
		{
			$and .= "AND sp.subattribute_id='" . $property_id . "' ";
		}

		$query = "SELECT sp.subattribute_color_id AS value, sp.subattribute_color_name AS text"
			. ",sp.*,p.property_name,p.setrequire_selected,p.setmulti_selected FROM " . $this->_table_prefix
			. "product_subattribute_color AS sp "
			. "LEFT JOIN " . $this->_table_prefix . "product_attribute_property AS p ON p.property_id=sp.subattribute_id "
			. "WHERE 1=1 AND sp.subattribute_published = 1 "
			. $and
			. " ORDER BY sp.ordering ASC";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAttributeTemplate($data_add = "", $display = true)
	{
		$attribute_template      = array();
		$attribute_template_data = array();
		$redTemplate             = new Redtemplate;
		$displayname             = "attribute_template";
		$nodisplayname           = "attributewithcart_template";

		if (INDIVIDUAL_ADD_TO_CART_ENABLE)
		{
			$displayname   = "attributewithcart_template";
			$nodisplayname = "attribute_template";
		}

		if (!$display)
		{
			$displayname = $nodisplayname;
		}

		if ($displayname == "attribute_template")
		{
			if (is_null($this->_attribute_template))
			{
				$this->_attribute_template = $attribute_template = $redTemplate->getTemplate($displayname);
			}
			else
			{
				$attribute_template = $this->_attribute_template;
			}
		}
		else
		{
			if (is_null($this->_attributewithcart_template))
			{
				$this->_attributewithcart_template = $attribute_template = $redTemplate->getTemplate($displayname);
			}
			else
			{
				$attribute_template = $this->_attributewithcart_template;
			}
		}

		if ($data_add != "")
		{
			for ($i = 0; $i < count($attribute_template); $i++)
			{
				if (strstr($data_add, "{" . $displayname . ":" . $attribute_template[$i]->template_name . "}"))
				{
					$attribute_template_data = $attribute_template[$i];
				}
			}
		}

		return $attribute_template_data;
	}

	public function getProductAccessory($accessory_id = 0, $product_id = 0, $child_product_id = 0, $cid = 0)
	{
		$orderby = "ORDER BY child_product_id ASC";

		if (DEFAULT_ACCESSORY_ORDERING_METHOD)
		{
			$orderby = " ORDER BY " . DEFAULT_ACCESSORY_ORDERING_METHOD;
		}

		$and     = "";
		$groupby = "";

		if ($accessory_id != 0)
		{
			$and .= "AND a.accessory_id IN (" . $accessory_id . ") ";
		}

		if ($product_id != 0)
		{
			$and .= "AND a.product_id IN (" . $product_id . ") ";
		}

		if ($child_product_id != 0)
		{
			$and .= "AND a.child_product_id='" . $child_product_id . "' ";
		}

		if ($cid != 0)
		{
			$and .= "AND a.category_id='" . $cid . "' ";
			$groupby = " GROUP BY a.child_product_id";
		}

		if (ACCESSORY_AS_PRODUCT_IN_CART_ENABLE)
		{
			$switchquery = ", IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
				. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) AS newaccessory_price ";
		}
		else
		{
			$switchquery = ", CASE a.oprand "
				. "WHEN '+' THEN IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
				. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) + accessory_price "
				. "WHEN '-' THEN IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
				. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) - accessory_price "
				. "WHEN '=' THEN accessory_price "
				. "END AS newaccessory_price ";
		}

		$mainpricequery = "IF ( (p.product_on_sale>0 && ((p.discount_enddate='' AND p.discount_stratdate='') OR ( p.discount_enddate>='"
			. time() . "' AND p.discount_stratdate<='" . time() . "'))), p.discount_price, p.product_price ) AS accessory_main_price ";

		$query = "SELECT a.*,p.product_number, p.product_name, " . $mainpricequery
			. ", p.product_s_desc, p.product_full_image, p.product_on_sale "
			. $switchquery
			. "FROM " . $this->_table_prefix . "product_accessory AS a "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = a.child_product_id "
			. "WHERE p.published = 1 "
			. $and . $groupby
			. $orderby;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getProductNavigator($accessory_id = 0, $product_id = 0, $child_product_id = 0, $cid = 0)
	{
		$orderby = "ORDER BY ordering ASC";

		$and     = "";
		$groupby = "";

		if ($product_id != 0)
		{
			$and .= "AND a.product_id IN (" . $product_id . ") ";
		}

		$query = "SELECT a.*, p.product_name, p.product_number "
			. "FROM " . $this->_table_prefix . "product_navigator AS a "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = a.child_product_id "
			. "WHERE p.published = 1 "
			. $and
			. $orderby;
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getAddtoCartTemplate($data_add = "")
	{
		$redTemplate = new Redtemplate;

		if (is_null($this->_cart_template))
		{
			$this->_cart_template = $cart_template = $redTemplate->getTemplate("add_to_cart");
		}
		else
		{
			$cart_template = $this->_cart_template;
		}

		$cart_template_data = array();

		if ($data_add != "")
		{
			for ($i = 0; $i < count($cart_template); $i++)
			{
				if (strstr($data_add, "{form_addtocart:" . $cart_template[$i]->template_name . "}"))
				{
					$cart_template_data = $cart_template[$i];

					if (count($cart_template_data) > 0 && $cart_template_data->template_desc == "")
					{
						$cart_template_data->template_desc = '<div style="clear: left;"></div><div class="cart-wrapper"><div class="cart-quantity">{quantity_lbl}: {addtocart_quantity}</div><div class="cart-link">{addtocart_image_aslink}</div></div>';
					}

					break;
				}
			}
		}

		return $cart_template_data;
	}

	public function getAccessoryTemplate($data_add = "")
	{
		$redTemplate = new Redtemplate;

		if (is_null($this->_acc_template))
		{
			$this->_acc_template = $acc_template = $redTemplate->getTemplate("accessory_template");
		}
		else
		{
			$acc_template = $this->_acc_template;
		}

		$acc_template_data = array();

		if ($data_add != "")
		{
			for ($i = 0; $i < count($acc_template); $i++)
			{
				if (strstr($data_add, "{accessory_template:" . $acc_template[$i]->template_name . "}"))
				{
					$acc_template_data = $acc_template[$i];

					if (count($acc_template_data) > 0 && $acc_template_data->template_desc == "")
					{
						$acc_template_data->template_desc = '<div class="accessory"><div class="accessory_info"><h2>Accessories</h2>Add accessories by clicking in the box.</div>{accessory_product_start}<div class="accessory_box"><div class="accessory_left"><div class="accessory_image">{accessory_image}</div></div><div class="accessory_right"><div class="accessory_title"><h3>{accessory_title}</h3></div><div class="accessory_desc">{accessory_short_desc}</div><div class="accessory_readmore">{accessory_readmore}</div><div class="accessory_add">{accessory_price} {accessory_add_chkbox}</div><div class="accessory_qua">{accessory_quantity_lbl} {accessory_quantity}</div></div><div style="clear: left">&nbsp;&nbsp;</div></div>{accessory_product_end}</div><div style="clear: left">&nbsp;&nbsp;</div>';
					}

					break;
				}
			}
		}

		return $acc_template_data;
	}

	public function getRelatedProductTemplate($data_add = "")
	{
		$redTemplate   = new Redtemplate;
		$template      = $redTemplate->getTemplate("related_product");
		$template_data = array();

		for ($i = 0; $i < count($template); $i++)
		{
			if (strstr($data_add, "{related_product:" . $template[$i]->template_name . "}"))
			{
				$template_data = $template[$i];

				if (count($template_data) > 0 && $template_data->template_desc == "")
				{
					$template_data->template_desc = '<div class="related_product_wrapper"><h2>Related Products</h2>{related_product_start}<div class="related_product_inside"><div class="related_product_left"><div class="related_product_image"><div class="related_product_image_inside">{relproduct_image}</div></div></div><div class="related_product_right"><div class="related_product_name">{relproduct_name}</div><div class="related_product_price">{relproduct_price}</div><div class="related_product_desc">{relproduct_s_desc}</div><div class="related_product_readmore">{read_more}</div></div><div class="related_product_bottom"><div class="related_product_attr">{attribute_template:attributes}</div><div class="related_product_addtocart">{form_addtocart:add_to_cart2}</div></div></div>{related_product_end}</div>';
				}

				break;
			}
		}

		return $template_data;
	}

	public function getRelatedProduct($product_id = 0, $related_id = 0)
	{
		$helper          = new redhelper;
		$and             = "";
		$orderby         = "ORDER BY p.product_id ASC ";
		$orderby_related = "";

		if (DEFAULT_RELATED_ORDERING_METHOD)
		{
			$orderby         = "ORDER BY " . DEFAULT_RELATED_ORDERING_METHOD;
			$orderby_related = "";
		}

		if ($product_id != 0)
		{
			if ($helper->isredProductfinder())
			{
				$q = "SELECT extrafield  FROM #__redproductfinder_types where type_select='Productfinder datepicker'";
				$this->_db->setQuery($q);
				$finaltypetype_result = $this->_db->loadObject();
			}
			else
			{
				$finaltypetype_result = array();
			}

			$and .= "AND r.product_id IN (" . $product_id . ") ";

			if (TWOWAY_RELATED_PRODUCT)
			{
				if (DEFAULT_RELATED_ORDERING_METHOD == "r.ordering ASC" || DEFAULT_RELATED_ORDERING_METHOD == "r.ordering DESC")
				{
					$orderby         = "";
					$orderby_related = "ORDER BY " . DEFAULT_RELATED_ORDERING_METHOD;
				}

				$InProduct = "";

				$query = "SELECT * FROM " . $this->_table_prefix . "product_related AS r "
					. "WHERE r.product_id IN (" . $product_id . ") OR r.related_id IN (" . $product_id . ")" . $orderby_related . "";
				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();

				$relatedArr = array();

				for ($i = 0; $i < count($list); $i++)
				{
					if ($list[$i]->product_id == $product_id)
					{
						$relatedArr[] = $list[$i]->related_id;
					}
					else
					{
						$relatedArr[] = $list[$i]->product_id;
					}
				}

				if (empty($relatedArr))
				{
					return array();
				}

				$relatedArr = array_unique($relatedArr);

				$query = "SELECT " . $product_id . " AS mainproduct_id,p.* "
					. "FROM " . $this->_table_prefix . "product AS p "
					. "WHERE p.published = 1 ";
				$query .= ' AND p.product_id IN (' . implode(", ", $relatedArr) . ') ';
				$query .= $orderby;

				$this->_db->setQuery($query);
				$list = $this->_db->loadObjectlist();

				return $list;
			}
		}

		if ($related_id != 0)
		{
			$and .= "AND r.related_id='" . $related_id . "' ";
		}

		if (count($finaltypetype_result) > 0 && $finaltypetype_result->extrafield != ''
			&& (DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt ASC' || DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt DESC'))
		{
			$add_e = ",e.*";
		}
		else
		{
			$add_e = " ";
		}

		$query = "SELECT r.product_id AS mainproduct_id,p.* " . $add_e . " "
			. "FROM " . $this->_table_prefix . "product_related AS r "
			. "LEFT JOIN " . $this->_table_prefix . "product AS p ON p.product_id = r.related_id ";

		if (count($finaltypetype_result) > 0
			&& $finaltypetype_result->extrafield != ''
			&& (DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt ASC' || DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt DESC'))
		{
			$query .= " LEFT JOIN " . $this->_table_prefix . "fields_data  AS e ON p.product_id = e.itemid ";
		}

		$query .= " WHERE p.published = 1 ";

		if (count($finaltypetype_result) > 0 && $finaltypetype_result->extrafield != ''
			&& (DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt ASC' || DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt DESC'))
		{
			$query .= " AND e.fieldid='" . $finaltypetype_result->extrafield . "' AND e.section=17 ";
		}

		$query .= " $and GROUP BY r.related_id ";

		if ((DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt ASC'
			|| DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt DESC'))
		{
			if (DEFAULT_RELATED_ORDERING_METHOD == 'e.data_txt ASC')
			{
				$s = "STR_TO_DATE( e.data_txt, '%d-%m-%Y' ) ASC";
			}
			else
			{
				$s = "STR_TO_DATE( e.data_txt, '%d-%m-%Y' ) DESC";
			}

			$query .= " ORDER BY " . $s;
		}
		else
		{
			$query .= " $orderby ";
		}

		$this->_db->setQuery($query);

		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function makeTotalPriceByOprand($price = 0, $oprandArr = array(), $priceArr = array())
	{
		$setEqual = true;

		for ($i = 0; $i < count($oprandArr); $i++)
		{
			$oprand   = $oprandArr[$i];
			$subprice = $priceArr[$i];

			if ($oprand == "-")
			{
				$price -= $subprice;
			}
			elseif ($oprand == "+")
			{
				$price += $subprice;
			}
			elseif ($oprand == "*")
			{
				$price *= $subprice;
			}
			elseif ($oprand == "/")
			{
				$price /= $subprice;
			}
			elseif ($oprand == "=")
			{
				$price    = $subprice;
				$setEqual = false;
				break;
			}
		}

		$retArr    = array();
		$retArr[0] = $setEqual;
		$retArr[1] = $price;

		return $retArr;
	}

	public function getProductOnSaleComment($product = array(), $data_add = "")
	{
		$redconfig = new Redconfiguration;

		if (strstr($data_add, "{if product_on_sale}") && strstr($data_add, "{product_on_sale end if}"))
		{
			$template_pd_sdata = explode('{if product_on_sale}', $data_add);
			$template_pd_edata = explode('{product_on_sale end if}', $template_pd_sdata [1]);

			if ($product->product_on_sale == 1 && (($product->discount_stratdate == 0 && $product->discount_enddate == 0) || ($product->discount_stratdate <= time() && $product->discount_enddate >= time())))
			{
				$data_add = str_replace("{discount_start_date}", $redconfig->convertDateFormat($product->discount_stratdate), $data_add);
				$data_add = str_replace("{discount_end_date}", $redconfig->convertDateFormat($product->discount_enddate), $data_add);
				$data_add = str_replace("{if product_on_sale}", '', $data_add);
				$data_add = str_replace("{product_on_sale end if}", '', $data_add);
			}
			else
			{
				$data_add = $template_pd_sdata[0] . $template_pd_edata[1];
			}

			$data_add = str_replace("{discount_start_date}", '', $data_add);
			$data_add = str_replace("{discount_end_date}", '', $data_add);
		}

		return $data_add;
	}

	public function getProductNotForSaleComment($product = array(), $data_add = "", $attributes = array(), $is_relatedproduct = 0, $seoTemplate = "")
	{
		if (!$product->not_for_sale)
		{
			// Product show price without formatted
			$applytax = $this->getApplyVatOrNot($data_add);

			if ($applytax)
			{
				$GLOBAL ['without_vat'] = false;
			}
			else
			{
				$GLOBAL ['without_vat'] = true;
			}

			$data_add = $this->GetProductShowPrice($product->product_id, $data_add, $seoTemplate, 0, $is_relatedproduct, $attributes);

		}
		else
		{
			$relPrefix = ($is_relatedproduct) ? 'rel' : '';
			$data_add  = str_replace("{" . $relPrefix . "product_price_lbl}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_novat}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "price_excluding_vat}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_table}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_old_price}", "", $data_add);
			$data_add  = str_replace("{" . $relPrefix . "product_price_saving}", "", $data_add);
		}

		return $data_add;
	}

	public function getSpecialProductComment($product = array(), $data_add = "")
	{
		if (strstr($data_add, "{if product_special}") && strstr($data_add, "{product_special end if}"))
		{
			if ($product->product_special == 0)
			{
				$template_pd_sdata = explode('{if product_special}', $data_add);
				$template_pd_edata = explode('{product_special end if}', $template_pd_sdata [1]);
				$data_add          = $template_pd_sdata[0] . $template_pd_edata[1];
			}

			$data_add = str_replace("{if product_special}", '', $data_add);
			$data_add = str_replace("{product_special end if}", '', $data_add);
		}

		return $data_add;
	}

	public function getAjaxDetailboxTemplate($product = array())
	{
		if (!AJAX_CART_BOX)
		{
			return array();
		}

		$redTemplate     = new Redtemplate ();
		$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

		if (!$this->_ajaxdetail_templatedata)
		{
			$ajaxdetail_templatedata         = array();
			$default_ajaxdetail_templatedata = array();
			$ajaxdetail_template             = $redTemplate->getTemplate("ajax_cart_detail_box");

			for ($i = 0; $i < count($ajaxdetail_template); $i++)
			{
				if (strstr($producttemplate[0]->template_desc, "{ajaxdetail_template:" . $ajaxdetail_template[$i]->template_name . "}"))
				{
					$ajaxdetail_templatedata = $ajaxdetail_template[$i];
					break;
				}

				if (DEFAULT_AJAX_DETAILBOX_TEMPLATE == $ajaxdetail_template[$i]->template_id)
				{
					$default_ajaxdetail_templatedata = $ajaxdetail_template[$i];
				}
			}

			if (empty($ajaxdetail_templatedata) && count($default_ajaxdetail_templatedata) > 0)
			{
				$ajaxdetail_templatedata = $default_ajaxdetail_templatedata;
			}

			if (count($ajaxdetail_templatedata) > 0 && $ajaxdetail_templatedata->template_desc == "")
			{
				$ajaxdetail_templatedata->template_desc = '<div id="ajax-cart"><div id="ajax-cart-attr">{attribute_template:attributes}</div><div id="ajax-cart-access">{accessory_template:accessory}</div>{if product_userfield}<div id="ajax-cart-user">{userfield-test}</div>{product_userfield end if}<div id="ajax-cart-label">{form_addtocart:add_to_cart2}</div></div>';
			}

			$this->_ajaxdetail_templatedata = $ajaxdetail_templatedata;
		}

		return $this->_ajaxdetail_templatedata;
	}

	public function replaceAccessoryData($product_id = 0, $relproduct_id = 0, $accessory = array(), $data_add, $isChilds = false, $selectAcc = array())
	{
		$user_id   = 0;
		$url       = JURI::base();
		$redconfig = new Redconfiguration();

		$viewacc = JRequest::getVar('viewacc', 1);
		$layout  = JRequest::getVar('layout');
		$Itemid  = JRequest::getVar('Itemid');

		$isAjax = 0;
		$prefix = "";

		if ($layout == "viewajaxdetail")
		{
			$isAjax = 1;
			$prefix = "ajax_";
		}

		if ($relproduct_id != 0)
		{
			$product_id = $relproduct_id;
		}

		$selectedAccessory    = array();
		$selectedAccessoryQua = array();
		$selectAtt            = array();

		if (count($selectAcc) > 0)
		{
			$selectedAccessory    = $selectAcc[0];
			$selectedAccessoryQua = $selectAcc[3];
			$selectAtt            = array($selectAcc[1], $selectAcc[2]);
		}

		$product            = $this->getProductById($product_id);
		$accessory_template = $this->getAccessoryTemplate($data_add);

		if (count($accessory_template) <= 0)
		{
			return $data_add;
		}

		$accessory_template_data = $accessory_template->template_desc;

		$totalAccessory = count($accessory);

		$attribute_template = $this->getAttributeTemplate($accessory_template_data);

		if ($totalAccessory > 0)
		{
			$acctemplate_data = $accessory_template_data;

			if (strstr($acctemplate_data, "{if accessory_main}") && strstr($acctemplate_data, "{accessory_main end if}"))
			{
				$acctemplate_data = explode('{if accessory_main}', $acctemplate_data);
				$accessory_start  = $acctemplate_data[0];
				$acctemplate_data = explode('{accessory_main end if}', $acctemplate_data[1]);
				$accessory_end    = $acctemplate_data[1];
				$accessory_middle = $acctemplate_data[0];

				if (strstr($accessory_middle, "{accessory_main_short_desc}"))
				{
					$accessory_main_short_description = $redconfig->maxchar(
						$product->product_s_desc,
						ACCESSORY_PRODUCT_DESC_MAX_CHARS,
						ACCESSORY_PRODUCT_DESC_END_SUFFIX
					);
					$accessory_middle = str_replace("{accessory_main_short_desc}",
						$accessory_main_short_description,
						$accessory_middle);
				}

				if (strstr($accessory_middle, "{accessory_main_title}"))
				{
					$accessory_main_product_name = $redconfig->maxchar(
						$product->product_name,
						ACCESSORY_PRODUCT_TITLE_MAX_CHARS,
						ACCESSORY_PRODUCT_TITLE_END_SUFFIX
					);
					$accessory_middle            = str_replace("{accessory_main_title}", $accessory_main_product_name, $accessory_middle);
				}

				$accessory_productdetail = "<a href='#' title='" . $product->product_name . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
				$accessory_middle        = str_replace("{accessory_main_readmore}", $accessory_productdetail, $accessory_middle);
				$accessory_main_image    = $product->product_full_image;
				$accessorymainimage      = '';

				if (strstr($accessory_middle, "{accessory_main_image_3}"))
				{
					$aimg_tag = '{accessory_main_image_3}';
					$ah_thumb = ACCESSORY_THUMB_HEIGHT_3;
					$aw_thumb = ACCESSORY_THUMB_WIDTH_3;
				}
				elseif (strstr($accessory_middle, "{accessory_main_image_2}"))
				{
					$aimg_tag = '{accessory_main_image_2}';
					$ah_thumb = ACCESSORY_THUMB_HEIGHT_2;
					$aw_thumb = ACCESSORY_THUMB_WIDTH_2;
				}
				elseif (strstr($accessory_middle, "{accessory_main_image_1}"))
				{
					$aimg_tag = '{accessory_main_image_1}';
					$ah_thumb = ACCESSORY_THUMB_HEIGHT;
					$aw_thumb = ACCESSORY_THUMB_WIDTH;
				}
				else
				{
					$aimg_tag = '{accessory_main_image}';
					$ah_thumb = ACCESSORY_THUMB_HEIGHT;
					$aw_thumb = ACCESSORY_THUMB_WIDTH;
				}

				if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessory_main_image))
				{
					if (ACCESSORY_PRODUCT_IN_LIGHTBOX == 1)
						$accessorymainimage = "<a id='a_main_image' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
							. "product/" . $accessory_main_image
							. "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\"><img id='main_image' class='redAttributeImage' src='"
							. $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $accessory_main_image
							. "&newxsize=" . $aw_thumb . "&newysize=" . $ah_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING
							. "' /></a>";
					else
						$accessorymainimage = "<img id='main_image' class='redAttributeImage' src='" . $url
							. "components/com_redshop/helpers/thumb.php?filename=product/" . $accessory_main_image
							. "&newxsize=" . $aw_thumb . "&newysize=" . $ah_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' />";
				}

				$accessory_middle = str_replace($aimg_tag, $accessorymainimage, $accessory_middle);
				$ProductPriceArr  = array();

				if (strstr($accessory_middle, "{accessory_mainproduct_price}") || strstr($data_add, "{selected_accessory_price}"))
				{
					$ProductPriceArr = $this->getProductNetPrice($product_id, $user_id, 1, $data_add);
				}

				if (strstr($accessory_middle, "{accessory_mainproduct_price}"))
				{
					$product_price = '';

					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						$accessory_mainproduct_price = $this->getPriceReplacement($ProductPriceArr['product_price']);
						$accessory_middle            = str_replace(
							"{accessory_mainproduct_price}",
							$accessory_mainproduct_price,
							$accessory_middle);
					}
				}

				$accessory_middle = $this->replaceProductInStock($product->product_id, $accessory_middle);
				$acctemplate_data = $accessory_start . $accessory_middle . $accessory_end;
			}

			$accessory_div = '';

			if (strstr($acctemplate_data, "{accessory_product_start}") && strstr($acctemplate_data, "{accessory_product_end}"))
			{
				$acctemplate_data    = explode('{accessory_product_start}', $acctemplate_data);
				$accessory_div_start = $acctemplate_data [0];
				$acctemplate_data    = explode('{accessory_product_end}', $acctemplate_data [1]);
				$accessory_div_end   = $acctemplate_data[1];

				$accessory_div_middle = $acctemplate_data[0];

				for ($a = 0; $a < count($accessory); $a++)
				{
					$ac_id    = $accessory [$a]->child_product_id;
					$c_p_data = $this->getProductById($ac_id);

					$commonid = $prefix . $product_id . '_' . $accessory [$a]->accessory_id;
					$accessory_div .= "<div id='divaccstatus" . $commonid . "' class='accessorystatus'>" . $accessory_div_middle . "</div>";

					$accessory_product_name = $redconfig->maxchar(
						$accessory [$a]->product_name,
						ACCESSORY_PRODUCT_TITLE_MAX_CHARS,
						ACCESSORY_PRODUCT_TITLE_END_SUFFIX
					);
					$accessory_div          = str_replace("{accessory_title}", $accessory_product_name, $accessory_div);

					$accessory_div = str_replace("{product_number}", $accessory [$a]->product_number, $accessory_div);

					$accessory_image = $accessory [$a]->product_full_image;
					$accessoryimage  = '';

					if (strstr($accessory_div, "{accessory_image_3}"))
					{
						$aimg_tag = '{accessory_image_3}';
						$ah_thumb = ACCESSORY_THUMB_HEIGHT_3;
						$aw_thumb = ACCESSORY_THUMB_WIDTH_3;
					}
					elseif (strstr($accessory_div, "{accessory_image_2}"))
					{
						$aimg_tag = '{accessory_image_2}';
						$ah_thumb = ACCESSORY_THUMB_HEIGHT_2;
						$aw_thumb = ACCESSORY_THUMB_WIDTH_2;
					}
					elseif (strstr($accessory_div, "{accessory_image_1}"))
					{
						$aimg_tag = '{accessory_image_1}';
						$ah_thumb = ACCESSORY_THUMB_HEIGHT;
						$aw_thumb = ACCESSORY_THUMB_WIDTH;
					}
					else
					{
						$aimg_tag = '{accessory_image}';
						$ah_thumb = ACCESSORY_THUMB_HEIGHT;
						$aw_thumb = ACCESSORY_THUMB_WIDTH;
					}

					$acc_prod_link      = JRoute::_('index.php?option=com_redshop&view=product&pid='
						. $ac_id . '&Itemid=' . $Itemid);
					$hidden_thumb_image = "<input type='hidden' name='acc_main_imgwidth' id='acc_main_imgwidth' value='"
						. $aw_thumb . "'><input type='hidden' name='acc_main_imgheight' id='acc_main_imgheight' value='"
						. $ah_thumb . "'>";

					if (ACCESSORY_PRODUCT_IN_LIGHTBOX == 1)
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessory_image))
							$accessoryimage = "<a id='a_main_image" . $accessory [$a]->accessory_id
								. "' href='" . REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $accessory_image
								. "' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\"><img id='main_image"
								. $accessory [$a]->accessory_id . "' class='redAttributeImage' src='"
								. $url . "/components/com_redshop/helpers/thumb.php?filename=product/"
								. $accessory_image . "&newxsize=" . $aw_thumb . "&newysize=" . $ah_thumb . "&swap="
								. USE_IMAGE_SIZE_SWAPPING . "' /></a>";
						else
							$accessoryimage = "<a id='a_main_image" . $accessory [$a]->accessory_id
								. "' href='" . REDSHOP_FRONT_IMAGES_ABSPATH
								. "noimage.jpg' title='' class=\"modal\" rel=\"{handler: 'image', size: {}}\"><img id='main_image"
								. $accessory [$a]->accessory_id . "' class='redAttributeImage' src='"
								. $url . "/components/com_redshop/helpers/thumb.php?filename=noimage.jpg&newxsize="
								. $aw_thumb . "&newysize=" . $ah_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' /></a>";
					}
					else
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $accessory_image))
							$accessoryimage = "<a href='$acc_prod_link'><img id='main_image" . $accessory [$a]->accessory_id
								. "' class='redAttributeImage' src='" . $url . "/components/com_redshop/helpers/thumb.php?filename=product/" . $accessory_image . "&newxsize=" . $aw_thumb . "&newysize=" . $ah_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' /></a>";
						else
							$accessoryimage = "<a href='$acc_prod_link'><img id='main_image" . $accessory [$a]->accessory_id
								. "' class='redAttributeImage' src='"
								. $url . "/components/com_redshop/helpers/thumb.php?filename=noimage.jpg&newxsize="
								. $aw_thumb . "&newysize=" . $ah_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' /></a>";
					}

					$accessory_div               = str_replace($aimg_tag, $accessoryimage . $hidden_thumb_image, $accessory_div);
					$accessory_short_description = $redconfig->maxchar($accessory [$a]->product_s_desc, ACCESSORY_PRODUCT_DESC_MAX_CHARS, ACCESSORY_PRODUCT_DESC_END_SUFFIX);
					$accessory_div               = str_replace("{accessory_short_desc}", $accessory_short_description, $accessory_div);

					// Add manufacturer
					if (strstr($accessory_div, "{manufacturer_name}") || strstr($accessory_div, "{manufacturer_link}"))
					{
						$manufacturer = $this->getSection("manufacturer", $accessory [$a]->manufacturer_id);

						if (count($manufacturer) > 0)
						{
							$man_url          = JRoute::_('index.php?option='
								. $option . '&view=manufacturers&layout=products&mid='
								. $related_product[$r]->manufacturer_id . '&Itemid=' . $pItemid);
							$manufacturerLink = "<a href='" . $man_url . "'>" . JText::_("VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
							$accessory_div    = str_replace("{manufacturer_name}", $manufacturer->manufacturer_name, $accessory_div);
							$accessory_div    = str_replace("{manufacturer_link}", $manufacturerLink, $accessory_div);
						}
						else
						{
							$accessory_div = str_replace("{manufacturer_name}", '', $accessory_div);
							$accessory_div = str_replace("{manufacturer_link}", '', $accessory_div);
						}
					}

					// Get accessory final price with VAT rules
					$accessoryprice_withoutvat = $this->getAccessoryPrice(
						$product_id,
						$accessory[$a]->newaccessory_price,
						$accessory[$a]->accessory_main_price,
						1
					);

					if (!strstr($data_add, "{without_vat}"))
					{
						$accessorypricelist = $this->getAccessoryPrice($product_id,
							$accessory[$a]->newaccessory_price,
							$accessory[$a]->accessory_main_price
						);
					}
					else
					{
						$accessorypricelist = $accessoryprice_withoutvat;
					}

					$accessory_price_withoutvat = $accessoryprice_withoutvat[0];

					$accessory_price       = $accessorypricelist[0];
					$accessory_main_price  = $accessorypricelist[1];
					$saved_accessory_price = $accessorypricelist[2];

					// Get Formatted prices
					$saved_accessory_price = $this->getProductFormattedPrice($saved_accessory_price);
					$accessory_main_price  = $this->getProductFormattedPrice($accessory_main_price);
					$accessory_price_show  = $this->getProductFormattedPrice($accessory_price);

					if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)))
					{
						$accessory_div = str_replace("{accessory_price}", $accessory_price_show, $accessory_div);
						$accessory_div = str_replace("{accessory_main_price}", $accessory_main_price, $accessory_div);
						$accessory_div = str_replace("{accessory_price_saving}", $saved_accessory_price, $accessory_div);
					}
					else
					{
						$accessory_div = str_replace("{accessory_price}", '', $accessory_div);
						$accessory_div = str_replace("{accessory_main_price}", '', $accessory_div);
						$accessory_div = str_replace("{accessory_price_saving}", '', $accessory_div);
					}

					$readmorelink            = JRoute::_('index.php?option=com_redshop&view=product&pid='
						. $ac_id . '&Itemid=' . $Itemid);
					$accessory_productdetail = "<a href='" . $readmorelink . "' title='" . $accessory [$a]->product_name
						. "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
					$accessory_div = str_replace("{accessory_readmore}", $accessory_productdetail, $accessory_div);
					$accessory_div = str_replace("{accessory_readmore_link}", $readmorelink, $accessory_div);

					// Accessory attribute  Start
					$attributes_set = array();

					if ($c_p_data->attribute_set_id > 0)
					{
						$attributes_set = $this->getProductAttribute(0, $c_p_data->attribute_set_id);
					}

					$attributes = $this->getProductAttribute($ac_id);
					$attributes = array_merge($attributes, $attributes_set);
					$totalatt   = count($attributes);

					$accessory_div = $this->replaceAttributeData(
						$product_id,
						$accessory [$a]->accessory_id,
						$relproduct_id,
						$attributes,
						$accessory_div,
						$attribute_template,
						$isChilds,
						$selectAtt
					);
					$accessory_div = $this->replaceProductInStock($accessory [$a]->child_product_id, $accessory_div);

					// Accessory attribute  End

					$accchecked = "";

					if (
						(
							$isAjax == 1
							&& in_array($accessory [$a]->accessory_id, $selectedAccessory)
						)
						|| ($isAjax == 0
						&& $accessory [$a]->setdefault_selected))
					{
						$accchecked = "checked";
					}

					$accessory_checkbox = "<input type='checkbox' name='accessory_id_" . $prefix . $product_id
						. "[]' onClick='calculateTotalPrice(\"" . $product_id . "\",\"" . $relproduct_id . "\");' totalattributs='"
						. count($attributes) . "' accessoryprice='" . $accessory_price
						. "' accessorywithoutvatprice='" . $accessory_price_withoutvat . "' id='accessory_id_"
						. $commonid . "' value='" . $accessory [$a]->accessory_id . "' " . $accchecked . " />";
					$accessory_div      = str_replace("{accessory_add_chkbox}", $accessory_checkbox, $accessory_div);
					$accessory_div      = str_replace(
						"{accessory_add_chkbox_lbl}",
						JText::_('COM_REDSHOP_ACCESSORY_ADD_CHKBOX_LBL') . '&nbsp;' . $accessory [$a]->product_name,
						$accessory_div);

					if (strstr($accessory_div, "{accessory_quantity}"))
					{
						if (ACCESSORY_AS_PRODUCT_IN_CART_ENABLE)
						{
							$key                = array_search($accessory [$a]->accessory_id, $selectedAccessory);
							$accqua             = ($accchecked != "" && isset($selectedAccessoryQua[$key]) && $selectedAccessoryQua[$key]) ? $selectedAccessoryQua[$key] : 1;
							$accessory_quantity = "<input type='text' name='accquantity_" . $prefix . $product_id . "[]' id='accquantity_" . $commonid . "' value='" . $accqua . "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY . "' onchange='validateInputNumber(this.id);'>";
							$accessory_div      = str_replace("{accessory_quantity}", $accessory_quantity, $accessory_div);
							$accessory_div      = str_replace("{accessory_quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY'), $accessory_div);
						}
						else
						{
							$accessory_div = str_replace("{accessory_quantity}", "", $accessory_div);
							$accessory_div = str_replace("{accessory_quantity_lbl}", "", $accessory_div);
						}
					}
				}

				$accessory_div = $accessory_div_start . $accessory_div . $accessory_div_end;
			}
			// Attribute ajax change
			if ($viewacc == 1)
			{
				$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", $accessory_div, $data_add);
			}
			else
			{
				if (AJAX_CART_BOX == 0)
				{
					$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", $accessory_div, $data_add);
				}
				else
				{
					$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", "", $data_add);
				}
			}

			if (strstr($data_add, "{selected_accessory_price}") && $isAjax == 0)
			{
				$selected_accessory_price = $this->getPriceReplacement($ProductPriceArr['product_price']);
				$data_add                 = str_replace(
					"{selected_accessory_price}",
					"<div id='rs_selected_accessory_price' class='rs_selected_accessory_price'>" . $selected_accessory_price . "</div>",
					$data_add);
			}
			else
			{
				$data_add = str_replace("{selected_accessory_price}", "", $data_add);
			}

			$data_add = str_replace("{accessory_product_start}", "", $data_add);
			$data_add = str_replace("{accessory_product_end}", "", $data_add);
		}
		else
		{
			$data_add = str_replace("{accessory_template:" . $accessory_template->template_name . "}", "", $data_add);
		}

		return $data_add;
	}

	public function replaceAttributewithCartData($product_id = 0, $accessory_id = 0, $relproduct_id = 0, $attributes = array(), $data_add, $attribute_template = array(), $isChilds = false)
	{
		$user_id         = 0;
		$url             = JURI::base();
		$stockroomhelper = new rsstockroomhelper();
		$redTemplate     = new Redtemplate();

		if (count($attribute_template) <= 0)
		{
			return $data_add;
		}

		if (!$isChilds && count($attributes) > 0)
		{
			$layout    = JRequest::getVar('layout');
			$preprefix = "";
			$isAjax    = 0;

			if ($layout == "viewajaxdetail")
			{
				$preprefix = "ajax_";
				$isAjax    = 1;
			}

			if ($accessory_id != 0)
			{
				$prefix = $preprefix . "acc_";
			}
			elseif ($relproduct_id != 0)
			{
				$prefix = $preprefix . "rel_";
			}
			else
			{
				$prefix = $preprefix . "prd_";
			}

			if ($relproduct_id != 0)
			{
				$product_id = $relproduct_id;
			}

			$product         = $this->getProductById($product_id);
			$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

			if (strstr($producttemplate[0]->template_desc, "{more_images_3}"))
			{
				$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3;
				$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_3;
			}
			elseif (strstr($producttemplate[0]->template_desc, "{more_images_2}"))
			{
				$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2;
				$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_2;
			}
			elseif (strstr($producttemplate[0]->template_desc, "{more_images_1}"))
			{
				$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
				$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
			}
			else
			{
				$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
				$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
			}

			$cart_template   = array();
			$attribute_table = "";

			for ($a = 0; $a < count($attributes); $a++)
			{
				$attribute_table .= $attribute_template->template_desc;

				$attribute_table = str_replace("{property_image_lbl}", JText::_('COM_REDSHOP_PROPERTY_IMAGE_LBL'), $attribute_table);
				$attribute_table = str_replace("{virtual_number_lbl}", JText::_('COM_REDSHOP_VIRTUAL_NUMBER_LBL'), $attribute_table);
				$attribute_table = str_replace("{property_name_lbl}", JText::_('COM_REDSHOP_PROPERTY_NAME_LBL'), $attribute_table);
				$attribute_table = str_replace("{property_price_lbl}", JText::_('COM_REDSHOP_PROPERTY_PRICE_LBL'), $attribute_table);
				$attribute_table = str_replace("{property_stock_lbl}", JText::_('COM_REDSHOP_PROPERTY_STOCK_LBL'), $attribute_table);
				$attribute_table = str_replace("{add_to_cart_lbl}", JText::_('COM_REDSHOP_ADD_TO_CART_LBL'), $attribute_table);

				$property = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id);

				if (
					$attributes[$a]->text != ""
					&& count($property) > 0 &&
					strstr($attribute_table, "{property_start}") &&
					strstr($attribute_table, "{property_end}")
				)
				{
					$start             = explode("{property_start}", $attribute_table);
					$end               = explode("{property_end}", $start[1]);
					$property_template = $end[0];

					$commonid   = $prefix . $product_id . '_' . $accessory_id . '_' . $attributes[$a]->value;
					$propertyid = 'property_id_' . $commonid;

					$property_data = "";

					for ($i = 0; $i < count($property); $i++)
					{
						$property_data .= $property_template;

						$property_data = str_replace("{property_name}", urldecode($property[$i]->property_name), $property_data);
						$property_data = str_replace("{virtual_number}", $property[$i]->property_number, $property_data);

						$property_stock          = $stockroomhelper->getStockAmountwithReserve($property[$i]->value, "property");
						$preorder_property_stock = $stockroomhelper->getPreorderStockAmountwithReserve($property[$i]->value, "property");
						$display_stock           = ($property_stock) ? JText::_('COM_REDSHOP_IN_STOCK') : JText::_('COM_REDSHOP_NOT_IN_STOCK');
						$property_data           = str_replace("{property_stock}", $display_stock, $property_data);

						$property_image = "";

						if ($property[$i]->property_image && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/"
							. $property[$i]->property_image))
						{
							$property_image = "<img title='" . urldecode($property[$i]->property_name)
								. "' src='" . $url . "components/com_redshop/helpers/thumb.php?filename=product_attributes/"
								. $property[$i]->property_image . "&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb
								. "&swap=" . USE_IMAGE_SIZE_SWAPPING . "'>";
						}

						$property_data = str_replace("{property_image}", $property_image, $property_data);

						$property_price      = "";
						$property_oprand     = "";
						$property_withvat    = 0;
						$property_withoutvat = 0;

						if ($property[$i]->property_price > 0)
						{
							$pricelist = $this->getPropertyPrice($property[$i]->value, 1, 'property');

							if (count($pricelist) > 0)
							{
								$property[$i]->property_price = $pricelist->product_price;
							}

							$property_withoutvat = $property[$i]->property_price;

							if (!strstr($data_add, "{without_vat}"))
							{
								$property_withvat = $this->getProducttax($product_id, $property[$i]->property_price, $user_id);
							}

							$property_withvat += $property[$i]->property_price;

							if (
								SHOW_PRICE &&
								(!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
								&& (!$attributes[$a]->hide_attribute_price)
							)
							{
								$property_oprand = $property[$i]->oprand;
								$property_price  = $this->getProductFormattedPrice($property_withvat);
							}
						}

						$property_data = str_replace("{property_oprand}", $property_oprand, $property_data);
						$property_data = str_replace("{property_price}", $property_price, $property_data);

						if (!count($cart_template))
						{
							$cart_template = $this->getAddtoCartTemplate($property_data);
						}

						if (count($cart_template) > 0)
						{
							$property_data = $this->replacePropertyAddtoCart(
								$product_id,
								$property[$i]->value,
								0,
								$propertyid,
								$property_stock,
								$property_data,
								$cart_template,
								$data_add
							);
						}

						$property_data .= '<input type="hidden" id="' . $propertyid . '_oprand' . $property[$i]->value
							. '" value="' . $property[$i]->oprand . '" />';
						$property_data .= '<input type="hidden" id="' . $propertyid . '_proprice' . $property[$i]->value
							. '" value="' . $property_withvat . '" />';
						$property_data .= '<input type="hidden" id="' . $propertyid . '_proprice_withoutvat'
							. $property[$i]->value . '" value="' . $property_withoutvat . '" />';
						$property_data .= '<input type="hidden" id="' . $propertyid . '_stock' . $property[$i]->value
							. '" value="' . $property_stock . '" />';
						$property_data .= '<input type="hidden" id="' . $propertyid . '_preorderstock'
							. $property[$i]->value . '" value="' . $preorder_property_stock . '" />';
					}

					if ($attributes[$a]->attribute_required > 0)
					{
						$pos        = ASTERISK_POSITION > 0 ? urldecode($attributes [$a]->text)
							. "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>"
							. urldecode($attributes[$a]->text);
						$attr_title = $pos;
					}
					else
					{
						$attr_title = urldecode($attributes[$a]->text);
					}

					$attribute_table = str_replace("{attribute_title}", $attr_title, $attribute_table);
					$attribute_table = str_replace("{property_start}", "", $attribute_table);
					$attribute_table = str_replace("{property_end}", "", $attribute_table);
					$attribute_table = str_replace($property_template, $property_data, $attribute_table);
				}
			}

			if ($attribute_table != "")
			{
				$cart_template = $this->getAddtoCartTemplate($data_add);

				if (count($cart_template) > 0)
				{
					$data_add = str_replace("{form_addtocart:$cart_template->template_name}", "", $data_add);
				}
			}

			$data_add = str_replace("{attributewithcart_template:$attribute_template->template_name}", $attribute_table, $data_add);
		}
		else
		{
			$data_add = str_replace("{attributewithcart_template:$attribute_template->template_name}", "", $data_add);
		}

		return $data_add;
	}

	public function get_hidden_attribute_cartimage($product_id, $property_id, $subproperty_id)
	{
		$url      = JURI::base();
		$attrbimg = "";

		if ($property_id > 0)
		{
			$property = $this->getAttibuteProperty($property_id);

			//Display attribute image in cart
			if (count($property) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[0]->property_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $property[0]->property_image;
			}
		}

		if ($subproperty_id > 0)
		{
			$subproperty = $this->getAttibuteSubProperty($subproperty_id);

			if (count($subproperty) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[0]->subattribute_color_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "subcolor/" . $subproperty[0]->subattribute_color_image;
			}
		}

		return $attrbimg;
	}

	public function replaceAttributeData($product_id = 0, $accessory_id = 0, $relproduct_id = 0, $attributes = array(), $data_add, $attribute_template = array(), $isChilds = false, $selectAtt = array(), $displayIndCart = 1, $category_id = 0)
	{
		$user_id         = 0;
		$url             = JURI::base();
		$redTemplate     = new Redtemplate ();
		$stockroomhelper = new rsstockroomhelper();

		$chktagArr['chkvat'] = $chktag = $this->getApplyattributeVatOrNot($data_add);
		$this->_session->set('chkvat', $chktagArr);

		if (INDIVIDUAL_ADD_TO_CART_ENABLE && $displayIndCart)
		{
			$att_template = $this->getAttributeTemplate($data_add, false);

			if (count($att_template) > 0)
			{
				$data_add = str_replace("{attribute_template:$att_template->template_name}", "", $data_add);
			}

			$data_add = $this->replaceAttributewithCartData($product_id, $accessory_id, $relproduct_id, $attributes, $data_add, $attribute_template, $isChilds);

			return $data_add;
		}
		else
		{
			$att_template = $this->getAttributeTemplate($data_add, false);

			if (count($att_template) > 0)
			{
				$data_add = str_replace("{attributewithcart_template:$att_template->template_name}", "", $data_add);
			}
		}

		if (count($attribute_template) <= 0)
		{
			return $data_add;
		}

		if ($isChilds || count($attributes) <= 0)
		{
			$data_add = str_replace("{attribute_template:$attribute_template->template_name}", "", $data_add);

			return $data_add;
		}

		$document = JFactory::getDocument();
		JHTML::Script('thumbscroller.js', 'components/com_redshop/assets/js/', false);
		$layout = JRequest::getVar('layout');

		$preprefix = "";
		$isAjax    = 0;

		if ($layout == "viewajaxdetail")
		{
			$preprefix = "ajax_";
			$isAjax    = 1;
		}

		if ($accessory_id != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relproduct_id != 0)
		{
			$prefix = $preprefix . "rel_";
		}
		else
		{
			$prefix = $preprefix . "prd_";
		}

		if ($relproduct_id != 0)
		{
			$product_id = $relproduct_id;
		}

		$selectProperty    = array();
		$selectSubproperty = array();

		if (count($selectAtt) > 0)
		{
			$selectProperty    = $selectAtt[0];
			$selectSubproperty = $selectAtt[1];
		}

		$attribute_template_data = $attribute_template->template_desc;

		$product         = $this->getProductById($product_id);
		$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

		if (strstr($producttemplate[0]->template_desc, "{more_images_3}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_3;
		}
		elseif (strstr($producttemplate[0]->template_desc, "{more_images_2}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_2;
		}
		elseif (strstr($producttemplate[0]->template_desc, "{more_images_1}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}
		else
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}

		$data_add .= '<span id="att_lebl" style="display:none;">' . JText::_('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') . '</span>';

		if (count($attributes) > 0)
		{
			$attribute_table = "<span id='attribute_ajax_span'>";

			for ($a = 0; $a < count($attributes); $a++)
			{
				$subdisplay = false;

				$property_all = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id);

				if (!DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA && USE_STOCKROOM)
				{
					$property = $this->getAttibutePropertyWithStock($property_all);
				}
				else
				{
					$property = $property_all;
				}

				if ($attributes[$a]->text != "" && count($property) > 0)
				{
					$attribute_table .= $attribute_template_data;

					$commonid    = $prefix . $product_id . '_' . $accessory_id . '_' . $attributes[$a]->value;
					$hiddenattid = 'attribute_id_' . $prefix . $product_id . '_' . $accessory_id;
					$propertyid  = 'property_id_' . $commonid;

					$imgAdded               = 0;
					$selectedproperty       = 0;
					$property_woscrollerdiv = "";

					if (strstr($attribute_table, "{property_image_without_scroller}"))
					{
						$attribute_table        = str_replace("{property_image_scroller}", "", $attribute_table);
						$property_woscrollerdiv = "<div class='property_main_outer'>";
						//$property_woscrollerdiv .= "<table border='0'>";
					}

					$property_scrollerdiv = "<table cellpadding='5' cellspacing='5'><tr>";
					$property_scrollerdiv .= "<td><a id=\"FirstButton\" href=\"javascript:isFlowers" . $commonid
						. ".scrollReverse();\" onmouseover=\"isFlowers" . $commonid
						. ".smoothScrollReverse();\" onmouseout=\"isFlowers" . $commonid
						. ".stopSmoothScroll();\"><img src=\"" . REDSHOP_FRONT_IMAGES_ABSPATH
						. "leftarrow.jpg\" style=\"margin-top: 12px;margin-right: 6px;\" style=\"margin-top: 3px;\" border=\"0\" /></a></td>";
					$property_scrollerdiv .= "<td><div id=\"isFlowersFrame" . $commonid
						. "\" name=\"isFlowersFrame" . $commonid
						. "\" style=\"margin: 0px; padding: 0px;position: relative; overflow: hidden;\"><div id=\"isFlowersImageRow"
						. $commonid . "\" name=\"isFlowersImageRow" . $commonid . "\" style=\"position: absolute; top: 0px;left: 0px;\">";
					$property_scrollerdiv .= "<script type=\"text/javascript\">var isFlowers" . $commonid
						. " = new ImageScroller(\"isFlowersFrame" . $commonid . "\", \"isFlowersImageRow"
						. $commonid . "\");";

					for ($i = 0; $i < count($property); $i++)
					{
						if (count($selectProperty) > 0)
						{
							if (in_array($property[$i]->value, $selectProperty))
							{
								$selectedproperty = $property[$i]->value;
							}
						}
						else
						{
							if ($property[$i]->setdefault_selected)
							{
								$selectedproperty = $property[$i]->value;
							}
						}

						$subproperty_all = $this->getAttibuteSubProperty(0, $property[$i]->value);

						// filter Out of stock data
						if (!DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA && USE_STOCKROOM)
						{
							$subproperty = $this->getAttibuteSubPropertyWithStock($subproperty_all);
						}
						else
						{
							$subproperty = $subproperty_all;
						}

						$subpropertystock          = 0;
						$preorder_subpropertystock = 0;

						for ($sub = 0; $sub < count($subproperty); $sub++)
						{
							$subpropertystock += $stockroomhelper->getStockAmountwithReserve($subproperty[$sub]->value, "subproperty");
							$preorder_subpropertystock += $stockroomhelper->getPreorderStockAmountwithReserve($subproperty[$sub]->value, "subproperty");
						}

						$property_stock = $stockroomhelper->getStockAmountwithReserve($property[$i]->value, "property");
						$property_stock += $subpropertystock;

						// preorder stock data
						$preorder_property_stock = $stockroomhelper->getPreorderStockAmountwithReserve($property[$i]->value, "property");
						$preorder_property_stock += $preorder_subpropertystock;

						if ($property[$i]->property_image)
						{
							if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/" . $property[$i]->property_image))
							{
								$borderstyle = ($selectedproperty == $property[$i]->value) ? " 1px solid " : "";

								$property_woscrollerdiv .= "<div class='property_image_inner' id='" . $propertyid
									. "_propimg_" . $property[$i]->value . "'><a onclick='setPropImage(\"" . $product_id
									. "\",\"" . $propertyid . "\",\"" . $property[$i]->value . "\");changePropertyDropdown(\""
									. $product_id . "\",\"" . $accessory_id . "\",\"" . $relproduct_id . "\",\""
									. $attributes [$a]->value . "\",\"" . $property[$i]->value . "\",\"" . $mpw_thumb
									. "\",\"" . $mph_thumb
									. "\");'><img class='redAttributeImage' width='50' height='50' src='"
									. $url . "components/com_redshop/helpers/thumb.php?filename=product_attributes/"
									. $property[$i]->property_image . "&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb
									. "&swap=" . USE_IMAGE_SIZE_SWAPPING . "'></a></div>";
								$property_scrollerdiv .= "isFlowers" . $commonid . ".addThumbnail(\""
									. $url . "components/com_redshop/helpers/thumb.php?filename=product_attributes/"
									. $property[$i]->property_image . "&newxsize=" . ATTRIBUTE_SCROLLER_THUMB_WIDTH
									. "&newysize=" . ATTRIBUTE_SCROLLER_THUMB_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING
									. "\",\"javascript:isFlowers" . $commonid . ".scrollImageCenter('" . $i . "');setPropImage('"
									. $product_id . "','" . $propertyid . "','" . $property[$i]->value . "');changePropertyDropdown('"
									. $product_id . "','" . $accessory_id . "','" . $relproduct_id . "','"
									. $attributes [$a]->value . "','" . $property[$i]->value . "','" . $mpw_thumb . "','"
									. $mph_thumb . "');\",\"\",\"\",\"" . $propertyid . "_propimg_" . $property[$i]->value
									. "\",\"" . $borderstyle . "\");";
								$imgAdded++;
							}
						}

						$attributes_property_vat_show   = 0;
						$attributes_property_withoutvat = 0;

						if ($property [$i]->property_price > 0)
						{
							$pricelist = $this->getPropertyPrice($property[$i]->value, 1, 'property');

							if (count($pricelist) > 0)
							{
								$property[$i]->property_price = $pricelist->product_price;
							}

							$attributes_property_withoutvat = $property [$i]->property_price;
							/*
							 * changes for {without_vat} tag output parsing
							 * only for display purpose
							 */
							$attributes_property_vat_show = 0;

							if (!empty($chktag))
							{
								$attributes_property_vat_show = $this->getProducttax($product_id, $property [$i]->property_price, $user_id);
							}

							$attributes_property_vat_show += $property [$i]->property_price;

							/*
							 * get product vat to include
							 */
							$attributes_property_vat = $this->getProducttax($product_id, $property [$i]->property_price, $user_id);
							$property [$i]->property_price += $attributes_property_vat;

							if (SHOW_PRICE
								&& (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE))
								&& (!$attributes[$a]->hide_attribute_price))
							{
								$property[$i]->text = urldecode($property[$i]->property_name) . " (" . $property [$i]->oprand
									. $this->getProductFormattedPrice($attributes_property_vat_show) . ")";
							}
							else
							{
								$property[$i]->text = urldecode($property[$i]->property_name);
							}
						}
						else
						{
							$property[$i]->text = urldecode($property[$i]->property_name);
						}

						$property[$i]->text = $property[$i]->text;

						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_oprand' . $property [$i]->value
							. '" value="' . $property [$i]->oprand . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_proprice' . $property [$i]->value
							. '" value="' . $attributes_property_vat_show . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_proprice_withoutvat' . $property [$i]->value
							. '" value="' . $attributes_property_withoutvat . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_stock' . $property [$i]->value . '" value="'
							. $property_stock . '" />';
						$attribute_table .= '<input type="hidden" id="' . $propertyid . '_preorderstock' . $property [$i]->value
							. '" value="' . $preorder_property_stock . '" />';
					}

					if (!$mph_thumb)
					{
						$mph_thumb = 50;
					}

					if (!$mpw_thumb)
					{
						$mpw_thumb = 50;
					}

					$atth = 50;
					$attw = 50;

					if (defined('ATTRIBUTE_SCROLLER_THUMB_HEIGHT') && ATTRIBUTE_SCROLLER_THUMB_HEIGHT)
					{
						$atth = ATTRIBUTE_SCROLLER_THUMB_HEIGHT;
					}

					if (defined('ATTRIBUTE_SCROLLER_THUMB_WIDTH') && ATTRIBUTE_SCROLLER_THUMB_WIDTH)
					{
						$attw = ATTRIBUTE_SCROLLER_THUMB_WIDTH;
					}

					$property_scrollerdiv .= "
					isFlowers" . $commonid . ".setThumbnailHeight(" . $atth . ");
					isFlowers" . $commonid . ".setThumbnailWidth(" . $attw . ");
					isFlowers" . $commonid . ".setThumbnailPadding(5);
					isFlowers" . $commonid . ".setScrollType(0);
					isFlowers" . $commonid . ".enableThumbBorder(false);
					isFlowers" . $commonid . ".setClickOpenType(1);
					isFlowers" . $commonid . ".setThumbsShown(" . NOOF_THUMB_FOR_SCROLLER . ");
					isFlowers" . $commonid . ".setNumOfImageToScroll(1);
					isFlowers" . $commonid . ".renderScroller();
        		    </script>";
					$property_scrollerdiv .= "</div></div></td>";
					$property_scrollerdiv .= "<td><a id=\"FirstButton\" href=\"javascript:isFlowers" . $commonid
						. ".scrollForward();\" onmouseover=\"isFlowers" . $commonid . ".smoothScrollForward();\" onmouseout=\"isFlowers"
						. $commonid . ".stopSmoothScroll();\"><img src=\"" . REDSHOP_FRONT_IMAGES_ABSPATH
						. "rightarrow.jpg\" style=\"margin-top: 12px;margin-right: 6px;\" style=\"margin-top: 3px;\" border=\"0\" /></a></td>";
					$property_scrollerdiv .= "</tr></table>";

					if (strstr($attribute_table, "{property_image_without_scroller}"))
					{
						$property_woscrollerdiv .= "</div>";

					}

					$tmp_array            = array(new stdClass);
					$tmp_array [0]->value = 0;
					$tmp_array [0]->text  = JText::_('COM_REDSHOP_SELECT') . " " . urldecode($attributes[$a]->text);

					$new_property      = array_merge($tmp_array, $property);
					$defaultpropertyId = array();
					$chklist           = "";
					$display_type      = $attributes [$a]->display_type;

					if ($attributes [$a]->allow_multiple_selection)
					{
						$display_type = 'checkbox';
					}

					if ($display_type == 'checkbox' || $display_type == 'radio')
					{
						for ($chk = 0; $chk < count($property); $chk++)
						{
							$checked = "";

							if (count($selectProperty) > 0)
							{
								if (in_array($property[$chk]->value, $selectProperty))
								{
									$checked             = "checked";
									$subdisplay          = true;
									$defaultpropertyId[] = $property[$chk]->value;
								}
							}
							else
							{
								if ($property[$chk]->setdefault_selected)
								{
									$checked             = "checked";
									$subdisplay          = true;
									$defaultpropertyId[] = $property[$chk]->value;
								}
							}

							$scrollerFunction = "";

							if ($imgAdded > 0 && strstr($attribute_table, "{property_image_scroller}"))
							{
								$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(\"" . $chk . "\");";
							}

							$chklist .= "<div class='attribute_multiselect_single'><input type='" . $display_type . "' "
								. $checked . " value='" . $property[$chk]->value . "' name='" . $propertyid . "[]' id='"
								. $propertyid . "' class='inputbox' attribute_name='" . urldecode($attributes [$a]->attribute_name)
								. "' required='" . $attributes[$a]->attribute_required . "' onClick='javascript:" . $scrollerFunction . "changePropertyDropdown(\"" . $product_id . "\",\"" . $accessory_id . "\",\"" . $relproduct_id . "\",\"" . $attributes [$a]->value . "\",\"" . $property[$chk]->value . "\",\"" . $mpw_thumb . "\",\"" . $mph_thumb . "\");'  />&nbsp;" . $property[$chk]->text . "</div>";
						}
					}
					else
					{
						$scrollerFunction = "";

						if ($imgAdded > 0 && strstr($attribute_table, "{property_image_scroller}"))
						{
							$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(this.selectedIndex-1);";
						}

						$chklist = JHTML::_('select.genericlist', $new_property, $propertyid . '[]', 'id="' . $propertyid . '"  class="inputbox" size="1" attribute_name="' . urldecode($attributes[$a]->attribute_name) . '" required="' . $attributes [$a]->attribute_required . '" onchange="javascript:' . $scrollerFunction . 'changePropertyDropdown(\'' . $product_id . '\',\'' . $accessory_id . '\',\'' . $relproduct_id . '\',\'' . $attributes [$a]->value . '\',this.value,\'' . $mpw_thumb . '\',\'' . $mph_thumb . '\');" ', 'value', 'text', $selectedproperty);

						if ($selectedproperty)
						{
							$subdisplay          = true;
							$defaultpropertyId[] = $selectedproperty;
						}
					}

					$lists ['property_id'] = $chklist;

					$attribute_table .= "<input type='hidden' name='" . $hiddenattid . "[]' value='" . $attributes [$a]->value . "' />";

					if ($attributes [$a]->attribute_required > 0)
					{
						$pos        = ASTERISK_POSITION > 0 ? urldecode($attributes [$a]->text) . "<span id='asterisk_right'> * " : "<span id='asterisk_left'>* </span>" . urldecode($attributes[$a]->text);
						$attr_title = $pos;
					}
					else
					{
						$attr_title = urldecode($attributes[$a]->text);
					}

					$attribute_table = str_replace("{attribute_title}", $attr_title, $attribute_table);
					$attribute_table = str_replace("{property_dropdown}", $lists ['property_id'], $attribute_table);

							// Changes for attribue Image Scroll
					if ($imgAdded == 0 || $isAjax == 1)
					{
						$property_scrollerdiv = "";
					}

					$attribute_table = str_replace("{property_image_scroller}", $property_scrollerdiv, $attribute_table);
					$attribute_table = str_replace("{property_image_without_scroller}", $property_woscrollerdiv, $attribute_table);

					if ($subdisplay)
					{
						$style = ' style="display:block" ';
					}
					else
					{
						$style = ' style="display:none" ';
					}

					$subpropertydata  = "";
					$subpropertystart = $attribute_table;
					$subpropertyend   = "";
					$subattdata       = explode("{subproperty_start}", $attribute_table);

					if (count($subattdata) > 0)
					{
						$subpropertystart = $subattdata[0];
					}

					if (count($subattdata) > 1)
					{
						$subattdata = explode("{subproperty_end}", $subattdata[1]);

						if (count($subattdata) > 0)
						{
							$subpropertydata = $subattdata[0];
							$replaceMiddle   = "{replace_subprodata}";

						}

						if (count($subattdata) > 1)
						{
							$subpropertyend = $subattdata[1];
						}
					}

					$subproperty_start = '<div id="property_responce' . $commonid . '" ' . $style . '>';

					$displaySubproperty = "";

					for ($selp = 0; $selp < count($defaultpropertyId); $selp++)
					{
						$displaySubproperty .= $this->replaceSubPropertyData($product_id, $accessory_id, $relproduct_id, $attributes[$a]->attribute_id, $defaultpropertyId[$selp], $subpropertydata, $layout, $selectSubproperty);
					}

					if ($subdisplay)
					{
						$attribute_table = $subpropertystart . "{subproperty_start}" . $replaceMiddle . "{subproperty_end}" . $subpropertyend;
						$attribute_table = str_replace($replaceMiddle, $displaySubproperty, $attribute_table);
					}

					$attribute_table .= "<input type='hidden' id='subattdata_" . $commonid . "' value='" . base64_encode(htmlspecialchars($subpropertydata)) . "' />";
					$attribute_table = str_replace("{subproperty_start}", $subproperty_start, $attribute_table);
					$attribute_table = str_replace("{subproperty_end}", "</div>", $attribute_table);
				}
			}

			$attribute_table .= "<span id='cart_attribute_box'></span></span>";

			$data_add = str_replace("{attribute_template:$attribute_template->template_name}", $attribute_table, $data_add);
		}
		else
		{
			$data_add = str_replace("{attribute_template:$attribute_template->template_name}", "", $data_add);
		}

		return $data_add;
	}

	public function replaceSubPropertyData($product_id = 0, $accessory_id = 0, $relatedprd_id = 0, $attribute_id = 0, $property_id = 0, $subatthtml = "", $layout = "", $selectSubproperty = array())
	{
		$redTemplate     = new Redtemplate();
		$stockroomhelper = new rsstockroomhelper();
		$url             = JURI::base();
		$attribute_table = "";
		$subproperty     = array();
		$document        = JFactory::getDocument();

		JHTML::Script('thumbscroller.js', 'components/com_redshop/assets/js/', false);
		$chkvatArr = $this->_session->get('chkvat');
		$chktag    = $chkvatArr['chkvat'];

		$preprefix = "";
		$isAjax    = 0;

		if ($layout == "viewajaxdetail")
		{
			$preprefix = "ajax_";
			$isAjax    = 1;
		}

		if ($property_id != 0 && $attribute_id != 0)
		{
			$attributes      = $this->getProductAttribute(0, 0, $attribute_id);
			$attributes      = $attributes[0];
			$subproperty_all = $this->getAttibuteSubProperty(0, $property_id);
			// filter Out of stock data
			if (!DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA && USE_STOCKROOM)
			{
				$subproperty = $this->getAttibuteSubPropertyWithStock($subproperty_all);
			}
			else
			{
				$subproperty = $subproperty_all;
			}
		}

		$stock = 0;

		if ($accessory_id != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relatedprd_id != 0)
		{
			$prefix = $preprefix . "rel_";
		}
		else
		{
			$prefix = $preprefix . "prd_";
		}

		if ($relatedprd_id != 0)
		{
			$product_id = $relatedprd_id;
		}

		$product         = $this->getProductById($product_id);
		$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

		if (strstr($producttemplate[0]->template_desc, "{more_images_3}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_3;
		}
		elseif (strstr($producttemplate[0]->template_desc, "{more_images_2}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_2;
		}
		elseif (strstr($producttemplate[0]->template_desc, "{more_images_1}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}
		else
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}

		if ($subatthtml != "")
		{
			if (count($subproperty) > 0)
			{
				$attribute_table = $subatthtml;
				$attribute_table .= '<span id="subprop_lbl" style="display:none;">'
					. JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . '</span>';
				$commonid            = $prefix . $product_id . '_' . $accessory_id . '_' . $attribute_id . '_'
					. $property_id;
				$subpropertyid       = 'subproperty_id_' . $commonid;
				$selectedsubproperty = 0;
				$imgAdded            = 0;

				$subproperty_woscrollerdiv = "";

				if (strstr($subatthtml, "{subproperty_image_without_scroller}"))
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$subproperty_woscrollerdiv .= "<div class='subproperty_main_outer' id='subproperty_main_outer'>";
				}

				$subproperty_scrollerdiv = "<table cellpadding='0' cellspacing='0' border='0'><tr>";
				$subproperty_scrollerdiv .= "<td><a id=\"FirstButton\" href=\"javascript:isFlowers" . $commonid
					. ".scrollReverse();\" onmouseover=\"isFlowers"
					. $commonid . ".smoothScrollReverse();\" onmouseout=\"isFlowers" . $commonid
					. ".stopSmoothScroll();\"><img src=\"" . REDSHOP_FRONT_IMAGES_ABSPATH
					. "leftarrow.jpg\" style=\"margin-top: 12px;margin-right: 6px;\" style=\"margin-top: 3px;\" border=\"0\" /></a></td>";
				$subproperty_scrollerdiv .= "<td><div id=\"isFlowersFrame" . $commonid . "\" name=\"isFlowersFrame"
					. $commonid
					. "\" style=\"margin: 0px; padding: 0px;position: relative; overflow: hidden;\"><div id=\"isFlowersImageRow"
					. $commonid . "\" name=\"isFlowersImageRow" . $commonid
					. "\" style=\"position: absolute; top: 0px;left: 0px;\">";
				$subproperty_scrollerdiv .= "<script type=\"text/javascript\">var isFlowers" . $commonid
					. " = new ImageScroller(\"isFlowersFrame" . $commonid . "\", \"isFlowersImageRow" . $commonid . "\");";

				$subprop_Arry = array();

				for ($i = 0; $i < count($subproperty); $i++)
				{
					if (count($selectSubproperty) > 0)
					{
						if (in_array($subproperty[$i]->value, $selectSubproperty))
						{
							$selectedsubproperty = $subproperty[$i]->value;
						}
					}
					else
					{
						if ($subproperty[$i]->setdefault_selected)
						{
							$selectedsubproperty = $subproperty[$i]->value;
						}
					}

					$stock = $stockroomhelper->getStockAmountwithReserve($subproperty[$i]->value, "subproperty");

					if ($subproperty[$i]->subattribute_color_image)
					{
						if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/" . $subproperty[$i]->subattribute_color_image))
						{
							$borderstyle    = ($selectedsubproperty == $subproperty[$i]->value) ? " 1px solid " : "";
							$subprop_Arry[] = $url . "components/com_redshop/helpers/thumb.php?filename=subcolor/"
								. $subproperty[$i]->subattribute_color_image . "&newxsize=" . ATTRIBUTE_SCROLLER_THUMB_WIDTH
								. "&newysize=" . ATTRIBUTE_SCROLLER_THUMB_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "`_`"
								. $subproperty[$i]->value;
							$subproperty_woscrollerdiv .= "<div id='" . $subpropertyid . "_subpropimg_"
								. $subproperty[$i]->value . "' class='subproperty_image_inner'><a onclick='setSubpropImage(\""
								. $product_id . "\",\"" . $subpropertyid . "\",\"" . $subproperty[$i]->value
								. "\");calculateTotalPrice(\"" . $product_id . "\",\"" . $relatedprd_id
								. "\");displayAdditionalImage(\"" . $product_id . "\",\"" . $accessory_id . "\",\""
								. $relatedprd_id . "\",\"" . $property_id . "\",\"" . $subproperty[$i]->value
								. "\");'><img class='redAttributeImage'  src='" . $url
								. "/components/com_redshop/helpers/thumb.php?filename=subcolor/"
								. $subproperty[$i]->subattribute_color_image . "&newxsize=" . $mpw_thumb . "&newysize="
								. $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "'></a></div>";
							$subproperty_scrollerdiv .= "isFlowers" . $commonid . ".addThumbnail(\""
								. $url . "components/com_redshop/helpers/thumb.php?filename=subcolor/"
								. $subproperty[$i]->subattribute_color_image . "&newxsize=" . $mpw_thumb
								. "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "\",\"javascript:isFlowers"
								. $commonid . ".scrollImageCenter('" . $i . "');setSubpropImage('" . $product_id . "','"
								. $subpropertyid . "','" . $subproperty[$i]->value . "');calculateTotalPrice('"
								. $product_id . "','" . $relatedprd_id . "');displayAdditionalImage('" . $product_id
								. "','" . $accessory_id . "','" . $relatedprd_id . "','" . $property_id . "','"
								. $subproperty[$i]->value . "');\",\"\",\"\",\"" . $subpropertyid . "_subpropimg_"
								. $subproperty[$i]->value . "\",\"" . $borderstyle . "\");";

							$imgAdded++;
						}
					}

					$attributes_subproperty_vat_show   = 0;
					$attributes_subproperty_withoutvat = 0;

					if ($subproperty [$i]->subattribute_color_price > 0)
					{
						$pricelist = $this->getPropertyPrice($subproperty[$i]->value, 1, 'subproperty');

						if (count($pricelist) > 0)
						{
							$subproperty[$i]->subattribute_color_price = $pricelist->product_price;
						}

						$attributes_subproperty_withoutvat = $subproperty [$i]->subattribute_color_price;

						if (!empty($chktag))
						{
							$attributes_subproperty_vat_show = $this->getProducttax($product_id, $subproperty [$i]->subattribute_color_price);
						}

						$attributes_subproperty_vat_show += $subproperty [$i]->subattribute_color_price;

						if (SHOW_PRICE && (!DEFAULT_QUOTATION_MODE || (DEFAULT_QUOTATION_MODE && SHOW_QUOTATION_PRICE)) && (!$attributes->hide_attribute_price))
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name) . " (" . $subproperty [$i]->oprand . $this->getProductFormattedPrice($attributes_subproperty_vat_show) . ")";
						}
						else
						{
							$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
						}
					}
					else
					{
						$subproperty [$i]->text = urldecode($subproperty [$i]->subattribute_color_name);
					}

					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_oprand' . $subproperty [$i]->value . '" value="' . $subproperty [$i]->oprand . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_proprice' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_vat_show . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_proprice_withoutvat' . $subproperty [$i]->value . '" value="' . $attributes_subproperty_withoutvat . '" />';
					$attribute_table .= '<input type="hidden" id="' . $subpropertyid . '_stock' . $subproperty [$i]->value . '" value="' . $stock . '" />';
				}

				if (!$mph_thumb)
				{
					$mph_thumb = 50;
				}

				if (!$mpw_thumb)
				{
					$mpw_thumb = 50;
				}

				$atth = 50;
				$attw = 50;

				if (defined('ATTRIBUTE_SCROLLER_THUMB_HEIGHT') && ATTRIBUTE_SCROLLER_THUMB_HEIGHT)
				{
					$atth = ATTRIBUTE_SCROLLER_THUMB_HEIGHT;
				}

				if (defined('ATTRIBUTE_SCROLLER_THUMB_WIDTH') && ATTRIBUTE_SCROLLER_THUMB_WIDTH)
				{
					$attw = ATTRIBUTE_SCROLLER_THUMB_WIDTH;
				}

				$subproperty_scrollerdiv .= "
				isFlowers" . $commonid . ".setThumbnailHeight(" . $atth . ");
				isFlowers" . $commonid . ".setThumbnailWidth(" . $attw . ");
				isFlowers" . $commonid . ".setThumbnailPadding(5);
				isFlowers" . $commonid . ".setScrollType(0);
				isFlowers" . $commonid . ".enableThumbBorder(false);
				isFlowers" . $commonid . ".setClickOpenType(1);
				isFlowers" . $commonid . ".setThumbsShown(" . NOOF_SUBATTRIB_THUMB_FOR_SCROLLER . ");
				isFlowers" . $commonid . ".setNumOfImageToScroll(1);
				isFlowers" . $commonid . ".renderScroller();
	      		    </script>";
				$subproperty_scrollerdiv .= "<div id=\"divsubimgscroll" . $commonid . "\" style=\"display:none\">"
					. implode("#_#", $subprop_Arry) . "</div>";
				$subproperty_scrollerdiv .= "</div></div></td>";
				$subproperty_scrollerdiv .= "<td><a id=\"FirstButton\" href=\"javascript:isFlowers" . $commonid
					. ".scrollForward();\" onmouseover=\"isFlowers" . $commonid
					. ".smoothScrollForward();\" onmouseout=\"isFlowers" . $commonid
					. ".stopSmoothScroll();\"><img src=\"" . REDSHOP_FRONT_IMAGES_ABSPATH
					. "rightarrow.jpg\" style=\"margin-top: 12px;margin-right: 6px;\" style=\"margin-top: 3px;\" border=\"0\" /></a></td>";
				$subproperty_scrollerdiv .= "</tr></table>";

				if (strstr($subatthtml, "{subproperty_image_without_scroller}"))
				{
					$subproperty_woscrollerdiv .= "</div>";
				}

				if (USE_ENCODING)
				{
					$displayPropertyName = mb_convert_encoding(urldecode($subproperty[0]->property_name), "ISO-8859-1", "UTF-8");

				}
				else
				{
					$displayPropertyName = urldecode($subproperty[0]->property_name);
				}

				if ($subproperty[0]->subattribute_color_title != "")
				{
					if (USE_ENCODING)
					{
						$displayPropertyName = mb_convert_encoding(
							urldecode($subproperty[0]->subattribute_color_title),
							"ISO-8859-1",
							"UTF-8"
						);
					}
					else
					{
						$displayPropertyName = urldecode($subproperty[0]->subattribute_color_title);
					}
				}

				$tmp_array           = array();
				$tmp_array[0]        = new stdClass;
				$tmp_array[0]->value = 0;
				$tmp_array[0]->text  = JText::_('COM_REDSHOP_SELECT') . " " . $displayPropertyName;

				$new_subproperty = array_merge($tmp_array, $subproperty);
				$chklist         = '';
				$display_type	 = '';

				if (isset($subproperty[0]->setdisplay_type) === true)
				{
					$display_type    = $subproperty[0]->setdisplay_type;
				}

				if ($subproperty[0]->setmulti_selected)
				{
					$display_type = 'checkbox';
				}

				if ($display_type == 'checkbox' || $display_type == 'radio')
				{
					for ($chk = 0; $chk < count($subproperty); $chk++)
					{
						$checked = "";

						if (count($selectSubproperty) > 0)
						{
							if (in_array($subproperty[$chk]->value, $selectSubproperty))
							{
								$checked = "checked";
							}
						}
						else
						{
							if ($subproperty[$chk]->setdefault_selected)
							{
								$checked = "checked";
							}
						}

						$scrollerFunction = "";

						if ($imgAdded > 0 && strstr($attribute_table, "{subproperty_image_scroller}"))
						{
							$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(\"" . $chk . "\");";
						}

						$chklist .= "<div class='attribute_multiselect_single'><input type='" . $display_type . "' "
							. $checked . " value='" . $subproperty[$chk]->value . "' name='" . $subpropertyid
							. "[]'  id='" . $subpropertyid . "' class='inputbox' onClick='javascript:" . $scrollerFunction
							. "calculateTotalPrice(\"" . $product_id . "\",\"" . $relatedprd_id
							. "\");displayAdditionalImage(\"" . $product_id . "\",\"" . $accessory_id . "\",\""
							. $relatedprd_id . "\",\"" . $property_id . "\",\"" . $subproperty[$chk]->value . "\");' />&nbsp;"
							. $subproperty[$chk]->text . "</div>";
					}
				}
				else
				{
					$scrollerFunction = "";

					if ($imgAdded > 0 && strstr($attribute_table, "{subproperty_image_scroller}"))
					{
						$scrollerFunction = "isFlowers" . $commonid . ".scrollImageCenter(this.selectedIndex-1);";
					}

					$chklist = JHTML::_('select.genericlist', $new_subproperty, $subpropertyid . '[]', ' id="'
						. $subpropertyid . '" class="inputbox" size="1" required="' . $subproperty[0]->setrequire_selected
						. '" subpropName="' . $displayPropertyName . '"  onchange="javascript:' . $scrollerFunction
						. 'calculateTotalPrice(\'' . $product_id . '\',\'' . $relatedprd_id . '\');displayAdditionalImage(\''
						. $product_id . '\',\'' . $accessory_id . '\',\'' . $relatedprd_id . '\',\'' . $property_id
						. '\',this.value);" ', 'value', 'text', $selectedsubproperty);
				}

				$lists ['subproperty_id'] = $chklist;

				if ($imgAdded == 0 || $isAjax == 1)
				{
					$subproperty_scrollerdiv = "";
				}

				if ($subproperty[0]->setrequire_selected == 1)
				{
					$displayPropertyName = ASTERISK_POSITION > 0 ? $displayPropertyName . "<span id='asterisk_right'> * </span>" : "<span id='asterisk_left'>* </span>" . $displayPropertyName;
				}
				$attribute_table = str_replace("{property_title}", $displayPropertyName, $attribute_table);
				$attribute_table = str_replace("{subproperty_dropdown}", $lists ['subproperty_id'], $attribute_table);

				if (strstr($subatthtml, "{subproperty_image_without_scroller}"))
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", "", $attribute_table);
					$attribute_table = str_replace("{subproperty_image_without_scroller}", $subproperty_woscrollerdiv, $attribute_table);
				}
				elseif (strstr($subatthtml, "{subproperty_image_scroller}"))
				{
					$attribute_table = str_replace("{subproperty_image_scroller}", $subproperty_scrollerdiv, $attribute_table);
					$attribute_table = str_replace("{subproperty_image_without_scroller}", "", $attribute_table);
				}
			}
		}

		return $attribute_table;
	}

	public function defaultAttributeDataPrice($product_id = 0, $product_showprice = 0, $data_add, $user_id = 0, $applyTax = 0, $attributes = array())
	{
		if (count($attributes) <= 0 || INDIVIDUAL_ADD_TO_CART_ENABLE)
		{
			return $product_showprice;
		}

		$attribute_template = $this->getAttributeTemplate($data_add);

		if (count($attribute_template) <= 0)
		{
			return $product_showprice;
		}

		for ($a = 0; $a < count($attributes); $a++)
		{
			$property = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id);

			if ($attributes[$a]->text != "" && count($property) > 0)
			{
				$selectedpropertyId = array();
				$proprice           = array();
				$prooprand          = array();

				for ($i = 0; $i < count($property); $i++)
				{
					if ($property[$i]->setdefault_selected)
					{
						if ($property[$i]->property_price > 0)
						{
							$attributes_property_vat = 0;

							if ($applyTax)
							{
								$attributes_property_vat = $this->getProducttax($product_id, $property [$i]->property_price, $user_id);
							}

							$property [$i]->property_price += $attributes_property_vat;
						}

						$proprice[]           = $property[$i]->property_price;
						$prooprand[]          = $property[$i]->oprand;
						$selectedpropertyId[] = $property[$i]->property_id;
					}
				}

				if (!$attributes [$a]->allow_multiple_selection && count($proprice) > 0)
				{
					$proprice           = array($proprice[count($proprice) - 1]);
					$prooprand          = array($prooprand[count($prooprand) - 1]);
					$selectedpropertyId = array($selectedpropertyId[count($selectedpropertyId) - 1]);
				}
				// Add default selected Property price to product price
				$default_priceArr  = $this->makeTotalPriceByOprand($product_showprice, $prooprand, $proprice);
				$product_showprice = $default_priceArr[1];

				for ($i = 0; $i < count($selectedpropertyId); $i++)
				{
					$subproprice  = array();
					$subprooprand = array();
					$subproperty  = $this->getAttibuteSubProperty(0, $selectedpropertyId[$i]);

					for ($sp = 0; $sp < count($subproperty); $sp++)
					{
						if ($subproperty[$sp]->setdefault_selected)
						{
							if ($subproperty[$sp]->subattribute_color_price > 0)
							{
								$attributes_subproperty_vat = 0;

								if ($applyTax)
								{
									$attributes_subproperty_vat = $this->getProducttax(
										$product_id,
										$subproperty[$sp]->subattribute_color_price,
										$user_id
									);
								}

								$subproperty[$sp]->subattribute_color_price += $attributes_subproperty_vat;
							}

							$subproprice[]  = $subproperty[$sp]->subattribute_color_price;
							$subprooprand[] = $subproperty[$sp]->oprand;
						}
					}

					if (count($subproprice) > 0 && !$subproperty[0]->setmulti_selected)
					{
						$subproprice  = array($subproprice[count($subproprice) - 1]);
						$subprooprand = array($subprooprand[count($subprooprand) - 1]);
					}
					// Add default selected Property price to product price
					$default_priceArr  = $this->makeTotalPriceByOprand($product_showprice, $subprooprand, $subproprice);
					$product_showprice = $default_priceArr[1];
				}
			}
		}

		return $product_showprice;
	}

	public function replacePropertyAddtoCart($product_id = 0, $property_id = 0, $category_id = 0, $commonid = "", $property_stock = 0, $property_data = "", $cart_template = array(), $data_add = "")
	{
		$user_id         = 0;
		$url             = JURI::base();
		$stockroomhelper = new rsstockroomhelper();
		$option          = 'com_redshop';
		$Itemid          = JRequest::getInt('Itemid');

		$product = $this->getProductById($product_id);

		if ($property_stock <= 0)
		{
			$property_data = str_replace("{form_addtocart:$cart_template->template_name}", JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE'), $property_data);

			return $property_data;
		}

		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		// IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
		$qunselect           = 1;
		$productArr          = $this->getProductNetPrice($product_id, $user_id, $qunselect, $data_add);
		$product_price       = $productArr['product_price'] * $qunselect;
		$product_price_novat = $productArr['product_price_novat'] * $qunselect;
		$product_old_price   = $productArr['product_old_price'] * $qunselect;

		if ($product->not_for_sale)
		{
			$product_price = 0;
		}

		$max_quantity = $product->max_order_product_quantity;
		$min_quantity = $product->min_order_product_quantity;

		$addtocartFormName = 'addtocart_' . $commonid . '_' . $property_id;
		$stockId           = $commonid . '_' . $property_id;
		$attribute_id      = 0;
		$arr               = explode("_", $commonid);

		if (count($arr) > 0)
		{
			$attribute_id = $arr[count($arr) - 1];
		}

		$cartform = "<form name='" . $addtocartFormName
			. "' id='" . $addtocartFormName
			. "' class='addtocart_formclass' action='" . JRoute::_('index.php') . "' method='post'>";
		$cartform .= $cart_template->template_desc;

		$cartform .= "
			<input type='hidden' name='product_id' id='product_id' value='" . $product_id . "'>
			<input type='hidden' name='category_id' value='" . $category_id . "'>
			<input type='hidden' name='view' value='cart'>
			<input type='hidden' name='task' value='add'>
			<input type='hidden' name='option' value='com_redshop'>
			<input type='hidden' name='Itemid' id='Itemid' value='" . $Itemid . "'>
			<input type='hidden' name='sel_wrapper_id' id='sel_wrapper_id' value='0'>

			<input type='hidden' name='accessory_data' id='accessory_data' value='0'>
			<input type='hidden' name='acc_attribute_data' id='acc_attribute_data' value='0'>
			<input type='hidden' name='acc_quantity_data' id='acc_quantity_data' value='0'>
			<input type='hidden' name='acc_property_data' id='acc_property_data' value='0'>
			<input type='hidden' name='acc_subproperty_data' id='acc_subproperty_data' value='0'>
			<input type='hidden' name='accessory_price' id='accessory_price' value='0'>

			<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute=''>
			<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty=''>

			<input type='hidden' name='main_price' id='main_price" . $product_id . "' value='" . $product_price . "' />
			<input type='hidden' name='tmp_product_price' id='tmp_product_price' value='0'>

			<input type='hidden' name='product_old_price' id='product_old_price" . $product_id . "' value='"
			. $product_old_price . "' />
			<input type='hidden' name='tmp_product_old_price' id='tmp_product_old_price' value='0'>

			<input type='hidden' name='product_price_no_vat' id='product_price_no_vat" . $product_id . "' value='"
			. $product_price_novat . "' />
			<input type='hidden' name='productprice_notvat' id='productprice_notvat' value='0'>

			<input type='hidden' name='min_quantity' id='min_quantity' value='" . $min_quantity . "' requiredtext='"
			. JText::_('COM_REDSHOP_MINIMUM_QUANTITY_SHOULD_BE') . "'>
			<input type='hidden' name='max_quantity' id='max_quantity' value='" . $max_quantity . "' requiredtext='"
			. JText::_('COM_REDSHOP_MAXIMUM_QUANTITY_SHOULD_BE') . "'>

			<input type='hidden' name='attribute_data' id='attribute_data' value='0'>
			<input type='hidden' name='property_data' id='property_data' value='0'>
			<input type='hidden' name='subproperty_data' id='subproperty_data' value='0'>

			<input type='hidden' name='calcHeight' id='hidden_calc_height' value='' />
			<input type='hidden' name='calcWidth' id='hidden_calc_width' value='' />
			<input type='hidden' name='calcDepth' id='hidden_calc_depth' value='' />
			<input type='hidden' name='calcRadius' id='hidden_calc_radius' value='' >
			<input type='hidden' name='calcUnit' id='hidden_calc_unit' value='' />
			<input type='hidden' name='pdcextraid' id='hidden_calc_extraid' value='' />
			<input type='hidden' name='hidden_attribute_cartimage' id='hidden_attribute_cartimage" . $product_id .
			"' value='' />";

		if ($product->product_type == "subscription")
		{
			$sub_id = JRequest::getInt('subscription_id', 0);
			$cartform .= "<input type='hidden' name='subscription_id' id='hidden_subscription_id' value='" . $sub_id .
				"' />";
			$cartform .= "<input type='hidden' name='subscription_prize' id='hidden_subscription_prize' value='0' />";
		}

		$quan = 1;

		if (strstr($cartform, "{addtocart_quantity}"))
		{
			$addtocart_quantity = "<span id='stockQuantity" . $stockId . "'><input type='text' name='quantity' id='quantity" .
				$product_id . "' value='" . $quan . "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY .
				"' onchange='validateInputNumber(this.id);' onkeypress='return event.keyCode!=13'></span>";
			$cartform           = str_replace("{addtocart_quantity}", $addtocart_quantity, $cartform);
			$cartform           = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
		}
		elseif (strstr($cartform, "{addtocart_quantity_selectbox}"))
		{
			$addtocart_quantity = "<input type='hidden' name='quantity' id='quantity" . $product_id . "' value='" .
				$quan . "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY . "'>";

			if ((DEFAULT_QUANTITY_SELECTBOX_VALUE != ""
				&& $product->quantity_selectbox_value == '')
				|| $product->quantity_selectbox_value != '')
			{
				$selectbox_value = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : DEFAULT_QUANTITY_SELECTBOX_VALUE;
				$quaboxarr       = explode(",", $selectbox_value);
				$quaboxarr       = array_merge(array(), array_unique($quaboxarr));
				sort($quaboxarr);
				$qselect = "<select name='quantity' id='quantity" . $product_id . "'  OnChange='calculateTotalPrice("
					. $product_id . ",0);'>";

				for ($q = 0; $q < count($quaboxarr); $q++)
				{
					if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
					{
						$quantityselect = ($quan == intval($quaboxarr[$q])) ? "selected" : "";
						$qselect .= "<option value='" . intVal($quaboxarr[$q]) . "' " . $quantityselect . ">"
							. intVal($quaboxarr[$q]) . "</option>";
					}
				}

				$qselect .= "</select>";
				$addtocart_quantity = "<span id='stockQuantity" . $stockId . "'>" . $qselect . "</span>";
			}

			$cartform = str_replace("{addtocart_quantity_selectbox}", $addtocart_quantity, $cartform);
			$cartform = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
		}
		else
		{
			$cartform .= "<input type='hidden' name='quantity' id='quantity" . $product_id . "' value='" . $quan
				. "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY . "'>";
		}

		$tooltip             = (DEFAULT_QUOTATION_MODE) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
		$ADD_OR_LBL          = (DEFAULT_QUOTATION_MODE) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : JText::_('COM_REDSHOP_ADD_TO_CART');
		$ADD_CART_IMAGE      = (DEFAULT_QUOTATION_MODE) ? REQUESTQUOTE_IMAGE : ADDTOCART_IMAGE;
		$ADD_CART_BACKGROUND = (DEFAULT_QUOTATION_MODE) ? REQUESTQUOTE_BACKGROUND : ADDTOCART_BACKGROUND;

		$cartTag   = '';
		$cartIcon  = '';
		$cartTitle = ' title="' . $ADD_OR_LBL . '" ';

		$onclick = 'onclick="if(displayAddtocartProperty(\'' . $addtocartFormName . '\',\'' . $product_id . '\',\'' .
			$attribute_id . '\',\'' . $property_id . '\')){checkAddtocartValidation(\'' . $addtocartFormName . '\',\'' .
			$product_id . '\',0,0,\'\',0,0,0);}" ';
		$class   = 'class=""';
		$title   = 'title=""';

		if (strstr($cartform, "{addtocart_tooltip}"))
		{
			$class    = 'class="editlinktip hasTip"';
			$title    = ' title="' . $tooltip . '" ';
			$cartform = str_replace("{addtocart_tooltip}", $cartform, "");
		}

		if (strstr($cartform, "{addtocart_button}"))
		{
			$cartTag  = "{addtocart_button}";
			$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . '"><input type="button" ' .
				$onclick . $cartTitle . ' name="addtocart_button" value="' . $ADD_OR_LBL . '" /></span>';
		}

		if (strstr($cartform, "{addtocart_link}"))
		{
			$cartTag  = "{addtocart_link}";
			$cartIcon = '<span ' . $class . ' ' . $title . ' id="pdaddtocart' . $stockId . '" ' . $onclick . $cartTitle .
				' style="cursor: pointer;">' . $ADD_OR_LBL . '</span>';
		}

		if (strstr($cartform, "{addtocart_image_aslink}"))
		{
			$cartTag  = "{addtocart_image_aslink}";
			$cartIcon = '<span ' . $class . ' ' . $title . ' id="pdaddtocart' . $stockId . '"><img ' . $onclick .
				$cartTitle . ' alt="' . $ADD_OR_LBL . '" style="cursor: pointer;" src="' . REDSHOP_FRONT_IMAGES_ABSPATH .
				$ADD_CART_IMAGE . '" /></span>';
		}

		if (strstr($cartform, "{addtocart_image}"))
		{
			$cartTag  = "{addtocart_image}";
			$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . '><div ' . $onclick .
				$cartTitle . ' align="center" style="cursor:pointer;background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH .
				$ADD_CART_BACKGROUND . ');background-position:bottom;background-repeat:no-repeat;">' . $ADD_OR_LBL .
				'</div></span>';
		}

		$cartform = str_replace($cartTag, '<span id="stockaddtocart' . $stockId . '"></span>' . $cartIcon, $cartform);
		$cartform .= "</form>";
		$property_data = str_replace("{form_addtocart:$cart_template->template_name}", $cartform, $property_data);

		return $property_data;
	}

	public function replaceCartTemplate($product_id = 0, $category_id = 0, $accessory_id = 0, $relproduct_id = 0, $data_add = "", $isChilds = false, $userfieldArr = array(), $totalatt = 0, $totalAccessory = 0, $count_no_user_field = 0, $module_id = 0, $giftcard_id = 0)
	{
		$user_id         = 0;
		$url             = JURI::root();
		$redconfig       = new Redconfiguration();
		$extraField      = new extraField();
		$stockroomhelper = new rsstockroomhelper();

		$product_quantity = JRequest::getVar('product_quantity');
		$option           = 'com_redshop';
		$Itemid           = JRequest::getInt('Itemid');
		$user             = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$add_cart_flag = false;
		$field_section = 12;

		if ($relproduct_id != 0)
		{
			$product_id = $relproduct_id;
		}
		elseif ($giftcard_id != 0)
		{
			$product_id = $giftcard_id;
		}

		if ($giftcard_id != 0)
		{
			$product       = $this->getGiftcardData($giftcard_id);
			$field_section = 13;
		}
		else
		{
			$product = $this->getProductById($product_id);
		}

		$taxexempt_addtocart = $this->taxexempt_addtocart($user_id, 1);

		$cart_template = $this->getAddtoCartTemplate($data_add);

		if (count($cart_template) <= 0 && $data_add != "")
		{
			$cart_template                = new stdclass();
			$cart_template->template_name = "";
			$cart_template->template_desc = "";
//			return $data_add;
		}

		if ($data_add == "" && count($cart_template) <= 0)
		{
			$cart_template                = new stdclass();
			$cart_template->template_name = "notemplate";
			$cart_template->template_desc = "<div>{addtocart_image_aslink}</div>";
			$data_add                     = "{form_addtocart:$cart_template->template_name}";
		}

		$layout = JRequest::getVar('layout');
		$cart   = $this->_session->get('cart');

		$isAjax                 = 0;
		$preprefix              = "";
		$preselected_attrib_img = "";

		if ($layout == "viewajaxdetail")
		{
			$isAjax    = 1;
			$preprefix = "ajax_";
		}

		$prefix = $preprefix . "prd_";

		if ($accessory_id != 0)
		{
			$prefix = $preprefix . "acc_";
		}
		elseif ($relproduct_id != 0)
		{
			$prefix = $preprefix . "rel_";
		}

		if (!empty($module_id))
		{
			$prefix = $prefix . $module_id . "_";
		}

		$totrequiredatt  = "";
		$totrequiredprop = '';

		if ($giftcard_id != 0)
		{
			$add_cart_flag       = true;
			$product_price       = $product->giftcard_price;
			$product_price_novat = 0;
			$product_old_price   = 0;
			$isStockExists       = true;
			$max_quantity        = 0;
			$min_quantity        = 0;
		}
		else
		{
			// IF PRODUCT CHILD IS EXISTS THEN DONT SHOW PRODUCT ATTRIBUTES
			if ($isChilds)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", "", $data_add);

				return $data_add;
			}
			elseif ($this->isProductDateRange($userfieldArr, $product_id))
			{
				// New type custome field - Selection based on selected conditions
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", JText::_('COM_REDSHOP_PRODUCT_DATE_FIELD_EXPIRED'), $data_add);

				return $data_add;
			}
			elseif ($product->not_for_sale)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", '', $data_add);

				return $data_add;
			}
			elseif (!$taxexempt_addtocart)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", '', $data_add);

				return $data_add;
			}
			elseif (!SHOW_PRICE)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", '', $data_add);

				return $data_add;
			}
			elseif ($product->expired == 1)
			{
				$data_add = str_replace("{form_addtocart:$cart_template->template_name}", PRODUCT_EXPIRE_TEXT, $data_add);

				return $data_add;
			}

			// Get stock for Product

			$isStockExists         = $stockroomhelper->isStockExists($product_id);
			$isPreorderStockExists = '';

			if ($totalatt > 0 && !$isStockExists)
			{
				$property = $this->getAttibuteProperty(0, 0, $product_id);

				for ($att_j = 0; $att_j < count($property); $att_j++)
				{
					$isSubpropertyStock = false;
					$sub_property       = $this->getAttibuteSubProperty(0, $property[$att_j]->property_id);

					for ($sub_j = 0; $sub_j < count($sub_property); $sub_j++)
					{
						$isSubpropertyStock = $stockroomhelper->isStockExists(
							$sub_property[$sub_j]->subattribute_color_id,
							'subproperty'
						);

						if ($isSubpropertyStock)
						{
							$isStockExists = $isSubpropertyStock;
							break;
						}
					}

					if ($isSubpropertyStock)
					{
						break;
					}
					else
					{
						$isPropertystock = $stockroomhelper->isStockExists($property[$att_j]->property_id, "property");

						if ($isPropertystock)
						{
							$isStockExists = $isPropertystock;
							break;
						}
					}
				}
			}

			$qunselect = $this->GetDefaultQuantity($product_id, $data_add);
			//$qunselect=1;
			$productArr          = $this->getProductNetPrice($product_id, $user_id, $qunselect, $data_add);
			$product_price       = $productArr['product_price'] * $qunselect;
			$product_price_novat = $productArr['product_price_novat'] * $qunselect;
			$product_old_price   = $productArr['product_old_price'] * $qunselect;

			if ($product->not_for_sale)
			{
				$product_price = 0;
			}

			$max_quantity = $product->max_order_product_quantity;
			$min_quantity = $product->min_order_product_quantity;
		}

		$stockdisplay        = false;
		$preorderdisplay     = false;
		$cartdisplay         = false;
		$preorder_stock_flag = false;
		$pre_order_value     = 0;

		$display_text = JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE');

		if (!$isStockExists)
		{
			// Check if preorder is set to yes than add pre order button
			$product_preorder = $product->preorder;
			//if (ALLOW_PRE_ORDER && !empty($product->product_availability_date))
			if (($product_preorder == "global"
				&& ALLOW_PRE_ORDER)
				|| ($product_preorder == "yes")
				|| ($product_preorder == "" && ALLOW_PRE_ORDER)
			)
			{
				// Get preorder stock for Product

				$isPreorderStockExists = $stockroomhelper->isPreorderStockExists($product_id);

				if ($totalatt > 0 && !$isPreorderStockExists)
				{
					$property = $this->getAttibuteProperty(0, 0, $product_id);

					for ($att_j = 0; $att_j < count($property); $att_j++)
					{
						$isSubpropertyStock = false;
						$sub_property       = $this->getAttibuteSubProperty(0, $property[$att_j]->property_id);

						for ($sub_j = 0; $sub_j < count($sub_property); $sub_j++)
						{
							$isSubpropertyStock = $stockroomhelper->isPreorderStockExists(
								$sub_property[$sub_j]->subattribute_color_id,
								'subproperty'
							);

							if ($isSubpropertyStock)
							{
								$isPreorderStockExists = $isSubpropertyStock;
								break;
							}
						}

						if ($isSubpropertyStock)
						{
							break;
						}
						else
						{
							$isPropertystock = $stockroomhelper->isPreorderStockExists(
								$property[$att_j]->property_id,
								"property"
							);

							if ($isPropertystock)
							{
								$isPreorderStockExists = $isPropertystock;
								break;
							}
						}
					}
				}

				// Check preorder stock
				if (!$isPreorderStockExists)
				{
					$preorder_stock_flag = true;
					$stockdisplay        = true;
					$add_cart_flag       = true;
					$display_text        = JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE');

				}
				else
				{
					//$pre_order_value = 1;
					$preorderdisplay     = true;
					$add_cart_flag       = true;
					$p_availability_date = "";

					if ($product->product_availability_date != "")
					{
						$p_availability_date = $redconfig->convertDateFormat($product->product_availability_date);
					}
				}

			}
			else
			{
				$stockdisplay  = true;
				$add_cart_flag = true;
			}
		}
		else
		{
			$cartdisplay   = true;
			$add_cart_flag = true;
		}

		$p_availability_date = "";
		$ADD_OR_PRE_LBL      = JText::_('COM_REDSHOP_PRE_ORDER');
		$ADD_OR_PRE_TOOLTIP  = str_replace("{availability_date}", $p_availability_date, ALLOW_PRE_ORDER_MESSAGE);
		$ADD_OR_PRE_BTN      = PRE_ORDER_IMAGE;
		$tooltip             = (DEFAULT_QUOTATION_MODE) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE_TOOLTIP') : JText::_('COM_REDSHOP_ADD_TO_CART_TOOLTIP');
		$ADD_OR_LBL          = (DEFAULT_QUOTATION_MODE) ? JText::_('COM_REDSHOP_REQUEST_A_QUOTE') : JText::_('COM_REDSHOP_ADD_TO_CART');
		$ADD_CART_IMAGE      = (DEFAULT_QUOTATION_MODE) ? REQUESTQUOTE_IMAGE : ADDTOCART_IMAGE;
		$ADD_CART_BACKGROUND = (DEFAULT_QUOTATION_MODE) ? REQUESTQUOTE_BACKGROUND : ADDTOCART_BACKGROUND;
		$ADD_OR_TOOLTIP      = "";

		if ($totalatt > 0)
		{
			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $this->getProductAttribute(0, $product->attribute_set_id, 0, 1, 1);
			}

			$requiredattribute = $this->getProductAttribute($product_id, 0, 0, 1, 1);
			$requiredattribute = array_merge($requiredattribute, $attributes_set);

			for ($i = 0; $i < count($requiredattribute); $i++)
			{
				$totrequiredatt .= JText::_('COM_REDSHOP_ATTRIBUTE_IS_REQUIRED') . " "
					. urldecode($requiredattribute[$i]->attribute_name) . "\n";
			}

			$requiredproperty = $this->getAttibuteProperty(0, 0, $product_id, 0, 1);

			for ($y = 0; $y < count($requiredproperty); $y++)
			{
				$totrequiredprop .= JText::_('COM_REDSHOP_SUBATTRIBUTE_IS_REQUIRED') . " "
					. urldecode($requiredproperty[$y]->property_name) . "\n";
			}
		}

		$stockId = $prefix . $product_id;

		if ($add_cart_flag)
		{
			if ($giftcard_id == 0 && $category_id == 0)
			{
				$category_id = $this->getCategoryProduct($product_id);
			}

			$addtocartFormName = 'addtocart_' . $prefix . $product_id; //$category_id
			$cartform          = "<form name='" . $addtocartFormName . "' id='" . $addtocartFormName
				. "' class='addtocart_formclass' action='" . JRoute::_('index.php') . "' method='post'>";
			$cartform .= $cart_template->template_desc;

			if (count($userfieldArr) > 0)
			{
				$product_userhiddenfileds = '<table>';
				$idx                      = 0;

				if (isset ($cart ['idx']))
				{
					$idx = ( int ) ($cart ['idx']);
				}

				$cart_id = '';

				for ($j = 0; $j < $idx; $j++)
				{
					if ($giftcard_id != 0)
					{
						if ($cart [$j] ['giftcard_id'] == $product_id)
						{
							$cart_id = $j;
						}
					}
					else
					{
						if ($cart [$j] ['product_id'] == $product_id)
						{
							$cart_id = $j;
						}
					}
				}

				for ($ui = 0; $ui < count($userfieldArr); $ui++)
				{
					$result_arr = $extraField->list_all_user_fields(
						$userfieldArr[$ui],
						$field_section,
						"hidden",
						$cart_id,
						$isAjax, $product_id
					);

					$product_userhiddenfileds .= $result_arr[1];
				}

				$product_userhiddenfileds .= '</table>';
				$cartform .= $product_userhiddenfileds;
			}
			//Start Hidden attribute image in cart
			$attributes = $this->getProductAttribute($product_id);
			$attrib     = $this->getProductAttribute($product_id);

			if (count($attributes) > 0)
			{
				$selectedpropertyId    = 0;
				$selectedsubpropertyId = 0;

				for ($a = 0; $a < count($attributes); $a++)
				{
					$selectedId = array();
					$property   = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id);

					if ($attributes[$a]->text != "" && count($property) > 0)
					{
						for ($i = 0; $i < count($property); $i++)
						{
							if ($property[$i]->setdefault_selected)
							{
								$selectedId[] = $property[$i]->property_id;
							}
						}

						if (count($selectedId) > 0)
						{
							$selectedpropertyId = $selectedId[count($selectedId) - 1];
							$subproperty        = $this->getAttibuteSubProperty(0, $selectedpropertyId);
							$selectedId         = array();

							for ($sp = 0; $sp < count($subproperty); $sp++)
							{
								if ($subproperty[$sp]->setdefault_selected)
								{
									$selectedId[] = $subproperty[$sp]->subattribute_color_id;
								}
							}

							if (count($selectedId) > 0)
							{
								$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
							}
						}
					}
				}

				$preselected_attrib_img = $this->get_hidden_attribute_cartimage(
					$product_id,
					$selectedpropertyId,
					$selectedsubpropertyId
				);

			}
			//End
			$cartform .= "
				<input type='hidden' name='preorder_product_stock' id='preorder_product_stock" . $product_id .
				"' value='" . $isPreorderStockExists . "'>
		        <input type='hidden' name='product_stock' id='product_stock" . $product_id . "' value='" .
				$isStockExists . "'>
				<input type='hidden' name='product_preorder' id='product_preorder" . $product_id . "' value='" .
				$product->preorder . "'>
				<input type='hidden' name='product_id' id='product_id' value='" . $product_id . "'>
				<input type='hidden' name='category_id' value='" . $category_id . "'>
				<input type='hidden' name='view' value='cart'>
				<input type='hidden' name='task' value='add'>
				<input type='hidden' name='option' value='com_redshop'>
				<input type='hidden' name='Itemid' id='Itemid' value='" . $Itemid . "'>
				<input type='hidden' name='sel_wrapper_id' id='sel_wrapper_id' value='0'>

				<input type='hidden' name='main_price' id='main_price" . $product_id . "' value='" . $product_price .
				"' />
				<input type='hidden' name='tmp_product_price' id='tmp_product_price' value='0'>

				<input type='hidden' name='product_old_price' id='product_old_price" . $product_id . "' value='" .
				$product_old_price . "' />
				<input type='hidden' name='tmp_product_old_price' id='tmp_product_old_price' value='0'>

				<input type='hidden' name='product_price_no_vat' id='product_price_no_vat" . $product_id . "' value='" .
				$product_price_novat . "' />
				<input type='hidden' name='productprice_notvat' id='productprice_notvat' value='0'>

				<input type='hidden' name='min_quantity' id='min_quantity' value='" . $min_quantity .
				"' requiredtext='" . JText::_('COM_REDSHOP_MINIMUM_QUANTITY_SHOULD_BE') . "'>
				<input type='hidden' name='max_quantity' id='max_quantity' value='" . $max_quantity .
				"' requiredtext='" . JText::_('COM_REDSHOP_MAXIMUM_QUANTITY_SHOULD_BE') . "'>

				<input type='hidden' name='accessory_data' id='accessory_data' value='0'>
				<input type='hidden' name='acc_attribute_data' id='acc_attribute_data' value='0'>
				<input type='hidden' name='acc_quantity_data' id='acc_quantity_data' value='0'>
				<input type='hidden' name='acc_property_data' id='acc_property_data' value='0'>
				<input type='hidden' name='acc_subproperty_data' id='acc_subproperty_data' value='0'>
				<input type='hidden' name='accessory_price' id='accessory_price' value='0'>
				<input type='hidden' name='accessory_price_withoutvat' id='accessory_price_withoutvat' value='0'>

				<input type='hidden' name='attribute_data' id='attribute_data' value='0'>
				<input type='hidden' name='property_data' id='property_data' value='0'>
				<input type='hidden' name='subproperty_data' id='subproperty_data' value='0'>
				<input type='hidden' name='attribute_price' id='attribute_price' value='0'>
				<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute='" . $totrequiredatt . "'>
				<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty='" . $totrequiredprop . "'>

				<input type='hidden' name='calcHeight' id='hidden_calc_height' value='' />
				<input type='hidden' name='calcWidth' id='hidden_calc_width' value='' />
				<input type='hidden' name='calcDepth' id='hidden_calc_depth' value='' />
				<input type='hidden' name='calcRadius' id='hidden_calc_radius' value='' >
				<input type='hidden' name='calcUnit' id='hidden_calc_unit' value='' />
				<input type='hidden' name='pdcextraid' id='hidden_calc_extraid' value='' />
				<input type='hidden' name='hidden_attribute_cartimage' id='hidden_attribute_cartimage" . $product_id
				. "' value='" . $preselected_attrib_img . "' />";

			if ($giftcard_id != 0)
			{
				$cartform .= "<input type='hidden' name='giftcard_id' id= 'giftcard_id' value='" . $giftcard_id . "'>
							<input type='hidden' name='reciver_email' id='reciver_email' value='" . @$cart['reciver_email'] . "'>
							<input type='hidden' name='reciver_name' id='reciver_name' value='" . @$cart['reciver_name'] . "'>";

				if ($product->customer_amount == 1)
					$cartform .= "<input type='hidden' name='customer_amount' id='customer_amount' value='" . @$cart['customer_amount'] . "'>";
			}
			else
			{
				if ($product->product_type == "subscription")
				{
					$sub_id = JRequest::getInt('subscription_id', 0);
					$cartform .= "<input type='hidden' name='subscription_id' id='hidden_subscription_id' value='"
						. $sub_id . "' />";
					$cartform .= "<input type='hidden' name='subscription_prize' id='hidden_subscription_prize' value='0' />";
				}

				$ajaxdetail_templatedata = $this->getAjaxDetailboxTemplate($product);

				if (count($ajaxdetail_templatedata) > 0)
				{
					$ajax_cart_detail_temp_desc = $ajaxdetail_templatedata->template_desc;
					/*
					 * attribute, accessory, userfield check for ajax detail template
					 */
					// 	make attribute count 0. if there is no tag in ajax detail template
					if (!strstr($ajax_cart_detail_temp_desc, "{attribute_template:"))
					{
						$totalatt = 0;
					}
					// 	make accessory count 0. if there is no tag in ajax detail template
					if (!strstr($ajax_cart_detail_temp_desc, "{accessory_template:"))
					{
						$totalAccessory = 0;
					}
					// make userfields 0.if there is no tag available in ajax detail template
					if (strstr($ajax_cart_detail_temp_desc, "{if product_userfield}"))
					{
						$ajax_extra_field1       = explode("{if product_userfield}", $ajax_cart_detail_temp_desc);
						$ajax_extra_field2       = explode("{product_userfield end if}", $ajax_extra_field1 [1]);
						$ajax_extra_field_center = $ajax_extra_field2 [0];

						if (!strstr($ajax_extra_field_center, "{"))
						{
							$count_no_user_field = 0;
						}
					}
					else
					{
						$count_no_user_field = 0;
					}
				}
			}

			if ($product_quantity)
			{
				$quan = $product_quantity;
			}
			else
			{
				if ($giftcard_id != 0)
				{
					$quan = 1;
				}
				elseif ($product->min_order_product_quantity > 0)
				{
					$quan = $product->min_order_product_quantity;
				}
				else
				{
					$quan = 1;
				}
			}

			if (strstr($cartform, "{addtocart_quantity}"))
			{
				$addtocart_quantity = "<span id='stockQuantity" . $stockId
					. "'><input type='text' name='quantity' id='quantity" . $product_id . "' value='" . $quan
					. "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY
					. "' onchange='validateInputNumber(this.id);' onkeypress='return event.keyCode!=13'></span>";
				$cartform           = str_replace("{addtocart_quantity}", $addtocart_quantity, $cartform);
				$cartform           = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
			}
			elseif (strstr($cartform, "{addtocart_quantity_increase_decrease}"))
			{
				$addtocart_quantity .= '<input type="text"  id="quantity' . $product_id
					. '" name="quantity" size="1"  value="' . $quan . '" onkeypress="return event.keyCode!=13"/>';

				$addtocart_quantity .= '<input type="button" class="myupbutton" onClick="quantity' . $product_id
					. '.value = (+quantity' . $product_id . '.value+1)">';

				$addtocart_quantity .= '<input type="button" class="mydownbutton" onClick="quantity' . $product_id
					. '.value = (quantity' . $product_id . '.value); var qty1 = quantity' . $product_id
					. '.value; if( !isNaN( qty1 ) &amp;&amp; qty1 > 1 ) quantity' . $product_id . '.value--;return false;">';

				$addtocart_quantity .= '<input type="hidden" name="product_id" value="' . $product_id . '">
				<input type="hidden" name="cart_index" value="' . $i . '">
				<input type="hidden" name="Itemid" value="' . $Itemid . '">
				<input type="hidden" name="task" value="">';
				$cartform = str_replace("{addtocart_quantity_increase_decrease}", $addtocart_quantity, $cartform);
				$cartform = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
			}
			elseif (strstr($cartform, "{addtocart_quantity_selectbox}"))
			{
				$addtocart_quantity = "<input type='hidden' name='quantity' id='quantity" . $product_id . "' value='"
					. $quan . "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY . "'>";

				if ((DEFAULT_QUANTITY_SELECTBOX_VALUE != "" && $product->quantity_selectbox_value == '')
					|| $product->quantity_selectbox_value != '')
				{
					$selectbox_value = ($product->quantity_selectbox_value) ? $product->quantity_selectbox_value : DEFAULT_QUANTITY_SELECTBOX_VALUE;
					$quaboxarr       = explode(",", $selectbox_value);
					$quaboxarr       = array_merge(array(), array_unique($quaboxarr));
					sort($quaboxarr);
					$qselect = "<select name='quantity' id='quantity" . $product_id
						. "'  OnChange='calculateTotalPrice(" . $product_id . "," . $relproduct_id . ");'>";

					for ($q = 0; $q < count($quaboxarr); $q++)
					{
						if (intVal($quaboxarr[$q]) && intVal($quaboxarr[$q]) != 0)
						{
							$quantityselect = ($quan == intval($quaboxarr[$q])) ? "selected" : "";
							$qselect .= "<option value='" . intVal($quaboxarr[$q]) . "' " . $quantityselect . ">"
								. intVal($quaboxarr[$q]) . "</option>";
						}
					}

					$qselect .= "</select>";
					$addtocart_quantity = "<span id='stockQuantity" . $stockId . "'>" . $qselect . "</span>";
				}

				$cartform = str_replace("{addtocart_quantity_selectbox}", $addtocart_quantity, $cartform);
				$cartform = str_replace("{quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY_LBL'), $cartform);
			}
			else
			{
				$cartform .= "<input type='hidden' name='quantity' id='quantity" . $product_id . "' value='"
					. $quan . "' maxlength='" . DEFAULT_QUANTITY . "' size='" . DEFAULT_QUANTITY . "'>";
			}

			$stockstyle    = '';
			$cartstyle     = '';
			$preorderstyle = '';

			if ($preorderdisplay)
			{
				$stockstyle    = 'style="display:none"';
				$cartstyle     = 'style="display:none"';
				$preorderstyle = '';

				if (USE_AS_CATALOG)
				{
					$preorderstyle = 'style="display:none"';

				}
			}

			if ($stockdisplay)
			{
				$stockstyle = '';

				if (USE_AS_CATALOG)
				{
					$stockstyle = 'style="display:none"';

				}

				$cartstyle     = 'style="display:none"';
				$preorderstyle = 'style="display:none"';

			}

			if ($cartdisplay)
			{
				$stockstyle    = 'style="display:none"';
				$cartstyle     = '';
				$preorderstyle = 'style="display:none"';

				if (USE_AS_CATALOG)
				{
					$cartstyle = 'style="display:none"';

				}
			}

			$cartTag   = '';
			$cartIcon  = '';
			$cartTitle = ' title="' . $ADD_OR_TOOLTIP . '" ';

			if ($giftcard_id)
				$onclick = ' onclick="if(validateEmail()){if(displayAddtocartForm(\'' . $addtocartFormName
					. '\',\'' . $product_id . '\',\'' . $relproduct_id . '\',\'' . $giftcard_id . '\', \'user_fields_form\')){checkAddtocartValidation(\'' . $addtocartFormName . '\',\'' . $product_id . '\',\'' . $relproduct_id . '\',\'' . $giftcard_id . '\', \'user_fields_form\',\'' . $totalatt . '\',\'' . $totalAccessory . '\',\'' . $count_no_user_field . '\');}}" ';
			else
			{
				$onclick = ' onclick="if(displayAddtocartForm(\'' . $addtocartFormName . '\',\'' . $product_id
					. '\',\'' . $relproduct_id . '\',\'' . $giftcard_id
					. '\', \'user_fields_form\')){checkAddtocartValidation(\'' . $addtocartFormName . '\',\''
					. $product_id . '\',\'' . $relproduct_id . '\',\'' . $giftcard_id . '\', \'user_fields_form\',\''
					. $totalatt . '\',\'' . $totalAccessory . '\',\'' . $count_no_user_field . '\');}" ';
			}

			$class = '';
			$title = '';

			if (strstr($cartform, "{addtocart_tooltip}"))
			{
				$class    = 'class="editlinktip hasTip"';
				$title    = ' title="' . $tooltip . '" ';
				$cartform = str_replace("{addtocart_tooltip}", "", $cartform);
			}

			if (strstr($cartform, "{addtocart_button}"))
			{
				$cartTag = "{addtocart_button}";

				if (AJAX_CART_BOX != 1)
				{
					$cartIcon = '<span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . ' ' . $cartstyle
						. '><input type="button" ' . $onclick . $cartTitle . ' name="addtocart_button" value="'
						. $ADD_OR_LBL . '" /></span>';
				}
				else
				{
					$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
						. ' ><span id="pdaddtocart' . $stockId . '" ' . $class . ' ' . $title . ' ' . $cartstyle
						. '><input type="button" ' . $cartTitle . ' name="addtocart_button" value="' . $ADD_OR_LBL
						. '" /></span></a>';
				}
			}

			if (strstr($cartform, "{addtocart_link}"))
			{
				$cartTag = "{addtocart_link}";

				if (AJAX_CART_BOX != 1)
				{
					$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
						. '" ' . $onclick . $cartTitle . ' style="cursor: pointer;">' . $ADD_OR_LBL . '</span>';
				}
				else
				{
					$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
						. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
						. '" ' . $cartTitle . ' style="cursor: pointer;">' . $ADD_OR_LBL . '</span></a>';
				}
			}

			if (strstr($cartform, "{addtocart_image_aslink}"))
			{
				$cartTag = "{addtocart_image_aslink}";

				if (AJAX_CART_BOX != 1)
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_IMAGE))
						$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '"><img ' . $onclick . $cartTitle . ' alt="' . $ADD_OR_LBL . '" style="cursor: pointer;" src="' . REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_CART_IMAGE . '" /></span>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId . '" ' . $cartTitle . ' style="cursor: pointer;">' . $ADD_OR_LBL . '</span></a>';

				}
				else
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_IMAGE))
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '"><img ' . $cartTitle . ' alt="' . $ADD_OR_LBL . '" style="cursor: pointer;" src="'
							. REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_CART_IMAGE . '" /></span></a>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;">' . $ADD_OR_LBL . '</span></a>';

				}

			}

			if (strstr($cartform, "{addtocart_image}"))
			{
				$cartTag = "{addtocart_image}";

				if (AJAX_CART_BOX != 1)
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_BACKGROUND))
						$cartIcon = '<span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '"><div ' . $onclick . $cartTitle
							. ' align="center" style="cursor:pointer;background:url(' . REDSHOP_FRONT_IMAGES_ABSPATH
							. $ADD_CART_BACKGROUND . ');background-position:bottom;background-repeat:no-repeat;">'
							. $ADD_OR_LBL . '</div></span>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;">' . $ADD_OR_LBL . '</span></a>';

				}
				else
				{
					if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_CART_BACKGROUND))
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '"><div ' . $cartTitle . ' align="center" style="cursor:pointer;background:url('
							. REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_CART_BACKGROUND
							. ');background-position:bottom;background-repeat:no-repeat;">' . $ADD_OR_LBL . '</div></span></a>';
					else
						$cartIcon = '<a class="ajaxcartcolorbox' . $product_id . '"  href="javascript:;" ' . $onclick
							. ' ><span ' . $class . ' ' . $title . ' ' . $cartstyle . ' id="pdaddtocart' . $stockId
							. '" ' . $cartTitle . ' style="cursor: pointer;">' . $ADD_OR_LBL . '</span></a>';

				}
			}
			// pre-Order
			if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . $ADD_OR_PRE_BTN))
			{
				$cartIconPreorder = '<span id="preordercart' . $stockId . '" ' . $preorderstyle . '><img ' . $onclick
					. $cartTitle . ' alt="' . $ADD_OR_PRE_LBL . '" style="cursor: pointer;" src="'
					. REDSHOP_FRONT_IMAGES_ABSPATH . $ADD_OR_PRE_BTN . '" /></span>';
			}
			else
			{
				$cartIconPreorder = '<span id="preordercart' . $stockId . '" ' . $preorderstyle
					. '><a href="javascript:;" ' . $onclick . '>' . JTEXT::_('COM_REDSHOP_PREORDER_BTN') . '</a></span>';
			}

			$cartform = str_replace($cartTag, '<span id="stockaddtocart' . $stockId . '" ' . $stockstyle
				. ' class="stock_addtocart">' . $display_text . '</span>' . $cartIconPreorder . $cartIcon, $cartform);
			$cartform .= "</form>";
			$data_add = str_replace("{form_addtocart:$cart_template->template_name}", $cartform, $data_add);
		}

		return $data_add;
	}

	public function replaceWishlistButton($product_id = 0, $data_add = "")
	{
		$my_wishlist = '';
		$wishtag     = '';
		$wishlist    = '';

		if (strstr($data_add, '{wishlist_button}'))
		{
			$wishtag = '{wishlist_button}';
		}

		if (strstr($data_add, '{wishlist_link}'))
		{
			$wishtag = '{wishlist_link}';
		}

		if (MY_WISHLIST != 0)
		{
			$u           = JFactory::getURI();
			$user        = JFactory::getUser();
			$my_wishlist = '';

			// Product Wishlist - New Feature Like Magento Store
			if ($user->id)
			{ //allow user to send wishlist while user is not logged in
				$mywishlist_link = JURI::root()
					. 'index.php?tmpl=component&option=com_redshop&view=wishlist&task=addtowishlist&tmpl=component&product_id='
					. $product_id;

				if ($wishtag == '{wishlist_button}')
				{
					$wishlist = "<input type='button' value='" . JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . "'>";
				}

				if ($wishtag == '{wishlist_link}')
				{
					$wishlist = JText::_("COM_REDSHOP_ADD_TO_WISHLIST");
				}

				$my_wishlist = "<a class=\"modal\" href=\"" . $mywishlist_link
					. "\" rel=\"{handler:'iframe',size:{x:450,y:350}}\" >" . $wishlist . "</a>";
			}
			else
			{
				$mywishlist_link = JRoute::_('index.php?option=com_redshop&view=wishlist&task=viewloginwishlist&tmpl=component');

				if ($wishtag == '{wishlist_button}')
				{
					$wishlist = "<input type='submit' name='btnwishlist' id='btnwishlist' value='"
						. JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . "'>";
				}

				if ($wishtag == '{wishlist_link}')
				{
					if (WISHLIST_LOGIN_REQUIRED != 0)
					{
						$wishlist = JText::_("COM_REDSHOP_ADD_TO_WISHLIST");
					}
					else
					{
						$wishlist = "<a href='javascript:document.form_wishlist_" . $product_id
							. ".submit();' class='wishlistlink'>" . JText::_("COM_REDSHOP_ADD_TO_WISHLIST") . "</a>";

					}
				}

				if (WISHLIST_LOGIN_REQUIRED != 0)
				{
					$my_wishlist = "<a class=\"modal\" href=\"" . $mywishlist_link
						. "\" rel=\"{handler:'iframe',size:{x:450,y:350}}\" >" . $wishlist . "</a>";
				}
				else
				{
					$my_wishlist = "<form method='post' action='' id='form_wishlist_" . $product_id
						. "' name='form_wishlist_" . $product_id . "'>
								<input type='hidden' name='task' value='addtowishlist' />
							    <input type='hidden' name='product_id' value='" . $product_id . "' />
								<input type='hidden' name='view' value='product' />
								<input type='hidden' name='rurl' value='" . base64_encode($u->toString()) . "' />"
						. $wishlist . "</form>";
				}
			}
		}

		$data_add = str_replace($wishtag, $my_wishlist, $data_add);

		return $data_add;
	}

	public function replaceCompareProductsButton($product_id = 0, $category_id = 0, $data_add = "", $is_relatedproduct = 0)
	{
		$Itemid = JRequest::getInt('Itemid');
		$prefix = ($is_relatedproduct == 1) ? "related" : "";
		// for compare product div...
		if (PRODUCT_COMPARISON_TYPE != "")
		{
			if (strstr($data_add, '{' . $prefix . 'compare_product_div}'))
			{
				$div                 = $this->makeCompareProductDiv();
				$compare_product_div = "<form name='frmCompare' method='post' action='"
					. JRoute::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $Itemid) . "' >";
				$compare_product_div .= "<a href='javascript:compare();' >" . JText::_('COM_REDSHOP_COMPARE')
					. "</a><br />";
				$compare_product_div .= "<div id='divCompareProduct'>" . $div . "</div>";
				$compare_product_div .= "</form>";
				$data_add = str_replace("{compare_product_div}", $compare_product_div, $data_add);
			}

			if (strstr($data_add, '{' . $prefix . 'compare_products_button}'))
			{
				if ($category_id == 0)
				{
					$category_id = $this->getCategoryProduct($product_id);
				}

				$chked           = $this->checkcompareproduct($product_id);
				$compare_product = "<input type='checkbox' id='chk" . $category_id . $product_id . "' " . $chked
					. " onClick='add_to_compare(" . $product_id . "," . $category_id . ")'>"
					. JText::_("COM_REDSHOP_ADD_TO_COMPARE");
				$data_add        = str_replace("{" . $prefix . "compare_products_button}", $compare_product, $data_add);
			}
		}
		else
		{
			$data_add = str_replace("{" . $prefix . "compare_product_div}", "", $data_add);
			$data_add = str_replace("{" . $prefix . "compare_products_button}", "", $data_add);
		}

		return $data_add;
	}

	public function makeAccessoryCart($attArr = array(), $product_id = 0, $user_id = 0, $data = '')
	{
		$user = JFactory::getUser();

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}
//		$data 					= $this->getcartTemplate();
		$chktag                = $this->getApplyattributeVatOrNot($data, $user_id);
		$setPropEqual          = true;
		$setSubpropEqual       = true;
		$displayaccessory      = "";
		$accessory_total_price = 0;
		$accessory_vat_price   = 0;

		if (count($attArr) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>" . JText::_("COM_REDSHOP_ACCESSORY") . "</div>";

			for ($i = 0; $i < count($attArr); $i++)
			{
				$acc_vat = 0;

				if ($attArr[$i]['accessory_price'] > 0)
				{
					$acc_vat = $this->getProducttax($product_id, $attArr[$i]['accessory_price'], $user_id);
				}

				$accessory_price = $attArr[$i]['accessory_price'];

				if (!empty($chktag))
				{
					$accessory_price     = $attArr[$i]['accessory_price'] + $acc_vat;
					$accessory_vat_price = $acc_vat;
				}

				$displayPrice = " (" . $this->getProductFormattedPrice($accessory_price) . ")";

				if (DEFAULT_QUOTATION_MODE && !SHOW_QUOTATION_PRICE)
				{
					$displayPrice = "";
				}

				$displayaccessory .= "<div class='checkout_accessory_title'>" . urldecode($attArr[$i]['accessory_name'])
					. $displayPrice . "</div>";
				$attchildArr = $attArr[$i]['accessory_childs'];

				for ($j = 0; $j < count($attchildArr); $j++)
				{
					$prooprand      = array();
					$proprice       = array();
					$provatprice    = array();
					$provat         = array();
					$subprooprand   = array();
					$subproprice    = array();
					$subprovatprice = array();
					$subprovat      = array();

					$attribute            = $this->getProductAttribute(0, 0, $attchildArr[$j]['attribute_id']);
					$hide_attribute_price = 0;

					if (count($attribute) > 0)
					{
						$hide_attribute_price = $attribute[0]->hide_attribute_price;
					}

					$propArr = $attchildArr[$j]['attribute_childs'];

					if (count($propArr) > 0)
					{
						$displayaccessory .= "<div class='checkout_attribute_title'>"
							. urldecode($attchildArr[$j]['attribute_name']) . ":</div>";
					}

					for ($k = 0; $k < count($propArr); $k++)
					{
						$property_price = $propArr[$k]['property_price'];
						$acc_vat        = 0;
						$acc_propvat    = 0;

						if ($propArr[$k]['property_price'] > 0)
						{
							$acc_propvat = $this->getProducttax($product_id, $propArr[$k]['property_price'], $user_id);
						}

						if (!empty($chktag))
						{
							$property_price = $property_price + $acc_propvat;
							$acc_vat        = $acc_propvat;
						}

						$prooprand[$k]   = $propArr[$k]['property_oprand'];
						$proprice[$k]    = $propArr[$k]['property_price'];
						$provatprice[$k] = $property_price;
						$provat[$k]      = $acc_vat;

						$displayPrice = " (" . $propArr[$k]['property_oprand'] . " "
							. $this->getProductFormattedPrice($property_price) . ")";

						if ((DEFAULT_QUOTATION_MODE && !SHOW_QUOTATION_PRICE) || $hide_attribute_price)
						{
							$displayPrice = "";
						}

						$property      = $this->getAttibuteProperty($propArr[$k]['property_id']);
						$virtualNumber = "";

						if (count($property) > 0 && $property[0]->property_number)
						{
							$virtualNumber = "<div class='checkout_attribute_number'>" . $property[0]->property_number
								. "</div>";
						}
//						if(!strstr($data,'{product_attribute_price}'))
//						{
//							$displayPrice = '';
//						}
//						if(!strstr($data,'{product_attribute_number}'))
//						{
//							$virtualNumber = '';
//						}

						$displayaccessory .= "<div class='checkout_attribute_wrapper'><div class='checkout_attribute_price'>"
							. urldecode($propArr[$k]['property_name']) . $displayPrice . "</div>" . $virtualNumber . "</div>";
						$subpropArr = $propArr[$k]['property_childs'];

						for ($l = 0; $l < count($subpropArr); $l++)
						{
							$acc_vat           = 0;
							$acc_subpropvat    = 0;
							$subproperty_price = $subpropArr[$l]['subproperty_price'];

							if ($subpropArr[$l]['subproperty_price'] > 0)
							{
								$acc_subpropvat = $this->getProducttax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
							}

							$subproperty   = $this->getAttibuteSubProperty($subpropArr[$l]['subproperty_id']);
							$virtualNumber = "";

							if (count($subproperty) > 0 && $subproperty[0]->subattribute_color_number)
							{
								$virtualNumber = "<div class='checkout_subattribute_number'>["
									. $subproperty[0]->subattribute_color_number . "]</div>";
							}

							if (!empty($chktag))
							{
								$subproperty_price = $subproperty_price + $acc_subpropvat;
								$acc_vat           = $acc_subpropvat;
							}

							$displayPrice = " (" . $subpropArr[$l]['subproperty_oprand'] . " "
								. $this->getProductFormattedPrice($subproperty_price) . ")";

							if ((DEFAULT_QUOTATION_MODE && !SHOW_QUOTATION_PRICE) || $hide_attribute_price)
							{
								$displayPrice = "";
							}

//							if(!strstr($data,'{product_attribute_price}'))
//							{
//								$displayPrice = '';
//							}
//							if(!strstr($data,'{product_attribute_number}'))
//							{
//								$virtualNumber = '';
//							}

							$displayaccessory .= "<div class='checkout_subattribute_wrapper'><div class='checkout_subattribute_price'>" . urldecode($subpropArr[$l]['subproperty_name']) . $displayPrice . "</div>" . $virtualNumber . "</div>";
							$subprooprand[$k][$l]   = $subpropArr[$l]['subproperty_oprand'];
							$subproprice[$k][$l]    = $subpropArr[$l]['subproperty_price'];
							$subprovatprice[$k][$l] = $subproperty_price;
							$subprovat[$k][$l]      = $acc_vat;
						}
					}

					/// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
					if ($setPropEqual && $setSubpropEqual)
					{
						$accessory_priceArr = $this->makeTotalPriceByOprand($accessory_price, $prooprand, $provatprice);
						$accessory_vatArr   = $this->makeTotalPriceByOprand($accessory_vat_price, $prooprand, $provat);
						//$setPropEqual = $accessory_priceArr[0];
						$accessory_price     = $accessory_priceArr[1];
						$accessory_vat_price = $accessory_vatArr[1];
					}

					for ($t = 0; $t < count($propArr); $t++)
					{
						$subElementArr = $propArr[$t]['property_childs'];

						if ($setPropEqual && $setSubpropEqual && isset($subprovatprice[$t]))
						{
							$accessory_priceArr = $this->makeTotalPriceByOprand(
								$accessory_price,
								$subprooprand[$t],
								$subprovatprice[$t]
							);
							$accessory_vatArr   = $this->makeTotalPriceByOprand(
								$accessory_vat_price,
								$subprooprand[$t],
								$subprovat[$t]
							);
							$accessory_price     = $accessory_priceArr[1];
							$accessory_vat_price = $accessory_vatArr[1];
						}
					}

					// FOR ACCESSORY PROPERTY AND SUBPROPERTY PRICE CALCULATION
				}

				$accessory_total_price += ($accessory_price);
			}
		}

		$accessory_total_price = $accessory_total_price - $accessory_vat_price;

		return array($displayaccessory, $accessory_total_price, $accessory_vat_price);
	}

	public function getcartTemplate()
	{
		if (empty($this->_cartTemplateData))
		{
			$redTemplate = new Redtemplate();

			if (!USE_AS_CATALOG || USE_AS_CATALOG)
				$this->_cartTemplateData = $redTemplate->getTemplate("cart");
			else
				$this->_cartTemplateData = $redTemplate->getTemplate("catalogue_cart");
		}

		return $this->_cartTemplateData;
	}

	public function makeAttributeCart($attArr = array(), $product_id = 0, $user_id = 0, $new_product_price = 0, $quantity = 1, $data = '')
	{
		$user            = JFactory::getUser();
		$cart            = $this->_session->get('cart');
		$stockroomhelper = new rsstockroomhelper();
		$product         = $this->getProductById($product_id);

		if ($user_id == 0)
		{
			$user_id = $user->id;
		}

		$sel                  = 0;
		$selP                 = 0;
		$chktag               = $this->getApplyattributeVatOrNot($data, $user_id);
		$setPropEqual         = true;
		$setSubpropEqual      = true;
		$displayattribute     = "";
		$selectedAttributs    = array();
		$selectedProperty     = array();
		$productOldprice      = 0;
		$product_vat_price    = 0;
		$product_vat_Oldprice = 0;

		if ($new_product_price != 0)
		{
			$product_price = $new_product_price;

			if ($product_price > 0)
			{
				$product_vat_price = $this->getProductTax($product_id, $product_price, $user_id);
			}

			if ((DEFAULT_QUOTATION_MODE || $cart['quotation'] == 1 || $product->use_discount_calc) && $chktag)
			{
				$product_price += $product_vat_price;
			}
		}
		else
		{
			$productArr        = $this->getProductNetPrice($product_id, $user_id, $quantity, $data);
			$product_price     = $productArr['product_price'];
			$product_vat_price = $productArr['productVat'];
			$productOldprice   = $productArr['product_old_price_excl_vat'];
		}

		$isStock         = $stockroomhelper->isStockExists($product_id);
		$isPreorderStock = $stockroomhelper->isPreorderStockExists($product_id);

		for ($i = 0; $i < count($attArr); $i++)
		{
			$prooprand      = array();
			$proprice       = array();
			$provatprice    = array();
			$provat         = array();
			$subprooprand   = array();
			$subproprice    = array();
			$subprovatprice = array();
			$subprovat      = array();
			$attribute      = $this->getProductAttribute(0, 0, $attArr[$i]['attribute_id']);

			$hide_attribute_price = 0;

			if (count($attribute) > 0)
			{
				$hide_attribute_price = $attribute[0]->hide_attribute_price;
			}

			$propArr = $attArr[$i]['attribute_childs'];

			if (count($propArr) > 0)
			{
				$displayattribute .= "<div class='checkout_attribute_title'>" . urldecode($attArr[$i]['attribute_name'])
					. ":</div>";
			}

			for ($k = 0; $k < count($propArr); $k++)
			{
				$att_vat = 0;

				if (isset($propArr[$k]['property_price']) === false)
				{
					$propArr[$k]['property_price'] = 0;
				}

				if ($propArr[$k]['property_price'] > 0)
				{
					$att_vat = $this->getProducttax($product_id, $propArr[$k]['property_price'], $user_id);
				}

				$property       = $this->getAttibuteProperty($propArr[$k]['property_id']);
				$property_price = $propArr[$k]['property_price'];

				if (!empty($chktag))
				{
					$property_price = $property_price + $att_vat;
				}

				$displayPrice = " (" . $propArr[$k]['property_oprand'] . " " . $this->getProductFormattedPrice($property_price) . ")";

				if ((DEFAULT_QUOTATION_MODE && !SHOW_QUOTATION_PRICE) || $hide_attribute_price)
				{
					$displayPrice = "";
				}

				$virtualNumber = "";

				if (count($property) > 0 && $property[0]->property_number)
				{
					$virtualNumber = "<div class='checkout_attribute_number'>" . $property[0]->property_number . "</div>";
				}

				$isStock         = $stockroomhelper->isStockExists($propArr[$k]['property_id'], "property");
				$isPreorderStock = $stockroomhelper->isPreorderStockExists($propArr[$k]['property_id'], "property");

				if (!strstr($data, '{product_attribute_price}'))
				{
					$displayPrice = '';
				}

				if (!strstr($data, '{product_attribute_number}'))
				{
					$virtualNumber = '';
				}

				$displayattribute .= "<div class='checkout_attribute_wrapper'><div class='checkout_attribute_price'>"
					. urldecode($propArr[$k]['property_name']) . $displayPrice . "</div>" . $virtualNumber . "</div>";
				$prooprand[$k]   = $propArr[$k]['property_oprand'];
				$proprice[$k]    = $propArr[$k]['property_price'];
				$provatprice[$k] = $property_price;
				$provat[$k]      = $att_vat;
				$subpropArr      = $propArr[$k]['property_childs'];

				if (count($subpropArr) > 0)
				{
					$displayattribute .= "<div class='checkout_subattribute_title'>"
						. urldecode($subpropArr[0]['subattribute_color_title']) . "</div>";
				}

				for ($l = 0; $l < count($subpropArr); $l++)
				{
					$att_vat = 0;

					if ($l == 0)
					{
						$selectedProperty[$selP++] = $propArr[$k]['property_id'];
					}

					if ($subpropArr[$l]['subproperty_price'] > 0)
					{
						$att_vat = $this->getProducttax($product_id, $subpropArr[$l]['subproperty_price'], $user_id);
					}

					$subproperty_price = $subpropArr[$l]['subproperty_price'];

					if (!empty($chktag))
					{
						$subproperty_price = $subproperty_price + $att_vat;
					}

					$displayPrice = " (" . $subpropArr[$l]['subproperty_oprand'] . " "
						. $this->getProductFormattedPrice($subproperty_price) . ")";

					if ((DEFAULT_QUOTATION_MODE && !SHOW_QUOTATION_PRICE) || $hide_attribute_price)
					{
						$displayPrice = "";
					}

					$subproperty   = $this->getAttibuteSubProperty($subpropArr[$l]['subproperty_id']);
					$virtualNumber = "";

					if (count($subproperty) > 0 && $subproperty[0]->subattribute_color_number)
					{
						$virtualNumber = "<div class='checkout_subattribute_number'>["
							. $subproperty[0]->subattribute_color_number . "]</div>";
					}

					$isStock         = $stockroomhelper->isStockExists(
						$subpropArr[$l]['subproperty_id'],
						"subproperty"
					);
					$isPreorderStock = $stockroomhelper->isPreorderStockExists(
						$subpropArr[$l]['subproperty_id'],
						"subproperty"
					);

					if (!strstr($data, '{product_attribute_price}'))
					{
						$displayPrice = '';
					}

					if (!strstr($data, '{product_attribute_number}'))
					{
						$virtualNumber = '';
					}
					$displayattribute .= "<div class='checkout_subattribute_wrapper'><div class='checkout_subattribute_price'>" . urldecode($subpropArr[$l]['subproperty_name']) . $displayPrice . "</div>" . $virtualNumber . "</div>";
					$subprooprand[$k][$l]   = $subpropArr[$l]['subproperty_oprand'];
					$subproprice[$k][$l]    = $subpropArr[$l]['subproperty_price'];
					$subprovatprice[$k][$l] = $subproperty_price;
					$subprovat[$k][$l]      = $att_vat;
				}
			}

			// FOR PROPERTY AND SUBPROPERTY PRICE CALCULATION
			if ($setPropEqual && $setSubpropEqual)
			{
				$accessory_priceArr    = $this->makeTotalPriceByOprand($product_price, $prooprand, $provatprice);
				$accessory_vatArr      = $this->makeTotalPriceByOprand($product_vat_price, $prooprand, $provat);
				$accessory_oldpriceArr = $this->makeTotalPriceByOprand($productOldprice, $prooprand, $proprice);
				$product_price     = $accessory_priceArr[1];
				$product_vat_price = $accessory_vatArr[1];
				$productOldprice   = $accessory_oldpriceArr[1];
			}

			for ($t = 0; $t < count($propArr); $t++)
			{
				$subElementArr             = $propArr[$t]['property_childs'];
				$selectedAttributs[$sel++] = $attArr[$i]['attribute_id'];

				if ($setPropEqual && $setSubpropEqual && isset($subprovatprice[$t]))
				{
					$accessory_priceArr    = $this->makeTotalPriceByOprand(
						$product_price,
						$subprooprand[$t],
						$subprovatprice[$t]
					);
					$accessory_vatArr      = $this->makeTotalPriceByOprand(
						$product_vat_price,
						$subprooprand[$t],
						$subprovat[$t]
					);
					$accessory_oldpriceArr = $this->makeTotalPriceByOprand(
						$productOldprice,
						$subprooprand[$t],
						$subproprice[$t]
					);
					$product_price     = $accessory_priceArr[1];
					$product_vat_price = $accessory_vatArr[1];
					$productOldprice   = $accessory_oldpriceArr[1];
				}
			}
			/// FOR PROPERTY AND SUBPROPERTY PRICE CALCULATION
		}

		if ($displayattribute != "")
		{
			$displayattribute = "<div class='checkout_attribute_static'>"
				. JText::_("COM_REDSHOP_ATTRIBUTE")
				. "</div>"
				. $displayattribute;
		}

		if ($productOldprice > 0)
		{
			$product_vat_Oldprice = $this->getProductTax($product_id, $productOldprice, $user_id);
		}

		$applytax = $this->getApplyVatOrNot($data, $user_id);

		if ($applytax)
		{
			$product_price = $product_price - $product_vat_price;
		}

		return array(
			$displayattribute,
			$product_price,
			$product_vat_price,
			$selectedAttributs,
			$isStock,
			$productOldprice,
			$product_vat_Oldprice,
			$isPreorderStock,
			$selectedProperty
		);
	}

	public function makeAccessoryOrder($order_item_id = 0)
	{
		$order_functions  = new order_functions();
		$displayaccessory = "";
		$orderItemdata    = $order_functions->getOrderItemAccessoryDetail($order_item_id);

		if (count($orderItemdata) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>"
				. JText::_("COM_REDSHOP_ACCESSORY") . ":</div>";

			for ($i = 0; $i < count($orderItemdata); $i++)
			{
				$accessory_quantity = " [" . JText::_('COM_REDSHOP_ACCESSORY_QUANTITY_LBL') . " "
					. $orderItemdata[$i]->product_quantity . "] ";
				$displayaccessory .= "<div class='checkout_accessory_title'>"
					. urldecode($orderItemdata[$i]->order_acc_item_name)
					. " ("
					. $this->getProductFormattedPrice($orderItemdata[$i]->order_acc_price + $orderItemdata[$i]->order_acc_vat)
					. ")" . $accessory_quantity . "</div>";
				$makeAttributeOrder = $this->makeAttributeOrder($order_item_id, 1, $orderItemdata[$i]->product_id);
				$displayaccessory   .= $makeAttributeOrder->product_attribute;
			}
		}
		else
		{
			$orderItemdata    = $order_functions->getOrderItemDetail(0, 0, $order_item_id);
			$displayaccessory = $orderItemdata[0]->product_accessory;
		}

		return $displayaccessory;
	}

	public function makeAttributeOrder($order_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $stock = 0, $export = 0, $data = '')
	{
		$stockroomhelper   = new rsstockroomhelper;
		$order_functions   = new order_functions;
		$displayattribute  = "";
		$chktag            = $this->getApplyattributeVatOrNot($data);
		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$orderItemdata     = $order_functions->getOrderItemDetail(0, 0, $order_item_id);

		$products = $this->getProductById($orderItemdata[0]->product_id);

		if (count($orderItemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $orderItemdata[0]->product_attribute;
			$quantity          = $orderItemdata[0]->product_quantity;
			$stockroom_id      = $orderItemdata[0]->stockroom_id;
		}

		$orderItemAttdata = $order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "attribute", $parent_section_id);

		// Get Attribute middle template
		$attribute_middle_template = $this->getAttributeTemplateLoop($data);
		$attribute_final_template = '';

		if (count($orderItemAttdata) > 0)
		{
			for ($i = 0; $i < count($orderItemAttdata); $i++)
			{
				$attribute            = $this->getProductAttribute(0, 0, $orderItemAttdata[$i]->section_id);
				$hide_attribute_price = 0;

				if (count($attribute) > 0)
				{
					$hide_attribute_price = $attribute[0]->hide_attribute_price;
				}

				if (!strstr($data, '{remove_product_attribute_title}'))
				{
					$displayattribute .= "<div class='checkout_attribute_title'>" . urldecode($orderItemAttdata[$i]->section_name) . "</div>";
				}

				// Assign Attribute middle template in tmp variable
				$tmp_attribute_middle_template = $attribute_middle_template;
				$tmp_attribute_middle_template = str_replace("{product_attribute_name}", urldecode($orderItemAttdata[$i]->section_name), $tmp_attribute_middle_template);

				$orderPropdata = $order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "property", $orderItemAttdata[$i]->section_id);

				for ($p = 0; $p < count($orderPropdata); $p++)
				{
					$property_price = $orderPropdata[$p]->section_price;

					if ($stock == 1)
					{
						$stockroomhelper->manageStockAmount($orderPropdata[$p]->section_id, $quantity, $orderPropdata[$p]->stockroom_id, "property");
					}

					$property      = $this->getAttibuteProperty($orderPropdata[$p]->section_id);
					$virtualNumber = "";

					if (count($property) > 0 && $property[0]->property_number)
					{
						$virtualNumber = "<div class='checkout_attribute_number'>" . $property[0]->property_number . "</div>";
					}

					if (!empty($chktag))
					{
						$property_price = $orderPropdata[$p]->section_price + $orderPropdata[$p]->section_vat;
					}

					if ($export == 1)
					{
						$disPrice = " (" . $orderPropdata[$p]->section_oprand . REDCURRENCY_SYMBOL . $property_price . ")";
					}
					else
					{
						$disPrice = "";

						if (!$hide_attribute_price)
						{
							$disPrice = " (" . $orderPropdata[$p]->section_oprand . $this->getProductFormattedPrice($property_price) . ")";
						}

						if (!strstr($data, '{product_attribute_price}'))
						{
							$disPrice = '';
						}

						if (!strstr($data, '{product_attribute_number}'))
						{
							$virtualNumber = '';
						}
					}

					$displayattribute .= "<div class='checkout_attribute_wrapper'><div class='checkout_attribute_price'>" . urldecode($orderPropdata[$p]->section_name) . $disPrice . "</div>" . $virtualNumber . "</div>";

					// Replace attribute property price and value
					$tmp_attribute_middle_template = str_replace("{product_attribute_value}", urldecode($orderPropdata[$p]->section_name), $tmp_attribute_middle_template);
					$tmp_attribute_middle_template = str_replace("{product_attribute_value_price}", $disPrice, $tmp_attribute_middle_template);

					// Assign tmp variable to looping variable to get copy of all texts
					$attribute_final_template .= $tmp_attribute_middle_template;

					$orderSubpropdata = $order_functions->getOrderItemAttributeDetail($order_item_id, $is_accessory, "subproperty", $orderPropdata[$p]->section_id);

					for ($sp = 0; $sp < count($orderSubpropdata); $sp++)
					{
						$subproperty_price = $orderSubpropdata[$sp]->section_price;

						if ($stock == 1)
						{
							$stockroomhelper->manageStockAmount($orderSubpropdata[$sp]->section_id, $quantity, $orderSubpropdata[$sp]->stockroom_id, "subproperty");
						}

						$subproperty   = $this->getAttibuteSubProperty($orderSubpropdata[$sp]->section_id);
						$virtualNumber = "";

						if (count($subproperty) > 0 && $subproperty[0]->subattribute_color_number)
						{
							$virtualNumber = "<div class='checkout_subattribute_number'>[" . $subproperty[0]->subattribute_color_number . "]</div>";
						}

						if (!empty($chktag))
						{
							$subproperty_price = $orderSubpropdata[$sp]->section_price + $orderSubpropdata[$sp]->section_vat;
						}

						if ($export == 1)
						{
							$disPrice = " (" . $orderSubpropdata[$sp]->section_oprand . REDCURRENCY_SYMBOL . $subproperty_price . ")";
						}
						else
						{
							$disPrice = "";

							if (!$hide_attribute_price)
							{
								$disPrice = " (" . $orderSubpropdata[$sp]->section_oprand . $this->getProductFormattedPrice($subproperty_price) . ")";
							}

							if (!strstr($data, '{product_attribute_price}'))
							{
								$disPrice = '';
							}

							if (!strstr($data, '{product_attribute_number}'))
							{
								$virtualNumber = '';
							}
						}

						if (!strstr($data, '{remove_product_subattribute_title}'))
						{
							$displayattribute .= "<div class='checkout_subattribute_title'>" . urldecode($subproperty[0]->subattribute_color_title) . " : </div>";
						}

						$displayattribute .= "<div class='checkout_subattribute_wrapper'><div class='checkout_subattribute_price'>" . urldecode($orderSubpropdata[$sp]->section_name) . $disPrice . "</div>" . $virtualNumber . "</div>";
					}
				}
			}
		}
		else
		{
			$displayattribute = $product_attribute;
		}

		if ($products->use_discount_calc == 1)
		{
			$displayattribute = $displayattribute . $orderItemdata[0]->discount_calc_data;
		}

		$data = new stdClass;
		$data->product_attribute = $displayattribute;
		$data->attribute_middle_template = $attribute_final_template;
		$data->attribute_middle_template_core = $attribute_middle_template;

		return $data;
	}

	/**
	 * Method to get string between inputs
	 *
	 * @param   string  $start   Starting string where you need to start search
	 * @param   string  $end     Ending string where you need to end search
	 * @param   string  $string  Target string from where need to search
	 *
	 * @return  array           Matched string array
	 */
	function findStringBetween($start, $end, $string)
	{
		preg_match_all('/' . preg_quote($start, '/') . '([^\.)]+)' . preg_quote($end, '/') . '/i', $string, $m);

		return $m[1];
	}

	/**
	 * Method to get attribute template loop
	 *
	 * @param   string  $template  Attribute Template data
	 *
	 * @return  string             Template middle data
	 */
	public function getAttributeTemplateLoop($template)
	{
		$start   = "{product_attribute_loop_start}";
		$end     = "{product_attribute_loop_end}";
		$matches = $this->findStringBetween($start, $end, $template);

		$template_middle = '';

		if (count($matches) > 0)
		{
			$template_middle = $matches[0];
		}

		return $template_middle;
	}

	public function makeAccessoryQuotation($quotation_item_id = 0, $quotation_status = 2)
	{
		$quotationHelper  = new quotationHelper;
		$displayaccessory = "";
		$Itemdata         = $quotationHelper->getQuotationItemAccessoryDetail($quotation_item_id);

		if (count($Itemdata) > 0)
		{
			$displayaccessory .= "<div class='checkout_accessory_static'>" . JText::_("COM_REDSHOP_ACCESSORY") . ":</div>";

			for ($i = 0; $i < count($Itemdata); $i++)
			{
				$displayaccessory .= "<div class='checkout_accessory_title'>" . urldecode($Itemdata[$i]->accessory_item_name) . " ";

				if ($quotation_status != 1 || ($quotation_status == 1 && SHOW_QUOTATION_PRICE == 1))
				{
					$displayaccessory .= "(" . $this->getProductFormattedPrice($Itemdata[$i]->accessory_price + $Itemdata[$i]->accessory_vat) . ")";
				}

				$displayaccessory .= "</div>";
				$displayaccessory .= $this->makeAttributeQuotation(
					$quotation_item_id,
					1,
					$Itemdata[$i]->accessory_id,
					$quotation_status
				);

			}
		}
		else
		{
			$Itemdata         = $quotationHelper->getQuotationProduct(0, $quotation_item_id);
			$displayaccessory = $Itemdata[0]->product_accessory;
		}

		return $displayaccessory;
	}

	public function makeAttributeQuotation($quotation_item_id = 0, $is_accessory = 0, $parent_section_id = 0, $quotation_status = 2, $stock = 0)
	{
		$quotationHelper  = new quotationHelper();
		$displayattribute = "";

		$product_attribute = "";
		$quantity          = 0;
		$stockroom_id      = "0";
		$Itemdata          = $quotationHelper->getQuotationProduct(0, $quotation_item_id);

		if (count($Itemdata) > 0 && $is_accessory != 1)
		{
			$product_attribute = $Itemdata[0]->product_attribute;
			$quantity          = $Itemdata[0]->product_quantity;
		}

		$ItemAttdata = $quotationHelper->getQuotationItemAttributeDetail(
			$quotation_item_id,
			$is_accessory,
			"attribute",
			$parent_section_id
		);

		if (count($ItemAttdata) > 0)
		{
			for ($i = 0; $i < count($ItemAttdata); $i++)
			{
				$displayattribute .= "<div class='checkout_attribute_title'>"
					. urldecode($ItemAttdata[$i]->section_name) . "</div>";
				$propdata = $quotationHelper->getQuotationItemAttributeDetail(
					$quotation_item_id,
					$is_accessory,
					"property",
					$ItemAttdata[$i]->section_id
				);

				for ($p = 0; $p < count($propdata); $p++)
				{
					$displayattribute .= "<div class='checkout_attribute_price'>"
						. urldecode($propdata[$p]->section_name) . " ";

					if ($quotation_status != 1 || ($quotation_status == 1 && SHOW_QUOTATION_PRICE == 1))
					{
						$propertyOprand       = $propdata[$p]->section_oprand;
						$propertyPrice        = $this->getProductFormattedPrice($propdata[$p]->section_price);
						$propertyPriceWithVat = $this->getProductFormattedPrice($propdata[$p]->section_price + $propdata[$p]->section_vat);

						$displayattribute .= "( $propertyOprand $propertyPrice excl. vat / $propertyPriceWithVat)";
					}

					$displayattribute .= "</div>";
					$subpropdata = $quotationHelper->getQuotationItemAttributeDetail(
						$quotation_item_id,
						$is_accessory,
						"subproperty",
						$propdata[$p]->section_id
					);

					for ($sp = 0; $sp < count($subpropdata); $sp++)
					{
						$displayattribute .= "<div class='checkout_subattribute_price'>"
							. urldecode($subpropdata[$sp]->section_name) . " ";

						if ($quotation_status != 1 || ($quotation_status == 1 && SHOW_QUOTATION_PRICE == 1))
						{
							$subpropertyOprand       = $subpropdata[$sp]->section_oprand;
							$subpropertyPrice        = $this->getProductFormattedPrice($subpropdata[$sp]->section_price);
							$subpropertyPriceWithVat = $this->getProductFormattedPrice($subpropdata[$sp]->section_price + $subpropdata[$sp]->section_vat);

							$displayattribute .= "( $subpropertyOprand $subpropertyPrice excl. vat $subpropertyPriceWithVat)";
						}

						$displayattribute .= "</div>";
					}
				}
			}
		}
		else
		{
			$displayattribute = $product_attribute;
		}

		return $displayattribute;
	}

	/*
	 * load Products Under categoriesd ACL Sopper Group
	 *
	 *  return : "," separated product string
	 */
	public function loadAclProducts()
	{
		$user    = JFactory::getUser();
		$userArr = $this->_session->get('rs_user');

		if (empty($userArr))
		{
			$userArr = $this->_userhelper->createUserSession($userid);
		}

		$shopperGroupId = $userArr['rs_user_shopperGroup'];
		//$shopperGroupId = $this->_userhelper->getShopperGroup($user->id);

		if ($user->id > 0)
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg LEFT JOIN #__redshop_users_info as uf ON sg.`shopper_group_id` = uf.shopper_group_id WHERE uf.user_id = '" . $user->id . "' AND sg.shopper_group_portal=1 ";
		else
			$catquery = "SELECT sg.shopper_group_categories FROM `#__redshop_shopper_group` as sg WHERE  sg.`shopper_group_id` = '" . $shopperGroupId . "' AND sg.shopper_group_portal=1";

		$this->_db->setQuery($catquery);
		$category_ids_obj = $this->_db->loadObjectList();
		if(empty($category_ids_obj))
		{
			$category_ids = "''";
		}
		else
		{
			$category_ids = $category_ids_obj[0]->shopper_group_categories;
		}

		$query = "SELECT product_id
						FROM `#__redshop_product_category_xref` WHERE category_id IN (" . $category_ids . ")";

		$this->_db->setQuery($query);
		$shopperprodata = $this->_db->loadObjectList();
		$aclProduct     = array();

		for ($i = 0; $i < count($shopperprodata); $i++)
		{
			$aclProduct[] = $shopperprodata[$i]->product_id;
		}

		if (count($aclProduct) > 0)
			$aclProduct = implode(",", $aclProduct);
		else
			$aclProduct = "";

		return $aclProduct;
	}

	/*
	 * redSHOP Unit conversation
	 * @params: $globalUnit
	 * $params: $calcUnit
	 *
	 * $globalUnit: base conversation unit
	 * $calcUnit: Unit ratio which to convert
	 */

	public function getUnitConversation($globalUnit, $calcUnit)
	{
		/*
		 * calculation for setting unit value
		 */
		$unit = 1;
		switch ($calcUnit)
		{
			case "mm":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 1;
						break;

					case "cm":
						$unit = 0.1;
						break;

					case "m":
						$unit = 0.001;
						break;

					case "inch":
						$unit = 0.0393700787;
						break;
					case "feet":
						$unit = 0.0032808399;
						break;
				}

				break;

			case "cm":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 10;
						break;

					case "cm":
						$unit = 1;
						break;

					case "m":
						$unit = 0.01;
						break;

					case "inch":
						$unit = 0.393700787;
						break;
					case "feet":
						$unit = 0.032808399;
						break;
				}

				break;

			case "m":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 1000;
						break;

					case "cm":
						$unit = 100;
						break;

					case "m":
						$unit = 1;
						break;

					case "inch":
						$unit = 39.3700787;
						break;
					case "feet":
						$unit = 3.2808399;
						break;
				}

				break;

			case "inch":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 25.4;
						break;

					case "cm":
						$unit = 2.54;
						break;

					case "m":
						$unit = 0.0254;
						break;

					case "inch":
						$unit = 1;
						break;
					case "feet":
						$unit = 0.0833333333;
						break;
				}

				break;

			case "feet":

				switch ($globalUnit)
				{
					case "mm":
						$unit = 304.8;
						break;

					case "cm":
						$unit = 30.48;
						break;

					case "m":
						$unit = 0.3048;
						break;

					case "inch":
						$unit = 12;
						break;
					case "feet":
						$unit = 1;
						break;
				}

				break;

			case "kg":

				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						$unit = 2.20462262;
						break;

					case "gram":
						$unit = 1000;
						break;

					case "kg":
						$unit = 1;
						break;
				}

				break;

			case "pounds":
			case "lbs":

				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						$unit = 1;
						break;

					case "gram":
						$unit = 453.59237;
						break;

					case "kg":
						$unit = 0.45359237;
						break;
				}

				break;

			case "gram":

				switch ($globalUnit)
				{
					case "pounds":
					case "lbs":
						$unit = 0.00220462262;
						break;

					case "gram":
						$unit = 1;
						break;

					case "kg":
						$unit = 0.001;
						break;
				}

				break;

		}

		return $unit;
	}

	// Get Product subscription price
	public function getProductSubscriptionDetail($product_id, $subscription_id)
	{
		$query = "SELECT * "
			. " FROM " . $this->_table_prefix . "product_subscription"
			. " WHERE "
			. " product_id = '" . $product_id . "' And subscription_id = '" . $subscription_id . "'";
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	// Get User Product subscription detail
	public function getUserProductSubscriptionDetail($order_item_id)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_subscribe_detail AS p "
			. "LEFT JOIN " . $this->_table_prefix . "product_subscription AS ps ON ps.subscription_id=p.subscription_id "
			. "WHERE order_item_id='" . $order_item_id . "' ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObject();

		return $list;
	}

	public function insertProductDownload($product_id, $user_id, $order_id, $media_name, $serial_number)
	{
		// download data
		$downloadable_product = $this->checkProductDownload($product_id, true); //die();

		$product_download_limit = ($downloadable_product->product_download_limit > 0) ? $downloadable_product->product_download_limit : PRODUCT_DOWNLOAD_LIMIT;

		$product_download_days      = ($downloadable_product->product_download_days > 0) ? $downloadable_product->product_download_days : PRODUCT_DOWNLOAD_DAYS;
		$product_download_clock     = ($downloadable_product->product_download_clock > 0) ? $downloadable_product->product_download_clock : 0;
		$product_download_clock_min = ($downloadable_product->product_download_clock_min > 0) ? $downloadable_product->product_download_clock_min : 0;

		$product_download_days = (date("H") > $product_download_clock && $product_download_days == 0) ? 1 : $product_download_days;

		$product_download_days_time = (time() + ($product_download_days * 24 * 60 * 60));

		$endtime = mktime($product_download_clock, $product_download_clock_min, 0, date("m", $product_download_days_time), date("d", $product_download_days_time), date("Y", $product_download_days_time));

		// if download product is set to infinit
		$endtime = ($downloadable_product->product_download_infinite == 1) ? 0 : $endtime;

		// Generate Download Token
		$token = md5(uniqid(mt_rand(), true));

		$sql = "INSERT INTO " . $this->_table_prefix . "product_download "
			. "(product_id,user_id,order_id, end_date, download_max, download_id, file_name,product_serial_number) "
			. "VALUES('" . $product_id . "', '" . $user_id . "', '" . $order_id . "', "
			. "'" . $endtime . "', '" . $product_download_limit . "', "
			. "'" . $token . "', '" . $media_name . "','" . $serial_number . "')";
		$this->_db->setQuery($sql);
		$this->_db->query();

		return true;
	}

	/*
	 *  Get serial number for downloadable product only retrive one number.
	 */

	public function getProdcutSerialNumber($product_id, $is_used = 0)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_serial_number "
			. "WHERE product_id = '" . $product_id . "' "
			. " AND is_used='" . $is_used . "' "
			. " LIMIT 0,1";
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObject();

		if (count($rs) > 0)
		{
			// Update serial number...
			$this->updateProdcutSerialNumber($rs->serial_id);
		}
		else
		{
			$rs->serial_number = "";
		}

		return $rs;
	}

	/*
	 *  Update used seraial number status
	 */
	public function updateProdcutSerialNumber($serial_id)
	{
		$update_query = "UPDATE " . $this->_table_prefix . "product_serial_number "
			. " SET is_used='1' WHERE serial_id='" . $serial_id . "'";
		$this->_db->setQuery($update_query);
		$this->_db->Query();
	}

	public function getSubscription($product_id = 0)
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product_subscription "
			. "WHERE product_id='" . $product_id . "' "
			. "ORDER BY subscription_id ";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	public function getQuestionAnswer($questionid = 0, $productid = 0, $faq = 0, $front = 0)
	{
		$and = "";

		if ($questionid != 0)
		{
			if ($faq != 0)
			{
				$and .= " AND q.parent_id='" . $questionid . "' ";
			}
			else
			{
				$and .= " AND q.question_id='" . $questionid . "' ";
			}
		}
		else
		{
			$and .= " AND q.product_id='" . $productid . "' AND q.parent_id=0 ";
		}

		if ($front != 0)
		{
			$and .= " AND q.published='1' ";
		}

		$query = "SELECT q.* FROM " . $this->_table_prefix . "customer_question AS q "
			. "WHERE 1=1 "
			. $and
			. "ORDER BY q.ordering ";
		$this->_db->setQuery($query);
		$rs = $this->_db->loadObjectList();

		return $rs;
	}

	public function getProductRating($product_id)
	{
		$url        = JURI::base();
		$avgratings = 0;
		$query      = "SELECT pr.* FROM " . $this->_table_prefix . "product_rating AS pr "
			. "WHERE pr.product_id='" . $product_id . "' AND pr.published=1";
		$this->_db->setQuery($query);
		$allreviews   = $this->_db->loadObjectList();
		$totalreviews = count($allreviews);

		$query = "SELECT SUM(user_rating) AS rating FROM " . $this->_table_prefix . "product_rating AS pr "
			. "WHERE pr.product_id='" . $product_id . "' AND pr.published=1";
		$this->_db->setQuery($query);
		$totalratings = $this->_db->loadResult();

		if ($totalreviews > 0)
		{
			$avgratings = $totalratings / $totalreviews;
		}

		$avgratings           = round($avgratings);
		$final_avgreview_data = "";

		if ($avgratings > 0)
		{
			$final_avgreview_data = '<img src="' . REDSHOP_ADMIN_IMAGES_ABSPATH . 'star_rating/' . $avgratings
				. '.gif" />';
			$final_avgreview_data .= JText::_('COM_REDSHOP_AVG_RATINGS_1') . " " . $totalreviews . " "
				. JText::_('COM_REDSHOP_AVG_RATINGS_2');
		}

		return $final_avgreview_data;
	}

	public function getProductReviewList($product_id)
	{
		$query = "SELECT ui.firstname,ui.lastname,pr.* FROM " . $this->_table_prefix . "product_rating AS pr "
			. "LEFT JOIN " . $this->_table_prefix . "users_info AS ui ON ui.user_id=pr.userid "
			. "WHERE pr.product_id='" . $product_id . "' "
			. "AND pr.published = 1 AND ui.address_type LIKE 'BT' "
			. "ORDER BY pr.favoured DESC";
		$this->_db->setQuery($query);
		$reviews = $this->_db->loadObjectList();

		return $reviews;
	}

	public function calOprandPrice($price_1, $oprand, $price_2)
	{
		switch ($oprand)
		{
			case "+" :
				$price = $price_1 + $price_2;
				break;
			case "-" :
				$price = $price_1 - $price_2;
				break;
			case "*" :
				$price = $price_1 * $price_2;
				break;
			case "/" :
				$price = $price_1 / $price_2;
				break;
			case "=" :
				$price = $price_2;
				break;
			default :
				$price = $price_1;
		}
		return $price;
	}

	/*
	 * 	return checked if product is in session of compare product cart else blank
	 */

	public function checkcompareproduct($product_id)
	{
		$compare_product = $this->_session->get('compare_product');

		if ($product_id != 0)
		{
			if (!$compare_product)
				return "";
			else
			{
				$idx = (int) ($compare_product['idx']);

				for ($i = 0; $i < $idx; $i++)
					if ($compare_product[$i]["product_id"] == $product_id)
						return "checked";

				return "";
			}
		}
	}

	public function makeCompareProductDiv()
	{
		$Itemid          = JRequest::getVar('Itemid');
		$cmd             = JRequest::getVar('cmd');
		$compare_product = $this->_session->get('compare_product');

		if (!$compare_product)
		{
			return;
		}

		$div    = "<ul id='compare_ul'>";
		$moddiv = '`<table border="0" cellpadding="5" cellspacing="0" width="100%">';
		$idx    = (int) ($compare_product['idx']);

		for ($i = 0; $i < $idx; $i++)
		{
			$product    = $this->getProductById($compare_product[$i]["product_id"]);
			$product_id = $compare_product[$i]["product_id"];

			$category_id = $compare_product[$i]["category_id"];

			$product_link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $product_id . '&Itemid='
				. $Itemid);

			$div .= "<li>" . $product->product_name . " <a onClick='javascript:add_to_compare(" . $product_id
				. "," . $category_id . ",\"remove\")' href='javascript:void(0)'>" . JText::_('COM_REDSHOP_DELETE')
				. "</a></li>";
			$moddiv .= '<tr valign="top"><td width="95%"><span><a href="' . $product_link . '">' . $product->product_name
				. '</a></span></td>';
			$moddiv .= '<td width="5%"><span><a href="javascript:void(0);" onClick="javascript:remove_compare('
				. $product_id . ',' . $category_id . ')">' . JText::_('COM_REDSHOP_DELETE') . '</a></span></td></tr>';
		}

		$moddiv .= "</table>";

		/* if function called directly than don't include module div */
		if ($cmd == "")
			$moddiv = "";

		$div .= "</ul><div id='totalCompareProduct' style='display:none;' >" . $idx . "</div>" . $moddiv;

		return $div;
	}

	public function getCompare()
	{
		$compare_product = $this->_session->get('compare_product');

		if (!$compare_product)
		{
			$compare_product = array();
		}

		return $compare_product;
	}

	/*
	 * function which will return product tag array form  given template
	 *
	 */
	public function product_tag($template_id, $section, $template_data)
	{
		$q = "SELECT field_name from " . $this->_table_prefix . "fields where field_section='" . $section . "' ";

		$this->_db->setQuery($q);

		$fields = $this->_db->loadResultArray();

		$tmp1 = explode("{", $template_data);

		$str = array();

		for ($h = 0; $h < count($tmp1); $h++)
		{
			$word = explode("}", $tmp1[$h]);

			if (in_array($word[0], $fields))
				$str[] = $word[0];
		}

		return $str;
	}

	public function getJcommentEditor($product = array(), $data_add = "")
	{
		$app       = JFactory::getApplication();
		$product_reviews = "";
		$product_id      = $product->product_id;

		if ($product_id && !strstr($data_add, "{jcomments off}") && strstr($data_add, "{jcomments on}"))
		{
			$comments = $app->getCfg('absolute_path') . '/components/com_jcomments/jcomments.php';

			if (file_exists($comments))
			{
				require_once $comments;
				$product_reviews = JComments::showComments($product_id, 'com_redshop', $product->product_name);
			}

			$data_add = str_replace("{jcomments on}", $product_reviews, $data_add);
		}

		$data_add = str_replace("{jcomments on}", $product_reviews, $data_add);
		$data_add = str_replace("{jcomments off}", "", $data_add);

		return $data_add;
	}

	public function getSelectedAccessoryArray($data = array())
	{
		$selectedAccessory    = array();
		$selectedAccessoryQua = array();
		$selectedproperty     = array();
		$selectedsubproperty  = array();

		if (isset($data['accessory_data']) && ($data['accessory_data'] != "" && $data['accessory_data'] != 0))
		{
			$accessory_data    = explode("@@", $data['accessory_data']);
			$acc_quantity_data = explode("@@", $data['acc_quantity_data']);

			for ($i = 0; $i < count($accessory_data); $i++)
			{
				if ($accessory_data[$i] != "")
				{
					$selectedAccessory[]    = $accessory_data[$i];
					$selectedAccessoryQua[] = $acc_quantity_data[$i];
				}
			}
		}

		if (isset($data['acc_property_data']) && ($data['acc_property_data'] != "" && $data['acc_property_data'] != 0))
		{
			$acc_property_data = explode('@@', $data['acc_property_data']);

			for ($i = 0; $i < count($acc_property_data); $i++)
			{
				$acc_property_data1 = explode('##', $acc_property_data[$i]);

				for ($ia = 0; $ia < count($acc_property_data1); $ia++)
				{
					$acc_property_data2 = explode(',,', $acc_property_data1[$ia]);

					for ($ip = 0; $ip < count($acc_property_data2); $ip++)
					{
						if ($acc_property_data2[$ip] != "")
						{
							$selectedproperty[] = $acc_property_data2[$ip];
						}
					}
				}
			}
		}

		if (isset($data['acc_subproperty_data']) && ($data['acc_subproperty_data'] != "" && $data['acc_subproperty_data'] != 0))
		{
			$acc_subproperty_data = explode('@@', $data['acc_subproperty_data']);

			for ($i = 0; $i < count($acc_subproperty_data); $i++)
			{
				$acc_subproperty_data1 = @explode('##', $acc_subproperty_data[$i]);

				for ($ia = 0; $ia < count($acc_subproperty_data1); $ia++)
				{
					$acc_subproperty_data2 = @explode(',,', $acc_subproperty_data1[$ia]);

					for ($ip = 0; $ip < count($acc_subproperty_data2); $ip++)
					{
						$acc_subproperty_data3 = explode('::', $acc_subproperty_data2[$ip]);

						for ($isp = 0; $isp < count($acc_subproperty_data3); $isp++)
						{
							if ($acc_subproperty_data3[$isp] != "")
							{
								$selectedsubproperty[] = $acc_subproperty_data3[$isp];
							}
						}
					}
				}
			}
		}

		$ret = array($selectedAccessory, $selectedproperty, $selectedsubproperty, $selectedAccessoryQua);

		return $ret;
	}

	public function getSelectedAttributeArray($data = array())
	{
		$selectedproperty    = array();
		$selectedsubproperty = array();

		if (!empty($data['property_data']))
		{
			$acc_property_data = explode('##', $data['property_data']);

			for ($ia = 0; $ia < count($acc_property_data); $ia++)
			{
				$acc_property_data1 = explode(',,', $acc_property_data[$ia]);

				for ($ip = 0; $ip < count($acc_property_data1); $ip++)
				{
					if ($acc_property_data1[$ip] != "")
					{
						$selectedproperty[] = $acc_property_data1[$ip];
					}
				}
			}
		}

		if (!empty($data['subproperty_data']))
		{
			$acc_subproperty_data = explode('##', $data['subproperty_data']);

			for ($ia = 0; $ia < count($acc_property_data); $ia++)
			{
				$acc_subproperty_data1 = @explode('::', $acc_subproperty_data[$ia]);

				for ($ip = 0; $ip < count($acc_subproperty_data1); $ip++)
				{
					$acc_subproperty_data2 = explode(',,', $acc_subproperty_data1[$ip]);

					for ($isp = 0; $isp < count($acc_subproperty_data2); $isp++)
					{
						if ($acc_subproperty_data2[$isp] != "")
						{
							$selectedsubproperty[] = $acc_subproperty_data2[$isp];
						}
					}
				}
			}
		}

		$ret = array($selectedproperty, $selectedsubproperty);

		return $ret;
	}

	public function replaceProductInStock($product_id = 0, $data_add, $attributes = array(), $attribute_template = array())
	{
		if (count($attribute_template) <= 0)
		{
			$attributes = array();
		}

		$stock_status_flag     = false;
		$totalatt              = count($attributes);
		$Id                    = $product_id;
		$sec                   = "product";
		$selectedpropertyId    = 0;
		$selectedsubpropertyId = 0;

		for ($a = 0; $a < count($attributes); $a++)
		{
			$selectedId = array();
			$property   = $this->getAttibuteProperty(0, $attributes[$a]->attribute_id);

			if ($attributes[$a]->text != "" && count($property) > 0)
			{
				for ($i = 0; $i < count($property); $i++)
				{
					if ($property[$i]->setdefault_selected)
					{
						$selectedId[] = $property[$i]->property_id;
					}
				}

				if (count($selectedId) > 0)
				{
					if ($attributes[$a]->allow_multiple_selection)
					{
						$selectedpropertyId = implode(",", $selectedId);
					}
					else
					{
						$selectedpropertyId = $selectedId[count($selectedId) - 1];
					}

					$Id  = $selectedpropertyId;
					$sec = "property";
				}

				if (count($selectedId) > 0)
				{
					$stock_status_flag = true;
					$i                 = count($selectedId) - 1;
					$subproperty       = $this->getAttibuteSubProperty(0, $selectedId[$i]);
					$selectedId        = array();

					for ($sp = 0; $sp < count($subproperty); $sp++)
					{
						if ($subproperty[$sp]->setdefault_selected)
						{
							$selectedId[] = $subproperty[$sp]->subattribute_color_id;
						}
					}

					if (count($selectedId) > 0)
					{
						if ($subproperty[0]->setmulti_selected)
						{
							$selectedsubpropertyId = implode(",", $selectedId);
						}
						else
						{
							$selectedsubpropertyId = $selectedId[count($selectedId) - 1];
						}

						$Id  = $selectedsubpropertyId;
						$sec = "subproperty";
					}
				}
			}
		}

		$stockroomhelper = new rsstockroomhelper();
		$productinstock  = $stockroomhelper->getStockAmountwithReserve($Id, $sec);

		if ($productinstock == 0)
		{
			$product_detail   = $this->getProductById($product_id);
			$product_preorder = $product_detail->preorder;

			if (($product_preorder == "global" && ALLOW_PRE_ORDER)
				|| ($product_preorder == "yes")
				|| ($product_preorder == "" && ALLOW_PRE_ORDER))
			{
				$productinpreorderstock = $stockroomhelper->getPreorderStockAmountwithReserve($Id, $sec);
			}
		}

		if (strstr($data_add, "{products_in_stock}"))
		{
			$data_add = str_replace("{products_in_stock}", JText::_('COM_REDSHOP_PRODUCT_IN_STOCK_LBL')
				. ' <span id="displayProductInStock' . $product_id . '">' . $productinstock . '</span>', $data_add);
		}

		if (strstr($data_add, "{product_stock_amount_image}"))
		{
			$stockamountList  = $stockroomhelper->getStockAmountImage($Id, $sec, $productinstock);
			$stockamountImage = "";

			if (count($stockamountList) > 0)
			{
				$stockamountImage = '<a class="imgtooltip"><span>';
				$stockamountImage .= '<div class="spnheader">' . JText::_('COM_REDSHOP_STOCK_AMOUNT') . '</div>';
				$stockamountImage .= '<div class="spnalttext" id="stockImageTooltip' . $product_id . '">'
					. $stockamountList[0]->stock_amount_image_tooltip . '</div></span>';
				$stockamountImage .= '<img src="' . JURI::base()
					. 'components/com_redshop/helpers/thumb.php?filename=stockroom/'
					. $stockamountList[0]->stock_amount_image . '&newxsize=' . DEFAULT_STOCKAMOUNT_THUMB_WIDTH
					. '&newysize=' . DEFAULT_STOCKAMOUNT_THUMB_HEIGHT . '&swap=' . USE_IMAGE_SIZE_SWAPPING . '" alt="'
					. $stockamountList[0]->stock_amount_image_tooltip . '" id="stockImage' . $product_id . '" /></a>';
			}

			$data_add = str_replace("{product_stock_amount_image}", $stockamountImage, $data_add);
		}

		return $data_add;
	}

	/*
	 * function to check product is parent
	 * or it has childs
	 *
	 * @return: integer
	 */

	public function getChildProduct($product_id = 0)
	{
		$query = "SELECT product_parent_id,product_id,product_name,product_number FROM " . $this->_table_prefix
			. "product "
			. "WHERE product_parent_id='" . $product_id . "' and published = 1 order by product_id";
		$this->_db->setQuery($query);
		$list = $this->_db->loadObjectlist();

		return $list;
	}

	/*
	 * function to get products parent id
	 *
	 * @return: int
	 */
	public function getMainParentProduct($parent_id)
	{
		$query = "SELECT product_parent_id FROM " . $this->_table_prefix . "product "
			. "WHERE published=1 "
			. "AND product_id='" . $parent_id . "' ";
		$this->_db->setQuery($query);
		$product_parent_id = $this->_db->loadResult();

		if ($product_parent_id != 0)
		{
			$parent_id = $this->getMainParentProduct($product_parent_id);
		}

		return $parent_id;
	}

	public function redpriceDecimal($price)
	{
		return number_format($price, PRICE_DECIMAL, '.', '');
	}

	public function redunitDecimal($price)
	{
		if (defined('UNIT_DECIMAL') && UNIT_DECIMAL != "")
			return number_format($price, UNIT_DECIMAL, '.', '');
		else
			return $price;
	}

	public function isProductDateRange($userfieldArr, $product_id)
	{
		$isEnable = true;

		if (count($userfieldArr) <= 0)
		{
			$isEnable = false;

			return $isEnable;
		}

		$query = "select field_name,field_id from " . $this->_table_prefix . "fields where field_type=15";
		$this->_db->setQuery($query);
		$fieldData = $this->_db->loadObject();

		if (count($fieldData) == 0)
		{
			$isEnable = false;

			return $isEnable;
		}

		$field_name = $fieldData->field_name;

		if (is_array($userfieldArr))
		{
			if (in_array($field_name, $userfieldArr))
			{
				$field_id  = $fieldData->field_id;
				$dateQuery = "select data_txt from " . $this->_table_prefix . "fields_data where fieldid = '" . $field_id . "' AND itemid = '" . $product_id . "'";
				$this->_db->setQuery($dateQuery);
				$datedata = $this->_db->loadObject();

				if (count($datedata) > 0)
				{
					$data_txt             = $datedata->data_txt;
					$mainsplit_date_total = preg_split(" ", $data_txt);
					$mainsplit_date       = preg_split(":", $mainsplit_date_total[0]);

					$dateStart  = mktime(
						0,
						0,
						0,
						date('m', $mainsplit_date[0]),
						date('d', $mainsplit_date[0]),
						date('Y', $mainsplit_date[0])
					);
					$dateEnd    = mktime(
						23,
						59,
						59,
						date('m', $mainsplit_date[1]),
						date('d', $mainsplit_date[1]),
						date('Y', $mainsplit_date[1])
					);
					$todayStart = mktime(
						0,
						0,
						0,
						date('m'),
						date('d'),
						date('Y')
					);
					$todayEnd   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));

					if ($dateStart <= $todayStart && $dateEnd >= $todayEnd)
					{
						// Show add to cart button
						$isEnable = false;
					}
				}
				else
				{
					// Show add to cart button
					$isEnable = false;
				}
			}
			else
			{
				// Show add to cart button
				$isEnable = false;
			}
		}
		else
		{
			// Show add to cart button
			$isEnable = false;
		}

		return $isEnable;
	}

	public function getassociatetag($product_id = 0)
	{
		$query = " SELECT a.product_id,at.tag_id,rg.tag_name,ty.type_name FROM  #__redproductfinder_associations as a left outer join #__redproductfinder_association_tag as at on a.id=at.association_id left outer join #__redproductfinder_tags as rg on at.tag_id=rg.id left outer join #__redproductfinder_types as ty on at.type_id=ty.id where a.product_id='" . $product_id . "' ";
		$this->_db->setQuery($query);
		$res = $this->_db->loadObjectlist();

		return $res;
	}

	public function getCategoryCompareTemplate($cid)
	{
		$query = "SELECT t.template_id  FROM " . $this->_table_prefix . "template  AS t "
			. "LEFT JOIN " . $this->_table_prefix . "category  AS c ON c.compare_template_id=t.template_id "
			. "WHERE c.category_id='" . $cid . "' "
			. "AND t.published=1";
		$this->_db->setQuery($query);
		$tmp_name = $this->_db->loadResult();

		return $tmp_name;
	}

	public function getProductCaterories($product_id, $displaylink = 0)
	{
		$prodCatsObjectArray = array();
		$query               = "SELECT  ct.category_name, ct.category_id FROM " . $this->_table_prefix . "category AS ct "
			. "LEFT JOIN " . $this->_table_prefix . "product_category_xref AS pct ON ct.category_id=pct.category_id "
			. "WHERE pct.product_id='" . $product_id . "' "
			. "AND ct.published=1 ";
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();

		for ($i = 0; $i < count($rows); $i++)
		{
			$ppCat = $pCat = '';
			$row   = $rows[$i];

			$query = "SELECT cx.category_parent_id,c.category_name FROM " . $this->_table_prefix . "category_xref AS cx "
				. "LEFT JOIN " . $this->_table_prefix . "category AS c ON cx.category_parent_id=c.category_id "
				. "WHERE cx.category_child_id='" . $row->category_id . "' ";
			$this->_db->setQuery($query);
			$parentCat = $this->_db->loadObject();

			if (count($parentCat) > 0 && $parentCat->category_parent_id)
			{
				$pCat  = $parentCat->category_name;
				$query = "SELECT cx.category_parent_id,c.category_name FROM " . $this->_table_prefix . "category_xref AS cx "
					. "LEFT JOIN " . $this->_table_prefix . "category AS c ON cx.category_parent_id=c.category_id "
					. "WHERE cx.category_child_id='" . $parentCat->category_parent_id . "' ";
				$this->_db->setQuery($query);
				$pparentCat = $this->_db->loadObject();

				if (count($pparentCat) > 0 && $pparentCat->category_parent_id)
				{
					$ppCat = $pparentCat->category_name;
				}
			}

			$spacediv  = (isset($pCat) && $pCat) ? " > " : "";
			$pspacediv = (isset($ppCat) && $ppCat) ? " > " : "";
			$catlink   = '';

			if ($displaylink)
			{
				$redhelper = new redhelper();
				$catItem   = $redhelper->getCategoryItemid($row->category_id);
				$catlink   = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid='
					. $row->category_id . '&Itemid=' . $catItem);
			}

			$prodCatsObject        = new stdClass();
			$prodCatsObject->name  = $ppCat . $pspacediv . $pCat . $spacediv . $row->category_name;
			$prodCatsObject->link  = $catlink;
			$prodCatsObjectArray[] = $prodCatsObject;
		}

		return $prodCatsObjectArray;
	}

	public function getdisplaymainImage($product_id = 0, $property_id = 0, $subproperty_id = 0, $pw_thumb = 0, $ph_thumb = 0, $redview = "")
	{
		$url                 = JURI::base();
		$option              = JRequest::getVar('option');
		$product             = $this->getProductById($product_id);
		$redhelper           = new redhelper();
		$aHrefImageResponse  = '';
		$imagename           = '';
		$aTitleImageResponse = '';
		$mainImageResponse   = '';
		$productmainimg      = '';
		$Arrreturn           = array();
		$product             = $this->getProductById($product_id);
		$type                = '';
		$pr_number           = $product->product_number;
		$attrbimg            = '';
		//$refererpath=explode("view=",$_SERVER['HTTP_REFERER']);
		//$getview=explode("&",$refererpath[1]);

		if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_thumb_image))
		{
			$type                = 'product';
			$imagename           = $product->product_thumb_image;
			$aTitleImageResponse = $product->product_name;
			$attrbimg            = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_thumb_image;
		}
		elseif (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image))
		{
			$type                = 'product';
			$imagename           = $product->product_full_image;
			$aTitleImageResponse = $product->product_name;
			$attrbimg            = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $product->product_full_image;
		}
		else
		{
			if (PRODUCT_DEFAULT_IMAGE && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . PRODUCT_DEFAULT_IMAGE))
			{
				$type                = 'product';
				$imagename           = PRODUCT_DEFAULT_IMAGE;
				$aTitleImageResponse = PRODUCT_DEFAULT_IMAGE;
				$attrbimg            = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . PRODUCT_DEFAULT_IMAGE;
			}
		}

		if ($property_id > 0)
		{
			$property  = $this->getAttibuteProperty($property_id);
			$pr_number = $property[0]->property_number;

			if (count($property) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "property/"
				. $property[0]->property_main_image))
			{
				$type                = 'property';
				$imagename           = $property[0]->property_main_image;
				$aTitleImageResponse = $property[0]->text;
			}
			//Display attribute image in cart
			if (count($property) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product_attributes/"
				. $property[0]->property_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "product_attributes/" . $property[0]->property_image;
			}
		}

		if ($subproperty_id > 0)
		{
			$subproperty = $this->getAttibuteSubProperty($subproperty_id);
			$pr_number   = $subproperty[0]->subattribute_color_number;
			//Display Sub-Property Number
			if (count($subproperty) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "subproperty/"
				. $subproperty[0]->subattribute_color_main_image))
			{
				$type                = 'subproperty';
				$imagename           = $subproperty[0]->subattribute_color_main_image;
				$aTitleImageResponse = $subproperty[0]->text;
				//$attrbimg=REDSHOP_FRONT_IMAGES_ABSPATH."subproperty/".$subproperty[0]->subattribute_color_image;
			}
			//Subproperty image in cart
			if (count($subproperty) > 0 && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "subcolor/"
				. $subproperty[0]->subattribute_color_image))
			{
				$attrbimg = REDSHOP_FRONT_IMAGES_ABSPATH . "subcolor/" . $subproperty[0]->subattribute_color_image;
			}

		}

		if (!empty($imagename) && !empty($type))
		{
			if ((WATERMARK_PRODUCT_THUMB_IMAGE) && $type == 'product')
			{
				$productmainimg = $redhelper->watermark('product', $imagename, $pw_thumb, $ph_thumb, WATERMARK_PRODUCT_THUMB_IMAGE, '0');
			}
			else
			{
				$productmainimg = $url . "components/com_redshop/helpers/thumb.php?filename=$type/" . $imagename
					. "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
			}

			if ((WATERMARK_PRODUCT_IMAGE) && $type == 'product')
			{
				$aHrefImageResponse = $redhelper->watermark('product', $imagename, '', '', WATERMARK_PRODUCT_IMAGE, '0');
			}
			else
			{
				$aHrefImageResponse = REDSHOP_FRONT_IMAGES_ABSPATH . $type . "/" . $imagename;
			}

			$mainImageResponse = "<img id='main_image" . $product_id . "' src='" . $productmainimg . "' alt='"
				. $product->product_name . "' title='" . $product->product_name . "'>";

			if ((!PRODUCT_ADDIMG_IS_LIGHTBOX || !PRODUCT_DETAIL_IS_LIGHTBOX) && $redview != "category")
				$mainImageResponse = $productmainimg;
		}

		$Arrreturn['aHrefImageResponse'] = $aHrefImageResponse;
		$Arrreturn['mainImageResponse']  = $mainImageResponse;
		$Arrreturn['productmainimg']     = $productmainimg;

		$Arrreturn['aTitleImageResponse'] = $aTitleImageResponse;
		$Arrreturn['imagename']           = $imagename;
		$Arrreturn['type']                = $type;
		$Arrreturn['attrbimg']            = $attrbimg;
		$Arrreturn['pr_number']           = $pr_number;

		return $Arrreturn;
	}

	public function displayAdditionalImage($product_id = 0, $accessory_id = 0, $relatedprd_id = 0, $property_id = 0, $subproperty_id = 0, $main_imgwidth = 0, $main_imgheight = 0, $redview = "", $redlayout = "")
	{
		$redshopconfig   = new Redconfiguration ();
		$redTemplate     = new Redtemplate ();
		$stockroomhelper = new rsstockroomhelper();
		$url             = JURI::base();
		$option          = JRequest::getVar('option');
		$redhelper       = new redhelper();

		if ($accessory_id != 0)
		{
			$accessory  = $this->getProductAccessory($accessory_id);
			$product_id = $accessory[0]->child_product_id;
		}

		$product = $this->getProductById($product_id);

		$producttemplate = $redTemplate->getTemplate("product", $product->product_template);

		// Get template for stockroom status
		if ($accessory_id != 0)
		{
			$template_desc = $redTemplate->getTemplate("accessory_product");
			$template_desc = $template_desc[0]->template_desc;
		}
		elseif ($relatedprd_id != 0)
		{
			$template_desc = $redTemplate->getTemplate("related_product");
			$template_desc = $template_desc[0]->template_desc;
		}
		else
		{
			$template_desc = $producttemplate[0]->template_desc;
		}

		$producttemplate = $producttemplate[0]->template_desc;

		if ($redlayout == 'categoryproduct' || $redlayout == 'detail')
		{
			if (strstr($producttemplate, "{product_thumb_image_3}"))
			{
				$pimg_tag = '{product_thumb_image_3}';
				$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_3;
				$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_3;
			}
			elseif (strstr($producttemplate, "{product_thumb_image_2}"))
			{
				$pimg_tag = '{product_thumb_image_2}';
				$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_2;
				$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_2;
			}
			elseif (strstr($producttemplate, "{product_thumb_image_1}"))
			{
				$pimg_tag = '{product_thumb_image_1}';
				$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
				$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
			}
			else
			{
				$pimg_tag = '{product_thumb_image}';
				$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
				$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
			}

		}
		else
		{
			if (strstr($producttemplate, "{product_thumb_image_3}"))
			{
				$pimg_tag = '{product_thumb_image_3}';
				$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_3;
				$pw_thumb = PRODUCT_MAIN_IMAGE_3;
			}
			elseif (strstr($producttemplate, "{product_thumb_image_2}"))
			{
				$pimg_tag = '{product_thumb_image_2}';
				$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_2;
				$pw_thumb = PRODUCT_MAIN_IMAGE_2;
			}
			elseif (strstr($producttemplate, "{product_thumb_image_1}"))
			{
				$pimg_tag = '{product_thumb_image_1}';
				$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
				$pw_thumb = PRODUCT_MAIN_IMAGE;
			}
			else
			{
				$pimg_tag = '{product_thumb_image}';
				$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
				$pw_thumb = PRODUCT_MAIN_IMAGE;
			}
		}

		if (strstr($producttemplate, "{more_images_3}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_3;
		}
		elseif (strstr($producttemplate, "{more_images_2}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_2;
		}
		elseif (strstr($producttemplate, "{more_images_1}"))
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}
		else
		{
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}

		if ($main_imgwidth != 0 || $main_imgheight != 0)
		{
			$pw_thumb = $main_imgwidth;
			$ph_thumb = $main_imgheight;
		}

		$ImageAttributes = $this->getdisplaymainImage($product_id, $property_id, $subproperty_id, $pw_thumb, $ph_thumb, $redview);

		$aHrefImageResponse  = $ImageAttributes['aHrefImageResponse'];
		$mainImageResponse   = $ImageAttributes['mainImageResponse'];
		$productmainimg      = $ImageAttributes['productmainimg'];
		$aTitleImageResponse = $ImageAttributes['aTitleImageResponse'];
		$imagename           = $ImageAttributes['imagename'];
//		$ImageAttributes['type']		= $type;
		$attrbimg  = $ImageAttributes['attrbimg'];
		$pr_number = $ImageAttributes['pr_number'];
		//$view				= $ImageAttributes['view'];

		$prodadditionImg               = "";
		$propadditionImg               = "";
		$subpropadditionImg            = "";
		$product_availability_date_lbl = '';
		$product_availability_date     = '';
		$media_image = $this->getAdditionMediaImage($product_id, "product");
		$tmp_prodimg = "";

		$val_prodadd = count($media_image);

		for ($m = 0; $m < count($media_image); $m++)
		{
			$thumb   = $media_image [$m]->media_name;
			$alttext = $this->getAltText('product', $media_image [$m]->section_id, '', $media_image [$m]->media_id);

			if (!$alttext)
			{
				$alttext = $media_image [$m]->media_name;
			}

			if ($thumb && ($thumb != $media_image [$m]->product_full_image) && is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $thumb))
			{
				if (WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
				{
					$pimg          = $redhelper->watermark('product', $thumb, $mpw_thumb, $mph_thumb, WATERMARK_PRODUCT_ADDITIONAL_IMAGE, "1");
					$linkimage     = $redhelper->watermark('product', $thumb, '', '', WATERMARK_PRODUCT_ADDITIONAL_IMAGE, "0");
					$hoverimg_path = $redhelper->watermark('product', $thumb, ADDITIONAL_HOVER_IMAGE_WIDTH, ADDITIONAL_HOVER_IMAGE_HEIGHT, WATERMARK_PRODUCT_ADDITIONAL_IMAGE, '2');

				}
				else
				{
					$pimg          = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb
						. "&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
					$linkimage     = REDSHOP_FRONT_IMAGES_ABSPATH . "product/" . $thumb;
					$hoverimg_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb
						. "&newxsize=" . ADDITIONAL_HOVER_IMAGE_WIDTH . "&newysize=" . ADDITIONAL_HOVER_IMAGE_HEIGHT
						. "&swap=" . USE_IMAGE_SIZE_SWAPPING;

				}

				if (PRODUCT_ADDIMG_IS_LIGHTBOX)
				{
					$prodadditionImg_div_start = "<div class='additional_image'><a href='" . $linkimage . "' title='"
						. $alttext . "'  rel=\"myallimg\">";
					$prodadditionImg_div_end   = "</a></div>";
					$prodadditionImg .= $prodadditionImg_div_start;
					$prodadditionImg .= "<img src='" . $pimg . "' alt='" . $alttext . "' title='" . $alttext . "'>";
					$producthrefend = "";
				}
				else
				{
					if (WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
						$img_path = $redhelper->watermark('product', $thumb, $pw_thumb, $ph_thumb, WATERMARK_PRODUCT_ADDITIONAL_IMAGE, '0');
					else
						$img_path = $url . "components/com_redshop/helpers/thumb.php?filename=product/" . $thumb
							. "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
					$filename_thumb = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_thumb_image;
					$filename_org   = REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $media_image [$m]->product_full_image;

					if (is_file($filename_thumb))
					{
						$thumb_original = $product->product_thumb_image;
					}
					elseif (is_file($filename_org))
					{
						$thumb_original = $media_image[$m]->product_full_image;
					}
					else
					{
						$thumb_original = PRODUCT_DEFAULT_IMAGE;
					}

					if (WATERMARK_PRODUCT_THUMB_IMAGE)
						$img_path_org = $redhelper->watermark('product', $thumb_original, $pw_thumb, $ph_thumb, WATERMARK_PRODUCT_THUMB_IMAGE, '0');
					else
						$img_path_org = $url . "components/" . $option . "/helpers/thumb.php?filename=product/"
							. $thumb_original . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap="
							. USE_IMAGE_SIZE_SWAPPING;
					$prodadditionImg_div_start = "<div class='additional_image' onmouseover='display_image_add(\""
						. $img_path . "\"," . $product_id . ");' onmouseout='display_image_add_out(\"" . $img_path_org
						. "\"," . $product_id . ");'>";
					$prodadditionImg_div_end   = "</div>";
					$prodadditionImg .= $prodadditionImg_div_start;
					$prodadditionImg .= '<a href="javascript:void(0)" >' . "<img src='" . $pimg . "' alt='" . $alttext
						. "' title='" . $alttext . "' style='cursor: auto;'>";
					$producthrefend = "</a>";
				}

				if (ADDITIONAL_HOVER_IMAGE_ENABLE)
				{
					$prodadditionImg .= "<img src='" . $hoverimg_path . "' alt='" . $alttext . "' title='" . $alttext
						. "' class='redImagepreview'>";
				}

				$prodadditionImg .= $producthrefend;
				$prodadditionImg .= $prodadditionImg_div_end;
				$tmp_prodimg = $prodadditionImg;
			}
		}

		if ($val_prodadd == 0)
		{
			$prodadditionImg = " ";
			$propadditionImg = " ";
		}

		if ($property_id > 0)
		{
			$media_image = $this->getAdditionMediaImage($property_id, "property");

			if (count($media_image) == 0)
			{
				$propadditionImg = $tmp_prodimg;
			}
			else
			{
				for ($m = 0; $m < count($media_image); $m++)
				{
					$thumb   = $media_image [$m]->media_name;
					$alttext = $this->getAltText('property', $media_image [$m]->section_id, '', $media_image [$m]->media_id);

					if (!$alttext)
					{
						$alttext = $thumb;
					}

					if ($thumb
						&& ($thumb != $media_image [$m]->property_main_image)
						&& is_file(REDSHOP_FRONT_IMAGES_RELPATH . "property/" . $thumb))
					{
						if (PRODUCT_ADDIMG_IS_LIGHTBOX)
						{
							$propadditionImg_div_start = "<div class='additional_image'><a href='"
								. REDSHOP_FRONT_IMAGES_ABSPATH . "property/" . $thumb . "' title='" . $alttext
								. "' rel=\"myallimg\">";
							$propadditionImg_div_end   = "</a></div>";
							$propadditionImg .= $propadditionImg_div_start;
							$propadditionImg .= "<img src='" . $url
								. "components/com_redshop/helpers/thumb.php?filename=property/" . $thumb . "&newxsize="
								. $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' alt='"
								. $alttext . "' title='" . $alttext . "'>";
							$prophrefend = "";
						}
						else
						{
							$imgs_path = $url . "components/com_redshop/helpers/thumb.php?filename=property/" . $thumb
								. "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;

							$property_filename_org = REDSHOP_FRONT_IMAGES_RELPATH . "property/" . $imagename;

							if (is_file($property_filename_org))
							{
								$property_thumb_original = $imagename;
								$property_img_path_org   = $url . "components/" . $option
									. "/helpers/thumb.php?filename=property/" . $property_thumb_original . "&newxsize="
									. $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
							}
							else
							{
								$property_thumb_original = $thumb_original;
								$property_img_path_org   = $url . "components/" . $option
									. "/helpers/thumb.php?filename=product/" . $property_thumb_original . "&newxsize="
									. $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
							}

							$propadditionImg_div_start = "<div class='additional_image' onmouseover='display_image_add(\""
								. $imgs_path . "\"," . $product_id . ");' onmouseout='display_image_add_out(\""
								. $property_img_path_org . "\"," . $product_id . ");'>";
							$propadditionImg_div_end   = "</div>";
							$propadditionImg .= $propadditionImg_div_start;
							$propadditionImg .= "<a href='javascript:void(0)'>" . "<img src='" . $url
								. "components/com_redshop/helpers/thumb.php?filename=property/" . $thumb . "&newxsize="
								. $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING
								. "' alt='" . $alttext . "' title='" . $alttext . "' style='cursor: auto;'>";
							$prophrefend = "</a>";
						}

						if (ADDITIONAL_HOVER_IMAGE_ENABLE)
						{
							$propadditionImg .= "<img src='" . $url
								. "components/com_redshop/helpers/thumb.php?filename=property/" . $thumb . "&newxsize="
								. ADDITIONAL_HOVER_IMAGE_WIDTH . "&newysize=" . ADDITIONAL_HOVER_IMAGE_HEIGHT . "&swap="
								. USE_IMAGE_SIZE_SWAPPING . "' alt='" . $alttext . "' title='" . $alttext
								. "' class='redImagepreview'>";
						}

						$propadditionImg .= $prophrefend;
						$propadditionImg .= $propadditionImg_div_end;
					}
				}
			}
		}

		if ($subproperty_id > 0)
		{
			//Display Sub-Property Number
			$media_image = $this->getAdditionMediaImage($subproperty_id, "subproperty");

			for ($m = 0; $m < count($media_image); $m++)
			{
				$thumb   = $media_image [$m]->media_name;
				$alttext = $this->getAltText('subproperty', $media_image [$m]->section_id, '', $media_image [$m]->media_id);

				if (!$alttext)
				{
					$alttext = $thumb;
				}

				$filedir = (is_file(REDSHOP_FRONT_IMAGES_RELPATH . "subproperty/" . $thumb)) ? 'subproperty' : 'property';

				if ($thumb
					&& ($thumb != $media_image [$m]->subattribute_color_main_image)
					&& is_file(REDSHOP_FRONT_IMAGES_RELPATH . $filedir . "/" . $thumb))
				{
					if (PRODUCT_ADDIMG_IS_LIGHTBOX)
					{
						$subpropadditionImg_div_start = "<div class='additional_image'><a href='"
							. REDSHOP_FRONT_IMAGES_ABSPATH . $filedir . "/" . $thumb . "' title='" . $alttext
							. "' rel=\"myallimg\">";
						$subpropadditionImg_div_end   = "</a></div>";
						$subpropadditionImg .= $subpropadditionImg_div_start;
						$subpropadditionImg .= "<img src='" . $url . "components/com_redshop/helpers/thumb.php?filename="
							. $filedir . "/" . $thumb . "&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb . "&swap="
							. USE_IMAGE_SIZE_SWAPPING . "' alt='" . $alttext . "' title='" . $alttext . "'>";
						$subprophrefend = "";
					}
					else
					{
						$imgs_path                = $url . "components/com_redshop/helpers/thumb.php?filename="
							. $filedir . "/" . $thumb . "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap="
							. USE_IMAGE_SIZE_SWAPPING;
						$subproperty_filename_org = REDSHOP_FRONT_IMAGES_RELPATH . "subproperty/" . $imagename;

						if (is_file($subproperty_filename_org))
						{
							$subproperty_thumb_original = $media_image [$m]->subattribute_color_image;
							$subproperty_img_path_org   = $url . "components/" . $option
								. "/helpers/thumb.php?filename=subproperty/" . $media_image [$m]->subattribute_color_main_image
								. "&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						}
						else
						{
							$subproperty_img_path_org = $property_img_path_org;
						}

						$subpropadditionImg_div_start = "<div class='additional_image' onmouseover='display_image_add(\""
							. $imgs_path . "\"," . $product_id . ");' onmouseout='display_image_add_out(\""
							. $subproperty_img_path_org . "\"," . $product_id . ");' >";
						$subpropadditionImg_div_end   = "</div>";
						$subpropadditionImg .= $subpropadditionImg_div_start;
						$subpropadditionImg .= "<a href='javascript:void(0)'>" . "<img src='" . $url
							. "components/com_redshop/helpers/thumb.php?filename=" . $filedir . "/" . $thumb . "&newxsize="
							. $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' alt='"
							. $alttext . "' title='" . $alttext . "' style='cursor: auto;'>";
						$subprophrefend = "</a>";
					}

					if (ADDITIONAL_HOVER_IMAGE_ENABLE)
					{
						$subpropadditionImg .= "<img src='" . $url . "components/com_redshop/helpers/thumb.php?filename="
							. $filedir . "/" . $thumb . "&newxsize=" . ADDITIONAL_HOVER_IMAGE_WIDTH . "&newysize="
							. ADDITIONAL_HOVER_IMAGE_HEIGHT . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' alt='" . $alttext
							. "' title='" . $alttext . "' class='redImagepreview'>";
					}

					$subpropadditionImg .= $subprophrefend;
					$subpropadditionImg .= $subpropadditionImg_div_end;
				}
			}
		}

		$response = "";

		if ($subpropadditionImg != "")
		{
			$response = "<div>" . $subpropadditionImg . "</div>";
		}
		elseif ($propadditionImg != "")
		{
			$response = "<div>" . $propadditionImg . "</div>";
		}
		elseif ($prodadditionImg != "")
		{
			$response = "<div>" . $prodadditionImg . "</div>";
		}

		$ProductAttributeDelivery = "";
		$attributeFlag            = false;

		if ($accessory_id == 0)
		{
			if ($subproperty_id)
			{
				$ProductAttributeDelivery = $this->getProductMinDeliveryTime($product_id, $subproperty_id, "subproperty", 0);

				if ($ProductAttributeDelivery)
					$attributeFlag = true;
			}

			if ($property_id && $attributeFlag == false)
			{
				$ProductAttributeDelivery = $this->getProductMinDeliveryTime($product_id, $property_id, "property", 0);

				if ($ProductAttributeDelivery)
					$attributeFlag = true;
			}

			if ($product_id && $attributeFlag == false)
			{
				$ProductAttributeDelivery = $this->getProductMinDeliveryTime($product_id);
			}
		}

		$stock_status       = '';
		$stockamountTooltip = "";
		$productinstock     = 0;
		$stockamountSrc     = "";
		$stockImgFlag       = false;
		$notify_stock       = '';

		if (USE_STOCKROOM == 1 && $accessory_id == 0)
		{
			if ($subproperty_id)
			{
				$productinstock  = $stockroomhelper->getStockAmountwithReserve($subproperty_id, "subproperty");
				$stockamountList = $stockroomhelper->getStockAmountImage($subproperty_id, "subproperty", $productinstock);
//				if(count($stockamountList)>0)
//				{
				$stockImgFlag = true;
//				}
			}

			if ($property_id && $stockImgFlag == false)
			{
				$productinstock  = $stockroomhelper->getStockAmountwithReserve($property_id, "property");
				$stockamountList = $stockroomhelper->getStockAmountImage($property_id, "property", $productinstock);
//				if(count($stockamountList)>0)
//				{
				$stockImgFlag = true;
//				}
			}

			if ($product_id && $stockImgFlag == false)
			{
				$productinstock  = $stockroomhelper->getStockAmountwithReserve($product_id);
				$stockamountList = $stockroomhelper->getStockAmountImage($product_id, "product", $productinstock);
			}

			if (count($stockamountList) > 0)
			{
				$stockamountTooltip = $stockamountList[0]->stock_amount_image_tooltip;
				$stockamountSrc     = REDSHOP_FRONT_IMAGES_ABSPATH . 'stockroom/'
					. $stockamountList[0]->stock_amount_image;
			}
		}

		// Stockroom status code->Ushma
		if (strstr($template_desc, "{stock_status")
			|| strstr($template_desc, "{stock_notify_flag}")
			|| strstr($template_desc, "{product_availability_date}"))
		{
			// for cunt attributes
			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $this->getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes         = $this->getProductAttribute($product->product_id);
			$attributes         = array_merge($attributes, $attributes_set);
			$totalatt           = count($attributes);
			$productStockStatus = $this->getproductStockStatus($product->product_id, $totalatt, $property_id, $subproperty_id);

			if (strstr($template_desc, "{stock_status"))
			{
				$stocktag    = strstr($template_desc, "{stock_status");
				$newstocktag = explode("}", $stocktag);

				$realstocktag = $newstocktag[0] . "}";

				$stock_tag = substr($newstocktag[0], 1);
				$sts_array = explode(":", $stock_tag);

				$avail_class = "available_stock_cls";

				if (isset($sts_array[1]) && $sts_array[1] != "")
				{
					$avail_class = $sts_array[1];
				}

				$out_stock_class = "out_stock_cls";

				if (isset($sts_array[2]) && $sts_array[2] != "")
				{
					$out_stock_class = $sts_array[2];
				}

				$pre_order_class = "pre_order_cls";

				if (isset($sts_array[3]) && $sts_array[3] != "")
				{
					$pre_order_class = $sts_array[3];
				}

				if (!$productStockStatus['regular_stock'])
				{
					if (($productStockStatus['preorder']
						&& !$productStockStatus['preorder_stock'])
						|| !$productStockStatus['preorder'])
					{
						$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $out_stock_class
							. "' class='" . $out_stock_class . "'>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div></span>";
					}
					else
					{
						$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $pre_order_class
							. "' class='" . $pre_order_class . "'>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div></span>";
					}

				}
				else
				{
					$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $avail_class
						. "' class='" . $avail_class . "'>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div></span>";
				}
			}

			if (strstr($template_desc, "{stock_notify_flag}"))
			{
				$userArr       = $this->_session->get('rs_user');
				$is_login      = $userArr['rs_is_user_login'];
				$users_info_id = $userArr['rs_user_info_id'];
				$user_id       = $userArr['rs_userid'];
				$is_notified   = $this->isAlreadyNotifiedUser(
					$user_id,
					$product->product_id,
					$property_id,
					$subproperty_id
				);

				if (!$productStockStatus['regular_stock'] && $is_login && $users_info_id)
				{
					if (($productStockStatus['preorder']
						&& !$productStockStatus['preorder_stock'])
						|| !$productStockStatus['preorder'])
					{
						if ($is_notified)
						{
							$notify_stock = "<span>" . JText::_('COM_REDSHOP_ALREADY_REQUESTED_FOR_NOTIFICATION')
								. "</span>";

						}
						else
						{
							$notify_stock = '<span >' . JText::_('COM_REDSHOP_NOTIFY_STOCK_LBL')
								. '</span><input type="button" name="" value="' . JText::_('COM_REDSHOP_NOTIFY_STOCK')
								. '" class="notifystockbtn" title="' . JText::_('COM_REDSHOP_NOTIFY_STOCK_LBL')
								. '" onclick="getStocknotify(\'' . $product->product_id . '\',\'' . $property_id . '\', \''
								. $subproperty_id . '\');">';
						}

					}
					else
					{
						$notify_stock = "";
					}
				}
				else
				{
					$notify_stock = '';
				}

			}

			if (strstr($template_desc, "{product_availability_date}"))
			{
				if (!$productStockStatus['regular_stock'] && $productStockStatus['preorder'])
				{
					if ($product->product_availability_date != "")
					{
						$product_availability_date_lbl = JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL') . ": ";
						$product_availability_date     = $redshopconfig->convertDateFormat($product->product_availability_date);
					}
					else
					{
						$product_availability_date_lbl = "";
						$product_availability_date     = "";
					}

				}
				else
				{
					$product_availability_date_lbl = "";
					$product_availability_date     = "";
				}
			}
		}

		$ret                                  = array();
		$ret['response']                      = $response;
		$ret['aHrefImageResponse']            = $aHrefImageResponse;
		$ret['aTitleImageResponse']           = $aTitleImageResponse;
		$ret['mainImageResponse']             = $mainImageResponse;
		$ret['stockamountSrc']                = $stockamountSrc;
		$ret['stockamountTooltip']            = $stockamountTooltip;
		$ret['ProductAttributeDelivery']      = $ProductAttributeDelivery;
		$ret['attrbimg']                      = $attrbimg;
		$ret['pr_number']                     = $pr_number;
		$ret['productinstock']                = $productinstock;
		$ret['stock_status']                  = $stock_status;
		$ret['product_mainimg']               = $productmainimg;
		$ret['ImageName']                     = $imagename;
		$ret['notifyStock']                   = $notify_stock;
		$ret['product_availability_date_lbl'] = $product_availability_date_lbl;
		$ret['product_availability_date']     = $product_availability_date;

		//$ret['view']			=$view;
		return $ret;
	}

	public function getProductFinderDatepickerValue($templatedata = "", $productid = 0, $fieldArray = array(), $giftcard = 0)
	{
		$extraField = new extraField();

		if (count($fieldArray) > 0)
		{
			for ($i = 0; $i < count($fieldArray); $i++)
			{
				$fieldValueArray = $extraField->getSectionFieldDataList($fieldArray[$i]->field_id, 17, $productid);

				if ($fieldValueArray->data_txt != ""
					&& $fieldArray[$i]->field_show_in_front == 1
					&& $fieldArray[$i]->published == 1
					&& $giftcard == 0)
				{
					$templatedata = str_replace('{' . $fieldArray[$i]->field_name . '}', $fieldValueArray->data_txt, $templatedata);
					$templatedata = str_replace('{' . $fieldArray[$i]->field_name . '_lbl}', $fieldArray[$i]->field_title, $templatedata);
				}
				else
				{
					$templatedata = str_replace('{' . $fieldArray[$i]->field_name . '}', "", $templatedata);
					$templatedata = str_replace('{' . $fieldArray[$i]->field_name . '_lbl}', "", $templatedata);
				}
			}
		}

		return $templatedata;
	}

// function for related product layout

	public function getRelatedtemplateView($template_desc, $product_id)
	{
		$extra_field      = new extraField();
		$config           = new Redconfiguration();
		$redTemplate      = new Redtemplate();
		$redhelper        = new redhelper();
		$related_product  = $this->getRelatedProduct($product_id);
		$related_template = $this->getRelatedProductTemplate($template_desc);
		$option           = 'com_redshop';
		$fieldArray       = $extra_field->getSectionFieldList(17, 0, 0);

		if (count($related_template) > 0)
		{
			if (count($related_product) > 0
				&& strstr($related_template->template_desc, "{related_product_start}")
				&& strstr($related_template->template_desc, "{related_product_end}"))
			{
				$related_template_data = '';
				$product_start         = explode("{related_product_start}", $related_template->template_desc);
				$product_end           = explode("{related_product_end}", $product_start [1]);

				$tempdata_div_start  = $product_start [0];
				$tempdata_div_middle = $product_end [0];
				$tempdata_div_end    = $product_end [1];

				$attribute_template = $this->getAttributeTemplate($tempdata_div_middle);

				for ($r = 0; $r < count($related_product); $r++)
				{
					$related_template_data .= $tempdata_div_middle;

					$ItemData = $this->getMenuInformation(0, 0, '', 'product&pid=' . $related_product[$r]->product_id);

					if (count($ItemData) > 0)
					{
						$pItemid = $ItemData->id;
					}
					else
					{
						$pItemid = $redhelper->getItemid($related_product[$r]->product_id);
					}

					$rlink = JRoute::_('index.php?option=com_redshop&view=product&pid='
						. $related_product[$r]->product_id . '&Itemid=' . $pItemid);

					if (strstr($related_template_data, "{relproduct_image_3}"))
					{
						$rpimg_tag = '{relproduct_image_3}';
						$rph_thumb = RELATED_PRODUCT_THUMB_HEIGHT_3;
						$rpw_thumb = RELATED_PRODUCT_THUMB_WIDTH_3;
					}
					elseif (strstr($related_template_data, "{relproduct_image_2}"))
					{
						$rpimg_tag = '{relproduct_image_2}';
						$rph_thumb = RELATED_PRODUCT_THUMB_HEIGHT_2;
						$rpw_thumb = RELATED_PRODUCT_THUMB_WIDTH_2;
					}
					elseif (strstr($related_template_data, "{relproduct_image_1}"))
					{
						$rpimg_tag = '{relproduct_image_1}';
						$rph_thumb = RELATED_PRODUCT_THUMB_HEIGHT;
						$rpw_thumb = RELATED_PRODUCT_THUMB_WIDTH;
					}
					else
					{
						$rpimg_tag = '{relproduct_image}';
						$rph_thumb = RELATED_PRODUCT_THUMB_HEIGHT;
						$rpw_thumb = RELATED_PRODUCT_THUMB_WIDTH;
					}

					$hidden_thumb_image    = "<input type='hidden' name='rel_main_imgwidth' id='rel_main_imgwidth' value='"
						. $rpw_thumb . "'><input type='hidden' name='rel_main_imgheight' id='rel_main_imgheight' value='"
						. $rph_thumb . "'>";
					$relimage              = $this->getProductImage($related_product [$r]->product_id, $rlink, $rpw_thumb, $rph_thumb);
					$related_template_data = str_replace($rpimg_tag, $relimage . $hidden_thumb_image, $related_template_data);

					if (strstr($related_template_data, "{relproduct_link}"))
					{
						$rpname = "<a href='" . $rlink . "' title='" . $related_product [$r]->product_name . "'>"
							. $config->maxchar($related_product [$r]->product_name, RELATED_PRODUCT_TITLE_MAX_CHARS, RELATED_PRODUCT_TITLE_END_SUFFIX)
							. "</a>";
					}
					else
					{
						$rpname = $config->maxchar($related_product [$r]->product_name, RELATED_PRODUCT_TITLE_MAX_CHARS, RELATED_PRODUCT_TITLE_END_SUFFIX);
					}

					$rpdesc       = $config->maxchar($related_product [$r]->product_desc, RELATED_PRODUCT_DESC_MAX_CHARS, RELATED_PRODUCT_DESC_END_SUFFIX);
					$rp_shortdesc = $config->maxchar($related_product [$r]->product_s_desc, RELATED_PRODUCT_SHORT_DESC_MAX_CHARS, RELATED_PRODUCT_SHORT_DESC_END_SUFFIX);

					$related_template_data = str_replace("{relproduct_link}", '', $related_template_data);

					if (strstr($related_template_data, "{relproduct_link}"))
					{
						$related_template_data = str_replace("{relproduct_name}", "", $related_template_data);
					}
					else
					{
						$related_template_data = str_replace("{relproduct_name}", $rpname, $related_template_data);
					}

					$related_template_data = str_replace("{relproduct_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL'), $related_template_data);
					$related_template_data = str_replace("{relproduct_number}", $related_product [$r]->product_number, $related_template_data);
					$related_template_data = str_replace("{relproduct_s_desc}", $rp_shortdesc, $related_template_data);
					$related_template_data = str_replace("{relproduct_desc}", $rpdesc, $related_template_data);

					// ProductFinderDatepicker Extra Field Start
					$related_template_data = $this->getProductFinderDatepickerValue($related_template_data, $related_product[$r]->product_id, $fieldArray);
					// ProductFinderDatepicker Extra Field End

					if (strstr($related_template_data, "{manufacturer_name}") || strstr($related_template_data, "{manufacturer_link}"))
					{
						$manufacturer = $this->getSection("manufacturer", $related_product [$r]->manufacturer_id);

						if (count($manufacturer) > 0)
						{
							$man_url               = JRoute::_('index.php?option=' . $option . '&view=manufacturers&layout=products&mid=' . $related_product[$r]->manufacturer_id . '&Itemid=' . $pItemid);
							$manufacturerLink      = "<a href='" . $man_url . "'>" . JText::_("COM_REDSHOP_VIEW_ALL_MANUFACTURER_PRODUCTS") . "</a>";
							$related_template_data = str_replace("{manufacturer_name}", $manufacturer->manufacturer_name, $related_template_data);
							$related_template_data = str_replace("{manufacturer_link}", $manufacturerLink, $related_template_data);
						}
						else
						{
							$related_template_data = str_replace("{manufacturer_name}", '', $related_template_data);
							$related_template_data = str_replace("{manufacturer_link}", '', $related_template_data);
						}
					}

					$relmorelink           = JRoute::_('index.php?option=' . $option . '&view=product&pid='
						. $related_product [$r]->product_id . '&cid=' . $related_product[$r]->cat_in_sefurl . '&Itemid='
						. $pItemid);
					$rmore                 = "<a href='" . $relmorelink . "' title='" . $related_product [$r]->product_name
						. "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
					$related_template_data = str_replace("{read_more}", $rmore, $related_template_data);
					$related_template_data = str_replace("{read_more_link}", $relmorelink, $related_template_data);
					/*
					 *  related product Required Attribute start
					 * 	this will parse only Required Attributes
					 */

					$relid          = $related_product [$r]->product_id;
					$attributes_set = array();

					if ($related_product [$r]->attribute_set_id > 0)
					{
						$attributes_set = $this->getProductAttribute(0, $related_product [$r]->attribute_set_id);
					}

					$attributes = $this->getProductAttribute($relid);
					$attributes = array_merge($attributes, $attributes_set);

					$related_template_data = $this->replaceAttributeData($related_product[$r]->mainproduct_id, 0, $related_product[$r]->product_id, $attributes, $related_template_data, $attribute_template);

					// Check product for not for sale
					$related_template_data = $this->getProductNotForSaleComment($related_product[$r], $related_template_data, $attributes, 1);

					$related_template_data = $this->replaceCartTemplate($related_product[$r]->mainproduct_id, 0, 0, $related_product[$r]->product_id, $related_template_data, false, 0, count($attributes), 0, 0);
					$related_template_data = $this->replaceCompareProductsButton($related_product[$r]->product_id, 0, $related_template_data, 1);
					$related_template_data = $this->replaceProductInStock($related_product[$r]->product_id, $related_template_data);

					$related_template_data = $this->getProductOnSaleComment($related_product[$r], $related_template_data);
					$related_template_data = $this->getSpecialProductComment($related_product[$r], $related_template_data);

					// related product attribute price list
					$related_template_data = $this->replaceAttributePriceList($related_product[$r]->product_id, $related_template_data);
				}

				$related_template_data = $tempdata_div_start . $related_template_data . $tempdata_div_end;

				$template_desc = str_replace("{related_product:$related_template->template_name}", $related_template_data, $template_desc);

				$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
			}
			else
			{
				$template_desc = str_replace("{related_product:$related_template->template_name}", "", $template_desc);
			}
		}

		return $template_desc;
	}

	/**
	 * replace related product attribute price list
	 *
	 * child product as related product concept is included
	 *    New Tag : {relproduct_attribute_pricelist} = related product attribute price list
	 *
	 * @params: $id :  product id
	 * @params: $templatedata : template data
	 */
	public function replaceAttributePriceList($id, $templatedata)
	{
		$output     = "";
		$attributes = $this->getProductAttribute($id, 0, 0, 1);

		$k = 0;

		for ($i = 0; $i < count($attributes); $i++)
		{
			$attribute      = $attributes[$i];
			$attribute_name = $attribute->text;
			$attribute_id   = $attribute->value;
			$propertys      = $this->getAttibuteProperty(0, $attribute_id);

			for ($p = 0; $p < count($propertys); $p++)
			{
				$property = $propertys[$p];

				$property_id             = $property->value;
				$property_name           = $property->text;
				$proprty_price           = $property->property_price;
				$property_formated_price = $this->getProductFormattedPrice($proprty_price);
				$proprty_oprand          = $property->oprand;

				$output .= '<div class="related_plist_property_name' . $k . '">' . $property_formated_price . '</div>';

				$subpropertys = $this->getAttibuteSubProperty(0, $property_id);

				for ($s = 0; $s < count($subpropertys); $s++)
				{
					$subproperty = $subpropertys[$s];

					$subproperty_id    = $subproperty->value;
					$subproperty_name  = $subproperty->text;
					$subproprty_price  = $subproperty->subattribute_color_price;
					$subproprty_oprand = $subproperty->oprand;
				}

				$k++;
			}
		}
		#$output = ($output!="") ? "<div>".$output."</div>" : "";
		$templatedata = str_replace("{relproduct_attribute_pricelist}", $output, $templatedata);

		return $templatedata;
	}

	public function getCategoryNameByProductId($pid)
	{
		$query = "SELECT c.category_name FROM " . $this->_table_prefix . "product_category_xref AS pcx "
			. "LEFT JOIN " . $this->_table_prefix . "category AS c ON c.category_id=pcx.category_id "
			. "WHERE pcx.product_id=" . $pid . " AND c.category_name IS NOT NULL ORDER BY c.category_id ASC LIMIT 0,1";

		$this->_db->setQuery($query);

		return $this->_db->loadResult();

	}

	public function removeOutofstockProduct($products)
	{
		$stockroomhelper = new rsstockroomhelper();
		$filter_products = array();

		for ($s = 0; $s < count($products); $s++)
		{
			$product = $products[$s];
			$pid     = $product->product_id;

			$attributes_set = array();

			if ($product->attribute_set_id > 0)
			{
				$attributes_set = $this->getProductAttribute(0, $product->attribute_set_id, 0, 1);
			}

			$attributes = $this->getProductAttribute($product->product_id);
			$attributes = array_merge($attributes, $attributes_set);
			$totalatt   = count($attributes);

			$stock_amount = $stockroomhelper->getFinalStockofProduct($pid, $totalatt);

			if ($stock_amount)
			{
				$filter_products[] = $products[$s];
			}
		}

		return $filter_products;
	}

	public function getproductStockStatus($product_id = 0, $totalatt = 0, $selectedpropertyId = 0, $selectedsubpropertyId = 0)
	{
		$stockroomhelper            = new rsstockroomhelper();
		$producDetail               = $this->getProductById($product_id);
		$product_preorder           = trim($producDetail->preorder);
		$rsltdata                   = array();
		$rsltdata['preorder']       = 0;
		$rsltdata['preorder_stock'] = 0;

		if ($selectedpropertyId)
		{
			if ($selectedsubpropertyId)
			{
				// Count status for selected subproperty
				$stocksts = $stockroomhelper->isStockExists($selectedsubpropertyId, "subproperty");

				if (!$stocksts && (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes")))
				{
					$prestocksts                = $stockroomhelper->isPreorderStockExists($selectedsubpropertyId, "subproperty");
					$rsltdata['preorder']       = 1;
					$rsltdata['preorder_stock'] = $prestocksts;
				}
			}
			else
			{
				// Count status for selected property
				$stocksts = $stockroomhelper->isStockExists($selectedpropertyId, "property");

				if (!$stocksts && (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes")))
				{
					$prestocksts                = $stockroomhelper->isPreorderStockExists($selectedpropertyId, "property");
					$rsltdata['preorder']       = 1;
					$rsltdata['preorder_stock'] = $prestocksts;
				}
			}
		}
		else
		{
			$stocksts = $stockroomhelper->getFinalStockofProduct($product_id, $totalatt);

			if (!$stocksts && (($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes")))
			{
				$prestocksts                = $stockroomhelper->getFinalPreorderStockofProduct($product_id, $totalatt);
				$rsltdata['preorder']       = 1;
				$rsltdata['preorder_stock'] = $prestocksts;
			}
		}

		$rsltdata['regular_stock'] = $stocksts;

		return $rsltdata;
	}

	public function replaceProductStockdata($product_id, $property_id, $subproperty_id, $data_add, $stockStatusArray)
	{
		if (strstr($data_add, "{stock_status"))
		{
			$stocktag     = strstr($data_add, "{stock_status");
			$newstocktag  = explode("}", $stocktag);
			$realstocktag = $newstocktag[0] . "}";

			$stock_tag = substr($newstocktag[0], 1);
			$sts_array = explode(":", $stock_tag);

			$avail_class = "available_stock_cls";

			if (isset($sts_array[1]) && $sts_array[1] != "")
			{
				$avail_class = $sts_array[1];
			}

			$out_stock_class = "out_stock_cls";

			if (isset($sts_array[2]) && $sts_array[2] != "")
			{
				$out_stock_class = $sts_array[2];
			}

			$pre_order_class = "pre_order_cls";

			if (isset($sts_array[3]) && $sts_array[3] != "")
			{
				$pre_order_class = $sts_array[3];
			}

			if (!$stockStatusArray['regular_stock'])
			{
				if (($stockStatusArray['preorder'] && !$stockStatusArray['preorder_stock']) || !$stockStatusArray['preorder'])
				{
					$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $out_stock_class
						. "' class='" . $out_stock_class . "'>" . JText::_('COM_REDSHOP_OUT_OF_STOCK') . "</div></span>";
				}
				else
				{
					$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $pre_order_class
						. "' class='" . $pre_order_class . "'>" . JText::_('COM_REDSHOP_PRE_ORDER') . "</div></span>";
				}
			}
			else
			{
				$stock_status = "<span id='stock_status_div" . $product_id . "'><div id='" . $avail_class . "' class='"
					. $avail_class . "'>" . JText::_('COM_REDSHOP_AVAILABLE_STOCK') . "</div></span>";
			}

			$data_add = str_replace($realstocktag, $stock_status, $data_add);

		}

		if (strstr($data_add, "{stock_notify_flag}"))
		{
			$userArr       = $this->_session->get('rs_user');
			$user_id       = $userArr['rs_userid'];
			$is_login      = $userArr['rs_is_user_login'];
			$users_info_id = $userArr['rs_user_info_id'];
			$is_notified   = $this->isAlreadyNotifiedUser($user_id, $product_id, $property_id, $subproperty_id);

			if (!$stockStatusArray['regular_stock'] && $is_login && $users_info_id && $user_id)
			{
				if (($stockStatusArray['preorder'] && !$stockStatusArray['preorder_stock']) || !$stockStatusArray['preorder'])
				{
					if ($is_notified)
					{
						$data_add = str_replace("{stock_notify_flag}", "<div id='notify_stock" . $product_id . "'>"
							. JText::_('COM_REDSHOP_ALREADY_REQUESTED_FOR_NOTIFICATION') . "</div>", $data_add);
					}
					else
					{
						$data_add = str_replace("{stock_notify_flag}", '<div id="notify_stock' . $product_id . '"><span >'
							. JText::_('COM_REDSHOP_NOTIFY_STOCK_LBL') . '</span><input type="button" name="" value="'
							. JText::_('COM_REDSHOP_NOTIFY_STOCK') . '" class="notifystockbtn" title="'
							. JText::_('COM_REDSHOP_NOTIFY_STOCK_LBL') . '" onclick="getStocknotify(\'' . $product_id
							. '\',\'' . $property_id . '\', \'' . $subproperty_id . '\');"></div>', $data_add);
					}

				}
				else
				{
					$data_add = str_replace("{stock_notify_flag}", "<div id='notify_stock" . $product_id . "'></div>", $data_add);
				}

			}
			else
			{
				$data_add = str_replace("{stock_notify_flag}", "<div id='notify_stock" . $product_id . "'></div>", $data_add);
			}

		}

		if (strstr($data_add, "{product_availability_date}"))
		{
			$redshopconfig = new Redconfiguration ();
			$product       = $this->getProductById($product_id);

			if (!$stockStatusArray['regular_stock'] && $stockStatusArray['preorder'])
			{
				if ($product->product_availability_date)
				{
					$data_add = str_replace("{product_availability_date_lbl}", "<span id='stock_availability_date_lbl"
						. $product_id . "'>" . JText::_('COM_REDSHOP_PRODUCT_AVAILABILITY_DATE_LBL') . ": </span>", $data_add);
					$data_add = str_replace("{product_availability_date}", "<span id='stock_availability_date" . $product_id
						. "'>" . $redshopconfig->convertDateFormat($product->product_availability_date) . "</span>", $data_add);
				}
				else
				{
					$data_add = str_replace("{product_availability_date_lbl}", "<span id='stock_availability_date_lbl"
						. $product_id . "'></span>", $data_add);
					$data_add = str_replace("{product_availability_date}", "<span id='stock_availability_date" . $product_id
						. "'></span>", $data_add);
				}

			}
			else
			{
				$data_add = str_replace("{product_availability_date_lbl}", "<span id='stock_availability_date_lbl"
					. $product_id . "'></span>", $data_add);
				$data_add = str_replace("{product_availability_date}", "<span id='stock_availability_date" . $product_id
					. "'></span>", $data_add);

			}
		}

		return $data_add;
	}

	public function isAlreadyNotifiedUser($user_id, $product_id, $property_id, $subproperty_id)
	{
		$query = 'SELECT * FROM ' . $this->_table_prefix . 'notifystock_users  WHERE product_id = ' . $product_id
			. ' and property_id = ' . $property_id . ' and subproperty_id = ' . $subproperty_id . ' and user_id ='
			. $user_id . ' and notification_status=0';
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	public function insertPaymentShippingField($cart = array(), $order_id = 0, $section_id = 18)
	{
		$extraField = new extraField();
		$row_data   = $extraField->getSectionFieldList($section_id, 1);

		for ($i = 0; $i < count($row_data); $i++)
		{
			$user_fields = $cart['extrafields_values'][$row_data[$i]->field_name];

			if (trim($user_fields) != '')
			{
				$sql = "INSERT INTO " . $this->_table_prefix . "fields_data "
					. "(fieldid,data_txt,itemid,section) "
					. "value ('" . $row_data[$i]->field_id . "','" . addslashes($user_fields) . "','" . $order_id
					. "','" . $section_id . "')";
				$this->_db->setQuery($sql);
				$this->_db->query();
			}
		}

		return;
	}

	public function getPaymentandShippingExtrafields($order, $section_id)
	{
		$extraField = new extraField();
		$row_data   = $extraField->getSectionFieldList($section_id, 1);
		$resultArr  = array();

		for ($j = 0; $j < count($row_data); $j++)
		{
			$main_result = $extraField->getSectionFieldDataList($row_data[$j]->field_id, $section_id, $order->order_id);

			if ($main_result->data_txt != "" && $row_data[$j]->field_show_in_front == 1)
			{
				$resultArr[] = $main_result->field_title . " : " . $main_result->data_txt;
			}
		}

		$resultstr = "";

		if (count($resultArr) > 0)
		{
			$resultstr = implode("<br/>", $resultArr);
		}

		return $resultstr;
	}
}
