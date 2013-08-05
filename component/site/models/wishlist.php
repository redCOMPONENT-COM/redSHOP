<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('Restricted access');

JLoader::import('joomla.application.component.model');

require_once JPATH_COMPONENT . '/helpers/product.php';
require_once JPATH_COMPONENT . '/helpers/extra_field.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/shipping.php';

/**
 * Class wishlistModelwishlist
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 * @since       1.0
 */
class wishlistModelwishlist extends JModel
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
		$db   = JFactory::getDBO();

		$query = "SELECT * FROM " . $this->_table_prefix . "wishlist WHERE user_id=" . (int) $user->id;
		$db->setQuery($query);

		return $db->loadObjectlist();
	}

	public function getWishlistProduct()
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDBO();

		if ($user->id)
		{
			$whislists     = $this->getUserWishlist();
			$wish_products = array();

			for ($i = 0; $i < count($whislists); $i++)
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
			$prod_id = "";
			$rows    = array();

			if (isset($_SESSION["no_of_prod"]))
			{
				for ($add_i = 1; $add_i < $_SESSION["no_of_prod"]; $add_i++)
				{
					$prod_id .= $_SESSION['wish_' . $add_i]->product_id . ",";
				}

				$prod_id .= $_SESSION['wish_' . $add_i]->product_id;

				$sql = "SELECT DISTINCT p.* "
					. "FROM #__redshop_product as p "
					. "WHERE p.product_id in( " . $prod_id . ")";
				$db->setQuery($sql);
				$rows = $db->loadObjectList();
			}

			return $rows;
		}
	}

	public function getWishlistProductFromSession()
	{
		$db      = JFactory::getDBO();
		$prod_id = "";
		$rows    = array();

		if (isset($_SESSION["no_of_prod"]))
		{
			for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)

				if ($_SESSION['wish_' . $add_i]->product_id != '')
				{
					$prod_id .= $_SESSION['wish_' . $add_i]->product_id . ",";
				}


			$prod_id .= $_SESSION['wish_' . $add_i]->product_id;

			$sql = "SELECT DISTINCT p.* "
				. "FROM #__redshop_product as p "
				. "WHERE p.product_id in( " . substr_replace($prod_id, "", -1) . ")";
			$db->setQuery($sql);
			$rows = $db->loadObjectList();
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
			$db         = JFactory::getDBO();
			$product_id = JRequest::getInt('product_id');

			if ($product_id)
			{
				$ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product "
					. " SET wishlist_id=" . (int) $row->wishlist_id
					. ", product_id=" . (int) $product_id
					. ", cdate=" . time();
				$db->setQuery($ins_query);

				if ($db->Query())
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
				$extraField = new extraField;
				$section    = 12;
				$row_data   = $extraField->getSectionFieldList($section);

				for ($si = 1; $si <= $_SESSION["no_of_prod"]; $si++)
				{
					for ($k = 0; $k < count($row_data); $k++)
					{
						$myfield = "productuserfield_" . $k;

						if ($_SESSION['wish_' . $si]->$myfield != '')
						{
							$myuserdata = $_SESSION['wish_' . $si]->$myfield;
							$ins_query  = "INSERT INTO #__redshop_wishlist_userfielddata SET "
								. " wishlist_id = " . (int) $row->wishlist_id
								. " , product_id = " . (int) $_SESSION['wish_' . $si]->product_id
								. ", userfielddata = '" . $myuserdata . "'";

							$db->setQuery($ins_query);
							$db->Query();
						}
					}

					$ins_query = "INSERT INTO #__redshop_wishlist_product SET "
						. " wishlist_id = " . (int) $row->wishlist_id
						. ", product_id = " . $this->_db->quote($_SESSION['wish_' . $si]->product_id)
						. ", cdate = " . $this->_db->quote($_SESSION['wish_' . $si]->cdate);
					$db->setQuery($ins_query);
					$db->Query();
					unset($_SESSION['wish_' . $si]);
				}

				unset($_SESSION["no_of_prod"]);
			}
		}

		return true;
	}

	public function savewishlist()
	{
		$cid        = JRequest::getVar('cid', '', 'request', 'array');
		$db         = JFactory::getDBO();
		$product_id = JRequest::getInt('product_id');

		for ($i = 0; $i < count($cid); $i++)
		{
			$query = "SELECT wishlist_product_id FROM " . $this->_table_prefix . "wishlist_product "
				. " WHERE wishlist_id=" . (int) $cid[$i] . " AND product_id=" . (int) $product_id;
			$db->setQuery($query);

			if (count($db->loadResult()) > 0)
			{
				continue;
			}

			$ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product "
				. " SET wishlist_id=" . (int) $cid[$i]
				. ", product_id=" . (int) $product_id
				. ", cdate=" . time();
			$db->setQuery($ins_query);

			if ($db->query())
			{
				continue;
			}
			else
			{
				return false;
			}
		}

		return true;
	}

	public function check_user_wishlist_authority($userid, $wishlist_id)
	{
		$db    = JFactory::getDBO();
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
		$db    = JFactory::getDBO();
		$query = "DELETE FROM " . $this->_table_prefix . "wishlist_product "
			. " WHERE wishlist_id=" . (int) $wishlist_id;
		$db->setQuery($query);

		$db->Query();
		$query = "DELETE FROM " . $this->_table_prefix . "wishlist_userfielddata "
			. " WHERE wishlist_id=" . (int) $wishlist_id;
		$db->setQuery($query);

		if ($db->Query())
		{
			$query = "DELETE FROM " . $this->_table_prefix . "wishlist "
				. " WHERE wishlist_id=" . (int) $wishlist_id . " AND user_id=" . (int) $userid;
			$db->setQuery($query);

			if ($db->Query())
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
}
