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

class shipping_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'shipping_detail');
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
        $post   = $this->input->getArray($_POST);
        $option = $this->input->get('option');
        $model  = $this->getModel('shipping_detail');
        $row    = $model->store($post);

        if ($row)
        {
            $msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_shipping');
        }
        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shipping_detail&task=edit&cid[]=' . $post['extension_id'], $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
        }
    }

    public function publish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('shipping_detail');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=shipping');
    }

    public function unpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }
        $model = $this->getModel('shipping_detail');

        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=shipping');
    }

    public function cancel()
    {
        $option = $this->input->get('option');
        $this->setRedirect('index.php?option=' . $option . '&view=shipping');
    }

    /**
     * logic for orderup manufacturer
     *
     * @access public
     * @return void
     */
    public function orderup()
    {
        $option = $this->input->get('option');
        $model  = $this->getModel('shipping_detail');
        $model->move(-1);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
    }

    /**
     * logic for orderdown manufacturer
     *
     * @access public
     * @return void
     */
    public function orderdown()
    {
        $option = $this->input->get('option');
        $model  = $this->getModel('shipping_detail');
        $model->move(1);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
    }

    /**
     * logic for save an order
     *
     * @access public
     * @return void
     */
    public function saveorder()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(), 'array');
        $order  = $this->input->post->get('order', array(), 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('shipping_detail');
        $model->saveorder($cid);

        $msg = JText::_('COM_REDSHOP_SHIPPING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=shipping', $msg);
    }
}
