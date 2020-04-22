<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

/**
 * Mail helper
 *
 * @since  2.1.0
 */
class Helper
{
	/**
	 * @var  array
	 */
	protected static $templates = array();

	/**
	 * Method to get mail section
	 *
	 * @param   integer  $templateId  Template id
	 * @param   string   $section     Template section
	 * @param   string   $extraCond   Extra condition for query
	 *
	 * @return  array
	 * @since   2.1.0
	 */
	public static function getTemplate($templateId = 0, $section = '', $extraCond = '')
	{
		\JFactory::getLanguage()->load('com_redshop', JPATH_SITE);

		$key = $templateId . '_' . $section . '_' . serialize($extraCond);

		if (!array_key_exists($key, self::$templates))
		{
			$db    = \JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('*')
				->from($db->qn('#__redshop_mail'))
				->where($db->qn('published') . ' = 1');

			if ($templateId)
			{
				$query->where($db->qn('mail_id') . ' = ' . (int) $templateId);
			}

			if ($section)
			{
				$query->where($db->qn('mail_section') . ' = ' . $db->quote($section));
			}

			if ($extraCond)
			{
				$query->where($extraCond);
			}

			self::$templates[$key] = $db->setQuery($query)->loadObjectList();
		}

		return self::$templates[$key];
	}

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   string  $message  Text message
	 *
	 * @return  void
	 * @since   2.1.0
	 */
	public static function imgInMail(&$message)
	{
		if (empty($message))
		{
			return;
		}

		$url    = \JUri::root();
		$images = array();

		preg_match_all("/\< *[img][^\>]*[.]*\>/i", $message, $matches);

		foreach ($matches[0] as $match)
		{
			preg_match_all("/(src|height|width)*= *[\"\']{0,1}([^\"\'\ \>]*)/i", $match, $m);
			$image    = array_combine($m[1], $m[2]);
			$images[] = $image['src'];
		}

		$images = array_unique($images);

		if (empty($images))
		{
			return;
		}

		foreach ($images as $change)
		{
			if (strpos($change, 'http') === false)
			{
				$message = str_replace($change, $url . $change, $message);
			}
		}
	}

	/**
	 * Send catalog request
	 *
	 * @param   string   $from         Sender email
	 * @param   string   $fromName     Sender name
	 * @param   mixed    $receiver     Receiver email
	 * @param   string   $subject      Mail subject
	 * @param   string   $body         Mail body
	 * @param   boolean  $isHtml       True for use HTML for plain.
	 * @param   mixed    $mailCC       List of CC emails
	 * @param   mixed    $mailBCC      List of Bcc emails
	 * @param   mixed    $attachment   Attachment files.
	 * @param   string   $mailSection  Mail Section
	 * @param   array    $argList      Function arguments
	 *
	 * @return  boolean          True on success. False otherwise.
	 */
	public static function sendEmail($from, $fromName, $receiver, $subject, $body, $isHtml = true, $mailCC = null,
		$mailBCC = null, $attachment = null, $mailSection = '', $argList = array()
	)
	{
		if (empty($receiver) || empty($subject) || empty($body))
		{
			return false;
		}

		if (empty($from) || empty($fromName))
		{
			$config   = \JFactory::getConfig();
			$from     = $config->get('mailfrom', '');
			$fromName = $config->get('fromname', '');
		}

		if (empty($from) || empty($fromName))
		{
			return false;
		}

		$mail = \JFactory::getMailer();
		$mail->setSender(array($from, $fromName));
		$mail->setSubject($subject);
		$mail->setBody($body);
		$mail->addRecipient($receiver);

		if (!empty($mailCC))
		{
			$mail->addCc($mailCC);
		}

		if (!empty($mailBCC))
		{
			$mail->addBcc($mailBCC);
		}

		$mail->isHtml((boolean) $isHtml);

		if (!empty($attachment))
		{
			$mail->addAttachment($attachment);
		}

		\JPluginHelper::importPlugin('redshop_mail');
		$dispatcher = \RedshopHelperUtility::getDispatcher();

		// Process the product plugin before send mail
		$dispatcher->trigger('beforeRedshopSendMail', array(&$mail, $mailSection, $argList));

		$isSend = $mail->Send();

		// Process the product plugin after send mail
		$dispatcher->trigger('afterRedshopSendMail', array(&$mail, $mailSection, $argList, $isSend));

		return $isSend;
	}
}
