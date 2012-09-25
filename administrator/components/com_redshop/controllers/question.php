<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class questionController extends JControllerLegacy
{
    function cancel()
    {
        $this->setRedirect('index.php');
    }

    /**
     * logic for save an order
     *
     * @access public
     * @return void
     */
    function saveorder()
    {
        $option = JRequest::getVar('option');
        $cid    = JRequest::getVar('cid', array(), 'post', 'array');
        $order  = JRequest::getVar('order', array(), 'post', 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);
        $model = $this->getModel('question');
        $model->saveorder($cid, $order);

        $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=question', $msg);
    }
}
