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

class addressfields_listingController extends RedshopCoreControllerDefault
{
    public function saveorder()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');
        $order  = $this->input->post->get('order', array(), 'array');

        $model = $this->getModel('addressfields_listing');

        if ($model->saveorder($cid, $order))
        {
            $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_NEW_ORDERING_ERROR');
        }

        $this->setRedirect('index.php?option=' . $option . '&view=addressfields_listing', $msg);
    }

    /**
     * logic for orderup manufacturer
     *
     * @access public
     * @return void
     */
    public function orderup()
    {
        global $context;

        $cid    = $this->input->post->get('cid', array(0), 'array');
        $option = $this->input->get('option');

        $filter_order_Dir = $this->app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $up               = 1;

        if (strtolower($filter_order_Dir) == "asc")
        {
            $up = -1;
        }

        $model = $this->getModel('addressfields_listing');
        $model->move($up, $cid[0]);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=addressfields_listing', $msg);
    }

    /**
     * logic for orderdown manufacturer
     *
     * @access public
     * @return void
     */
    public function orderdown()
    {
        global $context;

        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        $filter_order_Dir = $this->app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $down             = -1;

        if (strtolower($filter_order_Dir) == "asc")
        {
            $down = 1;
        }

        $model = $this->getModel('addressfields_listing');
        $model->move($down, $cid[0]);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=addressfields_listing', $msg);
    }
}
