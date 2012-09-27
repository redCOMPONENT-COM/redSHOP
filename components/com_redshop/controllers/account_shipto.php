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

/**
 * account_shiptoController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class account_shiptoController extends RedshopCoreController
{
    /**
     * Method to save Shipping Address
     *
     */
    public function save()
    {
        $post    = $this->input->getArray($_POST);
        $return  = $this->input->get('return');
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $cid     = $this->input->post->get('cid', array(), 'array');

        $post['users_info_id'] = $cid[0];
        $post['id']            = $post['user_id'];
        $post['address_type']  = "ST";

        $model = $this->getModel('account_shipto');
        if ($reduser = $model->store($post))
        {
            $post['users_info_id'] = $reduser->users_info_id;
            $msg                   = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_SAVE');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_SHIPPING_INFORMATION');
        }

        $setexit = $this->input->getInt('setexit', 1);
        if ($return != "")
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&users_info_id=' . $post['users_info_id'] . '&Itemid=' . $item_id, false);

            if (!isset($setexit) || $setexit != 0)
            {
                ?>
            <script language="javascript">
                window.parent.location.href = "<?php echo $link ?>";
            </script>

            <?php
                exit;
            }
        }
        else
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=account_shipto&Itemid=' . $item_id, false);
        }
        $this->setRedirect($link, $msg);
    }

    /**
     * Method to delete shipping address
     *
     */
    public function remove()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $infoid  = $this->input->getString('infoid', '');
        $cid[0]  = $infoid;

        $model = $this->getModel('account_shipto');

        if (!is_array($cid) || count($cid) < 1)
        {
            JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }
        $msg    = JText::_('COM_REDSHOP_ACCOUNT_SHIPPING_DELETED_SUCCESSFULLY');
        $return = $this->input->get('return');

        if ($return != "")
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&Itemid=' . $item_id, false);
        }
        else
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=account_shipto&Itemid=' . $item_id, false);
        }
        $this->setRedirect($link, $msg);
    }

    /**
     * Method called when user pressed cancel button
     *
     */
    public function cancel()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        $cid     = $this->input->post->get('cid', array(), 'array');

        $post['users_info_id'] = $cid[0];

        $msg = JText::_('COM_REDSHOP_SHIPPING_INFORMATION_EDITING_CANCELLED');

        $return  = $this->input->get('return');
        $setexit = $this->input->getInt('setexit', 1);
        $link    = '';
        if ($return != "")
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&users_info_id=' . $post['users_info_id'] . '&Itemid=' . $item_id . '', false);

            if (!isset($setexit) || $setexit != 0)
            {
                ?>
            <script language="javascript">
                window.parent.location.href = "<?php echo $link ?>";
            </script>
            <?php
                exit;
            }
        }
        else
        {
            $link = 'index.php?option=' . $option . '&view=account_shipto&Itemid=' . $item_id;
        }
        $this->setRedirect($link, $msg);
    }
}

