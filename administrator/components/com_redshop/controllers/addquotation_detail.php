<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class addquotation_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->input->set('hidemainmenu', 1);
    }

    public function save($send = 0)
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $post ['quotation_id'] = $cid [0];
        $model                 = $this->getModel('addquotation_detail');

        $adminproducthelper = new adminproducthelper();

        $acl = JFactory::getACL();

        if (!$post['users_info_id'])
        {
            $name             = $post['firstname'] . ' ' . $post['lastname'];
            $post['usertype'] = "Registered";
            $post['email']    = $post['user_email'];

            $post['username'] = $this->input->post->getUsername('username', '');

            $post['name'] = $name;

            $post['password']  = $this->input->post->getString('password', '');
            $post['password2'] = $this->input->post->getString('password2', '');

            $post['gid'] = $acl->get_group_id('', 'Registered', 'ARO');

            $date                 = JFactory::getDate();
            $post['registerDate'] = $date->toMySQL();
            $post['block']        = 0;

            # get Admin order detail Model Object
            $usermodel = JModel::getInstance('user_detail', 'user_detailModel');

            # call Admin order detail Model store public function for Billing
            $user = $usermodel->storeUser($post);

            if (!$user)
            {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $post['user_id'] = $user->id;
            $user_id         = $user->id;

            $user_data             = $model->storeShipping($post);
            $post['users_info_id'] = $user_data->users_info_id;
            if (count($user_data) <= 0)
            {
                $this->setRedirect('index.php?option=' . $option . '&view=quotaion_detail&user_id=' . $user_id);
            }
        }

        $orderItem          = $adminproducthelper->redesignProductItem($post);
        $post['order_item'] = $orderItem;

        $post['user_info_id'] = $post['users_info_id'];

        $row = $model->store($post);
        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SAVED');
            if ($send == 1)
            {
                if ($model->sendQuotationMail($row->quotation_id))
                {
                    $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_SENT');
                }
            }
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUOTATION_DETAIL');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=quotation', $msg);
    }

    public function send()
    {
        $this->save(1);
    }

    public function cancel()
    {
        $option = $this->input->getString('option', '');

        $msg = JText::_('COM_REDSHOP_QUOTATION_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=quotation', $msg);
    }

    public function displayOfflineSubProperty()
    {
        $product_id   = $this->input->get->getInt('product_id', 0);
        $accessory_id = $this->input->get->getInt('accessory_id', 0);
        $attribute_id = $this->input->get->getInt('attribute_id', 0);
        $user_id      = $this->input->get->getInt('user_id', 0);
        $unique_id    = $this->input->get->getInt('unique_id', 0);
        $propid       = $this->input->get->get('property_id');
        $propid       = explode(",", $propid);

        $model = $this->getModel('addquotation_detail');

        $response = "";

        foreach ($propid as $property)
        {
            $response .= $model->replaceSubPropertyData($product_id, $accessory_id, $attribute_id, $property, $user_id, $unique_id);
        }

        echo $response;
        exit;
    }
}
