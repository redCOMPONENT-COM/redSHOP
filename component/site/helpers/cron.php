<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/mail.php';

/**
 *  cron class
 */
class Cron
{
	/**
	 * cron constructor
	 */
	public function __construct()
	{
		// Mail center
		$date = JFactory::getDate();

		$today = time();
		$day   = date('D', $today);
		$time  = date('H:i', $today);

		if (DISCOUNT_MAIL_SEND)
		{
			cron::after_purchased_order_mail();
		}

		// Move Container to Stockroom start
		$fdate = date('Y-m-d', $today);

		$db = $db = JFactory::getDBO();

		// Calculation for move Container once in day
		$query = "SELECT count(id) FROM #__" . TABLE_PREFIX . "_cron WHERE date = '" . $fdate . "'";
		$db->setQuery($query);
		$data = $db->loadResult();

		if ($data != 1)
		{
			// Default $data != 1
			$q_update = "UPDATE #__" . TABLE_PREFIX . "_cron SET date = '" . $fdate . "' WHERE id = 1";
			$db->setQuery($q_update);
			$db->query();

			if (SEND_CATALOG_REMINDER_MAIL)
			{
				cron::catalog_mail();
			}

			cron::color_mail();

			// Send subscription renewal mail.
			cron::subscription_renewal_mail();

			// End mail center
			if (USE_CONTAINER)
			{
				$query = "SELECT * FROM #__" . TABLE_PREFIX . "_container";
				$db->setQuery($query);
				$data = $db->loadObjectList();

				foreach ($data as $cont_data)
				{
					$date_diff = $today - $cont_data->creation_date;

					$diff_day = $date_diff / (60 * 60 * 24);

					// Calculation of Days For moving
					$remain_day = ($cont_data->max_del_time * 7) - floor($diff_day);

					if ($remain_day >= 0 && $remain_day <= 7)
					{
						$container_id = $cont_data->container_id;
						$stockroom_id = $cont_data->stockroom_id;

						// Move Container into stockroom
						$move         = cron::move($container_id, $stockroom_id);

						if ($move)
						{
							// Call Order Status Change
							cron::order_status($container_id);
						}
					}
				}
			}
		}

	}

	/**
	 * Move function
	 *
	 * @param   int  $container_id  container id
	 * @param   int  $stockroom_id  stockroom id
	 *
	 * @return bool
	 */
	public function move($container_id, $stockroom_id)
	{
		// Move Container To Stockroom
		$db = $db = JFactory::getDBO();

		$q_insert = "INSERT INTO #__"
			. TABLE_PREFIX
			. "_stockroom_container_xref (stockroom_id ,container_id) VALUES ('"
			. $stockroom_id . "', '" . $container_id . "')";
		$db->setQuery($q_insert);

		if ($db->query())
		{
			return true;
		}
	}

	/**
	 * Order_status function update status to Payment Recieve
	 *
	 * @param   int  $container_id  container id
	 *
	 * @return bool
	 */
	public function order_status($container_id)
	{
		// Change Order Status
		$db = $db = JFactory::getDBO();

		$select_order = "SELECT  order_item_id, order_id,order_status,delivery_time,container_id,product_id,is_split from #__"
			. TABLE_PREFIX . "_order_item where container_id = " . $container_id;
		$db->setQuery($select_order);
		$data = $db->loadObjectList();

		foreach ($data as $newdata)
		{
			if ($newdata->order_status == 'PR')
			{
				// Payment Is recieved then Status will change
				if ($newdata->is_split != 0)
				{
					$query = "update #__" . TABLE_PREFIX . "_order_item set order_status = 'RD' where order_item_id = " . $newdata->order_item_id;
				}
				else
				{
					$query = "update #__" . TABLE_PREFIX . "_order_item set order_status = 'RD1' where order_item_id = " . $newdata->order_item_id;
				}

				$db->setQuery($query);
				$db->query();
			}
		}

		return true;
	}

