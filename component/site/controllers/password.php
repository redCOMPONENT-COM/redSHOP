<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';
JLoader::import('joomla.application.component.controller');

/**
 * Password Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class PasswordController extends JController
{
	/**
	 *  Metod to reset Password
	 *
	 * @return  void
	 */
	public function reset()
	{
		$post   = JRequest::get('post');
		$model  = $this->getModel('password');
		$Itemid = JRequest::getVar('Itemid');
		$layout = "";

		// Request a reset
		if ($model->resetpassword($post))
		{
			$redshopMail = new redshopMail;

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

		$this->setRedirect('index.php?option=com_redshop&view=password' . $layout . '&Itemid=' . $Itemid, $msg);
	}

	/**
	 *  Method to changepassword
	 *
	 * @return  void
	 */
	public function changepassword()
	{
		$post   = JRequest::get('post');
		$model  = $this->getModel('password');
		$token  = $post['token'];
		$Itemid = JRequest::getVar('Itemid');

		if ($model->changepassword($token))
		{
			parent::display();
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_TOKEN_ERROR');
			$this->setRedirect('index.php?option=com_redshop&view=password&layout=token&Itemid=' . $Itemid, $msg);
		}
	}

	/**
	 *  Method to setpassword
	 *
	 * @return  void
	 */
	public function setpassword()
	{
		$post   = JRequest::get('post');
		$Itemid = JRequest::getVar('Itemid');
		$model  = $this->getModel('password');

		if ($model->setpassword($post))
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_DONE');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_RESET_PASSWORD_ERROR');
		}

		$this->setRedirect('index.php?option=com_redshop&view=login&Itemid=' . $Itemid, $msg);
	}
}
