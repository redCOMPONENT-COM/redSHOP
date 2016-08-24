<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Cron class
 *
 * @since  1.5
 */
class RedshopHelperCron
{
	/**
	 * Init redshop cron
	 *
	 * @return  void
	 */
	public static function init()
	{
		if (DISCOUNT_MAIL_SEND)
		{
			self::afterPurchasedOrderMail();
		}

		// Move to Stockroom start
		$formattedDate = date('Y-m-d', time());

		$db = JFactory::getDbo();

		// Calculation to run once in day
		$query = $db->getQuery(true)
			->select('count(id)')
			->from($db->qn('#__redshop_cron'))
			->where($db->qn('date') . ' = ' . $db->q($formattedDate));

		$data = $db->setQuery($query, 0, 1)->loadResult();

		if ($data != 1)
		{
			// Default $data != 1
			$query = $db->getQuery(true)
				->update($db->qn('#__redshop_cron'))
				->set($db->qn('date') . ' = ' . $db->q($formattedDate))
				->where($db->qn('id') . ' = 1');

			$db->setQuery($query)->execute();

			if (SEND_CATALOG_REMINDER_MAIL)
			{
				self::catalogMail();
			}

			self::colorMail();

			// Send subscription renewal mail.
			self::subscriptionRenewalMail();
		}
	}

