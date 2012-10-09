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
 * wishlistController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class wishlistController extends RedshopCoreController
{
    /**
     * createsave wishlist function
     *
     * @access public
     * @return void
     */
    public function createsave()
    {
        $user  = JFactory::getUser();
        $model = $this->getModel("wishlist");

        $post ['wishlist_name'] = $this->input->get('txtWishlistname');
        $post ['user_id']       = $user->id;
        $post ['cdate']         = time();
        if ($model->store($post))
        {
            echo "<div>" . JText::_('COM_REDSHOP_PRODUCT_SAVED_IN_WISHLIST_SUCCESSFULLY') . "</div>";
        }
        else
        {
            echo "<div>" . JText::_('COM_REDSHOP_PRODUCT_NOT_SAVED_IN_WISHLIST') . "</div>";
        }
        if ($this->input->get('loginwishlist') == 1)
        {
            $wishreturn = JRoute::_('index.php?option=com_redshop&view=wishlist&task=viewwishlist&Itemid=' . $this->input->get('Itemid'), false);
            $this->setRedirect($wishreturn);
        }
        else
        {
            ?>
        <script language="javascript">
            var t = setTimeout("window.parent.SqueezeBox.close();window.parent.location.reload();", 2000);
        </script>
        <?php
        }
    }

    /**
     * savewishlist function
     *
     * @access public
     * @return void
     */
    public function savewishlist()
    {
        $model = $this->getModel("wishlist");

        if ($model->savewishlist())
        {
            echo "<div>" . JText::_('COM_REDSHOP_PRODUCT_SAVED_IN_WISHLIST_SUCCESSFULLY') . "</div>";
        }
        else
        {
            echo "<div>" . JText::_('COM_REDSHOP_PRODUCT_NOT_SAVED_IN_WISHLIST') . "</div>";
        }

        ?>
    <script language="javascript">
        var t = setTimeout("window.parent.SqueezeBox.close();window.parent.location.reload()", 2000);
    </script>
    <?php
    }

    /**
     * delete wishlist function
     *
     * @access public
     * @return void
     */
    public function delwishlist()
    {
        $user  = JFactory::getUser();
        $model = $this->getModel("wishlist");

        $item_id = $this->input->get('Itemid');
        $option  = $this->input->get('option');
        $post    = $this->input->getArray($_REQUEST);

        if ($model->check_user_wishlist_authority($user->id, $post["wishlist_id"]))
        {
            if ($model->delwishlist($user->id, $post["wishlist_id"]))
            {
                $msg = JText::_('COM_REDSHOP_WISHLIST_DELETED_SUCCESSFULLY');
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_ERROR_IN_DELETING_WISHLIST');
            }
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_YOU_ARE_NOT_AUTHORIZE_TO_DELETE');
        }

        $link = JRoute::_("index.php?option=" . $option . "&view=wishlist&task=viewwishlist&Itemid=" . $item_id, false);
        ;

        $this->app->redirect($link, $msg);
    }

    public function mysessdelwishlist()
    {
        $wishlist_id = $this->input->get('wishlist_id');
        $mydel       = $this->input->get('mydel');
        $model       = $this->getModel("wishlist");
        $option      = $this->input->get('option');
        $item_id     = $this->input->get('Itemid');

        if ($mydel != '')
        {
            $msg = '';
            if ($model->mysessdelwishlist($wishlist_id))
            {
                $msg = JText::_('COM_REDSHOP_WISHLIST_DELETED_SUCCESSFULLY');
            }

            $link = JRoute::_("index.php?mydel=1&option=" . $option . "&view=wishlist&task=viewwishlist&Itemid=" . $item_id, false);
            $this->setRedirect($link, $msg);
        }
    }
}
