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
 * Newsletter Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerNewsletter extends RedshopController
{
	/**
	 *  Method to subscribe newsletter
	 *
	 * @return  void
	 */
	public function subscribe()
	{
		$post             = $this->input->post->getArray();
		$model            = $this->getModel('newsletter');
		$Itemid           = $this->input->get('Itemid');
		$newsletteritemid = $this->input->get('newsletteritemid');
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
		$alreadysubscriberbymail = $model->checksubscriptionbymail($post['email']);

		if ($alreadysubscriberbymail)
		{
			$msg = JText::_('COM_REDSHOP_ALREADY_NEWSLETTER_SUBSCRIBER');
		}
		else
		{
			$userhelper = rsUserHelper::getInstance();

			if ($userhelper->newsletterSubscribe(0, $post, 1))
			{
				if (Redshop::getConfig()->get('NEWSLETTER_CONFIRMATION'))
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
		$post  = $this->input->get->getArray();
		$model = $this->getModel('newsletter');

		$Itemid           = $this->input->get('Itemid');
		$email            = $this->input->get('email');
		$newsletteritemid = $this->input->get('newsletteritemid');
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
			if (RedshopHelperNewsletter::removeSubscribe($email))
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

	/**
	 * Newsletter tracker to confirm email is read
	 *
	 * @return  void
	 */
	public function tracker()
	{
		$db    = JFactory::getDbo();

		$trackerId = $this->input->getInt('tracker_id', 0);

		if (!$trackerId)
		{
			JError::raiseError(500, 'No Tracking Id found.');
		}

		// Mark Newsletter as read
		$query = $db->getQuery(true)
					->update($db->qn('#__redshop_newsletter_tracker'))
					->set($db->qn('read') . ' = 1')
					->where($db->qn('tracker_id') . ' = ' . (int) $trackerId);

		// Set the query and execute the update.
		$db->setQuery($query)->execute();

		// Set image header
		header("Content-type: image/gif");
		readfile(JURI::root() . 'components/com_redshop/assets/images/spacer.gif');

		JFactory::getApplication()->close();
	}
}
