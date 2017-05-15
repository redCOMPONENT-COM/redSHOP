<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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
		$today      = time();
		$formatDate = date('Y-m-d', $today);

		if (Redshop::getConfig()->get('DISCOUNT_MAIL_SEND'))
		{
			self::sendMailAfterPurchaseOrder();
		}

		// Calculation to run once in day
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('count(id)')
			->from($db->qn('#__redshop_cron'))
			->where($db->qn('date') . ' = ' . $db->quote($formatDate));

		$result = $db->setQuery($query)->loadResult();

		if ($result == 1)
		{
			return;
		}

		// Default $data != 1
		$query->clear()
			->update($db->qn('#__redshop_cron'))
			->set($db->qn('date') . ' = ' . $db->quote($formatDate))
			->where($db->qn('id') . ' = 1');
		$db->setQuery($query)->execute();

		if (Redshop::getConfig()->get('SEND_CATALOG_REMINDER_MAIL'))
		{
			self::sendCatalogMail();
		}

		self::sendColorMail();

		// Send subscription renewal mail.
		self::sendSubscriptionRenewalMail();
	}

	/**
	 * Catalog mail function
	 *
	 * @return void
	 *
	 * @deprecated  2.0.6
	 */
	public static function sendCatalogMail()
	{
		RedshopHelperCatalog::sendMail();
	}

	/**
	 * After purchased order mail function
	 *
	 * @return void
	 */
	public static function sendMailAfterPurchaseOrder()
	{
		$db            = JFactory::getDbo();
		$date          = JFactory::getDate();
		$formattedDate = $date->format('Y-m-d');
		$config        = JFactory::getConfig();
		$from          = $config->get('mailfrom');
		$fromName      = $config->get('fromname');

		$query = $db->getQuery(true)
			->select('*')
			->from($db->qn('#__redshop_orders'))
			->where($db->qn('order_payment_status') . ' = ' . $db->quote('Paid'))
			->where($db->qn('order_status') . ' = ' . $db->quote('C'));

		$mails = $db->setQuery($query)->loadObjectList();

		JTable::addIncludePath(JPATH_SITE . '/administrator/components/com_redshop/tables');

		foreach ($mails as $mail)
		{
			$mailBody     = "";
			$subject      = "";
			$orderId      = $mail->order_id;
			$mailBcc      = null;
			$startDate    = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
			$endDate      = $startDate + (Redshop::getConfig()->get('DISCOUPON_DURATION') * 23 * 59 * 59);
			$validEndDate = RedshopHelperDatetime::convertDateFormat($endDate);

			if (Redshop::getConfig()->get('DISCOUPON_PERCENT_OR_TOTAL') == 0)
			{
				$discountCouponValue = Redshop::getConfig()->get('REDCURRENCY_SYMBOL') . " "
					. number_format(
						Redshop::getConfig()->get('DISCOUPON_VALUE'),
						2,
						Redshop::getConfig()->get('PRICE_SEPERATOR'),
						Redshop::getConfig()->get('THOUSAND_SEPERATOR')
					);
			}
			else
			{
				$discountCouponValue = Redshop::getConfig()->get('DISCOUPON_VALUE') . " %";
			}

			$query->clear()
				->select('CONCAT(' . $db->qn('firstname') . ',' . $db->quote(' ') . ',' . $db->qn('lastname') . ') AS ' . $db->qn('name'))
				->select($db->qn('user_email', 'email'))
				->from($db->qn('#__redshop_order_users_info'))
				->where($db->qn('order_id') . ' = ' . $orderId)
				->where($db->qn('address_type') . ' = ' . $db->quote('BT'));

			$orderUser = $db->setQuery($query)->loadObject();

			$query->clear()
				->select($db->qn('coupon_left', 'total'))
				->select($db->qn('coupon_code'))
				->select($db->qn('end_date'))
				->from($db->qn('#__redshop_coupons'))
				->where($db->qn('order_id') . ' = ' . $orderId)
				->where($db->qn('coupon_left') . ' <> 0');

			$coupon = $db->setQuery($query)->loadObject();

			if (empty($coupon) && $mail->mail1_status != 0)
			{
				continue;
			}

			$couponCode    = '';
			$total         = 0;
			$couponEndDate = '';

			if (!empty($coupon))
			{
				$total         = $coupon->total;
				$couponCode    = $coupon->coupon_code;
				$couponEndDate = $coupon->end_date;
			}

			$name      = "";
			$recipient = "";

			if (!empty($orderUser))
			{
				$recipient = $orderUser->email;
				$name      = $orderUser->name;
			}

			if ($mail->mail1_status == 0 && Redshop::getConfig()->get('DAYS_MAIL1'))
			{
				$sendDate      = date("Y-m-d", $mail->cdate + (Redshop::getConfig()->get('DAYS_MAIL1') * (60 * 60 * 24)));
				$firstMailData = RedshopHelperMail::getMailTemplate(0, "first_mail_after_order_purchased");

				if (count($firstMailData) > 0)
				{
					$mailBody = $firstMailData[0]->mail_body;
					$subject  = $firstMailData[0]->mail_subject;

					if (trim($firstMailData[0]->mail_bcc) != "")
					{
						$mailBcc = explode(",", $firstMailData[0]->mail_bcc);
					}
				}

				$pathUrl = '<a href="' . JUri::root() . '">' . JUri::root() . '</a>';
				$body    = str_replace("{name}", $name, $mailBody);
				$body    = str_replace("{url}", $pathUrl, $body);
				$body    = str_replace("{coupon_amount}", $discountCouponValue, $body);

				if ($formattedDate == $sendDate)
				{
					$token = md5(uniqid(mt_rand(), true));
					$token = substr($token, 0, 10);
					$body  = str_replace("{coupon_code}", $token, $body);
					$body  = str_replace("{coupon_duration}", $validEndDate, $body);
					$body  = RedshopHelperMail::imgInMail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $mailBcc))
					{
						$couponTable                   = JTable::getInstance('coupon_detail', 'Table');
						$couponTable->coupon_code      = $token;
						$couponTable->percent_or_total = Redshop::getConfig()->get('DISCOUPON_PERCENT_OR_TOTAL');
						$couponTable->coupon_value     = Redshop::getConfig()->get('DISCOUPON_VALUE');
						$couponTable->start_date       = $startDate;
						$couponTable->end_date         = $endDate;
						$couponTable->coupon_type      = 1;
						$couponTable->userid           = $mail->user_id;
						$couponTable->coupon_left      = 1;
						$couponTable->published        = 1;
						$couponTable->order_id         = $orderId;
						$couponTable->store();

						$query->clear()
							->update($db->qn('#__redshop_orders'))
							->set($db->qn('mail1_status') . ' = ' . $db->quote('1'))
							->where($db->qn('order_id') . ' = ' . $orderId);
						$db->setQuery($query)->execute();
					}
				}
			}
			elseif ($mail->mail2_status == 0 && Redshop::getConfig()->get('DAYS_MAIL2') && $total != 0)
			{
				$sendDate       = date("Y-m-d", $mail->cdate + (Redshop::getConfig()->get('DAYS_MAIL2') * (59 * 59 * 23)));
				$secondMailData = RedshopHelperMail::getMailTemplate(0, "second_mail_after_order_purchased");

				if (count($secondMailData) > 0)
				{
					$mailBody = $secondMailData[0]->mail_body;
					$subject  = $secondMailData[0]->mail_subject;

					if (trim($secondMailData[0]->mail_bcc) != "")
					{
						$mailBcc = explode(",", $secondMailData[0]->mail_bcc);
					}
				}

				$days    = RedshopHelperStockroom::getDateDiff($couponEndDate, $startDate);
				$pathUrl = '<a href="' . JUri::root() . '">' . JUri::root() . '</a>';
				$body    = str_replace("{name}", $name, $mailBody);
				$body    = str_replace("{url}", $pathUrl, $body);
				$body    = str_replace("{coupon_amount}", $discountCouponValue, $body);

				if ($days && $formattedDate == $sendDate)
				{
					$validEndDate = RedshopHelperDatetime::convertDateFormat($couponEndDate);
					$body         = str_replace("{coupon_code}", $couponCode, $body);
					$body         = str_replace("{coupon_duration}", $validEndDate, $body);
					$body         = RedshopHelperMail::imgInMail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $mailBcc))
					{
						$query->clear()
							->update($db->qn('#__redshop_orders'))
							->set($db->qn('mail2_status') . ' = ' . $db->quote('1'))
							->where($db->qn('order_id') . ' = ' . $orderId);
						$db->setQuery($query)->execute();
					}
				}
			}
			elseif ($mail->mail3_status == 0
				&& (Redshop::getConfig()->get('DAYS_MAIL3') != 0 || Redshop::getConfig()->get('DAYS_MAIL3') != '') && $total != 0
			)
			{
				// Coupon reminder
				$thirdMailData = RedshopHelperMail::getMailTemplate(0, "third_mail_after_order_purchased");

				if (count($thirdMailData) > 0)
				{
					$mailBody = $thirdMailData[0]->mail_body;
					$subject  = $thirdMailData[0]->mail_subject;

					if (trim($thirdMailData[0]->mail_bcc) != "")
					{
						$mailBcc = explode(",", $thirdMailData[0]->mail_bcc);
					}
				}

				$sendDate = date("Y-m-d", $mail->cdate + (Redshop::getConfig()->get('DAYS_MAIL3') * (60 * 60 * 24)));
				$days     = RedshopHelperStockroom::getDateDiff($couponEndDate, $startDate);
				$pathUrl  = '<a href="' . JUri::root() . '">' . JUri::root() . '</a>';
				$body     = str_replace("{name}", $name, $mailBody);
				$body     = str_replace("{url}", $pathUrl, $body);
				$body     = str_replace("{coupon_amount}", $discountCouponValue, $body);

				if ($days && $formattedDate == $sendDate)
				{
					$validEndDate = RedshopHelperDatetime::convertDateFormat($couponEndDate);
					$body         = str_replace("{coupon_code}", $couponCode, $body);
					$body         = str_replace("{coupon_duration}", $validEndDate, $body);
					$body         = RedshopHelperMail::imgInMail($body);

					if (JFactory::getMailer()->sendMail($from, $fromName, $recipient, $subject, $body, $mode = 1, null, $mailBcc))
					{
						$query->clear()
							->update($db->qn('#__redshop_orders'))
							->set($db->qn('mail3_status') . ' = ' . $db->quote('1'))
							->where($db->qn('order_id') . ' = ' . $orderId);
						$db->setQuery($query)->execute();
					}
				}
			}
		}
	}

	/**
	 * Color mail function.
	 *
	 * @return void
	 *
	 * @deprecated  2.0.6
	 */
	public static function sendColorMail()
	{
		RedshopHelperSample::sendMail();
	}

	/**
	 * Subscription renewal mail function
	 *
	 * @return void
	 */
	public static function sendSubscriptionRenewalMail()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('ps.*')
			->from($db->qn('#__redshop_product_subscribe_detail', 'ps'))
			->leftJoin($db->qn('#__redshop_subscription_renewal', 'r') . ' ON ' . $db->qn('r.product_id') . ' = ' . $db->qn('ps.product_id'))
			->where($db->qn('r.before_no_days') . ' >= DATEDIFF(FROM_UNIXTIME( ps.end_date ),curdate())')
			->where($db->qn('ps.renewal_reminder') . ' = 1');

		$subscriptions = $db->setQuery($query)->loadObjectList();

		if (empty($subscriptions))
		{
			return;
		}

		$productSubscribeIds = array();

		foreach ($subscriptions as $subscription)
		{
			// Subscription renewal mail
			RedshopHelperMail::sendSubscriptionRenewalMail($subscription);

			$productSubscribeIds[] = (int) $subscription->product_subscribe_id;
		}

		// Update mail sent field to 0
		$query->clear()
			->update($db->qn('#__redshop_product_subscribe_detail'))
			->set($db->qn('renewal_reminder') . ' = 0')
			->where($db->qn('product_subscribe_id') . ' IN (' . implode(',', $productSubscribeIds) . ')');
		$db->setQuery($query)->execute();
	}
}
