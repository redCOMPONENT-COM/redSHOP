<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller' . DS . 'detail.php';

class shipping_rate_detailController extends RedshopCoreControllerDetail
{
    public $redirectViewName = 'shipping_rate';

    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function save($apply = 0)
    {
        $post = $this->input->getArray($_POST);

        // include extra field class
        require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');

        $option                           = $this->input->get('option');
        $post['shipping_rate_on_product'] = $post['container_product'];
        $post["shipping_location_info"]   = $this->input->post->getString('shipping_location_info', '');

        $model = $this->getModel('shipping_rate_detail');

        if ($row = $model->store($post))
        {
            $field = new extra_field();
            $field->extra_field_save($post, "11", $row->shipping_rate_id); // field_section 11 :Shipping
            $msg = JText::_('COM_REDSHOP_SHIPPING_LOCATION_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
        }
        if ($apply)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate_detail&cid=' . $row->shipping_rate_id . '&id=' . $post['id'], $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id'], $msg);
        }
    }

    public function remove()
    {
        parent::remove();

        $id = $this->input->post->get('id');
        $this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $id);
    }

    public function cancel()
    {
        $id = $this->input->post->get('id');
        $this->setRedirect('index.php?option=com_redshop&view=shipping_rate&id=' . $id);
    }

    public function copy()
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel('shipping_rate_detail');
        if ($model->copy($cid))
        {
            $msg = JText::_('COM_REDSHOP_SHIPPING_RATE_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id'], $msg);
    }

    public function GetStateDropdown()
    {
        $get   = $this->input->getArray($_GET);
        $model = $this->getModel('shipping_rate_detail');
        $model->GetStateDropdown($get);
        exit;
    }
}

