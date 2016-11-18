<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



/**
 * Class wishlistModelwishlist
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class RedshopModelWishlist extends RedshopModel
{
	public $_id = null;

	public $_name = null;

	// Product data
	public $_userid = null;

	public $_table_prefix = null;

	public $_comment = null;

	public $_cdate = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
	}

	public function getUserWishlist()
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();

		$query = "SELECT * FROM " . $this->_table_prefix . "wishlist WHERE user_id=" . (int) $user->id;
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getWishlistProduct()
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();

		if ($user->id)
		{
			$whislists     = $this->getUserWishlist();
			$wish_products = array();

			for ($i = 0, $in = count($whislists); $i < $in; $i++)
			{
				$sql = "SELECT DISTINCT wp.* ,p.* "
					. "FROM  #__redshop_product as p "
					. ", #__redshop_wishlist_product as wp "
					. "WHERE wp.product_id = p.product_id AND wp.wishlist_id = " . (int) $whislists[$i]->wishlist_id;
				$db->setQuery($sql);
				$wish_products[$whislists[$i]->wishlist_id] = $db->loadObjectList();
			}

			return $wish_products;
		}
		else
		{
			$productIds = array();
			$rows    = array();

			if (isset($_SESSION["no_of_prod"]))
			{
				for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
				{
					if (isset($_SESSION['wish_' . $add_i]->product_id))
					{
						$productIds[] = (int) $_SESSION['wish_' . $add_i]->product_id;
					}
				}

				if (count($productIds))
				{
					// Sanitize ids
					JArrayHelper::toInteger($productIds);

					$sql = "SELECT DISTINCT p.* "
						. "FROM #__redshop_product as p "
						. "WHERE p.product_id IN( " . implode(',', $productIds) . ")";
					$db->setQuery($sql);
					$rows = $db->loadObjectList();
				}
			}

			return $rows;
		}
	}

	public function getWishlistProductFromSession()
	{
		$db      = JFactory::getDbo();
		$rows    = array();

		$productIds = array();

		if (isset($_SESSION["no_of_prod"]))
		{
			for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
			{
				if (isset($_SESSION['wish_' . $add_i]->product_id))
				{
					$productIds[] = (int) $_SESSION['wish_' . $add_i]->product_id;
				}
			}

			if (count($productIds))
			{
				// Sanitize ids
				JArrayHelper::toInteger($productIds);

				$sql = "SELECT DISTINCT p.*, pcx.category_id "
					. "FROM #__redshop_product as p "
					. 'LEFT JOIN #__redshop_product_category_xref AS pcx on pcx.product_id = p.product_id '
					. "WHERE p.product_id IN( " . implode(',', $productIds) . ")";
				$db->setQuery($sql);
				$rows = $db->loadObjectList();
			}
		}

		return $rows;
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
		else
		{
			$db         = JFactory::getDbo();
			$product_id = JRequest::getInt('product_id');

			if ($product_id)
			{
				$ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product "
					. " SET wishlist_id=" . (int) $row->wishlist_id
					. ", product_id=" . (int) $product_id
					. ", cdate = " . $db->quote(time());
				$db->setQuery($ins_query);

				if ($db->execute())
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			elseif (!empty($_SESSION["no_of_prod"]))
			{
				ob_clean();
				$extraField = extraField::getInstance();
				$section    = 12;
				$row_data   = $extraField->getSectionFieldList($section);

				for ($si = 1; $si <= $_SESSION["no_of_prod"]; $si++)
				{
					for ($k = 0, $kn = count($row_data); $k < $kn; $k++)
					{
						$myfield = "productuserfield_" . $k;

						if ($_SESSION['wish_' . $si]->$myfield != '')
						{
							$myuserdata = $_SESSION['wish_' . $si]->$myfield;
							$ins_query  = "INSERT INTO #__redshop_wishlist_userfielddata SET "
								. " wishlist_id = " . (int) $row->wishlist_id
								. " , product_id = " . (int) $_SESSION['wish_' . $si]->product_id
								. ", userfielddata = " . $db->quote($myuserdata);

							$db->setQuery($ins_query);
							$db->execute();
						}
					}

					$ins_query = "INSERT INTO #__redshop_wishlist_product SET "
						. " wishlist_id = " . (int) $row->wishlist_id
						. ", product_id = " . (int) $_SESSION['wish_' . $si]->product_id
						. ", cdate = " . $db->quote($_SESSION['wish_' . $si]->cdate);
					$db->setQuery($ins_query);
					$db->execute();
					unset($_SESSION['wish_' . $si]);
				}

				unset($_SESSION["no_of_prod"]);
			}
		}

		return true;
	}

	/**
	 * Method for save wishlist.
	 *
	 * @param   array  $data  List of data
	 *
	 * @return  boolean       True if success. False otherwise.
	 *
	 * @throws  Exception
	 */
	public function savewishlist($data)
	{
		if (empty($data))
		{
			$input = JFactory::getApplication()->input;

			$wishlistIds     = $input->get('wishlist_id', array(), 'Array');
			$productId       = $input->getInt('product_id', 0);
			$attributeIds    = $input->getString('attribute_id', 0);
			$propertyIds     = $input->getString('property_id', 0);
			$subAttributeIds = $input->getString('subattribute_id', 0);
		}
		else
		{
			$wishlistIds     = isset($data['wishlist_id']) ? $data['wishlist_id'] : array();
			$productId       = isset($data['product_id']) ? $data['product_id'] : 0;
			$attributeIds    = isset($data['attribute_id']) ? $data['attribute_id'] : '';
			$propertyIds     = isset($data['property_id']) ? $data['property_id'] : '';
			$subAttributeIds = isset($data['subattribute_id']) ? $data['subattribute_id'] : '';
		}

		if (empty($wishlistIds))
		{
			return false;
		}

		$attributeIds    = explode('##', $attributeIds);
		$propertyIds     = explode('##', $propertyIds);
		$subAttributeIds = explode('##', $subAttributeIds);

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		foreach ($wishlistIds as $wishlistId)
		{
			/** @var RedshopTableWishlist_Product $table */
			$wishlistProductTable = JTable::getInstance('Wishlist_Product', 'RedshopTable');

			$tmpData = array(
				'wishlist_id' => $wishlistId,
				'product_id'  => $productId
			);

			// If wishlist product has already exist.
			if ($wishlistProductTable->load($tmpData))
			{
				// Start check on product's attribute data. If this has different. Keep process for store as new.
				if ($this->isProductDataExist($wishlistId, $productId, $attributeIds, $propertyIds, $subAttributeIds))
				{
					continue;
				}
			}

			$wishlistProductTable->reset();
			$wishlistProductTable->set('wishlist_product_id', null);
			$wishlistProductTable->set('wishlist_id', $wishlistId);
			$wishlistProductTable->set('product_id', $productId);
			$wishlistProductTable->set('cdate', time());

			if (!$wishlistProductTable->store())
			{
				throw new Exception($wishlistProductTable->getError());
			}

			$attributeIds = array_filter($attributeIds);

			// If there are not attribute with product.
			if (empty($attributeIds))
			{
				return true;
			}

			foreach ($attributeIds as $index => $attributeId)
			{
				/** @var RedshopTableWishlist_Product_Item $table */
				$wishlistProductItemTable = JTable::getInstance('Wishlist_Product_Item', 'RedshopTable');

				$tmpData = array(
					'ref_id'       => (int) $wishlistProductTable->get('wishlist_product_id'),
					'attribute_id' => $attributeId
				);

				if (!empty($propertyIds[$index]))
				{
					$tmpData['property_id'] = (int) $propertyIds[$index];
				}

				if (!empty($subAttributeIds[$index]))
				{
					$tmpData['subattribute_id'] = (int) $subAttributeIds[$index];
				}

				// If wishlist product item has already exist. Skip it.
				if ($wishlistProductItemTable->load($tmpData))
				{
					continue;
				}

				if (!$wishlistProductItemTable->save($tmpData))
				{
					throw new Exception($wishlistProductItemTable->getError());
				}
			}
		}

		return true;
	}

	public function check_user_wishlist_authority($userid, $wishlist_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT wishlist_id FROM " . $this->_table_prefix . "wishlist "
			. " WHERE wishlist_id=" . (int) $wishlist_id . " AND user_id=" . (int) $userid;
		$db->setQuery($query);

		$rs = $db->loadResult();

		if ($rs)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function delwishlist($userid, $wishlist_id)
	{
		$db    = JFactory::getDbo();
		$query = "DELETE FROM " . $this->_table_prefix . "wishlist_product "
			. " WHERE wishlist_id=" . (int) $wishlist_id;
		$db->setQuery($query);

		$db->execute();
		$query = "DELETE FROM " . $this->_table_prefix . "wishlist_userfielddata "
			. " WHERE wishlist_id=" . (int) $wishlist_id;
		$db->setQuery($query);

		if ($db->execute())
		{
			$query = "DELETE FROM " . $this->_table_prefix . "wishlist "
				. " WHERE wishlist_id=" . (int) $wishlist_id . " AND user_id=" . (int) $userid;
			$db->setQuery($query);

			if ($db->execute())
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function mysessdelwishlist($wishlist_id)
	{
		if (!empty($_SESSION["no_of_prod"]))
		{
			for ($k = 1; $k <= $_SESSION["no_of_prod"]; $k++)
			{
				if ($_SESSION['wish_' . $k]->product_id == $wishlist_id)
				{
					unset($_SESSION['wish_' . $k]);
				}
			}
		}
	}

	/**
	 * Method for check if product data has been exist.
	 *
	 * @param   int    $wishlistId     Wishlist ID.
	 * @param   int    $productId      Product ID.
	 * @param   array  $attributes     Attributes data.
	 * @param   array  $properties     Properties data.
	 * @param   array  $subAttributes  Sub-properties data.
	 *
	 * @return  boolean       True on exist. False otherwise.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function isProductDataExist($wishlistId, $productId, $attributes = null, $properties = null, $subAttributes = null)
	{
		if (!$wishlistId || !$productId)
		{
			return false;
		}

		$wishlistData = RedshopHelperWishlist::getWishlist($wishlistId);

		// Check: If this product is not exist in this wishlist.
		//        Or this product is exist but new product doesn't have attribute data.
		if (!isset($wishlistData->products[$productId]) || !$attributes)
		{
			return false;
		}

		$attributes    = !is_array($attributes) ? array($attributes) : $attributes;
		$properties    = !is_array($properties) ? array($properties) : $properties;
		$subAttributes = !is_array($subAttributes) ? array($subAttributes) : $subAttributes;

		foreach ($wishlistData->products[$productId] as $wishlistProduct)
		{
			// Check: If attributes has different.
			//        Or properties has different.
			//        Or sub-attributes has different.
			if (!empty(array_diff($attributes, $wishlistProduct->attributes))
				|| !empty(array_diff($properties, $wishlistProduct->properties))
				|| !empty(array_diff($subAttributes, $wishlistProduct->subAttributes)))
			{
				return false;
			}
		}

		return true;
	}
}