	/**
	 * Catalog mail function
	 *
	 * @return void
	 */
	public function catalog_mail()
	{
		$date        = JFactory::getDate();
		$redshopMail = new redshopMail;
		$fdate       = $date->toFormat('%Y-%m-%d');

		$db = $db = JFactory::getDBO();

		$query = "SELECT * FROM #__" . TABLE_PREFIX . "_catalog_request where block = 0 ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		foreach ($data as $catalog_detail)
		{
			if ($catalog_detail->reminder_1 == 0)
			{
				$send_date = date("Y-m-d", $catalog_detail->registerDate + (CATALOG_REMINDER_1 * (60 * 60 * 24)));

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
					$from      = $config->getValue('mailfrom');
					$fromname  = $config->getValue('fromname');
					$recipient = $catalog_detail->email;

					$body = str_replace("{name}", $catalog_detail->name, $bodytmp);
					$body = str_replace("{discount}", DISCOUNT_PERCENTAGE, $body);

					$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

					if ($sent == 1)
					{
						$q_update = "UPDATE #__" . TABLE_PREFIX . "_catalog_request SET reminder_1 = 1 WHERE catalog_user_id = " . $catalog_detail->catalog_user_id;
						$db->setQuery($q_update);
						$db->query();
					}
				}
			}

			if ($catalog_detail->reminder_2 == 0)
			{
				$send_date = date("Y-m-d", $catalog_detail->registerDate + (CATALOG_REMINDER_2 * (60 * 60 * 24)));

				$better_token = md5(uniqid(mt_rand(), true));

				$token = substr($better_token, 0, 10);

				$start_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

				$end_date = $start_date + (DISCOUNT_DURATION * 23 * 59 * 59);

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
					$from      = $config->getValue('mailfrom');
					$fromname  = $config->getValue('fromname');
					$recipient = $catalog_detail->email;

					$body = str_replace("{name}", $catalog_detail->name, $bodytmp);
					$body = str_replace("{days}", DISCOUNT_DURATION, $body);
					$body = str_replace("{discount}", DISCOUNT_PERCENTAGE, $body);
					$body = str_replace("{coupon_code}", $token, $body);

					$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

					$sql = "select id FROM #__users where email = '" . $recipient . "'";
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "INSERT INTO  #__" . TABLE_PREFIX
						. "_coupons` (`coupon_code`, `percent_or_total`, `coupon_value`, `start_date`, `end_date`, `coupon_type`, `userid`, `published`) "
						. "VALUES ('" . $token . "', '1', '" . DISCOUNT_PERCENTAGE . "', '" . $start_date
						. "', '" . $end_date . "', '1', '" . $uid . "', '1')";

					$db->setQuery($sql);
					$db->query();

					if ($sent == 1)
					{
						$q_update = "UPDATE #__" . TABLE_PREFIX
							. "_catalog_request SET reminder_2 = 1 WHERE catalog_user_id = " . $catalog_detail->catalog_user_id;
						$db->setQuery($q_update);
						$db->query();
					}
				}
			}
			else
			{
				if ($catalog_detail->reminder_3 == 0)
				{
					// Coupon reminder
					$send_date = date("Y-m-d", $catalog_detail->registerDate + (DISCOUNT_DURATION * (60 * 60 * 24)) + (4 * 60 * 60 * 24));

					$sql = "select id FROM #__users where email = '" . $catalog_detail->email . "'";
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "select id FROM #__" . TABLE_PREFIX . "_coupons where userid = '" . $uid . "'";
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
						$from      = $config->getValue('mailfrom');
						$fromname  = $config->getValue('fromname');
						$recipient = $catalog_detail->email;

						$body = str_replace("{name}", $catalog_detail->name, $bodytmp);
						$body = str_replace("{discount}", DISCOUNT_PERCENTAGE, $body);
						$body = str_replace("{coupon_code}", $coupon_code, $body);

						$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

						if ($sent == 1)
						{
							$q_update = "UPDATE #__" . TABLE_PREFIX . "_catalog_request SET reminder_3 = 1 WHERE catalog_user_id = " . $catalog_detail->catalog_user_id;
							$db->setQuery($q_update);
							$db->query();
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
	public function after_purchased_order_mail()
	{
		$redshopMail     = new redshopMail;
		$redconfig       = new Redconfiguration;
		$stockroomhelper = new rsstockroomhelper;
		$db              = JFactory::getDBO();
		$date            = JFactory::getDate();
		$fdate           = $date->toFormat('%Y-%m-%d');

		$query = "SELECT * FROM #__redshop_orders where order_payment_status ='Paid' and order_status = 'C'";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');

		foreach ($data as $mail_detail)
		{
			$bodytmp         = "";
			$subject         = "";
			$order_id        = $mail_detail->order_id;
			$mailbcc         = null;
			$config          = JFactory::getConfig();
			$from            = $config->getValue('mailfrom');
			$fromname        = $config->getValue('fromname');
			$start_date      = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$end_date        = $start_date + (DISCOUPON_DURATION * 23 * 59 * 59);
			$valid_end_date  = $redconfig->convertDateFormat($end_date);
			$discoupon_value = (DISCOUPON_PERCENT_OR_TOTAL == 0) ? REDCURRENCY_SYMBOL
				. " "
				. number_format(DISCOUPON_VALUE, 2, PRICE_SEPERATOR, THOUSAND_SEPERATOR) : $discoupon_value = DISCOUPON_VALUE
				. " %";

			$sql = "SELECT CONCAT(firstname,' ',lastname) as name,user_email as email FROM  `#__redshop_order_users_info` WHERE `order_id` =  '"
				. $mail_detail->order_id . "' AND `address_type` = 'BT' limit 0,1";
			$db->setQuery($sql);
			$orderuserarr = $db->loadObject();

			$sql = "SELECT coupon_left as total,coupon_code,end_date FROM  `#__redshop_coupons` WHERE `order_id` =  '"
				. $order_id . "' AND coupon_left != 0 limit 0,1";
			$db->setQuery($sql);
			$couponeArr = $db->loadObject();

			if (count($couponeArr) <= 0)
			{
				continue;
			}

			$total       = $couponeArr->total;
			$coupon_code = $couponeArr->coupon_code;
			$cend_date   = $couponeArr->end_date;
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
					$sent         = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

					if ($sent == 1)
					{
						$couponItems                   = JTable::getInstance('coupon_detail', 'Table');
						$couponItems->coupon_code      = $token;
						$couponItems->percent_or_total = DISCOUPON_PERCENT_OR_TOTAL;
						$couponItems->coupon_value     = DISCOUPON_VALUE;
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
						$db->query();
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
					$sent           = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);
					$q_update       = "UPDATE #__redshop_orders SET mail2_status = 1 WHERE order_id = " . $order_id;
					$db->setQuery($q_update);
					$db->query();
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
					$sent           = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);
					$q_update       = "UPDATE #__redshop_orders SET mail3_status = 1 WHERE order_id = " . $order_id;
					$db->setQuery($q_update);
					$db->query();
				}
			}
		}
	}