	/**
	 * Catalog mail function
	 *
	 * @return void
	 */
	public static function catalogMail()
	{
		$date          = JFactory::getDate();
		$mail          = redshopMail::getInstance();
		$formattedDate = $date->format('Y-m-d');

		$db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_catalog_request where block = 0 ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		foreach ($data as $catalog)
		{
			if ($catalog->reminder_1 == 0)
			{
				$sendDate = date("Y-m-d", $catalog->registerDate + (CATALOG_REMINDER_1 * (60 * 60 * 24)));

				if ($formattedDate == $sendDate)
				{
					$body         = "";
					$subject      = "";
					$bcc          = null;
					$mailTemplate = $mail->getMailtemplate(0, "catalog_first_reminder");

					if (count($mailTemplate) > 0)
					{
						$mailTemplate = $mailTemplate[0];
						$body         = $mailTemplate->mail_body;
						$subject      = $mailTemplate->mail_subject;

						if (trim($mailTemplate->mail_bcc) != "")
						{
							$bcc = explode(",", $mailTemplate->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');
					$recipient = $catalog->email;

					$body = str_replace("{name}", $catalog->name, $body);
					$body = str_replace("{discount}", DISCOUNT_PERCENTAGE, $body);
					$body = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_catalog_request SET reminder_1 = 1 WHERE catalog_user_id = " . (int) $catalog->catalog_user_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}

			if ($catalog->reminder_2 == 0)
			{
				$sendDate  = date("Y-m-d", $catalog->registerDate + (CATALOG_REMINDER_2 * (60 * 60 * 24)));
				$token     = substr(md5(uniqid(mt_rand(), true)), 0, 10);
				$startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
				$endDate   = $startDate + (DISCOUNT_DURATION * 23 * 59 * 59);

				if ($formattedDate == $sendDate)
				{
					$body         = "";
					$subject      = "";
					$bcc          = null;
					$mailTemplate = $mail->getMailtemplate(0, "catalog_second_reminder");

					if (count($mailTemplate) > 0)
					{
						$mailTemplate = $mailTemplate[0];
						$body         = $mailTemplate->mail_body;
						$subject      = $mailTemplate->mail_subject;

						if (trim($mailTemplate->mail_bcc) != "")
						{
							$bcc = explode(",", $mailTemplate->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');
					$recipient = $catalog->email;

					$body = str_replace("{name}", $catalog->name, $body);
					$body = str_replace("{days}", DISCOUNT_DURATION, $body);
					$body = str_replace("{discount}", DISCOUNT_PERCENTAGE, $body);
					$body = str_replace("{coupon_code}", $token, $body);
					$body = $mail->imginmail($body);

					$sql = "select id FROM #__users where email = " . $db->quote($recipient);
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "INSERT INTO  #__redshop_coupons` (`coupon_code`, `percent_or_total`, `coupon_value`, `start_date`, `end_date`, `coupon_type`, `userid`, `published`) "
						. "VALUES ('" . $token . "', '1', '" . DISCOUNT_PERCENTAGE . "', " . $db->quote($startDate)
						. ", " . $db->quote($endDate) . ", '1', " . (int) $uid . ", '1')";

					$db->setQuery($sql);
					$db->execute();

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_catalog_request SET reminder_2 = 1 WHERE catalog_user_id = " . $catalog->catalog_user_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			elseif ($catalog->reminder_3 == 0)
			{
				// Coupon reminder
				$sendDate = date("Y-m-d", $catalog->registerDate + (DISCOUNT_DURATION * (60 * 60 * 24)) + (4 * 60 * 60 * 24));

				$sql = "select id FROM #__users where email = " . $db->quote($catalog->email);
				$db->setQuery($sql);
				$uid = $db->loadResult();

				$sql = "select id FROM #__redshop_coupons where userid = " . (int) $uid;
				$db->setQuery($sql);
				$coupon_code = $db->loadResult();

				if ($formattedDate == $sendDate)
				{
					$body         = "";
					$subject      = "";
					$bcc          = null;
					$mailTemplate = $mail->getMailtemplate(0, "catalog_coupon_reminder");

					if (count($mailTemplate) > 0)
					{
						$mailTemplate = $mailTemplate[0];
						$body         = $mailTemplate->mail_body;
						$subject      = $mailTemplate->mail_subject;

						if (trim($mailTemplate->mail_bcc) != "")
						{
							$bcc = explode(",", $mailTemplate->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');
					$recipient = $catalog->email;

					$body = str_replace("{name}", $catalog->name, $body);
					$body = str_replace("{discount}", DISCOUNT_PERCENTAGE, $body);
					$body = str_replace("{coupon_code}", $coupon_code, $body);
					$body = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_catalog_request SET reminder_3 = 1 WHERE catalog_user_id = " . (int) $catalog->catalog_user_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
		}
	}

	/**
	 * After purchased order mail function
	 *
	 * @return void
	 */
	public static function afterPurchasedOrderMail()
	{
		$mail            = redshopMail::getInstance();
		$redshopConfig   = Redconfiguration::getInstance();
		$stockroomHelper = rsstockroomhelper::getInstance();
		$db              = JFactory::getDbo();
		$date            = JFactory::getDate();
		$formattedDate   = $date->format('Y-m-d');

		$query = $db->getQuery(true)
			->select(
				array(
					'o.*',
					'CONCAT(' . $db->qn('uf.firstname') . ',\' \',' . $db->qn('uf.lastname') . ') as name',
					$db->qn('uf.user_email', 'email')
				)
			)
			->from($db->qn('#__redshop_orders', 'o'))
			->leftJoin(
				$db->qn('#__redshop_order_users_info', 'uf')
				. ' ON ' . $db->qn('uf.order_id') . ' = ' . $db->qn('o.order_id')
				. ' AND ' . $db->qn('uf.address_type') . ' = ' . $db->q('BT')
			)
			->where($db->qn('o.order_payment_status') . ' = ' . $db->q('Paid'))
			->where($db->qn('o.order_status') . ' = ' . $db->q('C'));

		$orders = $db->setQuery($query)->loadObjectList('order_id');

		$orderIds = array_keys($orders);

		if (empty($orderIds))
		{
			return;
		}

		$sql = $db->getQuery(true)
			->select(
				array(
					$db->qn('coupon_left', 'total'),
					$db->qn('coupon_code'),
					$db->qn('end_date'),
					$db->qn('order_id')
				)
			)
			->from($db->qn('#__redshop_coupons'))
			->where($db->qn('order_id') . ' IN(' . implode(',', $orderIds) . ')')
			->where($db->qn('coupon_left') . ' != 0');

		$coupons = $db->setQuery($sql)->loadObjectList('order_id');

		if (empty($coupons))
		{
			return;
		}

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');

		foreach ($orders as $order)
		{
			$body         = "";
			$subject      = "";
			$orderId      = $order->order_id;
			$bcc          = null;
			$config       = JFactory::getConfig();
			$from         = $config->get('mailfrom');
			$fromName     = $config->get('fromname');
			$startDate    = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$endDate      = $startDate + (DISCOUPON_DURATION * 23 * 59 * 59);
			$validEndDate = $redshopConfig->convertDateFormat($endDate);

			$couponValue = DISCOUPON_VALUE . " %";

			if (DISCOUPON_PERCENT_OR_TOTAL == 0)
			{
				$couponValue = REDCURRENCY_SYMBOL . ' ' . number_format(DISCOUPON_VALUE, 2, PRICE_SEPERATOR, THOUSAND_SEPERATOR);
			}

			if ($order->mail1_status != 0 || !isset($coupons[$orderId]))
			{
				continue;
			}

			$total         = $coupons[$orderId]->total;
			$coupon_code   = $coupons[$orderId]->coupon_code;
			$couponEndDate = $coupons[$orderId]->end_date;

			$recipient = $order->email;
			$name      = $order->name;

			if ($order->mail1_status == 0 && (DAYS_MAIL1 != 0 || DAYS_MAIL1 != ''))
			{
				$sendDate      = date("Y-m-d", $order->cdate + (DAYS_MAIL1 * (60 * 60 * 24)));
				$firstMailData = $mail->getMailtemplate(0, "first_mail_after_order_purchased");

				if (count($firstMailData) > 0)
				{
					$body    = $firstMailData[0]->mail_body;
					$subject = $firstMailData[0]->mail_subject;

					if (trim($firstMailData[0]->mail_bcc) != "")
					{
						$bcc = explode(",", $firstMailData[0]->mail_bcc);
					}
				}

				$jPathUrl = '<a href="' . JUri::root() . '">' . JUri::root() . '</a>';
				$body     = str_replace("{name}", $name, $body);
				$body     = str_replace("{url}", $jPathUrl, $body);
				$body     = str_replace("{coupon_amount}", $couponValue, $body);

				if ($formattedDate == $sendDate)
				{
					$token = substr(md5(uniqid(mt_rand(), true)), 0, 10);
					$body  = str_replace("{coupon_code}", $token, $body);
					$body  = str_replace("{coupon_duration}", $validEndDate, $body);
					$body  = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$couponItems                   = JTable::getInstance('coupon_detail', 'Table');
						$couponItems->coupon_code      = $token;
						$couponItems->percent_or_total = DISCOUPON_PERCENT_OR_TOTAL;
						$couponItems->coupon_value     = DISCOUPON_VALUE;
						$couponItems->start_date       = $startDate;
						$couponItems->end_date         = $endDate;
						$couponItems->coupon_type      = 1;
						$couponItems->userid           = $order->user_id;
						$couponItems->coupon_left      = 1;
						$couponItems->published        = 1;
						$couponItems->order_id         = $orderId;
						$couponItems->store();

						$q_update = "UPDATE #__redshop_orders SET mail1_status = 1 WHERE order_id = " . $orderId;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			elseif ($order->mail2_status == 0 && (DAYS_MAIL2 != 0 || DAYS_MAIL2 != '') && $total != 0)
			{
				$sendDate       = date("Y-m-d", $order->cdate + (DAYS_MAIL2 * (59 * 59 * 23)));
				$secondMailData = $mail->getMailtemplate(0, "second_mail_after_order_purchased");

				if (count($secondMailData) > 0)
				{
					$body    = $secondMailData[0]->mail_body;
					$subject = $secondMailData[0]->mail_subject;

					if (trim($secondMailData[0]->mail_bcc) != "")
					{
						$bcc = explode(",", $secondMailData[0]->mail_bcc);
					}
				}

				$days     = $stockroomHelper->getdatediff($couponEndDate, $startDate);
				$jPathUrl = '<a href="' . JUri::root() . '">' . JUri::root() . '</a>';
				$body     = str_replace("{name}", $name, $body);
				$body     = str_replace("{url}", $jPathUrl, $body);
				$body     = str_replace("{coupon_amount}", $couponValue, $body);

				if ($days && $formattedDate == $sendDate)
				{
					$validEndDate = $redshopConfig->convertDateFormat($couponEndDate);
					$body         = str_replace("{coupon_code}", $coupon_code, $body);
					$body         = str_replace("{coupon_duration}", $validEndDate, $body);
					$body         = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_orders SET mail2_status = 1 WHERE order_id = " . $orderId;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			elseif ($order->mail3_status == 0 && (DAYS_MAIL3 != 0 || DAYS_MAIL3 != '') && $total != 0)
			{
				// Coupon reminder
				$thirdMailData = $mail->getMailtemplate(0, "third_mail_after_order_purchased");

				if (count($thirdMailData) > 0)
				{
					$body    = $thirdMailData[0]->mail_body;
					$subject = $thirdMailData[0]->mail_subject;

					if (trim($thirdMailData[0]->mail_bcc) != "")
					{
						$bcc = explode(",", $thirdMailData[0]->mail_bcc);
					}
				}

				$sendDate = date("Y-m-d", $order->cdate + (DAYS_MAIL3 * (60 * 60 * 24)));
				$days     = $stockroomHelper->getdatediff($couponEndDate, $startDate);
				$jPathUrl = '<a href="' . JUri::root() . '">' . JUri::root() . '</a>';
				$body     = str_replace("{name}", $name, $body);
				$body     = str_replace("{url}", $jPathUrl, $body);
				$body     = str_replace("{coupon_amount}", $couponValue, $body);

				if ($days && $formattedDate == $sendDate)
				{
					$validEndDate = $redshopConfig->convertDateFormat($couponEndDate);
					$body         = str_replace("{coupon_code}", $coupon_code, $body);
					$body         = str_replace("{coupon_duration}", $validEndDate, $body);
					$body         = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_orders SET mail3_status = 1 WHERE order_id = " . $orderId;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
		}
	}

	/**
	 * Color mail function.
	 *
	 * @return void
	 */
	public static function colorMail()
	{
		$date          = JFactory::getDate();
		$mail          = redshopMail::getInstance();
		$formattedDate = $date->format('Y-m-d');

		$db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_sample_request where block = 0 ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		foreach ($data as $sample)
		{
			if ($sample->reminder_1 == 0)
			{
				$sendDate = $sample->registerdate + (COLOUR_SAMPLE_REMAINDER_1 * (60));

				if (time() >= $sendDate)
				{
					$body         = "";
					$subject      = "";
					$bcc          = null;
					$mailTemplate = $mail->getMailtemplate(0, 'colour_sample_first_reminder');

					if (count($mailTemplate) > 0)
					{
						$mailTemplate = $mailTemplate[0];
						$body         = $mailTemplate->mail_body;
						$subject      = $mailTemplate->mail_subject;

						if (trim($mailTemplate->mail_bcc) != "")
						{
							$bcc = explode(",", $mailTemplate->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');
					$recipient = $sample->email;

					$body = str_replace("{name}", $sample->name, $body);
					$body = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_sample_request SET reminder_1 = 1 WHERE request_id  = " . (int) $sample->request_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}

			if ($sample->reminder_2 == 0)
			{
				$sendDate = date("Y-m-d", $sample->registerdate + (COLOUR_SAMPLE_REMAINDER_2 * (60 * 60 * 24)));

				if ($formattedDate == $sendDate)
				{
					$body         = "";
					$subject      = "";
					$bcc          = null;
					$mailTemplate = $mail->getMailtemplate(0, 'colour_sample_second_reminder');

					if (count($mailTemplate) > 0)
					{
						$mailTemplate = $mailTemplate[0];
						$body         = $mailTemplate->mail_body;
						$subject      = $mailTemplate->mail_subject;

						if (trim($mailTemplate->mail_bcc) != "")
						{
							$bcc = explode(",", $mailTemplate->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');
					$recipient = $sample->email;

					$body = str_replace("{name}", $sample->name, $body);
					$body = $mail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_sample_request SET reminder_2 = 1 WHERE request_id  = " . (int) $sample->request_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}

			if ($sample->reminder_3 == 0)
			{
				$sendDate = date("Y-m-d", $sample->registerdate + (COLOUR_SAMPLE_REMAINDER_3 * (60 * 60 * 24)));
				$token    = substr(md5(uniqid(mt_rand(), true)), 0, 10);

				$startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

				$endDate = $startDate + (COLOUR_COUPON_DURATION * 23 * 59 * 59);

				if ($formattedDate == $sendDate)
				{
					$body         = "";
					$subject      = "";
					$bcc          = null;
					$mailTemplate = $mail->getMailtemplate(0, 'colour_sample_third_reminder');

					if (count($mailTemplate) > 0)
					{
						$mailTemplate = $mailTemplate[0];
						$body         = $mailTemplate->mail_body;
						$subject      = $mailTemplate->mail_subject;

						if (trim($mailTemplate->mail_bcc) != "")
						{
							$bcc = explode(",", $mailTemplate->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromName  = $config->get('fromname');
					$recipient = $sample->email;

					$body = str_replace("{name}", $sample->name, $body);
					$body = str_replace("{days}", COLOUR_COUPON_DURATION, $body);
					$body = str_replace("{discount}", COLOUR_DISCOUNT_PERCENTAGE, $body);
					$body = str_replace("{coupon_code}", $token, $body);
					$body = $mail->imginmail($body);

					$sql = "select id FROM #__users where email = " . $db->quote($recipient);
					$db->setQuery($sql);

					if ($uid = $db->loadResult())
					{
						$sql = "INSERT INTO  #__redshop_coupons` (`coupon_code`, `percent_or_total`, `coupon_value`, `start_date`, `end_date`, `coupon_type`, `userid`, `published`)
										VALUES (" . $db->quote($token) . ", '1', '" . DISCOUNT_PERCENTAGE . "', " . $db->quote($startDate) . ", " . $db->quote($endDate) . ", '1', '" . (int) $uid . "', '1')";

						$db->setQuery($sql);
						$db->execute();
					}

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
					{
						$q_update = "UPDATE #__redshop_sample_request SET reminder_3 = 1 WHERE request_id  = " . (int) $sample->request_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			else
			{
				if ($sample->reminder_coupon == 0)
				{
					$sendDate = date("Y-m-d", $sample->registerdate + (4 * (60 * 60 * 24)));

					$sql = "select id FROM #__users where email = " . $db->quote($sample->email);
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "select id FROM #__redshop_coupons where userid = " . (int) $uid;
					$db->setQuery($sql);
					$coupon_code = $db->loadResult();

					if ($formattedDate == $sendDate)
					{
						$body         = "";
						$subject      = "";
						$bcc          = null;
						$mailTemplate = $mail->getMailtemplate(0, 'colour_sample_third_reminder');

						if (count($mailTemplate) > 0)
						{
							$mailTemplate = $mailTemplate[0];
							$body         = $mailTemplate->mail_body;
							$subject      = $mailTemplate->mail_subject;

							if (trim($mailTemplate->mail_bcc) != "")
							{
								$bcc = explode(",", $mailTemplate->mail_bcc);
							}
						}

						$config    = JFactory::getConfig();
						$from      = $config->get('mailfrom');
						$fromName  = $config->get('fromname');
						$recipient = $sample->email;

						$body = str_replace("{name}", $sample->name, $body);
						$body = str_replace("{days}", COLOUR_COUPON_DURATION, $body);
						$body = str_replace("{discount}", COLOUR_DISCOUNT_PERCENTAGE, $body);
						$body = str_replace("{coupon_code}", $coupon_code, $body);
						$body = $mail->imginmail($body);

						if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $bcc))
						{
							$q_update = "UPDATE #__redshop_sample_request SET reminder_coupon = 1 WHERE request_id  = " . (int) $sample->request_id;
							$db->setQuery($q_update);
							$db->execute();
						}
					}
				}
			}
		}
	}

	/**
	 * Subscription renewal mail function
	 *
	 * @return void
	 */
	public static function subscriptionRenewalMail()
	{
		$db    = $db = JFactory::getDbo();
		$mail  = redshopMail::getInstance();
		$query = "SELECT ps.* FROM #__redshop_product_subscribe_detail AS ps"
			. " ,#__redshop_subscription_renewal AS r"
			. " WHERE r.product_id = ps.product_id AND r.before_no_days >= DATEDIFF(FROM_UNIXTIME( ps.end_date ),curdate())"
			. " AND ps.renewal_reminder = 1";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			// Subscription renewal mail
			$mail->sendSubscriptionRenewalMail($data[$i]);

			// Update mail sent field to 0
			$update_query = "UPDATE #__redshop_product_subscribe_detail "
				. "SET renewal_reminder = 0 "
				. "WHERE product_subscribe_id=" . (int) $data[$i]->product_subscribe_id;
			$db->setQuery($update_query);
			$db->execute();
		}
	}
}
