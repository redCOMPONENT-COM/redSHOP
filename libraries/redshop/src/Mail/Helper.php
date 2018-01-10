<?php
/**
 * @package     RedShop
 * @subpackage  Mail
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

/**
 * Class to handle email only
 *
 * @package     Redshop\Mail
 *
 * @since       __DEPLOY_VERSION__
 */
class Helper
{
	/**
	 * Send email function
	 *
	 * @param   string  $from        Sender email
	 * @param   string  $fromName    Sender name
	 * @param   mixed   $receiver    Receiver email
	 * @param   string  $subject     Mail subject
	 * @param   string  $body        Mail body
	 * @param   boolean $isHtml      True for use HTML for plain.
	 * @param   mixed   $mailCC      List of CC emails
	 * @param   mixed   $mailBCC     List of Bcc emails
	 * @param   mixed   $attachment  Attachment files.
	 * @param   string  $mailSection Mail Section
	 * @param   array   $argList     Function arguments
	 *
	 * @return  boolean              True on success. False otherwise.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function sendEmail($from, $fromName, $receiver, $subject, $body, $isHtml = true, $mailCC = null, $mailBCC = null, $attachment = null, $mailSection = '', $argList = array())
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

		$mail = JFactory::getMailer();
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

	/**
	 * Use absolute paths instead of relative ones when linking images
	 *
	 * @param   string  $message  Text message
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function useImage($message)
	{
		if (empty($message))
		{
			return '';
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
			return $message;
		}

		foreach ($images as $change)
		{
			if (strpos($change, 'http') === false)
			{
				$message = str_replace($change, $url . $change, $message);
			}
		}

		return $message;
	}
}
