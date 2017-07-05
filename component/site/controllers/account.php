<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
		$app   = JFactory::getApplication();
		$model = $this->getModel('account');

		if ($model->editTag($app->input->post->getArray()))
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
		$input      = JFactory::getApplication()->input->post;
		$itemId     = $input->get('Itemid');
		$wishlisId = $input->get('wishlist_id');

		$model = $this->getModel('account');

		if ($input->get('emailto') == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_TO');
		}
		elseif ($input->get('sender') == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SENDER_NAME');
		}
		elseif ($input->get('email') == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SENDER_EMAIL');
		}
		elseif ($input->get('subject') == "")
		{
			$msg = JText::_('COM_REDSHOP_PLEASE_ENTER_SUBJECT');
		}
		elseif ($model->sendWishlist($input->getArray()))
		{
			$msg = JText::_('COM_REDSHOP_SEND_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SENDING');
		}

		$this->setRedirect('index.php?option=com_redshop&view=account&layout=mywishlist&mail=0&window=1&tmpl=component&wishlist_id=' . $wishlisId . '&Itemid' . $itemId, $msg);
	}

	/**
	 * Method to subscribe newsletter
	 *
	 * @return  void
	 */
	public function newsletterSubscribe()
	{
		RedshopHelperNewsletter::subscribe(0, array(), 1);

		$itemId = JFactory::getApplication()->input->getInt('Itemid');
		$this->setRedirect(
			"index.php?option=com_redshop&view=account&Itemid=" . $itemId,
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
		$itemId     = JFactory::getApplication()->input->getInt('Itemid');
		$userhelper = rsUserHelper::getInstance();

		$userhelper->newsletterUnsubscribe($user->email);
		$msg = JText::_('COM_REDSHOP_CANCLE_SUBSCRIPTION');

		$this->setRedirect("index.php?option=com_redshop&view=account&Itemid=" . $itemId, $msg);
	}
}
