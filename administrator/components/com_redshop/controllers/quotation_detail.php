<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once(JPATH_ROOT . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'quotation.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'product.php');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class RedshopControllerQuotation_detail extends RedshopCoreController
{
    public $redirectViewName = 'quotation';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($send = 0)
    {
        $quotationHelper = new quotationHelper();
        $post            = $this->input->getArray($_POST);
        $option          = $this->input->getString('option', '');
        $cid             = $this->input->post->get('cid', array(0), 'array');

        $post['quotation_id'] = $cid [0];

        $model = $this->getModel('quotation_detail');

        if ($post['quotation_id'] == 0)
        {
            $post['quotation_cdate']   = time();
            $post['quotation_encrkey'] = $quotationHelper->randomQuotationEncrkey();
        }
        if ($post['user_id'] == 0 && $post['quotation_email'] == "")
        {
            $msg = JText::_('COM_REDSHOP_CREATE_ACCOUNT_FOR_QUOTATION');
            $this->setRedirect('index.php?option=' . $option . '&view=quotation_detail&task=edit&cid[]=' . $post['quotation_id'], $msg);
        }

        $quotation_item = array();
        $i              = 0;

        foreach ($post as $key=> $value)
        {
            if (!strcmp("quotation_item_id", substr($key, 0, 17)))
            {
                $quotation_item[$i]->quotation_item_id = $value;
            }
            if (!strcmp("product_excl_price", substr($key, 0, 18)))
            {
                $quotation_item[$i]->product_excl_price = $value;
            }
            if (!strcmp("product_price", substr($key, 0, 13)))
            {
                $quotation_item[$i]->product_price = $value;
            }
            if (!strcmp("quantity", substr($key, 0, 8)) && strlen($key) < 12)
            {
                $quotation_item[$i]->product_quantity = $value;
                $i++;
            }
        }

        $post['quotation_item'] = $quotation_item;
        $row                    = $model->store($post);
        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
        }

        $quotation_status = $post['quotation_status'] > 0 ? $post['quotation_status'] : 2;

        $quotationHelper->updateQuotationStatus($row->quotation_id, $quotation_status);

        if ($send == 1)
        {
            if ($model->sendQuotationMail($row->quotation_id))
            {
                $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
            }
        }
        $this->setRedirect('index.php?option=' . $option . '&view=quotation', $msg);
    }

    public function deleteitem()
    {
        $option  = $this->input->getString('option', '');
        $qitemid = $this->input->getInt('qitemid', 0);
        $cid     = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel('quotation_detail');

        if (!$model->deleteitem($qitemid, $cid[0]))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_QUOTATION_ITEM_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=quotation_detail&task=edit&cid[]=' . $cid[0], $msg);
    }

    public function newQuotationItem()
    {
        $adminproducthelper = new adminproducthelper();
        $post               = $this->input->getArray($_POST);
        $option             = $this->input->getString('option', '');
        $cid                = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel('quotation_detail');

        $quotationItem = $adminproducthelper->redesignProductItem($post);

        $post['quotation_item'] = $quotationItem;

        if ($model->newQuotationItem($post))
        {
            $msg = JText::_('COM_REDSHOP_QUOTATION_ITEM_ADDED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_ADDING_QUOTATION_ITEM');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=quotation_detail&cid[]=' . $cid[0], $msg);
    }

    public function getQuotationPriceTax()
    {
        $producthelper = new producthelper();
        $get           = $this->input->getArray($_GET);
        $product_id    = $get['product_id'];
        $user_id       = $get['user_id'];
        $newprice      = $get['newprice'];
        $vatprice      = 0;
        if ($newprice > 0)
        {
            $vatprice = $producthelper->getProductTax($product_id, $newprice, $user_id);
        }
        echo "<div id='newtax'>" . $vatprice . "</div>";
        exit;
    }
}
