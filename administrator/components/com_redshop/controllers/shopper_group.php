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

class RedshopControllerShopper_group extends RedshopCoreControllerDefault
{
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    public function remove()
    {
        $option = $this->input->get('option');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        if (!is_array($cid) && ($cid == 1 || $cid == 2))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
        }

        else if (in_array(1, $cid))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
        }

        else if (in_array(2, $cid))
        {
            $msg = JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CAN_NOT_BE_DELETED');
        }

        else
        {
            $model = $this->getModel('shopper_group_detail');
            if (!$model->delete($cid))
            {
                echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
            }

            $msg = JText::_('COM_REDSHOP_SHOPPER_GROUP_DETAIL_DELETED_SUCCESSFULLY');
        }
        $this->setRedirect('index.php?option=' . $option . '&view=shopper_group', $msg);
    }
}

