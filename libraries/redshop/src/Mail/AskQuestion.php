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
 * Mail Ask Question helper
 *
 * @since  2.1.0
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
	 * @since   2.1.0
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

		return $email && Helper::sendEmail($from, $fromName, $email, $subject, $dataAdd, 1, null, $mailBcc, null, $mailSection, func_get_args());
	}

	/**
	 * Send Mail For Ask Question
	 *
	 * @param   array  $data  Question data
	 *
	 * @return  boolean
	 * @throws  \Exception
	 */
	public static function sendAskQuestion($data)
	{
		if (empty(\Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL')))
		{
			return true;
		}

		$itemId    = $data['Itemid'];
		$mailBcc   = null;
		$subject   = '';
		$message   = $data['your_question'];
		$productId = $data['product_id'];
		$mailBody  = Helper::getTemplate(0, 'ask_question_mail');
		$content   = $message;

		if (!empty($mailBody))
		{
			$content = $mailBody[0]->mail_body;
			$subject = $mailBody[0]->mail_subject;

			if (trim($mailBody[0]->mail_bcc) != '')
			{
				$mailBcc = explode(',', $mailBody[0]->mail_bcc);
			}
		}

		$product = \RedshopHelperProduct::getProductById($productId);
		$content = str_replace('{product_name}', $product->product_name, $content);
		$content = str_replace('{product_desc}', $product->product_desc, $content);

		// Init required properties
		$data['address']   = isset($data['address']) ? $data['address'] : null;
		$data['telephone'] = isset($data['telephone']) ? $data['telephone'] : null;

		$link    = \JRoute::_(\JUri::base() . 'index.php?option=com_redshop&view=product&pid=' . $productId . '&Itemid=' . $itemId);
		$content = str_replace('{product_link}', '<a href="' . $link . '">' . $product->product_name . '</a>', $content);
		$content = str_replace('{user_question}', $message, $content);
		$content = str_replace('{answer}', '', $content);
		$subject = str_replace('{user_question}', $message, $subject);
		$subject = str_replace('{shopname}', \Redshop::getConfig()->get('SHOP_NAME'), $subject);
		$content = str_replace('{user_address}', $data['address'], $content);
		$content = str_replace('{user_telephone}', $data['telephone'], $content);
		$content = str_replace('{user_telephone_lbl}', \JText::_('COM_REDSHOP_USER_PHONE_LBL'), $content);
		$content = str_replace('{user_address_lbl}', \JText::_('COM_REDSHOP_USER_ADDRESS_LBL'), $content);

		Helper::imgInMail($content);

		return \JFactory::getMailer()->sendMail(
			$data['your_email'],
			$data['your_name'],
			explode(',', \Redshop::getConfig()->getString('ADMINISTRATOR_EMAIL')),
			$subject,
			$content,
			true,
			null,
			$mailBcc
		);
	}
}
