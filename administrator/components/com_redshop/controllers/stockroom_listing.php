<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'default.php';

class RedshopControllerStockroom_listing extends RedshopCoreControllerDefault
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function saveStock()
    {
        $stockroom_type   = $this->input->post->getString('stockroom_type', 'product');
        $pid              = $this->input->post->get('pid', array(0), 'array');
        $sid              = $this->input->post->get('sid', array(0), 'array');
        $quantity         = $this->input->post->get('quantity', array(0), 'array');
        $preorder_stock   = $this->input->post->get('preorder_stock', array(0), 'array');
        $ordered_preorder = $this->input->post->get('ordered_preorder', array(0), 'array');

        $model = $this->getModel('stockroom_listing');

        for ($i = 0; $i < count($sid); $i++)
        {
            $model->storeStockroomQuantity($stockroom_type, $sid[$i], $pid[$i], $quantity[$i], $preorder_stock[$i], $ordered_preorder[$i]);
        }

        $this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type=' . $stockroom_type);
    }

    public function ResetPreorderStock()
    {
        $stockroom_type = $this->input->get('stockroom_type', 'product');
        $pid            = $this->input->get('product_id');
        $sid            = $this->input->get('stockroom_id');

        $model = $this->getModel('stockroom_listing');
        $model->ResetPreOrderStockroomQuantity($stockroom_type, $sid, $pid);

        $this->setRedirect('index.php?option=com_redshop&view=stockroom_listing&id=0&stockroom_type=' . $stockroom_type);
    }

    public function export_data()
    {
        $model = $this->getModel('stockroom_listing');
        $cid   = $this->input->get('category_id');

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
            header('Content-Disposition: inline; filename=StockroomProduct.csv');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        }
        else
        {
            header('Content-Disposition: attachment; filename=StockroomProduct.csv');
            header('Pragma: no-cache');
        }

        echo "Stockroom_Id,Stockroom_Name";
        echo ",Product_SKU,Product_Name,Quantity,M3\n\n";

        $product_ids = 0;
        if ($cid != "" && $cid != 0)
        {
            $product_list = $model->getProductIdsfromCategoryid($cid);

            for ($p = 0; $p < count($product_list); $p++)
            {
                $product_ids = implode(",", $product_list);
            }
        }
        $data = $model->getcontainerproducts($product_ids);

        for ($i = 0; $i < count($data); $i++)
        {
            echo $data[$i]->stockroom_id . ",";
            echo $data[$i]->stockroom_name . ",";
            echo $data[$i]->product_number . ",";
            echo $data[$i]->product_name . ",";
            echo $data[$i]->quantity . ",";
            echo $data[$i]->quantity * $data[$i]->product_volume . "\n";
        }

        exit;
    }

    public function print_data()
    {
        echo '<script type="text/javascript" language="javascript">	window.print(); </script>';
    }
}
