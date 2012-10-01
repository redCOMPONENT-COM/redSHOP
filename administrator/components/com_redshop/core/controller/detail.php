<?php
/**
 * @package     redSHOP
 * @subpackage  Core.Controller
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

/**
 * Base Controller for Detail Controllers.
 * ATM only used for detail controllers in Backend.
 *
 * @package     redSHOP
 * @subpackage  Core.Controller
 */
class RedshopCoreControllerDetail extends RedshopCoreController
{
    /**
     * @var  string  The name of the view to redirect to (not the detail one).
     */
    public $redirectViewName = '';

    /**
     * Default publish method.
     *
     * @return  void
     *
     * @throws  RuntimeException
     */
    public function publish()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'), 500);
        }

        $model = $this->getModel();

        if (!$model->publish($cid, 1))
        {
            throw new RuntimeException('Failed to publish the item(s).', 500);
        }

        $msg = JText::_('COM_REDSHOP_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default publish method.
     *
     * @return  void
     *
     * @throws  RuntimeException
     */
    public function unpublish()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'), 500);
        }

        $model = $this->getModel();

        if (!$model->publish($cid, 0))
        {
            throw new RuntimeException('Failed to unpublish the item(s).', 500);
        }

        $msg = JText::_('COM_REDSHOP_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default remove method.
     *
     * @return  void
     *
     * @throws  RuntimeException
     */
    public function remove()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'), 500);
        }

        $model = $this->getModel();

        if (!$model->delete($cid))
        {
            throw new RuntimeException('Failed to delete the item(s).', 500);
        }

        $msg = JText::_('COM_REDSHOP_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default cancel method.
     *
     * @return  void
     */
    public function cancel()
    {
        $msg = JText::_('COM_REDSHOP_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default edit method.
     *
     * @return  void
     */
    public function edit()
    {
        $this->input->set('view', $this->getName());
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    /**
     * Default apply method.
     *
     * @return  void
     */
    public function apply()
    {
        $this->save(1);
    }

    /**
     * Default send method.
     *
     * @return  void
     */
    public function send()
    {
        $this->save(1);
    }

    /**
     * Default orderup method.
     *
     * @return  void
     */
    public function orderup()
    {
        $model = $this->getModel();

        $model->orderup();

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default orderdown method.
     *
     * @return  void
     */
    public function orderdown()
    {
        $model = $this->getModel();

        $model->orderdown();

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default save order method.
     *
     * @access public
     * @return void
     */
    public function saveorder()
    {
        $cid   = $this->input->post->get('cid', array(0), 'array');
        $order = $this->input->post->get('order', array(), 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel();
        $model->saveorder($cid, $order);

        $msg = JText::_('COM_REDSHOP_ORDERING_SAVED');
        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }

    /**
     * Default copy method.
     *
     * @return  void
     */
    public function copy()
    {
        $cid = $this->input->post->get('cid', array(0), 'array');

        $model = $this->getModel();

        if ($model->copy($cid))
        {
            $msg = JText::_('COM_REDSHOP_COPIED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_COPIED');
        }

        $this->setRedirect('index.php?option=com_redshop&view=' . $this->redirectViewName, $msg);
    }
}

