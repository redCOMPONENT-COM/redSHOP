<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Account Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerAccount extends RedshopController
{
	/**
	 * Method to edit created Tag
	 *
	 * @return void
	 */
	public function editTag()
	{
		$app    = JFactory::getApplication();
		$post   = JRequest::get('post');
		$model  = $this->getModel('account');

		if ($model->editTag($post))
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_TAG_EDITED_SUCCESSFULLY'));
		}
		else
		{
			$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_EDITING_TAG'));
		}

		$this->setRedirect('index.php?option=com_redshop&view=account&layout=mytags&Itemid=' . $app->input->getInt('Itemid'));
	}

	/**
	 * Method to send created wishlist
	 *
	 * @return void
	 */
	public function sendWishlist()
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
		elseif ($sender == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SENDER_NAME');
		}
		elseif ($email == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SENDER_EMAIL');
		}
		elseif ($subject == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SUBJECT');
		}
		elseif ($model->sendWishlist($post))
		{
			$msg = JText::_('COM_REDSHOP_SEND_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SENDING');
		}

		$this->setRedirect('index.php?option=com_redshop&view=account&layout=mywishlist&mail=0&window=1&tmpl=component&wishlist_id=' . $wishlis_id . '&Itemid' . $Itemid, $msg);
	}

	/**
	 *  Method to subscribe newsletter
	 *
	 * @return void
	 */
	public function newsletterSubscribe()
	{
		$userhelper = rsUserHelper::getInstance();
		$userhelper->newsletterSubscribe(0, array(), 1);

		$Itemid = JFactory::getApplication()->input->getInt('Itemid');
		$this->setRedirect(
			"index.php?option=com_redshop&view=account&Itemid=" . $Itemid,
			JText::_('COM_REDSHOP_SUBSCRIBE_SUCCESS')
		);
	}

	/**
	 *  Method to unsubscribe newsletter
	 *
	 * @return void
	 */
	public function newsletterUnsubscribe()
	{
		$user       = JFactory::getUser();
		$Itemid     = JFactory::getApplication()->input->getInt('Itemid');
		$userhelper = rsUserHelper::getInstance();

		$userhelper->newsletterUnsubscribe($user->email);
		$msg = JText::_('COM_REDSHOP_CANCLE_SUBSCRIPTION');

		$this->setRedirect("index.php?option=com_redshop&view=account&Itemid=" . $Itemid, $msg);
	}
}
