<?php
/**
 * @package     redSHOP
 * @subpackage  Models
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'model.php';

class RedshopModelOrder extends RedshopCoreModel
{
    public $_total = null;

    public $_pagination = null;

    public $_context = 'order_id';

    public function __construct()
    {
        parent::__construct();

        $app = JFactory::getApplication();

        $limit                 = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
        $limitstart            = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter_status         = $app->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', '', 'word');
        $filter_payment_status = $app->getUserStateFromRequest($this->_context . 'filter_payment_status', 'filter_payment_status', '', '');
        $filter                = $app->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $limitstart            = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
        $this->setState('filter_status', $filter_status);
        $this->setState('filter_payment_status', $filter_payment_status);
    }

    public function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total))
        {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    public function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $where    = "";
        $order_id = array();

        $filter                = $this->getState('filter');
        $filter_status         = $this->getState('filter_status');
        $filter_payment_status = $this->getState('filter_payment_status');
        $cid                   = JRequest::getVar('cid', array(0), 'method', 'array');
        $order_id              = implode(',', $cid);
        $layout                = JRequest::getVar('layout');

        $where[] = "1=1";
        if ($filter_status)
        {
            $where[] = "o.order_status ='" . $filter_status . "'";
        }
        if ($filter_payment_status)
        {
            $where[] = "o.order_payment_status = '" . $filter_payment_status . "'";
        }
        if ($filter)
        {
            $where[] = "(  uf.firstname like '%" . $filter . "%' OR uf.lastname like '%" . $filter . "%' OR o.order_id like '%" . $filter . "%' OR o.order_number like '%" . $filter . "%' OR o.referral_code like '%" . $filter . "%'  OR uf.user_email like '%" . $filter . "%')";
        }
        if ($cid[0] != 0)
        {
            $where[] = " o.order_id IN (" . $order_id . ")";
        }
        $where   = count($where) ? '  ' . implode(' AND ', $where) : '';
        $orderby = $this->_buildContentOrderBy();
        if ($layout == 'labellisting')
        {
            $where = " order_label_create=1 ";
        }
        $query = 'SELECT distinct(o.cdate),o.*,uf.* FROM ' . $this->_table_prefix . 'orders AS o ' . 'LEFT JOIN ' . $this->_table_prefix . 'order_users_info AS uf ON o.user_id=uf.user_id ' . 'WHERE uf.address_type LIKE "BT" ' . 'AND ' . $where . ' ' . $orderby;
        return $query;
    }

    public function _buildContentOrderBy()
    {
        $app = JFactory::getApplication();

        $filter_order     = $app->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', ' o.order_id');
        $filter_order_Dir = $app->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', ' DESC ');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    public function update_status()
    {
        $order_functions = new order_functions();
        $order_functions->update_status();
    }

    public function update_status_all()
    {
        $order_functions = new order_functions();
        $order_functions->update_status_all();
    }

    public function export_data($cid)
    {
        //$query1 = $this->_buildQuery();

        $where = "";

        $order_id = implode(',', $cid);

        $where[] = " 1=1";

        if ($cid[0] != 0)
        {
            $where[] = " o.order_id IN (" . $order_id . ")";
        }
        $where   = count($where) ? '  ' . implode(' AND ', $where) : '';
        $orderby = " order by o.order_id DESC";

        $query = 'SELECT distinct(o.cdate),o.*,ouf.* FROM ' . $this->_table_prefix . 'orders AS o ' . 'LEFT JOIN ' . $this->_table_prefix . 'order_users_info AS ouf ON o.order_id=ouf.order_id ' . 'WHERE ouf.address_type LIKE "BT" ' . 'AND ' . $where . ' ' . $orderby;

        return $this->_getList($query);
    }

    public function updateDownloadSetting($did, $limit, $enddate)
    {

        $query = "UPDATE " . $this->_table_prefix . "product_download " . " SET `download_max` = " . $limit . " , `end_date` = " . $enddate . " " . " WHERE download_id = '" . $did . "'";
        $this->_db->setQuery($query);

        if (!$this->_db->Query())
        {
            return false;
        }
        return true;
    }

    public function gls_export($cid)
    {
        $oids         = implode(',', $cid);
        $where        = "";
        $order_helper = new order_functions();
        $shipping     = new shipping();

        $exportfilename = 'redshop_gls_order_export.csv';
        /* Start output to the browser */
        if (preg_match('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
        {
            $UserBrowser = "Opera";
        }
        elseif (preg_match('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
        {
            $UserBrowser = "IE";
        }
        else
        {
            $UserBrowser = '';
        }
        $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

        /* Clean the buffer */
        while (@ob_end_clean())
        {
            ;
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Encoding: UTF-8');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        if ($UserBrowser == 'IE')
        {
            header('Content-Disposition: inline; filename="' . $exportfilename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $exportfilename . '"');
            header('Pragma: no-cache');
        }

        if ($cid[0] != 0)
        {
            $where = " WHERE order_id IN (" . $oids . ")";
        }

        $q = "SELECT * FROM #__redshop_orders " . $where . " ORDER BY order_id asc";
        $this->_db->setQuery($q);
        $gls_arr = $this->_db->loadObjectList();

        for ($i = 0; $i < count($gls_arr); $i++)
        {
            $details = explode("|", $shipping->decryptShipping(str_replace(" ", "+", $gls_arr[$i]->ship_method_id)));

            if (($details[0] == 'plgredshop_shippingdefault_shipping_GLS') && $gls_arr[$i]->shop_id != "")
            {
                $orderproducts   = $order_helper->getOrderItemDetail($gls_arr[$i]->order_id);
                $shippingDetails = $order_helper->getOrderShippingUserInfo($gls_arr[$i]->order_id);
                $billingDetails  = $order_helper->getOrderBillingUserInfo($gls_arr[$i]->order_id);

                $totalWeight = "";
                $parceltype  = "";
                $qty         = "";
                for ($c = 0; $c < count($orderproducts); $c++)
                {
                    $product_id[] = $orderproducts [$c]->product_id;
                    $qty += $orderproducts [$c]->product_quantity;
                    $content_products[] = $orderproducts[$c]->order_item_name;

                    $sql = "SELECT weight FROM #__redshop_product WHERE product_id ='" . $orderproducts [$c]->product_id . "'";
                    $this->_db->setQuery($sql);
                    $weight = $this->_db->loadResult();
                    $totalWeight += ($weight * $orderproducts [$c]->product_quantity);
                }
                if (empty($totalWeight))
                {
                    $totalWeight = 1;
                }

                $parceltype      = 'A';
                $shopDetails_arr = explode("|", $gls_arr[$i]->shop_id);

                $userphoneArr = explode("###", $gls_arr[$i]->shop_id);

                $shopDetails_temparr = explode("###", $shopDetails_arr[7]);
                $shopDetails_arr[7]  = $shopDetails_temparr[0];

                $shopDetails_arr[2] = str_replace(',', '-', $shopDetails_arr[2]);
                $userDetail         = "";
                if ($shopDetails_arr[4] != 'DK')
                {
                    $shipmenttype = 'U';
                }
                else if ($gls_arr[$i]->ship_method_id != "")
                {
                    $userDetail = ',"' . $shippingDetails->firstname . ' ' . $shippingDetails->lastname . '","' . $gls_arr[$i]->customer_note . '","36515","' . $billingDetails->user_email . '"';
                    $userDetail .= ',"' . $userphoneArr[1]; //.",,,,,,,";
                }
                $shipmenttype = 'Z';
                echo '"' . $gls_arr[$i]->order_number . '","' . $shopDetails_arr[1] . '","' . $shopDetails_arr[2] . '","Pakkeshop: ' . $shopDetails_arr[0] . '","' . $shopDetails_arr[3] . '","' . $shopDetails_arr[7] . '","008","' . date("d-m-Y", $gls_arr[$i]->cdate) . '","' . $totalWeight . '","1"," "," ","' . $parceltype . '","' . $shipmenttype . '"' . $userDetail . '"'; //",,,,,,,,,,,,";exit;
                echo "\r\n";
            }
        }
        exit;
    }

    public function business_gls_export($cid)
    {
        $oids         = implode(',', $cid);
        $where        = "";
        $order_helper = new order_functions();
        $shipping     = new shipping();

        $exportfilename = 'redshop_gls_order_export.csv';
        /* Start output to the browser */
        if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
        {
            $UserBrowser = "Opera";
        }
        elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
        {
            $UserBrowser = "IE";
        }
        else
        {
            $UserBrowser = '';
        }
        $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

        /* Clean the buffer */
        while (@ob_end_clean())
        {
            ;
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Encoding: UTF-8');
        header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

        if ($UserBrowser == 'IE')
        {
            header('Content-Disposition: inline; filename="' . $exportfilename . '"');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: attachment; filename="' . $exportfilename . '"');
            header('Pragma: no-cache');
        }
        if ($cid[0] != 0)
        {
            $where = " WHERE order_id IN (" . $oids . ")";
        }

        $q = "SELECT * FROM #__redshop_orders " . $where . " ORDER BY order_id asc";
        $this->_db->setQuery($q);
        $gls_arr = $this->_db->loadObjectList();

        echo "Order_number,Quantity,Create_date,total_weight,reciever_firstName,reciever_lastname,Customer_note";
        echo "\r\n";

        for ($i = 0; $i < count($gls_arr); $i++)
        {
            $details = explode("|", $shipping->decryptShipping(str_replace(" ", "+", $gls_arr[$i]->ship_method_id)));

            if ($details[0] == 'shipper')
            {
                $orderproducts   = $order_helper->getOrderItemDetail($gls_arr[$i]->order_id);
                $shippingDetails = $order_helper->getOrderShippingUserInfo($gls_arr[$i]->order_id);
                $billingDetails  = $order_helper->getOrderBillingUserInfo($gls_arr[$i]->order_id);

                $totalWeight = "";
                $qty         = "";
                for ($c = 0; $c < count($orderproducts); $c++)
                {
                    $product_id[] = $orderproducts [$c]->product_id;
                    $qty += $orderproducts [$c]->product_quantity;
                    $content_products[] = $orderproducts[$c]->order_item_name;

                    $sql = "SELECT weight FROM #__redshop_product WHERE product_id ='" . $orderproducts [$c]->product_id . "'";
                    $this->_db->setQuery($sql);
                    $weight = $this->_db->loadResult();
                    $totalWeight += ($weight * $orderproducts [$c]->product_quantity);
                }

                $userDetail = ',"' . $shippingDetails->firstname . ' ' . $shippingDetails->lastname . '","' . $gls_arr[$i]->customer_note;

                echo '"' . $gls_arr[$i]->order_number . '","' . $qty . '","' . date("d-m-Y", $gls_arr[$i]->cdate) . '","' . $totalWeight . '","' . $userDetail . '"';
                echo "\r\n";
            }
        }

        exit;
    }

    public function delete($cid = array())
    {
        $producthelper   = new producthelper();
        $order_functions = new order_functions();
        $quotationHelper = new quotationHelper();
        $stockroomhelper = new rsstockroomhelper();

        if (count($cid))
        {
            if (ECONOMIC_INTEGRATION == 1)
            {
                $economic = new economic();
                for ($i = 0; $i < count($cid); $i++)
                {
                    $orderdata = $this->getTable('orders');
                    $orderdata->load($cid[$i]);
                    $invoiceHandle = $economic->deleteInvoiceInEconomic($orderdata);
                }
            }
            $cids = implode(',', $cid);

            $order_item = $order_functions->getOrderItemDetail($cids);
            for ($i = 0; $i < count($order_item); $i++)
            {
                $quntity = $order_item[$i]->product_quantity;

                $order_id     = $order_item[$i]->order_id;
                $order_detail = $order_functions->getOrderDetails($order_id);
                if ($order_detail->order_payment_status == "Unpaid")
                {
                    // update stock roommanageStockAmount
                    $stockroomhelper->manageStockAmount($order_item[$i]->product_id, $quntity, $order_item[$i]->stockroom_id);
                }
                $producthelper->makeAttributeOrder($order_item[$i]->order_item_id, 0, $order_item[$i]->product_id, 1);
                $query = "DELETE FROM `" . $this->_table_prefix . "order_attribute_item` " . "WHERE `order_item_id` = " . $order_item[$i]->order_item_id;
                $this->_db->setQuery($query);
                $this->_db->query();

                $query = "DELETE FROM `" . $this->_table_prefix . "order_acc_item` " . "WHERE `order_item_id` = " . $order_item[$i]->order_item_id;
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
                    $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_fields_data ' . 'WHERE quotation_item_id=' . $quotation_item[$j]->quotation_item_id;
                    $this->_db->setQuery($query);
                    if (!$this->_db->query())
                    {
                        $this->setError($this->_db->getErrorMsg());
                        return false;
                    }
                }
                $query = 'DELETE FROM ' . $this->_table_prefix . 'quotation_item ' . 'WHERE quotation_id=' . $quotation[$q]->quotation_id;
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
}
