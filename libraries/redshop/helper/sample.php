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
 * Sample helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.6
 */
class RedshopHelperSample
{
	/**
	 * Color mail function.
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendMail()
	{
		$date          = JFactory::getDate();
		$today         = time();
		$formattedDate = $date->format('Y-m-d');

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_sample_request'))
			->where($db->qn('block') . ' = 0');

		$sampleRequests = $db->setQuery($query)->loadObjectList();

		if (empty($sampleRequests))
		{
			return;
		}

		$config   = JFactory::getConfig();
		$from     = $config->get('mailfrom');
		$fromName = $config->get('fromname');

		foreach ($sampleRequests as $sampleRequest)
		{
			self::sendFirstReminder($sampleRequest, $today, $from, $fromName);

			self::sendSecondReminder($sampleRequest, $formattedDate, $from, $fromName);

			self::sendThirdReminder($sampleRequest, $formattedDate, $from, $fromName);
		}
	}

	/**
	 * Method for send first reminder of sample request
	 *
	 * @param   object   $sampleRequest  Sample request data
	 * @param   integer  $currentDate    Current date
	 * @param   string   $from           Email from for send mail
	 * @param   string   $fromName       Name for send mail
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendFirstReminder($sampleRequest = null, $currentDate = 0, $from = '', $fromName = '')
	{
		if (empty($sampleRequest) || !is_object($sampleRequest) || $sampleRequest->reminder_1 != 0)
		{
			return;
		}

		$sendDate = $sampleRequest->registerdate + (Redshop::getConfig()->get('COLOUR_SAMPLE_REMAINDER_1') * 60);

		if ($currentDate < $sendDate)
		{
			return;
		}

		$from     = empty($from) ? JFactory::getConfig()->get('mailfrom') : $from;
		$fromName = empty($fromName) ? JFactory::getConfig()->get('fromname') : $fromName;

		$mailBody = "";
		$subject  = "";
		$mailBcc  = null;
		$mailData = RedshopHelperMail::getMailTemplate(0, 'colour_sample_first_reminder');

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

		$body = str_replace("{name}", $sampleRequest->name, $mailBody);
		$body = RedshopHelperMail::imgInMail($body);

		if (JFactory::getMailer()->sendMail($from, $fromName, $sampleRequest->email, $subject, $body, $mode = 1, null, $mailBcc))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_sample_request'))
				->set($db->qn('reminder_1') . ' = 1')
				->where($db->qn('request_id') . ' = ' . $sampleRequest->request_id);

			$db->setQuery($query)->execute();
		}
	}

	/**
	 * Method for send second reminder of sample request
	 *
	 * @param   object  $sampleRequest  Sample request data
	 * @param   string  $currentDate    Current date
	 * @param   string  $from           Email from for send mail
	 * @param   string  $fromName       Name for send mail
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendSecondReminder($sampleRequest = null, $currentDate = '', $from = '', $fromName = '')
	{
		if (empty($sampleRequest) || !is_object($sampleRequest) || $sampleRequest->reminder_2 != 0)
		{
			return;
		}

		$currentDate = empty($currentDate) ? JFactory::getDate()->format('Y-m-d') : $currentDate;
		$sendDate    = date("Y-m-d", $sampleRequest->registerdate + (Redshop::getConfig()->get('COLOUR_SAMPLE_REMAINDER_2') * (60 * 60 * 24)));

		if ($currentDate != $sendDate)
		{
			return;
		}

		$from     = empty($from) ? JFactory::getConfig()->get('mailfrom') : $from;
		$fromName = empty($fromName) ? JFactory::getConfig()->get('fromname') : $fromName;

		$mailBody = "";
		$subject  = "";
		$mailBcc  = null;
		$mailData = RedshopHelperMail::getMailTemplate(0, 'colour_sample_second_reminder');

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

		$body = str_replace("{name}", $sampleRequest->name, $mailBody);
		$body = RedshopHelperMail::imgInMail($body);

		if (JFactory::getMailer()->sendMail($from, $fromName, $sampleRequest->email, $subject, $body, $mode = 1, null, $mailBcc))
		{
			$db = JFactory::getDbo();

			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_sample_request'))
				->set($db->qn('reminder_2') . ' = 1')
				->where($db->qn('request_id') . ' = ' . $sampleRequest->request_id);
			$db->setQuery($query)->execute();
		}
	}

	/**
	 * Method for send third reminder of sample request
	 *
	 * @param   object  $sampleRequest  Sample request data
	 * @param   string  $currentDate    Current date
	 * @param   string  $from           Email from for send mail
	 * @param   string  $fromName       Name for send mail
	 *
	 * @return  void
	 *
	 * @since   2.0.6
	 */
	public static function sendThirdReminder($sampleRequest = null, $currentDate = '', $from = '', $fromName = '')
	{
		if (empty($sampleRequest) || !is_object($sampleRequest) || ($sampleRequest->reminder_3 != 0 && $sampleRequest->reminder_coupon != 0))
		{
			return;
		}

		$currentDate = empty($currentDate) ? JFactory::getDate()->format('Y-m-d') : $currentDate;
		$db          = JFactory::getDbo();

		if ($sampleRequest->reminder_3 == 0)
		{
			$sendDate  = date("Y-m-d", $sampleRequest->registerdate + (Redshop::getConfig()->get('COLOUR_SAMPLE_REMAINDER_3') * 60 * 60 * 24));
			$goodToken = md5(uniqid(mt_rand(), true));
			$token     = substr($goodToken, 0, 10);
			$startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$endDate   = $startDate + (Redshop::getConfig()->get('COLOUR_COUPON_DURATION') * 23 * 59 * 59);

			if ($currentDate != $sendDate)
			{
				return;
			}

			$mailBody = "";
			$subject  = "";
			$mailBcc  = null;
			$mailData = RedshopHelperMail::getMailTemplate(0, 'colour_sample_third_reminder');

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

			$body = str_replace("{name}", $sampleRequest->name, $mailBody);
			$body = str_replace("{days}", Redshop::getConfig()->get('COLOUR_COUPON_DURATION'), $body);
			$body = str_replace("{discount}", Redshop::getConfig()->get('COLOUR_DISCOUNT_PERCENTAGE'), $body);
			$body = str_replace("{coupon_code}", $token, $body);
			$body = RedshopHelperMail::imgInMail($body);

			$query = $db->getQuery(true)
				->select($db->qn('id'))
				->from($db->qn('#__users'))
				->where($db->qn('email') . ' = ' . $db->quote($sampleRequest->email));
			$uid   = $db->setQuery($query)->loadResult();

			if (!empty($uid))
			{
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
			}

			if (JFactory::getMailer()->sendMail($from, $fromName, $sampleRequest->email, $subject, $body, $mode = 1, null, $mailBcc))
			{
				$query->clear()
					->update($db->qn('#__redshop_sample_request'))
					->set($db->qn('reminder_3') . ' = 1')
					->where($db->qn('request_id') . ' = ' . $sampleRequest->request_id);

				$db->setQuery($query)->execute();
			}
		}
		elseif ($sampleRequest->reminder_coupon == 0)
		{
			$sendDate = date("Y-m-d", $sampleRequest->registerdate + (4 * (60 * 60 * 24)));

			if ($currentDate != $sendDate)
			{
				return;
			}

			$query = $db->getQuery(true)
				->select($db->qn('id'))
				->from($db->qn('#__users'))
				->where($db->qn('email') . ' = ' . $db->quote($sampleRequest->email));
			$uid   = $db->setQuery($query)->loadResult();

			$query->clear()
				->select($db->qn('id'))
				->from($db->qn('#__redshop_coupons'))
				->where($db->qn('userid') . ' = ' . $db->quote($uid));
			$couponCode = $db->setQuery($query)->loadResult();

			$mailBody = "";
			$subject  = "";
			$mailBcc  = null;
			$mailData = RedshopHelperMail::getMailTemplate(0, 'colour_sample_third_reminder');

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

			$body = str_replace("{name}", $sampleRequest->name, $mailBody);
			$body = str_replace("{days}", Redshop::getConfig()->get('COLOUR_COUPON_DURATION'), $body);
			$body = str_replace("{discount}", Redshop::getConfig()->get('COLOUR_DISCOUNT_PERCENTAGE'), $body);
			$body = str_replace("{coupon_code}", $couponCode, $body);
			$body = RedshopHelperMail::imgInMail($body);

			if (JFactory::getMailer()->sendMail($from, $fromName, $sampleRequest->email, $subject, $body, $mode = 1, null, $mailBcc))
			{
				$query->clear()
					->update($db->qn('#__redshop_sample_request'))
					->set($db->qn('reminder_coupon') . ' = 1')
					->where($db->qn('request_id') . ' = ' . $sampleRequest->request_id);

				$db->setQuery($query)->execute();
			}
		}
	}
}