	/**
	 * Color mail function.
	 *
	 * @return void
	 */
	public function color_mail()
	{
		$date        = JFactory::getDate();
		$redshopMail = new redshopMail;
		$today       = time();

		$fdate = $date->toFormat('%Y-%m-%d');

		$db = $db = JFactory::getDBO();

		$query = "SELECT * FROM #__" . TABLE_PREFIX . "_sample_request where block = 0 ";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		foreach ($data as $color_detail)
		{
			if ($color_detail->reminder_1 == 0)
			{
				$send_date = $color_detail->registerdate + (COLOUR_SAMPLE_REMAINDER_1 * (60));

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
					$from      = $config->getValue('mailfrom');
					$fromname  = $config->getValue('fromname');
					$recipient = $color_detail->email;

					$body = str_replace("{name}", $color_detail->name, $bodytmp);

					$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

					if ($sent == 1)
					{
						$q_update = "UPDATE #__" . TABLE_PREFIX . "_sample_request SET reminder_1 = 1 WHERE request_id  = " . $color_detail->request_id;
						$db->setQuery($q_update);
						$db->query();
					}
				}
			}

			if ($color_detail->reminder_2 == 0)
			{
				$send_date = date("Y-m-d", $color_detail->registerdate + (COLOUR_SAMPLE_REMAINDER_2 * (60 * 60 * 24)));

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
					$from      = $config->getValue('mailfrom');
					$fromname  = $config->getValue('fromname');
					$recipient = $color_detail->email;

					$body = str_replace("{name}", $color_detail->name, $bodytmp);

					$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

					if ($sent == 1)
					{
						$q_update = "UPDATE #__" . TABLE_PREFIX . "_sample_request SET reminder_2 = 1 WHERE request_id  = " . $color_detail->request_id;
						$db->setQuery($q_update);
						$db->query();
					}
				}
			}

