<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT_SITE . '/helpers/product.php';

jimport('joomla.application.component.model');

class mass_discount_detailModelmass_discount_detail extends JModel
{
	public $_id = null;

	public $_data = null;

	public $_shoppers = null;

	public $_table_prefix = null;

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
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'mass_discount WHERE mass_discount_id = ' . $this->_id;

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			return (boolean) $this->_data;
		}

		return true;
	}


	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->mass_discount_id = 0;
			$detail->discount_name = null;
			$detail->category_id = 0;
			$detail->discount_amount = 0;
			$detail->discount_type = 0;
			$detail->discount_startdate = time();
			$detail->discount_enddate = time();
			$detail->manufacturer_id = 0;
			$detail->discount_product = 0;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$producthelper = new producthelper;

		$row =& $this->getTable('mass_discount_detail');

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$row->category_id = @implode(",", $data['category_id']);
		$row->manufacturer_id = @implode(",", $data['manufacturer_id']);
		$row->discount_product = @implode(",", $data['discount_product']);

		$change_product = false;
		$newchange_product = false;

		$this->setId($row->mass_discount_id);
		$dataDiscount =& $this->getData();

		$discount_product = explode(',', $dataDiscount->discount_product);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $discount_product);


		$newdiscount_product = explode(',', $row->discount_product);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $newdiscount_product);

		$arr_diff = array_diff($discount_product, $newdiscount_product);

		if (count($arr_diff) > 0)
		{
			sort($arr_diff);
		}

		else
		{
			$change_product = true;
		}

		for ($i = 0; $i < count($arr_diff); $i++)
		{
			$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="0" WHERE product_id="' . $arr_diff[$i] . '" ';

			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		$arr_diff = array_diff($newdiscount_product, $discount_product);

		if (count($arr_diff) > 0)
		{
			sort($arr_diff);
		}
		else
		{
			$newchange_product = true;
		}

		if ($change_product && $newchange_product)
		{
			$arr_diff = $discount_product;
		}

		for ($i = 0; $i < count($arr_diff); $i++)
		{
			$productData = $producthelper->getProductById($arr_diff[$i]);

			if ($productData->product_on_sale != 1)
			{
				$p_price = ($data['discount_type'] == 1) ?
					($productData->product_price - ($productData->product_price * $data['discount_amount'] / 100)) :
					$productData->product_price - ($data['discount_amount']);
				$p_price = $producthelper->productPriceRound($p_price);
				$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="1" , discount_price="'
					. $p_price . '" , discount_stratdate="' . $data['discount_startdate'] . '" , discount_enddate="'
					. $data['discount_enddate'] . '" WHERE product_id="' . $arr_diff[$i] . '" ';
				$this->_db->setQuery($query);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		$category_id = explode(',', $dataDiscount->category_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $category_id);

		$newcategory_id = explode(',', $row->category_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $newcategory_id);
		$change_category = false;
		$newchange_category = false;
		$arr_diff = array_diff($category_id, $newcategory_id);

		if (count($arr_diff) > 0)
		{
			sort($arr_diff);
		}
		else
		{
			$change_category = true;
		}

		for ($i = 0; $i < count($arr_diff); $i++)
		{
			$product_Ids = $producthelper->getProductCategory($arr_diff[$i]);

			for ($p = 0; $p < count($product_Ids); $p++)
			{
				$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="0" WHERE product_id="' . $product_Ids[$p]->product_id . '" ';

				$this->_db->setQuery($query);

				if (!$this->_db->query())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}
			}
		}

		$arr_diff = array_diff($newcategory_id, $category_id);

		if (count($arr_diff) > 0)
		{
			sort($arr_diff);
		}
		else
		{
			$newchange_category = true;
		}

		if ($change_category && $newchange_category)
		{
			$arr_diff = $category_id;
		}
		for ($i = 0; $i < count($arr_diff); $i++)
		{

			$product_Ids = $producthelper->getProductCategory($arr_diff[$i]);

			for ($p = 0; $p < count($product_Ids); $p++)
			{
				$productData = $producthelper->getProductById($product_Ids[$p]->product_id);

				if ($productData->product_on_sale != 1)
				{
					$p_price = ($data['discount_type'] == 1) ?
						($productData->product_price - ($productData->product_price * $data['discount_amount'] / 100)) :
						$data['discount_amount'];
					$p_price = $producthelper->productPriceRound($p_price);
					$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="1" , discount_price="'
						. $p_price . '" , discount_stratdate="' . $data['discount_startdate'] . '" , discount_enddate="'
						. $data['discount_enddate'] . '" WHERE product_id="' . $product_Ids[$p]->product_id . '" ';

					$this->_db->setQuery($query);

					if (!$this->_db->query())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}
			}
		}

		$manufacturer_id = explode(',', $dataDiscount->manufacturer_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $manufacturer_id);
		$change_manufacturer = false;
		$newchange_manufacturer = false;

		$newmanufacturer_id = explode(',', $row->manufacturer_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $newmanufacturer_id);

		$manu_arr_diff = array_diff($manufacturer_id, $newmanufacturer_id);

		if (count($manu_arr_diff) > 0)
		{
			sort($manu_arr_diff);
		}
		else
		{
			$change_manufacturer = true;
		}

		for ($i = 0; $i < count($manu_arr_diff); $i++)
		{
			if ($manu_arr_diff[$i] > 0)
			{
				$product_Ids = $this->GetProductmanufacturer($manu_arr_diff[$i]);

				for ($p = 0; $p < count($product_Ids); $p++)
				{
					$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="0" WHERE product_id="' . $product_Ids[$p]->product_id . '" ';
					$this->_db->setQuery($query);

					if (!$this->_db->query())
					{
						$this->setError($this->_db->getErrorMsg());

						return false;
					}
				}
			}
		}

		$manu_arr_diff = array_diff($newmanufacturer_id, $manufacturer_id);

		if (count($manu_arr_diff) > 0)
		{
			sort($manu_arr_diff);
		}
		else
		{
			$newchange_manufacturer = true;
		}

		if ($newchange_manufacturer && $change_manufacturer)
		{
			$manu_arr_diff = $manufacturer_id;
		}

		for ($i = 0; $i < count($manu_arr_diff); $i++)
		{
			if ($manu_arr_diff[$i] > 0)
			{
				$product_Ids = $this->GetProductmanufacturer($manu_arr_diff[$i]);

				for ($p = 0; $p < count($product_Ids); $p++)
				{
					$productData = $producthelper->getProductById($product_Ids[$p]->product_id);
					$p_price = ($data['discount_type'] == 1) ?
						($productData->product_price - ($productData->product_price * $data['discount_amount'] / 100)) :
						$data['discount_amount'];

					if ($productData->product_on_sale != 1)
					{
						$p_price = $producthelper->productPriceRound($p_price);
						$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="1" , discount_price="' .
							$p_price . '" , discount_stratdate="' . $data['discount_startdate'] . '" , discount_enddate="'
							. $data['discount_enddate'] . '" WHERE product_id="' . $product_Ids[$p]->product_id . '" ';
						$this->_db->setQuery($query);

						if (!$this->_db->query())
						{
							$this->setError($this->_db->getErrorMsg());

							return false;
						}
					}
				}
			}
		}

		$row->manufacturer_id = $row->manufacturer_id ? $row->manufacturer_id : '';
		$row->category_id = $row->category_id ? $row->category_id : '';
		$row->discount_product = $row->discount_product ? $row->discount_product : '';

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	public function delete($cid = array())
	{
		$layout = JRequest::getVar('layout');
		$producthelper = new producthelper;

		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'SELECT * FROM ' . $this->_table_prefix . 'mass_discount WHERE mass_discount_id in (' . $cids . ') ';

			$this->_db->setQuery($query);
			$massDList = $this->_db->loadObjectList();

			for ($m = 0; $m < count($massDList); $m++)
			{
				if (!empty($massDList[$m]->discount_product))
				{
					$this->updateProduct($massDList[$m]->discount_product);
				}

				$categoryArr = explode(',', $massDList[$m]->category_id);

				for ($c = 0; $c < count($categoryArr); $c++)
				{
					$product_Ids = $producthelper->getProductCategory($categoryArr[$c]);
					$cproduct = $this->customImplode($product_Ids);
					$this->updateProduct($cproduct);
				}

				$manufacturerArr = explode(',', $massDList[$m]->manufacturer_id);

				for ($mn = 0; $mn < count($manufacturerArr); $mn++)
				{
					$product_Ids = $this->GetProductmanufacturer($manufacturerArr[$mn]);
					$mproduct = $this->customImplode($product_Ids);
					$this->updateProduct($mproduct);
				}
			}

			$query = 'DELETE FROM ' . $this->_table_prefix . 'mass_discount WHERE mass_discount_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function customImplode($productArr)
	{
		$pArr = array(0);

		for ($i = 0; $i < count($productArr); $i++)
		{
			$pArr[] = $productArr[$i]->product_id;
		}

		return implode(',', $pArr);
	}

	public function updateProduct($productId)
	{
		$query = 'UPDATE ' . $this->_table_prefix . 'product SET product_on_sale="0" where product_id in (' . $productId . ')';
		$this->_db->setQuery($query);

		if (!$this->_db->query())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}
	}

	public function getmanufacturers()
	{
		$query = 'SELECT manufacturer_id as value,manufacturer_name as text FROM ' . $this->_table_prefix . 'manufacturer  WHERE published=1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function GetProductmanufacturer($id)
	{
		$query = 'SELECT product_id FROM ' . $this->_table_prefix . 'product   WHERE manufacturer_id="' . $id . '" ';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectlist();
	}

	public function GetProductListshippingrate($d)
	{
		if ($d != '')
		{
			$query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix
				. 'product WHERE published = 1 and product_id in   (' . $d . ')';
		}
		else
		{
			$query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix
				. 'product WHERE published = 1 and product_id =""';
		}

		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}

	public function GetProductList()
	{
		$query = 'SELECT product_name as text,product_id as value FROM ' . $this->_table_prefix . 'product WHERE published = 1';
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}
}
