<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper for Media
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperNewsletter
{
	/**
	 * Method for add an subscriber for Newsletter
	 *
	 * @param   int      $userId    ID of user.
	 * @param   array    $data      Data of subscriber
	 * @param   boolean  $sendMail  True for send mail.
	 * @param   null     $isNew     Capability for old method.
	 *
	 * @return  boolean             True on success. False otherwise.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function subscribe($userId = 0, $data = array(), $sendMail = false, $isNew = null)
	{
		$newsletter = 1;
		$userId     = (int) $userId;
		$user       = JFactory::getUser();

		if (!$userId)
		{
			$userId = $user->id;
		}

		if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') > 0)
		{
			$newsletter = Redshop::getConfig()->get('DEFAULT_NEWSLETTER');
		}

		if (empty($data))
		{
			$data['user_id']  = $user->id;
			$data['username'] = $user->username;
			$data['email']    = $user->email;
			$data['name']     = $user->name . " (" . $user->username . ")";
		}
		else
		{
			$data['user_id'] = $userId;

			if (isset($data['username']))
			{
				$data['name'] = $data['username'];
			}

			if ($user->id && $user->email == $data['email'])
			{
				$data['name'] = $user->name . " (" . $user->username . ")";
			}
		}

		$data['date']          = time();
		$data['newsletter_id'] = $newsletter;
		$data['published']     = 1;

		$needSendMail = Redshop::getConfig()->getBool('NEWSLETTER_CONFIRMATION') && $sendMail ? true : false;

		if ($needSendMail)
		{
			$data['published'] = 0;
		}

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

		/** @var Tablenewslettersubscr_detail $row */
		$row = JTable::getInstance('newslettersubscr_detail', 'Table');

		if (!$row->bind($data) || !$row->store())
		{
			JFactory::getApplication()->enqueueMessage($row->getError(), 'error');
		}

		if ($needSendMail)
		{
			RedshopHelperMail::sendNewsletterConfirmationMail($row->subscription_id);
		}

		return true;
	}
}
