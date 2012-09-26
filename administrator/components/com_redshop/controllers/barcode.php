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

class barcodeController extends RedshopCoreController
{
    public function getsearch()
    {
        $post = $this->input->getArray($_POST);

        if (strlen($post['barcode']) != 13)
        {
            $msg = 'Invalid Barcode';
            JError::raiseWarning(0, $msg);
            parent::display();
        }

        else
        {
            $model   = $this->getModel('barcode');
            $barcode = $post['barcode'];
            $barcode = substr($barcode, 0, 12);

            $user = JFactory::getUser();
            $uid  = $user->get('id');

            $row = $model->checkorder($barcode);

            if ($row)
            {
                $post['search_date'] = date("y-m-d H:i:s");
                $post['user_id']     = $uid;
                $post['order_id']    = $row->order_id;

                if ($model->save($post))
                {
                    $msg = JText::_('COM_REDSHOP_THANKS_FOR_YOUR_REVIEWS');
                }

                else
                {
                    $msg = JText::_('COM_REDSHOP_ERROR_PLEASE_TRY_AGAIN');
                }

                //return $log;
                $this->setRedirect('index.php?option=com_redshop&view=barcode&order_id=' . $row->order_id, $msg);
            }

            else
            {
                $msg = 'Invalid Barcode';
                JError::raiseWarning(0, $msg);
                parent::display();
            }
        }
    }

    public function changestatus()
    {
        $post = $this->input->getArray($_POST);

        if (strlen($post['barcode']) != 13)
        {
            $msg = 'Invalid Barcode';
            JError::raiseWarning(0, $msg);
            $this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order');
        }

        else
        {
            $model   = $this->getModel('barcode');
            $barcode = $post['barcode'];
            $barcode = substr($barcode, 0, 12);
            $row     = $model->checkorder($barcode);

            if ($row)
            {
                $update_status = $model->updateorderstatus($barcode, $row->order_id);
                $this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order', JText::_('ORDER_STATUS_CHANGED_TO_SHIPPED'));
            }

            else
            {
                $msg = 'Invalid Barcode';
                JError::raiseWarning(0, $msg);
                $this->setRedirect('index.php?option=com_redshop&view=barcode&layout=barcode_order');
            }
        }
    }
}
