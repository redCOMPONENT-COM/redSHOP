<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
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

