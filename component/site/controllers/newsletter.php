<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('joomla.application.component.controller');

include_once JPATH_COMPONENT . '/helpers/user.php';

/**
 * Newsletter Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class NewsletterController extends JController
{
	/**
	 *  Method to subscribe newsletter
	 *
	 * @return  void
	 */
	public function subscribe()
	{
		$post             = JRequest::get('post');
		$model            = $this->getModel('newsletter');
		$option           = JRequest::getVar('option');
		$Itemid           = JRequest::getVar('Itemid');
		$newsletteritemid = JRequest::getVar('newsletteritemid');
		$menu             = JFactory::getApplication()->getMenu();
		$item             = $menu->getItem($newsletteritemid);

		if ($item)
		{
			$return = $item->link . '&Itemid=' . $newsletteritemid;
		}
		else
		{
			$return = "index.php?option=com_redshop&view=newsletter&layout=thankyou&Itemid=" . $Itemid;
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
			$userhelper = new rsUserhelper;

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

	/**
	 *  Method to unsubscribe newsletter
	 *
	 * @return void
	 */
	public function unsubscribe()
	{
		$post  = JRequest::get('get');
		$model = $this->getModel('newsletter');

		$option           = JRequest::getVar('option');
		$Itemid           = JRequest::getVar('Itemid');
		$email            = JRequest::getVar('email1');
		$newsletteritemid = JRequest::getVar('newsletteritemid');
		$menu             = JFactory::getApplication()->getMenu();
		$item             = $menu->getItem($newsletteritemid);

		if ($item)
		{
			$return = $item->link . '&Itemid=' . $newsletteritemid;
		}
		else
		{
			$return = "index.php?option=com_redshop&view=newsletter&layout=thankyou&Itemid=" . $Itemid;
		}

		/*
 	 	 *  check if user has subscribe or not.
 	 	 */
		$alreadysubscriberbymail = $model->checksubscriptionbymail($email);

		if ($alreadysubscriberbymail)
		{
			$userhelper = new rsUserhelper;

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
