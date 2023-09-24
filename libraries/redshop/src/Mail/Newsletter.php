<?php
/**
 * @package     RedShop
 * @subpackage  Order
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Mail;

use Joomla\CMS\Factory;

defined('_JEXEC') or die;

/**
 * Mail Newsletter helper
 *
 * @since  2.1.0
 */
class Newsletter
{
    /**
     * Send newsletter cancellation mail
     *
     * @param   string  $email  Email
     *
     * @return  boolean
     *
     * @since   2.1.0
     */
    public static function sendCancellationMail($email = "")
    {
        $mailSection = "newsletter_cancellation";
        $mailInfo    = Helper::getTemplate(0, $mailSection);

        if (empty($mailInfo)) {
            return false;
        }

        $config  = \JFactory::getConfig();
        $mailBcc = null;
        $message = $mailInfo[0]->mail_body;
        $subject = $mailInfo[0]->mail_subject;

        if (trim($mailInfo[0]->mail_bcc) != "") {
            $mailBcc = explode(",", $mailInfo[0]->mail_bcc);
        }

        $search[]  = "{shopname}";
        $replace[] = \Redshop::getConfig()->get('SHOP_NAME');
        $subject   = str_replace($search, $replace, $subject);
        $message   = str_replace($search, $replace, $message);

        Helper::imgInMail($message);

        $from     = $config->get('mailfrom');
        $fromName = $config->get('fromname');

        // Send the e-mail
        if ($email != "") {
            return Helper::sendEmail(
                $from,
                $fromName,
                $email,
                $subject,
                $message,
                1,
                null,
                $mailBcc,
                null,
                $mailSection,
                func_get_args()
            );
        }

        return true;
    }

    /**
     * Send newsletter confirmation mail
     *
     * @param   integer  $subscriptionId  Subscription id
     *
     * @return  boolean
     *
     * @since   2.1.0
     */
    public static function sendConfirmationMail($subscriptionId)
    {
        if (!\Redshop::getConfig()->getBool('NEWSLETTER_CONFIRMATION') || !$subscriptionId) {
            return false;
        }

        $config  = \JFactory::getConfig();
        $url     = \JUri::root();
        $db      = \JFactory::getDbo();
        $mailBcc = null;

        $mailSection = "newsletter_confirmation";
        $mailInfo    = Helper::getTemplate(0, $mailSection);

        if (empty($mailInfo)) {
            return false;
        }

        $message = $mailInfo[0]->mail_body;
        $subject = $mailInfo[0]->mail_subject;

        if (trim($mailInfo[0]->mail_bcc) != "") {
            $mailBcc = explode(",", $mailInfo[0]->mail_bcc);
        }

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__redshop_newsletter_subscription'))
            ->where($db->qn('id') . ' = ' . (int)$subscriptionId);

        $list      = $db->setQuery($query)->loadObject();
        $link      = '<a href="' . $url . 'index.php?option=com_redshop&view=newsletter&sid=' . $subscriptionId . '">' .
            \JText::_('COM_REDSHOP_CLICK_HERE') . '</a>';
        $search[]  = "{shopname}";
        $replace[] = \Redshop::getConfig()->get('SHOP_NAME');
        $search[]  = "{link}";
        $replace[] = $link;
        $search[]  = "{name}";
        $replace[] = $list->name;

        $email   = $list->email;
        $subject = str_replace($search, $replace, $subject);
        $message = str_replace($search, $replace, $message);

        Helper::imgInMail($message);

        $from     = $config->get('mailfrom');
        $fromName = $config->get('fromname');

        // Send the e-mail
        if ($email != "") {
            if (!Helper::sendEmail(
                $from,
                $fromName,
                $email,
                $subject,
                $message,
                1,
                null,
                $mailBcc,
                null,
                $mailSection,
                func_get_args()
            )) {
				Factory::getApplication()->enqueueMessage(
					\JText::_('COM_REDSHOP_ERROR_SENDING_CONFIRMATION_MAIL'),
					'warning'
				);
            }
        }

        return true;
    }
}
