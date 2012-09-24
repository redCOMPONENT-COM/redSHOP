<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Account Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class accountController extends JControllerLegacy
{
    /**
     * Method to edit created Tag
     *
     */
    function editTag()
    {
        global $mainframe;
        $Itemid = JRequest::getVar('Itemid');
        $option = JRequest::getVar('option');

        $post = JRequest::get('post');

        $model = $this->getModel('account');

        if ($model->editTag($post))
        {
            $mainframe->enqueueMessage(JText::_('COM_REDSHOP_TAG_EDITED_SUCCESSFULLY'));
        }
        else
        {
            $mainframe->enqueueMessage(JText::_('COM_REDSHOP_ERROR_EDITING_TAG'));
        }

        $this->setRedirect('index.php?option=' . $option . '&view=account&layout=mytags&Itemid=' . $Itemid);
    }

    /**
     * Method to send created wishlist
     *
     */
    function sendWishlist()
    {

        $post = JRequest::get('post');

        $emailto    = $post['emailto'];
        $sender     = $post['sender'];
        $email      = $post['email'];
        $subject    = $post['subject'];
        $Itemid     = $post['Itemid'];
        $wishlis_id = $post['wishlist_id'];
        $model      = $this->getModel('account');

        if ($emailto == "")
        {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_TO');
        }
        else if ($sender == "")
        {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SENDER_NAME');
        }
        else if ($email == "")
        {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SENDER_EMAIL');
        }
        else if ($subject == "")
        {
            $msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SUBJECT');
        }
        else if ($model->sendWishlist($post))
        {
            $msg = JText::_('COM_REDSHOP_SEND_SUCCESSFULLY');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SENDING');
        }

        $this->setRedirect('index.php?option=com_redshop&view=account&layout=mywishlist&mail=0&window=1&tmpl=component&wishlist_id=' . $wishlis_id . '&Itemid' . $Itemid, $msg);
    }

    /*
      *  Method to subscribe newsletter
      */
    function newsletterSubscribe()
    {
        $option = JRequest::getVar('option');
        $Itemid = JRequest::getVar('Itemid');

        $userhelper = new rsUserhelper();
        $userhelper->newsletterSubscribe(0, array(), 1);

        $msg = JText::_('COM_REDSHOP_SUBSCRIBE_SUCCESS');
        $this->setRedirect("index.php?option=" . $option . "&view=account&Itemid=" . $Itemid, $msg);
    }

    /*
      *  Method to unsubscribe newsletter
      */
    function newsletterUnsubscribe()
    {
        $user   = JFactory::getUser();
        $option = JRequest::getVar('option');
        $Itemid = JRequest::getVar('Itemid');

        $userhelper = new rsUserhelper();
        $userhelper->newsletterUnsubscribe($user->email);
        $msg = JText::_('COM_REDSHOP_CANCLE_SUBSCRIPTION');

        $this->setRedirect("index.php?option=" . $option . "&view=account&Itemid=" . $Itemid, $msg);
    }
}
