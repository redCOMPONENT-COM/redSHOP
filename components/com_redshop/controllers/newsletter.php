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
include_once (JPATH_COMPONENT . DS . 'helpers' . DS . 'user.php');

/**
 * newsletterController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class newsletterController extends RedshopCoreController
{
    /*
      *  Method to subscribe newsletter
      */
    public function subscribe()
    {
        $post             = $this->input->getArray($_POST);
        $model            = $this->getModel('newsletter');
        $item_id          = $this->input->get('Itemid');
        $newsletteritemid = $this->input->get('newsletteritemid');
        $menu             = JSite::getMenu();
        $item             = $menu->getItem($newsletteritemid);
        if ($item)
        {
            $return = $item->link . '&Itemid=' . $newsletteritemid;
        }
        else
        {
            $return = "index.php?option=com_redshop&view=newsletter&layout=thankyou&Itemid=" . $item_id;
        }

        /*
            *  check if user has alreday subscribe.
            */
        $alreadysubscriberbymail = $model->checksubscriptionbymail($post['email1']);
        if ($alreadysubscriberbymail)
        {
            $msg = JText::_('COM_REDSHOP_ALREADY_NEWSLETTER_SUBSCRIBER');
        }
        else
        {
            $userhelper = new rsUserhelper();
            if ($userhelper->newsletterSubscribe(0, $post, 1))
            {
                if (NEWSLETTER_CONFIRMATION)
                {
                    $msg = JText::_('COM_REDSHOP_SUBSCRIBE_SUCCESS');
                }
                else
                {
                    $msg = JText::_('COM_REDSHOP_NEWSLEETER_SUBSCRIBE_SUCCESS');
                }
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_NEWSLEETER_SUBSCRIBE_FAIL');
            }
        }
        $this->setRedirect($return, $msg);
    }

    /*
      *  Method to unsubscribe newsletter
      */
    public function unsubscribe()
    {
        $model = $this->getModel('newsletter');

        $item_id          = $this->input->get('Itemid');
        $email            = $this->input->get('email1');
        $newsletteritemid = $this->input->get('newsletteritemid');
        $menu             = JSite::getMenu();
        $item             = $menu->getItem($newsletteritemid);
        if ($item)
        {
            $return = $item->link . '&Itemid=' . $newsletteritemid;
        }
        else
        {
            $return = "index.php?option=com_redshop&view=newsletter&layout=thankyou&Itemid=" . $item_id;
        }

        /*
             *  check if user has subscribe or not.
             */
        $alreadysubscriberbymail = $model->checksubscriptionbymail($email);

        if ($alreadysubscriberbymail)
        {
            $userhelper = new rsUserhelper();
            if ($userhelper->newsletterUnsubscribe($email))
            {
                $msg = JText::_('COM_REDSHOP_CANCLE_SUBSCRIPTION');
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_CANCLE_SUBSCRIPTION_FAIL');
            }
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ALREADY_CANCLE_SUBSCRIPTION');
        }

        $this->setRedirect($return, $msg);
    }
}

