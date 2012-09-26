<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

define('WARNSAME', "There is already a file called '%s'.");
define('INSTALLEXT', 'Install %s %s');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class payment_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function install()
    {
        $model = $this->getModel('payment_detail');

        $model->install();

        $this->input->set('view', 'payment_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function edit()
    {
        $this->input->set('view', 'payment_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);
        parent::display();
    }

    public function save()
    {
        $post                          = $this->input->getArray($_POST);
        $option                        = $this->input->get('option');
        $accepted_credit_card          = $this->input->post->get('accepted_credict_card', '', 'array');
        $accepted_credit_card          = implode(",", $accepted_credit_card);
        $post["accepted_credict_card"] = $accepted_credit_card;

        $model                     = $this->getModel('payment_detail');
        $post["payment_extrainfo"] = $this->input->post->getString('payment_extrainfo', '');

        if ($model->store($post))
        {

            $msg = JText::_('COM_REDSHOP_PAYMENT_SAVED');
        }
        else
        {

            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_PAYMENT');
        }

        $this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
    }

    public function remove()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel('payment_detail');

        $model->uninstall($cid);

        $this->setRedirect('index.php?option=' . $option . '&view=payment');
    }

    public function publish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('payment_detail');

        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=payment');
    }

    public function unpublish()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('payment_detail');

        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $this->setRedirect('index.php?option=' . $option . '&view=payment');
    }

    public function cancel()
    {
        $option = $this->input->get('option');
        $this->setRedirect('index.php?option=' . $option . '&view=payment');
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

        $model = $this->getModel('payment_detail');
        $model->move(-1);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
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
        $model  = $this->getModel('payment_detail');
        $model->move(1);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
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

        $model = $this->getModel('payment_detail');
        $model->saveorder($cid);

        $msg = JText::_('COM_REDSHOP_PAYMENT_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=payment', $msg);
    }
}
