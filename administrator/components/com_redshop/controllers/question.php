<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class questionController extends RedshopCoreController
{
    public function cancel()
    {
        $this->setRedirect('index.php');
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

        $model = $this->getModel('question');
        $model->saveorder($cid, $order);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }
}
