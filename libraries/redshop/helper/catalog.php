<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Catalog helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.6
 */
class RedshopHelperCatalog
{
	/**
	 * Catalog mail function
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendMail()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_catalog_request'))
			->where($db->qn('block') . ' = 0');

		$catalogs = $db->setQuery($query)->loadObjectList();

		if (empty($catalogs))
		{
			return;
		}

		$config        = JFactory::getConfig();
		$from          = $config->get('mailfrom');
		$fromName      = $config->get('fromname');
		$formattedDate = JFactory::getDate()->format('Y-m-d');

		foreach ($catalogs as $catalog)
		{
			self::sendFirstReminder($catalog, $formattedDate, $from, $fromName);

			if ($catalog->reminder_2 == 0)
			{
				self::sendSecondReminder($catalog, $formattedDate, $from, $fromName);
			}
			elseif ($catalog->reminder_3 == 0)
			{
				self::sendThirdReminder($catalog, $formattedDate, $from, $fromName);
			}
		}
	}

	/**
	 * Method for send first reminder of catalog
	 *
	 * @param   object $catalog     Catalog data
	 * @param   string $currentDate Current date
	 * @param   string $from        Email from for send mail
	 * @param   string $fromName    Name for send mail
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendFirstReminder($catalog = null, $currentDate = '', $from = '', $fromName = '')
	{
		if (empty($catalog) || !is_object($catalog) || $catalog->reminder_1 != 0)
		{
			return;
		}

		$currentDate = empty($currentDate) ? JFactory::getDate()->format('Y-m-d') : $currentDate;
		$sendDate    = date("Y-m-d", $catalog->registerDate + (Redshop::getConfig()->get('CATALOG_REMINDER_1') * (60 * 60 * 24)));

		if ($currentDate != $sendDate)
		{
			return;
		}

		$from     = empty($from) ? JFactory::getConfig()->get('mailfrom') : $from;
		$fromName = empty($fromName) ? JFactory::getConfig()->get('fromname') : $fromName;

		$mailBody = "";
		$subject  = "";
		$mailBcc  = null;
		$mailData = RedshopHelperMail::getMailTemplate(0, "catalog_first_reminder");

		if (count($mailData) > 0)
		{
			$mailData = $mailData[0];
			$mailBody = $mailData->mail_body;
			$subject  = $mailData->mail_subject;

			if (trim($mailData->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailData->mail_bcc);
			}
		}

		$body = str_replace("{name}", $catalog->name, $mailBody);
		$body = str_replace("{discount}", Redshop::getConfig()->get('DISCOUNT_PERCENTAGE'), $body);
		$body = RedshopHelperMail::imgInMail($body);

		if (JFactory::getMailer()->sendMail($from, $fromName, $catalog->email, $subject, $body, $mode = 1, null, $mailBcc))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_catalog_request'))
				->set($db->qn('reminder_1') . ' = ' . $db->quote(1))
				->where($db->qn('catalog_user_id') . ' = ' . $catalog->catalog_user_id);

			$db->setQuery($query)->execute();
		}
	}

	/**
	 * Method for send second reminder of catalog
	 *
	 * @param   object $catalog     Catalog data
	 * @param   string $currentDate Current date
	 * @param   string $from        Email from for send mail
	 * @param   string $fromName    Name for send mail
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendSecondReminder($catalog = null, $currentDate = '', $from = '', $fromName = '')
	{
		if (empty($catalog) || !is_object($catalog) || $catalog->reminder_2 != 0)
		{
			return;
		}

		$currentDate = empty($currentDate) ? JFactory::getDate()->format('Y-m-d') : $currentDate;
		$sendDate    = date("Y-m-d", $catalog->registerDate + (Redshop::getConfig()->get('CATALOG_REMINDER_2') * (60 * 60 * 24)));

		if ($currentDate != $sendDate)
		{
			return;
		}

		$from      = empty($from) ? JFactory::getConfig()->get('mailfrom') : $from;
		$fromName  = empty($fromName) ? JFactory::getConfig()->get('fromname') : $fromName;
		$token     = md5(uniqid(mt_rand(), true));
		$token     = substr($token, 0, 10);
		$startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$endDate   = $startDate + (Redshop::getConfig()->get('DISCOUNT_DURATION') * 23 * 59 * 59);

		$mailBody = "";
		$subject  = "";
		$mailBcc  = null;
		$mailData = RedshopHelperMail::getMailTemplate(0, "catalog_second_reminder");

		if (count($mailData) > 0)
		{
			$mailData = $mailData[0];
			$mailBody = $mailData->mail_body;
			$subject  = $mailData->mail_subject;

			if (trim($mailData->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailData->mail_bcc);
			}
		}

		$body = str_replace("{name}", $catalog->name, $mailBody);
		$body = str_replace("{days}", Redshop::getConfig()->get('DISCOUNT_DURATION'), $body);
		$body = str_replace("{discount}", Redshop::getConfig()->get('DISCOUNT_PERCENTAGE'), $body);
		$body = str_replace("{coupon_code}", $token, $body);
		$body = RedshopHelperMail::imgInMail($body);

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__users'))
			->where($db->qn('email') . ' = ' . $db->quote($catalog->email));

		$uid = $db->setQuery($query)->loadResult();

		$query->clear()
			->insert($db->qn('#__redshop_coupons'))
			->columns(
				$db->qn(
					array('coupon_code', 'percent_or_total', 'coupon_value', 'start_date', 'end_date', 'coupon_type', 'userid', 'published')
				)
			)
			->values(
				$db->quote($token) . ',' . $db->quote(1) . ',' . $db->quote(Redshop::getConfig()->get('DISCOUNT_PERCENTAGE')) . ',' .
				$db->quote($startDate) . ',' . $db->quote($endDate) . ',' . $db->quote(1) . ',' . $db->quote($uid) . ',' . $db->quote(1)
			);

		$db->setQuery($query)->execute();

		if (JFactory::getMailer()->sendMail($from, $fromName, $catalog->email, $subject, $body, $mode = 1, null, $mailBcc))
		{
			$query->clear()
				->update($db->qn('#__redshop_catalog_request'))
				->set($db->qn('reminder_2') . ' = ' . $db->quote(1))
				->where($db->qn('catalog_user_id') . ' = ' . $catalog->catalog_user_id);

			$db->setQuery($query)->execute();
		}
	}

	/**
	 * Method for send second reminder of catalog
	 *
	 * @param   object $catalog     Catalog data
	 * @param   string $currentDate Current date
	 * @param   string $from        Email from for send mail
	 * @param   string $fromName    Name for send mail
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendThirdReminder($catalog = null, $currentDate = '', $from = '', $fromName = '')
	{
		if (empty($catalog) || !is_object($catalog) || $catalog->reminder_3 != 0)
		{
			return;
		}

		$currentDate = empty($currentDate) ? JFactory::getDate()->format('Y-m-d') : $currentDate;

		// Coupon reminder
		$sendDate = date(
			"Y-m-d",
			$catalog->registerDate + (Redshop::getConfig()->get('DISCOUNT_DURATION') * (60 * 60 * 24)) + (4 * 60 * 60 * 24)
		);

		if ($currentDate != $sendDate)
		{
			return;
		}

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__users'))
			->where($db->qn('email') . ' = ' . $db->quote($catalog->email));

		$uid = $db->setQuery($query)->loadResult();

		$query->clear()
			->select($db->qn('id'))
			->from($db->qn('#__redshop_coupons'))
			->where($db->qn('userid') . ' = ' . $db->quote($uid));

		$couponCode = $db->setQuery($query)->loadResult();

		$from     = empty($from) ? JFactory::getConfig()->get('mailfrom') : $from;
		$fromName = empty($fromName) ? JFactory::getConfig()->get('fromname') : $fromName;

		$mailBody = "";
		$subject  = "";
		$mailBcc  = null;
		$mailData = RedshopHelperMail::getMailTemplate(0, "catalog_coupon_reminder");

		if (count($mailData) > 0)
		{
			$mailData = $mailData[0];
			$mailBody = $mailData->mail_body;
			$subject  = $mailData->mail_subject;

			if (trim($mailData->mail_bcc) != "")
			{
				$mailBcc = explode(",", $mailData->mail_bcc);
			}
		}

		$body = str_replace("{name}", $catalog->name, $mailBody);
		$body = str_replace("{discount}", Redshop::getConfig()->get('DISCOUNT_PERCENTAGE'), $body);
		$body = str_replace("{coupon_code}", $couponCode, $body);
		$body = RedshopHelperMail::imgInMail($body);

		if (JFactory::getMailer()->sendMail($from, $fromName, $catalog->email, $subject, $body, $mode = 1, null, $mailBcc))
		{
			$query->clear()
				->update($db->qn('#__redshop_catalog_request'))
				->set($db->qn('reminder_3') . ' = ' . $db->quote(1))
				->where($db->qn('catalog_user_id') . ' = ' . $catalog->catalog_user_id);

			$db->setQuery($query)->execute();
		}
	}
}
