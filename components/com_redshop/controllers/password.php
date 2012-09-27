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
 * passwordController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class passwordController extends RedshopCoreController
{
    /*
      *  Metod to reset Password
      */
    public function reset()
    {
        $post    = $this->input->getArray($_POST);
        $model   = &$this->getModel('password');
        $item_id = $this->input->get('Itemid');
        $layout  = "";
        //Request a reset
        if ($model->resetpassword($post))
        {
            $redshopMail = new redshopMail();
            if ($redshopMail->sendResetPasswordMail($post['email']))
            {
                $layout = "&layout=token";
                $msg    = JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_SEND');
            }
            else
            {
                $msg = JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_NOT_SEND');
            }
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_RESET_PASSWORD_MAIL_NOT_SEND');
        }
        $this->setRedirect('index.php?option=com_redshop&view=password' . $layout . '&Itemid=' . $item_id, $msg);
    }

    /*
      *  Method to changepassword
      */
    public function changepassword()
    {
        $post    = $this->input->getArray($_POST);
        $model   = &$this->getModel('password');
        $token   = $post['token'];
        $item_id = $this->input->get('Itemid');
        if ($model->changepassword($token))
        {
            parent::display();
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_RESET_PASSWORD_TOKEN_ERROR');
            $this->setRedirect('index.php?option=com_redshop&view=password&layout=token&Itemid=' . $item_id, $msg);
        }
    }

    /*
      *  Method to setpassword
      */
    public function setpassword()
    {
        $post    = $this->input->getArray($_POST);
        $item_id = $this->input->get('Itemid');

        $model = &$this->getModel('password');
        if ($model->setpassword($post))
        {
            $msg = JText::_('COM_REDSHOP_RESET_PASSWORD_DONE');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_RESET_PASSWORD_ERROR');
        }
        $this->setRedirect('index.php?option=com_redshop&view=login&Itemid=' . $item_id, $msg);
    }
}
