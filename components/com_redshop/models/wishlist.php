<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'shipping.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class wishlistModelwishlist extends RedshopCoreModel
{
    public $_name = null;

    public $_userid = null;

    public $_comment = null;

    public $_cdate = null;

    public function getUserWishlist()
    {
        $user = JFactory::getUser();

        $query = "SELECT * FROM " . $this->_table_prefix . "wishlist WHERE user_id=" . $user->id;
        $this->_db->setQuery($query);

        return $this->_db->loadObjectlist();
    }

    public function getWishlistProduct()
    {
        $user = JFactory::getUser();

        if ($user->id)
        {
            $whislists     = $this->getUserWishlist();
            $wish_products = array();
            for ($i = 0; $i < count($whislists); $i++)
            {
                $sql = "SELECT DISTINCT wp.* ,p.* " . "FROM  #__redshop_product as p " . ", #__redshop_wishlist_product as wp " . "WHERE wp.product_id = p.product_id AND wp.wishlist_id = " . $whislists[$i]->wishlist_id;
                $this->_db->setQuery($sql);
                $wish_products[$whislists[$i]->wishlist_id] = $this->_db->loadObjectList();
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

                $sql = "SELECT DISTINCT p.* " . "FROM #__redshop_product as p " . "WHERE p.product_id in( " . $prod_id . ")";
                $this->_db->setQuery($sql);
                $rows = $this->_db->loadObjectList();
            }
            return $rows;
        }
    }

    public function getWishlistProductFromSession()
    {
        $prod_id = "";
        $rows    = array();
        if (isset($_SESSION["no_of_prod"]))
        {
            for ($add_i = 1; $add_i <= $_SESSION["no_of_prod"]; $add_i++)
            {
                if ($_SESSION['wish_' . $add_i]->product_id != '')
                {
                    $prod_id .= $_SESSION['wish_' . $add_i]->product_id . ",";
                }
            }

            $prod_id .= $_SESSION['wish_' . $add_i]->product_id;

            $sql = "SELECT DISTINCT p.* " . "FROM #__redshop_product as p " . "WHERE p.product_id in( " . substr_replace($prod_id, "", -1) . ")";
            $this->_db->setQuery($sql);
            $rows = $this->_db->loadObjectList();
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
            $product_id = JRequest :: getInt('product_id');

            if ($product_id)
            {
                $ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product " . " SET wishlist_id=" . $row->wishlist_id . ", product_id=" . $product_id . ", cdate=" . time();
                $this->_db->setQuery($ins_query);
                if ($this->_db->Query())
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
                $extraField = new extraField();
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
                            $ins_query  = "INSERT INTO #__redshop_wishlist_userfielddata SET " . " wishlist_id = " . $row->wishlist_id . " , product_id = " . $_SESSION['wish_' . $si]->product_id . ", userfielddata = '" . $myuserdata . "'";

                            $this->_db->setQuery($ins_query);
                            $this->_db->Query();
                        }
                    }
                    $ins_query = "INSERT INTO #__redshop_wishlist_product SET " . " wishlist_id = " . $row->wishlist_id . ", product_id = " . $_SESSION['wish_' . $si]->product_id . ", cdate = " . $_SESSION['wish_' . $si]->cdate;
                    $this->_db->setQuery($ins_query);
                    $this->_db->Query();
                    unset($_SESSION['wish_' . $si]);
                }
                unset($_SESSION["no_of_prod"]);
            }
        }
        return true;
    }

    public function savewishlist()
    {
        $cid        = JRequest :: getVar('cid', '', 'request', 'array');
        $product_id = JRequest :: getInt('product_id');
        for ($i = 0; $i < count($cid); $i++)
        {
            $query = "SELECT wishlist_product_id FROM " . $this->_table_prefix . "wishlist_product " . " WHERE wishlist_id=" . $cid[$i] . " AND product_id=" . $product_id;
            $this->_db->setQuery($query);

            if (count($this->_db->loadResult()) > 0)
            {
                continue;
            }
            $ins_query = "INSERT INTO " . $this->_table_prefix . "wishlist_product " . " SET wishlist_id=" . $cid[$i] . ", product_id=" . $product_id . ", cdate=" . time();
            $this->_db->setQuery($ins_query);
            if ($this->_db->query())
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
        $query = "SELECT wishlist_id FROM " . $this->_table_prefix . "wishlist " . " WHERE wishlist_id=" . $wishlist_id . " AND user_id=" . $userid;
        $this->_db->setQuery($query);

        $rs = $this->_db->loadResult();
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
        $query = "DELETE FROM " . $this->_table_prefix . "wishlist_product " . " WHERE wishlist_id=" . $wishlist_id;
        $this->_db->setQuery($query);

        $this->_db->Query();
        $query = "DELETE FROM " . $this->_table_prefix . "wishlist_userfielddata " . " WHERE wishlist_id=" . $wishlist_id;
        $this->_db->setQuery($query);

        if ($this->_db->Query())
        {
            $query = "DELETE FROM " . $this->_table_prefix . "wishlist " . " WHERE wishlist_id=" . $wishlist_id . " AND user_id=" . $userid;
            $this->_db->setQuery($query);
            if ($this->_db->Query())
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
