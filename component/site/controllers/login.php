<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * login Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerLogin extends RedshopController
{
    /**
     *  Setlogin function
     *
     * @return  void
     */
    public function setlogin()
    {
        $username     = $this->input->getUsername('username', '');
        $password     = $this->input->post->get('password', '', 'raw');
        $Itemid       = $this->input->get('Itemid');
        $returnitemid = $this->input->get('returnitemid');
        $mywishlist   = $this->input->get('mywishlist');
        $productId    = $this->input->get('product_id');
        $menu         = JFactory::getApplication()->getMenu();
        $item         = $menu->getItem($returnitemid);

        $model = $this->getModel('login');

        $shoppergroupid = $this->input->post->getInt('protalid', '');

        $msg = "";

        if ($shoppergroupid != 0) {
            $check = $model->CheckShopperGroup($username, $shoppergroupid);
            $link  = "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $shoppergroupid;

            if ($check > 0) {
                $model->setlogin($username, $password);
                $return = $this->input->get('return');
            } else {
                $msg    = JText::_("COM_REDSHOP_SHOPPERGROUP_NOT_MATCH");
                $return = "";
            }
        } else {
            $model->setlogin($username, $password);
            $return = $this->input->get('return');
        }

        if ($mywishlist == 1) {
            $error = $model->setlogin($username, $password);

            if ($error == true) {
                $wishreturn = Redshop\IO\Route::_('index.php?option=com_redshop&view=product&pid=' . $productId, false);
            } else {
                $wishreturn = Redshop\IO\Route::_(
                    'index.php?loginwishlist=1&option=com_redshop&view=login&wishlist=1&Itemid=' . $Itemid,
                    false
                );
            }
            $this->setRedirect($wishreturn);
        } else {
            if ($item) {
                $link = $item->link . '&Itemid=' . $returnitemid;

                if ($item->params->get('aliasoptions')) {
                    $link = '/?Itemid=' . $returnitemid;
                }
            } else {
                $error = $model->setlogin($username, $password);

                if ($error == true) {
                    $link = Redshop\IO\Route::_('index.php?option=com_redshop&Itemid=' . $returnitemid, false);
                } else {
                    $link = Redshop\IO\Route::_('index.php?option=com_redshop&view=login&Itemid=' . $Itemid, false);
                }
            }

            if (!empty($return)) {
                $s_Itemid = RedshopHelperRouter::getCheckoutItemId();
                $Itemid   = $s_Itemid ? $s_Itemid : $Itemid;
                $return   = Redshop\IO\Route::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid, false);

                $this->setRedirect($return);
            } else {
                $this->setRedirect($link, $msg);
            }
        }
    }

    /**
     *  logout function
     *
     * @return void
     */
    public function logout()
    {
        $app           = JFactory::getApplication();
        $params        = $app->getParams('com_redshop');
        $logout_itemid = $this->input->get('logout');
        $menu          = JFactory::getApplication()->getMenu();
        $item          = $menu->getItem($logout_itemid);

        if ($item) {
            $link = Redshop\IO\Route::_($item->link . '&Itemid=' . $logout_itemid, false);
        } else {
            $link = Redshop\IO\Route::_('index.php?option=com_redshop', false);
        }

        $app->logout();
        $this->setRedirect($link);
    }
}
