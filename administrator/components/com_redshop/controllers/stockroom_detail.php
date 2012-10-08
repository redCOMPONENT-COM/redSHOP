<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class RedshopControllerStockroom_detail extends RedshopCoreController
{
    public $redirectViewName = 'stockroom';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function preview()
    {
        $this->input->set('view', 'stockroom_detail');
        $this->input->set('layout', 'default_product');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function save($apply = 0)
    {
        $post           = $this->input->getArray($_POST);
        $stockroom_desc = $this->input->post->getString('stockroom_desc', '');

        $post["stockroom_desc"] = $stockroom_desc;
        if ($post["delivery_time"] == 'Weeks')
        {
            $post["min_del_time"] = $post["min_del_time"] * 7;
            $post["max_del_time"] = $post["max_del_time"] * 7;
        }
        $option                 = $this->input->get('option');
        $cid                    = $this->input->post->get('cid', array(0), 'array');
        $post ['stockroom_id']  = $cid [0];
        $post ['creation_date'] = strtotime($post ['creation_date']);
        $model                  = $this->getModel('stockroom_detail');
        $post['stockroom_name'] = htmlspecialchars($post['stockroom_name']);

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_STOCKROOM_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_STOCKROOM_DETAIL');
        }
        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=stockroom_detail&task=edit&cid[]=' . $row->stockroom_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
        }
    }

    public function frontpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('stockroom_detail');
        if (!$model->frontpublish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
    }

    public function frontunpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('stockroom_detail');
        if (!$model->frontpublish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_STOCK_ROOM_DETAIL_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=stockroom', $msg);
    }

    public function export_data()
    {
        $model = $this->getModel('stockroom_detail');

        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-type: text/x-csv");
        header("Content-type: text/csv");
        header("Content-type: application/csv");
        header('Content-Disposition: attachment; filename=StockroomProduct.csv');

        echo "Stockroom,Container,Product SKU,Product Name,Product Volume,Quantity\n\n";

        $data = $model->stock_container(0);

        for ($i = 0; $i < count($data); $i++)
        {
            $product = $model->stock_product($data[$i]->container_id);

            echo $data[$i]->stockroom_name . ",";
            echo $data[$i]->container_name . ",";

            for ($p = 0; $p < count($product); $p++)
            {
                if ($p > 0)
                {
                    echo ",,";
                }
                echo $product[$p]->product_number . ",";
                echo $product[$p]->product_name . ",";
                echo $product[$p]->product_volume . ",";
                echo $product[$p]->quantity . "\n";
            }
            echo "\n";
        }
        exit;
    }

    public function importStockFromEconomic()
    {
        #Add product stock from economic
        $cnt          = $this->input->getInt('cnt', 0);
        $stockroom_id = $this->input->getInt('stockroom_id', 0);
        $totalprd     = 0;
        $msg          = '';
        if (ECONOMIC_INTEGRATION == 1)
        {
            $economic = new economic();
            $db       = JFactory::getDBO();
            $incNo    = $cnt;
            $query    = 'SELECT p.* FROM #__redshop_product AS p ' . 'LIMIT ' . $cnt . ', 10 ';
            $db->setQuery($query);
            $prd         = $db->loadObjectlist();
            $totalprd    = count($prd);
            $responcemsg = '';
            for ($i = 0; $i < count($prd); $i++)
            {
                $incNo++;
                $ecoProductNumber = $economic->importStockFromEconomic($prd[$i]);
                $responcemsg .= "<div>" . $incNo . ": " . JText::_('COM_REDSHOP_PRODUCT_NUMBER') . " " . $prd[$i]->product_number . " -> ";
                if (count($ecoProductNumber) > 0 && isset($ecoProductNumber[0]))
                {
                    $query = "UPDATE #__redshop_product_stockroom_xref " . "SET quantity='" . $ecoProductNumber[0] . "' " . "WHERE product_id='" . $prd[$i]->product_id . "' " . "AND stockroom_id='" . $stockroom_id . "' ";
                    $db->setQuery($query);
                    $db->Query();
                    $responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_SUCCESS') . "</span>";
                }
                else
                {
                    $errmsg = JText::_('COM_REDSHOP_ERROR_IN_IMPORT_STOCK_FROM_ECONOMIC');
                    if (JError::isError(JError::getError()))
                    {
                        $error  = JError::getError();
                        $errmsg = $error->message;
                    }
                    $responcemsg .= "<span style='color: #ff0000'>" . $errmsg . "</span>";
                }
                $responcemsg .= "</div>";
            }
            if ($totalprd > 0)
            {
                $msg = $responcemsg;
            }
            else
            {
                $msg = JText::_("COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC_IS_COMPLETED");
            }
        }
        echo "<div id='sentresponse'>" . $totalprd . "`_`" . $msg . "</div>";
        die();
    }
}
