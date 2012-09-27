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

class shipping_rate_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'shipping_rate_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function apply()
    {
        $this->save(1);
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
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('shipping_rate_detail');

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id']);
    }

    public function publish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }
        $model = $this->getModel('shipping_rate_detail');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate');
    }

    public function unpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('shipping_rate_detail');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate');
    }

    public function cancel()
    {
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $this->setRedirect('index.php?option=' . $option . '&view=shipping_rate&id=' . $post['id']);
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

