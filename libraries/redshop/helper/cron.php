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
		// Mail center
		$date = JFactory::getDate();

		$today = time();
		$day   = date('D', $today);
		$time  = date('H:i', $today);

		if (Redshop::getConfig()->get('DISCOUNT_MAIL_SEND'))
		{
			self::after_purchased_order_mail();
		}

		// Move to Stockroom start
		$fdate = date('Y-m-d', $today);

		$db = JFactory::getDbo();

		// Calculation to run once in day
		$query = "SELECT count(id) FROM #__redshop_cron WHERE date = " . $db->quote($fdate);
		$db->setQuery($query);
		$data = $db->loadResult();

		if ($data != 1)
		{
			// Default $data != 1
			$q_update = "UPDATE #__redshop_cron SET date = " . $db->quote($fdate) . " WHERE id = 1";
			$db->setQuery($q_update);
			$db->execute();

			if (Redshop::getConfig()->get('SEND_CATALOG_REMINDER_MAIL'))
			{
				self::catalog_mail();
			}

			self::color_mail();

			// Send subscription renewal mail.
			self::subscription_renewal_mail();
		}
	}

	/**
	 * Catalog mail function
	 *
	 * @return void
	 */
	public static function catalog_mail()
	{
		$date        = JFactory::getDate();
		$redshopMail = redshopMail::getInstance();
		$fdate       = $date->format('Y-m-d');

		$db = $db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_catalog_request where block = 0 ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		foreach ($data as $catalog_detail)
		{
			if ($catalog_detail->reminder_1 == 0)
			{
				$send_date = date("Y-m-d", $catalog_detail->registerDate + (Redshop::getConfig()->get('CATALOG_REMINDER_1') * (60 * 60 * 24)));

				if ($fdate == $send_date)
				{
					$bodytmp   = "";
					$subject   = "";
					$mailbcc   = null;
					$mail_data = $redshopMail->getMailtemplate(0, "catalog_first_reminder");

					if (count($mail_data) > 0)
					{
						$mail_data = $mail_data[0];
						$bodytmp   = $mail_data->mail_body;
						$subject   = $mail_data->mail_subject;

						if (trim($mail_data->mail_bcc) != "")
						{
							$mailbcc = explode(",", $mail_data->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromname  = $config->get('fromname');
					$recipient = $catalog_detail->email;

					$body = str_replace("{name}", $catalog_detail->name, $bodytmp);
					$body = str_replace("{discount}", Redshop::getConfig()->get('DISCOUNT_PERCENTAGE'), $body);
					$body = $redshopMail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_catalog_request SET reminder_1 = 1 WHERE catalog_user_id = " . (int) $catalog_detail->catalog_user_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}

			if ($catalog_detail->reminder_2 == 0)
			{
				$send_date = date("Y-m-d", $catalog_detail->registerDate + (Redshop::getConfig()->get('CATALOG_REMINDER_2') * (60 * 60 * 24)));

				$better_token = md5(uniqid(mt_rand(), true));

				$token = substr($better_token, 0, 10);

				$start_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

				$end_date = $start_date + (Redshop::getConfig()->get('DISCOUNT_DURATION') * 23 * 59 * 59);

				if ($fdate == $send_date)
				{
					$bodytmp   = "";
					$subject   = "";
					$mailbcc   = null;
					$mail_data = $redshopMail->getMailtemplate(0, "catalog_second_reminder");

					if (count($mail_data) > 0)
					{
						$mail_data = $mail_data[0];
						$bodytmp   = $mail_data->mail_body;
						$subject   = $mail_data->mail_subject;

						if (trim($mail_data->mail_bcc) != "")
						{
							$mailbcc = explode(",", $mail_data->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromname  = $config->get('fromname');
					$recipient = $catalog_detail->email;

					$body = str_replace("{name}", $catalog_detail->name, $bodytmp);
					$body = str_replace("{days}", Redshop::getConfig()->get('DISCOUNT_DURATION'), $body);
					$body = str_replace("{discount}", Redshop::getConfig()->get('DISCOUNT_PERCENTAGE'), $body);
					$body = str_replace("{coupon_code}", $token, $body);
					$body = $redshopMail->imginmail($body);

					$sql = "select id FROM #__users where email = " . $db->quote($recipient);
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "INSERT INTO  #__redshop_coupons` (`coupon_code`, `percent_or_total`, `coupon_value`, `start_date`, `end_date`, `coupon_type`, `userid`, `published`) "
						. "VALUES ('" . $token . "', '1', '" . Redshop::getConfig()->get('DISCOUNT_PERCENTAGE') . "', " . $db->quote($start_date)
						. ", " . $db->quote($end_date) . ", '1', " . (int) $uid . ", '1')";

					$db->setQuery($sql);
					$db->execute();

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_catalog_request SET reminder_2 = 1 WHERE catalog_user_id = " . $catalog_detail->catalog_user_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			else
			{
				if ($catalog_detail->reminder_3 == 0)
				{
					// Coupon reminder
					$send_date = date("Y-m-d", $catalog_detail->registerDate + (Redshop::getConfig()->get('DISCOUNT_DURATION') * (60 * 60 * 24)) + (4 * 60 * 60 * 24));

					$sql = "select id FROM #__users where email = " . $db->quote($catalog_detail->email);
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "select id FROM #__redshop_coupons where userid = " . (int) $uid;
					$db->setQuery($sql);
					$coupon_code = $db->loadResult();

					if ($fdate == $send_date)
					{
						$bodytmp   = "";
						$subject   = "";
						$mailbcc   = null;
						$mail_data = $redshopMail->getMailtemplate(0, "catalog_coupon_reminder");

						if (count($mail_data) > 0)
						{
							$mail_data = $mail_data[0];
							$bodytmp   = $mail_data->mail_body;
							$subject   = $mail_data->mail_subject;

							if (trim($mail_data->mail_bcc) != "")
							{
								$mailbcc = explode(",", $mail_data->mail_bcc);
							}
						}

						$config    = JFactory::getConfig();
						$from      = $config->get('mailfrom');
						$fromname  = $config->get('fromname');
						$recipient = $catalog_detail->email;

						$body = str_replace("{name}", $catalog_detail->name, $bodytmp);
						$body = str_replace("{discount}", Redshop::getConfig()->get('DISCOUNT_PERCENTAGE'), $body);
						$body = str_replace("{coupon_code}", $coupon_code, $body);
						$body = $redshopMail->imginmail($body);

						if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
						{
							$q_update = "UPDATE #__redshop_catalog_request SET reminder_3 = 1 WHERE catalog_user_id = " . (int) $catalog_detail->catalog_user_id;
							$db->setQuery($q_update);
							$db->execute();
						}
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
	public static function after_purchased_order_mail()
	{
		$redshopMail     = redshopMail::getInstance();
		$redconfig       = Redconfiguration::getInstance();
		$stockroomhelper = rsstockroomhelper::getInstance();
		$db              = JFactory::getDbo();
		$date            = JFactory::getDate();
		$fdate           = $date->format('Y-m-d');

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_payment_status') . ' = ' . $db->quote('Paid'))
			->where($db->qn('order_status') . ' = ' . $db->quote('C'));

		$data = $db->setQuery($query)->loadObjectList();

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');

		foreach ($data as $mail_detail)
		{
			$bodytmp         = "";
			$subject         = "";
			$order_id        = $mail_detail->order_id;
			$mailbcc         = null;
			$config          = JFactory::getConfig();
			$from            = $config->get('mailfrom');
			$fromname        = $config->get('fromname');
			$start_date      = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$end_date        = $start_date + (Redshop::getConfig()->get('DISCOUPON_DURATION') * 23 * 59 * 59);
			$valid_end_date  = $redconfig->convertDateFormat($end_date);
			$discoupon_value = (Redshop::getConfig()->get('DISCOUPON_PERCENT_OR_TOTAL') == 0) ? Redshop::getConfig()->get('REDCURRENCY_SYMBOL')
				. " "
				. number_format(Redshop::getConfig()->get('DISCOUPON_VALUE'), 2, Redshop::getConfig()->get('PRICE_SEPERATOR'), Redshop::getConfig()->get('THOUSAND_SEPERATOR')) : $discoupon_value = Redshop::getConfig()->get('DISCOUPON_VALUE')
				. " %";

			$sql = "SELECT CONCAT(firstname,' ',lastname) as name,user_email as email FROM  `#__redshop_order_users_info` WHERE `order_id` =  "
				. (int) $mail_detail->order_id . " AND `address_type` = 'BT' limit 0,1";
			$db->setQuery($sql);
			$orderuserarr = $db->loadObject();

			$sql = "SELECT coupon_left as total,coupon_code,end_date FROM  `#__redshop_coupons` WHERE `order_id` =  "
				. (int) $order_id . " AND coupon_left != 0 limit 0,1";
			$db->setQuery($sql);
			$couponeArr = $db->loadObject();

			if (count($couponeArr) <= 0 && $mail_detail->mail1_status != 0)
			{
				continue;
			}

			$coupon_code = '';
			$total       = 0;
			$cend_date   = '';

			if (count($couponeArr))
			{
				$total       = $couponeArr->total;
				$coupon_code = $couponeArr->coupon_code;
				$cend_date   = $couponeArr->end_date;
			}

			$name        = "";
			$recipient   = "";

			if (isset($orderuserarr))
			{
				$recipient = $orderuserarr->email;
				$name      = $orderuserarr->name;
			}

			if ($mail_detail->mail1_status == 0 && (DAYS_MAIL1 != 0 || DAYS_MAIL1 != ''))
			{
				$send_date      = date("Y-m-d", $mail_detail->cdate + (DAYS_MAIL1 * (60 * 60 * 24)));
				$firstmail_data = $redshopMail->getMailtemplate(0, "first_mail_after_order_purchased");

				if (count($firstmail_data) > 0)
				{
					$bodytmp = $firstmail_data[0]->mail_body;
					$subject = $firstmail_data[0]->mail_subject;

					if (trim($firstmail_data[0]->mail_bcc) != "")
					{
						$mailbcc = explode(",", $firstmail_data[0]->mail_bcc);
					}
				}

				$jpathurl = '<a href="' . JURI::root() . '">' . JURI::root() . '</a>';
				$body     = str_replace("{name}", $name, $bodytmp);
				$body     = str_replace("{url}", $jpathurl, $body);
				$body     = str_replace("{coupon_amount}", $discoupon_value, $body);

				if ($fdate == $send_date)
				{
					$better_token = md5(uniqid(mt_rand(), true));
					$token        = substr($better_token, 0, 10);
					$body         = str_replace("{coupon_code}", $token, $body);
					$body         = str_replace("{coupon_duration}", $valid_end_date, $body);
					$body = $redshopMail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$couponItems                   = JTable::getInstance('coupon_detail', 'Table');
						$couponItems->coupon_code      = $token;
						$couponItems->percent_or_total = Redshop::getConfig()->get('DISCOUPON_PERCENT_OR_TOTAL');
						$couponItems->coupon_value     = Redshop::getConfig()->get('DISCOUPON_VALUE');
						$couponItems->start_date       = $start_date;
						$couponItems->end_date         = $end_date;
						$couponItems->coupon_type      = 1;
						$couponItems->userid           = $mail_detail->user_id;
						$couponItems->coupon_left      = 1;
						$couponItems->published        = 1;
						$couponItems->order_id         = $order_id;
						$couponItems->store();

						$q_update = "UPDATE #__redshop_orders SET mail1_status = 1 WHERE order_id = " . $order_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			elseif ($mail_detail->mail2_status == 0 && (DAYS_MAIL2 != 0 || DAYS_MAIL2 != '') && $total != 0)
			{
				$send_date    = date("Y-m-d", $mail_detail->cdate + (DAYS_MAIL2 * (59 * 59 * 23)));
				$secmail_data = $redshopMail->getMailtemplate(0, "second_mail_after_order_purchased");

				if (count($secmail_data) > 0)
				{
					$bodytmp = $secmail_data[0]->mail_body;
					$subject = $secmail_data[0]->mail_subject;

					if (trim($secmail_data[0]->mail_bcc) != "")
					{
						$mailbcc = explode(",", $secmail_data[0]->mail_bcc);
					}
				}

				$days     = $stockroomhelper->getdatediff($cend_date, $start_date);
				$jpathurl = '<a href="' . JURI::root() . '">' . JURI::root() . '</a>';
				$body     = str_replace("{name}", $name, $bodytmp);
				$body     = str_replace("{url}", $jpathurl, $body);
				$body     = str_replace("{coupon_amount}", $discoupon_value, $body);

				if ($days && $fdate == $send_date)
				{
					$valid_end_date = $redconfig->convertDateFormat($cend_date);
					$body           = str_replace("{coupon_code}", $coupon_code, $body);
					$body           = str_replace("{coupon_duration}", $valid_end_date, $body);
					$body = $redshopMail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_orders SET mail2_status = 1 WHERE order_id = " . $order_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			elseif ($mail_detail->mail3_status == 0 && (DAYS_MAIL3 != 0 || DAYS_MAIL3 != '') && $total != 0)
			{
				// Coupon reminder
				$thrdmail_data = $redshopMail->getMailtemplate(0, "third_mail_after_order_purchased");

				if (count($thrdmail_data) > 0)
				{
					$bodytmp = $thrdmail_data[0]->mail_body;
					$subject = $thrdmail_data[0]->mail_subject;

					if (trim($thrdmail_data[0]->mail_bcc) != "")
					{
						$mailbcc = explode(",", $thrdmail_data[0]->mail_bcc);
					}
				}

				$send_date = date("Y-m-d", $mail_detail->cdate + (DAYS_MAIL3 * (60 * 60 * 24)));
				$days      = $stockroomhelper->getdatediff($cend_date, $start_date);
				$jpathurl  = '<a href="' . JURI::root() . '">' . JURI::root() . '</a>';
				$body      = str_replace("{name}", $name, $bodytmp);
				$body      = str_replace("{url}", $jpathurl, $body);
				$body      = str_replace("{coupon_amount}", $discoupon_value, $body);

				if ($days && $fdate == $send_date)
				{
					$valid_end_date = $redconfig->convertDateFormat($cend_date);
					$body           = str_replace("{coupon_code}", $coupon_code, $body);
					$body           = str_replace("{coupon_duration}", $valid_end_date, $body);
					$body = $redshopMail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_orders SET mail3_status = 1 WHERE order_id = " . $order_id;
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
	public static function color_mail()
	{
		$date        = JFactory::getDate();
		$redshopMail = redshopMail::getInstance();
		$today       = time();

		$fdate = $date->format('Y-m-d');

		$db = $db = JFactory::getDbo();

		$query = "SELECT * FROM #__redshop_sample_request where block = 0 ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		foreach ($data as $color_detail)
		{
			if ($color_detail->reminder_1 == 0)
			{
				$send_date = $color_detail->registerdate + (Redshop::getConfig()->get('COLOUR_SAMPLE_REMAINDER_1') * (60));

				if ($today >= $send_date)
				{
					$bodytmp   = "";
					$subject   = "";
					$mailbcc   = null;
					$mail_data = $redshopMail->getMailtemplate(0, 'colour_sample_first_reminder');

					if (count($mail_data) > 0)
					{
						$mail_data = $mail_data[0];
						$bodytmp   = $mail_data->mail_body;
						$subject   = $mail_data->mail_subject;

						if (trim($mail_data->mail_bcc) != "")
						{
							$mailbcc = explode(",", $mail_data->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromname  = $config->get('fromname');
					$recipient = $color_detail->email;

					$body = str_replace("{name}", $color_detail->name, $bodytmp);
					$body = $redshopMail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_sample_request SET reminder_1 = 1 WHERE request_id  = " . (int) $color_detail->request_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}

			if ($color_detail->reminder_2 == 0)
			{
				$send_date = date("Y-m-d", $color_detail->registerdate + (Redshop::getConfig()->get('COLOUR_SAMPLE_REMAINDER_2') * (60 * 60 * 24)));

				if ($fdate == $send_date)
				{
					$bodytmp   = "";
					$subject   = "";
					$mailbcc   = null;
					$mail_data = $redshopMail->getMailtemplate(0, 'colour_sample_second_reminder');

					if (count($mail_data) > 0)
					{
						$mail_data = $mail_data[0];
						$bodytmp   = $mail_data->mail_body;
						$subject   = $mail_data->mail_subject;

						if (trim($mail_data->mail_bcc) != "")
						{
							$mailbcc = explode(",", $mail_data->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromname  = $config->get('fromname');
					$recipient = $color_detail->email;

					$body = str_replace("{name}", $color_detail->name, $bodytmp);
					$body = $redshopMail->imginmail($body);

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_sample_request SET reminder_2 = 1 WHERE request_id  = " . (int) $color_detail->request_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}

			if ($color_detail->reminder_3 == 0)
			{
				$send_date = date("Y-m-d", $color_detail->registerdate + (Redshop::getConfig()->get('COLOUR_SAMPLE_REMAINDER_3') * (60 * 60 * 24)));

				$better_token = md5(uniqid(mt_rand(), true));

				$token = substr($better_token, 0, 10);

				$start_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

				$end_date = $start_date + (Redshop::getConfig()->get('COLOUR_COUPON_DURATION') * 23 * 59 * 59);

				if ($fdate == $send_date)
				{
					$bodytmp   = "";
					$subject   = "";
					$mailbcc   = null;
					$mail_data = $redshopMail->getMailtemplate(0, 'colour_sample_third_reminder');

					if (count($mail_data) > 0)
					{
						$mail_data = $mail_data[0];
						$bodytmp   = $mail_data->mail_body;
						$subject   = $mail_data->mail_subject;

						if (trim($mail_data->mail_bcc) != "")
						{
							$mailbcc = explode(",", $mail_data->mail_bcc);
						}
					}

					$config    = JFactory::getConfig();
					$from      = $config->get('mailfrom');
					$fromname  = $config->get('fromname');
					$recipient = $color_detail->email;

					$body = str_replace("{name}", $color_detail->name, $bodytmp);
					$body = str_replace("{days}", Redshop::getConfig()->get('COLOUR_COUPON_DURATION'), $body);
					$body = str_replace("{discount}", Redshop::getConfig()->get('COLOUR_DISCOUNT_PERCENTAGE'), $body);
					$body = str_replace("{coupon_code}", $token, $body);
					$body = $redshopMail->imginmail($body);

					$sql = "select id FROM #__users where email = " . $db->quote($recipient);
					$db->setQuery($sql);

					if ($uid = $db->loadResult())
					{
						$sql = "INSERT INTO  #__redshop_coupons` (`coupon_code`, `percent_or_total`, `coupon_value`, `start_date`, `end_date`, `coupon_type`, `userid`, `published`)
										VALUES (" . $db->quote($token) . ", '1', '" . Redshop::getConfig()->get('DISCOUNT_PERCENTAGE') . "', " . $db->quote($start_date) . ", " . $db->quote($end_date) . ", '1', '" . (int) $uid . "', '1')";

						$db->setQuery($sql);
						$db->execute();
					}

					if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
					{
						$q_update = "UPDATE #__redshop_sample_request SET reminder_3 = 1 WHERE request_id  = " . (int) $color_detail->request_id;
						$db->setQuery($q_update);
						$db->execute();
					}
				}
			}
			else
			{
				if ($color_detail->reminder_coupon == 0)
				{
					$send_date = date("Y-m-d", $color_detail->registerdate + (4 * (60 * 60 * 24)));

					$sql = "select id FROM #__users where email = " . $db->quote($color_detail->email);
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "select id FROM #__redshop_coupons where userid = " . (int) $uid;
					$db->setQuery($sql);
					$coupon_code = $db->loadResult();

					if ($fdate == $send_date)
					{
						$bodytmp   = "";
						$subject   = "";
						$mailbcc   = null;
						$mail_data = $redshopMail->getMailtemplate(0, 'colour_sample_third_reminder');

						if (count($mail_data) > 0)
						{
							$mail_data = $mail_data[0];
							$bodytmp   = $mail_data->mail_body;
							$subject   = $mail_data->mail_subject;

							if (trim($mail_data->mail_bcc) != "")
							{
								$mailbcc = explode(",", $mail_data->mail_bcc);
							}
						}

						$config    = JFactory::getConfig();
						$from      = $config->get('mailfrom');
						$fromname  = $config->get('fromname');
						$recipient = $color_detail->email;

						$body = str_replace("{name}", $color_detail->name, $bodytmp);
						$body = str_replace("{days}", Redshop::getConfig()->get('COLOUR_COUPON_DURATION'), $body);
						$body = str_replace("{discount}", Redshop::getConfig()->get('COLOUR_DISCOUNT_PERCENTAGE'), $body);
						$body = str_replace("{coupon_code}", $coupon_code, $body);
						$body = $redshopMail->imginmail($body);

						if (JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc))
						{
							$q_update = "UPDATE #__redshop_sample_request SET reminder_coupon = 1 WHERE request_id  = " . (int) $color_detail->request_id;
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
	public static function subscription_renewal_mail()
	{
		$db          = $db = JFactory::getDbo();
		$redshopMail = redshopMail::getInstance();
		$query       = "SELECT ps.* FROM #__redshop_product_subscribe_detail AS ps"
			. " ,#__redshop_subscription_renewal AS r"
			. " WHERE r.product_id = ps.product_id AND r.before_no_days >= DATEDIFF(FROM_UNIXTIME( ps.end_date ),curdate())"
			. " AND ps.renewal_reminder = 1";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			// Subscription renewal mail
			$redshopMail->sendSubscriptionRenewalMail($data[$i]);

			// Update mail sent field to 0
			$update_query = "UPDATE #__redshop_product_subscribe_detail "
				. "SET renewal_reminder = 0 "
				. "WHERE product_subscribe_id=" . (int) $data[$i]->product_subscribe_id;
			$db->setQuery($update_query);
			$db->execute();
		}
	}
}
