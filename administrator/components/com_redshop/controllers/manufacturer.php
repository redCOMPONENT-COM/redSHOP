<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class manufacturerController extends JController
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

        $cid   = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');

        JArrayHelper::toInteger($cid);
        JArrayHelper::toInteger($order);

        $model = $this->getModel('manufacturer');
        $model->saveorder($cid);

        $msg = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_SAVED');
        $this->setRedirect('index.php?option=' . $option . '&view=manufacturer', $msg);
    }
}

