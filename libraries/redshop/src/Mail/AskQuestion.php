<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

defined('_JEXEC') or die;

/**
 * Mail Ask Question helper
 *
 * @since  __DEPLOY_VERSION__
 */
class AskQuestion
{
	/**
	 * Send ask question mail
	 *
	 * @param   integer  $answerId  Answer id
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function sendMail($answerId)
	{
		$mailSection = "ask_question_mail";
		$mailInfo    = Helper::getTemplate(0, $mailSection);

		if (empty($mailInfo) || !$answerId)
		{
			return false;
		}

		$url     = \JUri::root();
		$mailBcc = null;
		$dataAdd = $mailInfo[0]->mail_body;
		$subject = $mailInfo[0]->mail_subject;

		// Only check if this field is not empty
		if (!empty($mailInfo[0]->mail_bcc))
		{
			$mailBcc = explode(",", $mailInfo[0]->mail_bcc);
		}

		$answerData = \productHelper::getInstance()->getQuestionAnswer($answerId);

		if (empty($answerData))
		{
			return false;
		}

		$answerData = $answerData[0];
		$fromName   = $answerData->user_name;
		$from       = $answerData->user_email;
		$email      = explode(",", trim(\Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL')));
		$question   = $answerData->question;
		$telephone  = "";
		$address    = "";
		$productId  = $answerData->product_id;
		$answer     = '';

		if ($answerData->parent_id)
		{
			$answer       = $answerData->question;
			$questionData = \productHelper::getInstance()->getQuestionAnswer($answerData->parent_id);

			if (count($questionData) > 0)
			{
				$config   = \JFactory::getConfig();
				$from     = $config->get('mailfrom');
				$fromName = $config->get('fromname');

				$questionData = $questionData[0];
				$question     = $questionData->question;
				$email        = $questionData->user_email;
				$productId    = $questionData->product_id;
				$address      = $questionData->address;
				$telephone    = $questionData->telephone;
			}
		}

		$product    = \Redshop::product((int) $productId);
		$link       = \JRoute::_($url . "index.php?option=com_redshop&view=product&pid=" . $productId);
		$dataAdd    = str_replace("{product_name}", $product->product_name, $dataAdd);
		$dataAdd    = str_replace("{product_desc}", $product->product_desc, $dataAdd);
		$productUrl = "<a href=" . $link . ">" . $product->product_name . "</a>";
		$dataAdd    = str_replace("{product_link}", $productUrl, $dataAdd);
		$dataAdd    = str_replace("{user_question}", $question, $dataAdd);
		$dataAdd    = str_replace("{answer}", $answer, $dataAdd);
		$dataAdd    = str_replace("{user_address}", $address, $dataAdd);
		$dataAdd    = str_replace("{user_telephone}", $telephone, $dataAdd);
		$subject    = str_replace("{user_question}", $question, $subject);
		$subject    = str_replace("{shopname}", \Redshop::getConfig()->get('SHOP_NAME'), $subject);
		$subject    = str_replace("{product_name}", $product->product_name, $subject);

		Helper::imgInMail($dataAdd);

		if ($email && Helper::sendEmail($from, $fromName, $email, $subject, $dataAdd, 1, null, $mailBcc, null, $mailSection, func_get_args()))
		{
			return true;
		}

		return false;
	}
}