			if ($color_detail->reminder_3 == 0)
			{
				$send_date = date("Y-m-d", $color_detail->registerdate + (COLOUR_SAMPLE_REMAINDER_3 * (60 * 60 * 24)));

				$better_token = md5(uniqid(mt_rand(), true));

				$token = substr($better_token, 0, 10);

				$start_date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

				$end_date = $start_date + (COLOUR_COUPON_DURATION * 23 * 59 * 59);

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
					$from      = $config->getValue('mailfrom');
					$fromname  = $config->getValue('fromname');
					$recipient = $color_detail->email;

					$body = str_replace("{name}", $color_detail->name, $bodytmp);
					$body = str_replace("{days}", COLOUR_COUPON_DURATION, $body);
					$body = str_replace("{discount}", COLOUR_DISCOUNT_PERCENTAGE, $body);
					$body = str_replace("{coupon_code}", $token, $body);

					$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

					$sql = "select id FROM #__users where email = '" . $recipient . "'";
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "INSERT INTO  #__"
						. TABLE_PREFIX
						. "_coupons` (`coupon_code`, `percent_or_total`, `coupon_value`, `start_date`, `end_date`, `coupon_type`, `userid`, `published`)
									VALUES ('" . $token . "', '1', '" . DISCOUNT_PERCENTAGE . "', '" . $start_date . "', '" . $end_date . "', '1', '" . $uid . "', '1')";

					$db->setQuery($sql);
					$db->query();

					if ($sent == 1)
					{
						$q_update = "UPDATE #__" . TABLE_PREFIX . "_sample_request SET reminder_3 = 1 WHERE request_id  = " . $color_detail->request_id;
						$db->setQuery($q_update);
						$db->query();
					}
				}
			}
			else
			{
				if ($color_detail->reminder_coupon == 0)
				{
					$send_date = date("Y-m-d", $color_detail->registerdate + (4 * (60 * 60 * 24)));

					$sql = "select id FROM #__users where email = '" . $color_detail->email . "'";
					$db->setQuery($sql);
					$uid = $db->loadResult();

					$sql = "select id FROM #__" . TABLE_PREFIX . "_coupons where userid = '" . $uid . "'";
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
						$from      = $config->getValue('mailfrom');
						$fromname  = $config->getValue('fromname');
						$recipient = $color_detail->email;

						$body = str_replace("{name}", $color_detail->name, $bodytmp);
						$body = str_replace("{days}", COLOUR_COUPON_DURATION, $body);
						$body = str_replace("{discount}", COLOUR_DISCOUNT_PERCENTAGE, $body);
						$body = str_replace("{coupon_code}", $coupon_code, $body);

						$sent = JUtility::sendMail($from, $fromname, $recipient, $subject, $body, $mode = 1, null, $mailbcc);

						if ($sent == 1)
						{
							$q_update = "UPDATE #__" . TABLE_PREFIX . "_sample_request SET reminder_coupon = 1 WHERE request_id  = " . $color_detail->request_id;
							$db->setQuery($q_update);
							$db->query();
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
	public function subscription_renewal_mail()
	{
		$db          = $db = JFactory::getDBO();
		$redshopMail = new redshopMail;
		$query       = "SELECT ps.* FROM #__" . TABLE_PREFIX . "_product_subscribe_detail AS ps"
			. " ,#__" . TABLE_PREFIX . "_subscription_renewal AS r"
			. " WHERE r.product_id = ps.product_id AND r.before_no_days >= DATEDIFF(FROM_UNIXTIME( ps.end_date ),curdate())"
			. " AND ps.renewal_reminder = 1";
		$db->setQuery($query);
		$data = $db->loadObjectList();

		for ($i = 0; $i < count($data); $i++)
		{
			// Subscription renewal mail
			$redshopMail->sendSubscriptionRenewalMail($data[$i]);

			// Update mail sent field to 0
			$update_query = "UPDATE #__" . TABLE_PREFIX . "_product_subscribe_detail "
				. "SET renewal_reminder = 0 "
				. "WHERE product_subscribe_id=" . $data[$i]->product_subscribe_id;
			$db->setQuery($update_query);
			$db->Query();
		}
	}
}

$cron = new Cron;