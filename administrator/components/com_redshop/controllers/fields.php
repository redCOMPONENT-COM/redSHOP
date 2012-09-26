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

class fieldsController extends RedshopCoreController
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function saveorder()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');
        $model  = $this->getModel('fields');

        if ($model->saveorder($cid))
        {
            $msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_NEW_ORDERING_ERROR');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=fields', $msg);
    }
}
