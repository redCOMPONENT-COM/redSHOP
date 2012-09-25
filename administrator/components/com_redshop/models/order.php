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
class orderModelorder extends JModelLegacy
{
    public $_data = null;

    public $_total = null;

    public $_pagination = null;

    public $_table_prefix = null;

    public $_context = null;

    function __construct()
    {
        parent::__construct();

        global $mainframe;
        $this->_context        = 'order_id';
        $this->_table_prefix   = '#__redshop_';
        $limit                 = $mainframe->getUserStateFromRequest($this->_context . 'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
        $limitstart            = $mainframe->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);
        $filter_status         = $mainframe->getUserStateFromRequest($this->_context . 'filter_status', 'filter_status', '', 'word');
        $filter_payment_status = $mainframe->getUserStateFromRequest($this->_context . 'filter_payment_status', 'filter_payment_status', '', '');
        $filter                = $mainframe->getUserStateFromRequest($this->_context . 'filter', 'filter', 0);
        $limitstart            = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter', $filter);
        $this->setState('filter_status', $filter_status);
        $this->setState('filter_payment_status', $filter_payment_status);
    }

    function getData()
    {
        if (empty($this->_data))
        {
            $query       = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->_data;
    }

    function getTotal()
    {
        if (empty($this->_total))
        {
            $query        = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    function getPagination()
    {
        if (empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    function _buildQuery()
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

    function _buildContentOrderBy()
    {
        global $mainframe;

        $filter_order     = $mainframe->getUserStateFromRequest($this->_context . 'filter_order', 'filter_order', ' o.order_id');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($this->_context . 'filter_order_Dir', 'filter_order_Dir', ' DESC ');

        $orderby = ' ORDER BY ' . $filter_order . ' ' . $filter_order_Dir;

        return $orderby;
    }

    function update_status()
    {
        $order_functions = new order_functions();
        $order_functions->update_status();
    }

    function update_status_all()
    {
        $order_functions = new order_functions();
        $order_functions->update_status_all();
    }

    function export_data($cid)
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

    function updateDownloadSetting($did, $limit, $enddate)
    {

        $query = "UPDATE " . $this->_table_prefix . "product_download " . " SET `download_max` = " . $limit . " , `end_date` = " . $enddate . " " . " WHERE download_id = '" . $did . "'";
        $this->_db->setQuery($query);

        if (!$this->_db->Query())
        {
            return false;
        }
        return true;
    }

    function gls_export($cid)
    {
        global $mainframe;
        $oids                       = implode(',', $cid);
        $where                      = "";
        $redhelper                  = new redhelper();
        $order_helper               = new order_functions();
        $shipping                   = new shipping();
        $plugin                     = JPluginHelper::getPlugin('rs_labels_GLS');
        $glsparams                  = new JParameter($plugin[0]->params);
        $normal_parcel_weight_start = $glsparams->get('normal_parcel_weight_start', '');
        $normal_parcel_weight_end   = $glsparams->get('normal_parcel_weight_end', '');
        $small_parcel_weight_start  = $glsparams->get('small_parcel_weight_start', '');
        $small_parcel_weight_end    = $glsparams->get('small_parcel_weight_end', '');
        $pallet_parcel_weight_start = $glsparams->get('pallet_parcel_weight_start', '');
        $pallet_parcel_weight_end   = $glsparams->get('pallet_parcel_weight_end', '');
        /* Set the export filename */

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
        $db = JFactory::getDBO();
        $q  = "SELECT * FROM #__redshop_orders " . $where . " ORDER BY order_id asc";
        $db->setQuery($q);
        $gls_arr = $db->loadObjectList();

        //echo "Order_number,Consignee_name,Consignee_address_1,Consignee_address_2,Consignee_postal_code,Consignee_city,Consignee_country,Date,Parcel_weight,
        //Number_of_parcels,COD_amount,Parcel_value_amount,Parcel_type,Shipment_type,Attention,Comment,Customer_number,Alt_consignor_name,Consignee_mobile_phone_no,
        //Alt_consignor_name,Alt_consignor_address_1,Alt_consignor_address_2,Alt_consignor_postal_code,Alt_consignor_city,Alt_consignor_country,Alt_consignor_phone_no";
        //	echo "\r\n";

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
                    $db->setQuery($sql);
                    $weight = $db->loadResult();
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
                    $shipmenttype = 'Z';

                    //$shippingDetails->firstname = '"test, test"';
                    //$shippingDetails->firstname='test,test';
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

    function business_gls_export($cid)
    {
        global $mainframe;
        $oids         = implode(',', $cid);
        $where        = "";
        $redhelper    = new redhelper();
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
        $db = JFactory::getDBO();
        $q  = "SELECT * FROM #__redshop_orders " . $where . " ORDER BY order_id asc";
        $db->setQuery($q);
        $gls_arr = $db->loadObjectList();

        //echo "Order_number,quantity,Consignee_address_1,Consignee_address_2,Consignee_postal_code,Consignee_city,Consignee_country,Date,Parcel_weight,
        //Number_of_parcels,COD_amount,Parcel_value_amount,Parcel_type,Shipment_type,Attention,Comment,Customer_number,Alt_consignor_name,Consignee_mobile_phone_no,
        //Alt_consignor_name,Alt_consignor_address_1,Alt_consignor_address_2,Alt_consignor_postal_code,Alt_consignor_city,Alt_consignor_country,Alt_consignor_phone_no";
        //	echo "\r\n";
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
                    $db->setQuery($sql);
                    $weight = $db->loadResult();
                    $totalWeight += ($weight * $orderproducts [$c]->product_quantity);
                }

                $userDetail = ',"' . $shippingDetails->firstname . ' ' . $shippingDetails->lastname . '","' . $gls_arr[$i]->customer_note;

                echo '"' . $gls_arr[$i]->order_number . '","' . $qty . '","' . date("d-m-Y", $gls_arr[$i]->cdate) . '","' . $totalWeight . '","' . $userDetail . '"';
                echo "\r\n";
            }
        }

        exit;
    }
}
