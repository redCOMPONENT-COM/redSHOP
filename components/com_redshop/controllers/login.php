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
 * loginController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class loginController extends RedshopCoreController
{
    /*
      *  setlogin function
      */
    public function setlogin()
    {
        $username     = $this->input->method->get('username', '');
        $password     = $this->input->post->getString('password', '');
        $option       = $this->input->get('option');
        $item_id      = $this->input->get('Itemid');
        $returnitemid = $this->input->get('returnitemid');
        $menu         = JSite::getMenu();
        $mywishlist   = $this->input->get('mywishlist');
        $item         = $menu->getItem($returnitemid);

        include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
        $redhelper = new redhelper();

        $model = &$this->getModel('login');

        $shoppergroupid = $this->input->post->getInt('protalid', 0);

        $msg = "";

        if ($shoppergroupid != 0)
        {
            $check = $model->CheckShopperGroup($username, $shoppergroupid);
            $link  = "index.php?option=" . $option . "&view=login&layout=portal&protalid=" . $shoppergroupid;
            if ($check > 0)
            {
                $model->setlogin($username, $password);
                $return = $this->input->get('return');
            }
            else
            {
                $msg    = JText::_("COM_REDSHOP_SHOPPERGROUP_NOT_MATCH");
                $return = "";
            }
        }
        else
        {
            $model->setlogin($username, $password);
            $return = $this->input->get('return');
        }

        if ($mywishlist == 1)
        {
            $wishreturn = JRoute::_('index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid=' . $item_id, false);
            $this->setRedirect($wishreturn);
        }
        else
        {
            if ($item)
            {
                $link = $item->link . '&Itemid=' . $returnitemid;
            }
            else
            {
                $link = 'index.php?option=' . $option . '&Itemid=' . $returnitemid;
            }

            if (!empty($return))
            {
                $s_Itemid = $redhelper->getCheckoutItemid();
                $item_id  = $s_Itemid ? $s_Itemid : $item_id;
                $return   = JRoute::_('index.php?option=com_redshop&view=checkout&Itemid=' . $item_id, false);

                $this->setRedirect($return);
            }
            else
            {
                $this->setRedirect($link, $msg);
            }
        }
    }

    /*
      *  logout function
      */
    public function logout()
    {

        $mainframe     = JFactory::getApplication();
        $logout_itemid = $this->input->get('logout');
        $menu          = JSite::getMenu();
        $item          = $menu->getItem($logout_itemid);
        if ($item)
        {
            $link = JRoute::_($item->link . '&Itemid=' . $logout_itemid);
        }
        else
        {
            $link = JRoute::_('index.php?option=com_redshop');
        }
        $mainframe->logout();
        $this->setRedirect($link);
    }
}
