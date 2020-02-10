<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Newsletter;

defined('_JEXEC') or die;

/**
 * Terms & Condition tag
 *
 * @since  __DEPLOY_VERSION__
 */
class Tag
{
    /**
     * @param   string  $templateDesc
     * @param   int     $onChange
     *
     * @return string|string[]
     * @since __DEPLOY_VERSION__
     */
    public function replaceNewsletterSubscription($templateDesc = "", $onChange = 0)
    {
        $db = JFactory::getDbo();

        if (strpos($templateDesc, "{newsletter_signup_chk}") !== false)
        {
            $itemId               = $this->input->get('Itemid');
            $newsletterSignUp     = "";
            $newsletterSignUpLabel = "";
            $link                 = "";

            if (Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != 0)
            {
                //@TODO: move query code into function for common use
                $user  = JFactory::getUser();
                $query = $db->getQuery(true);
                $query->select($db->qn('subscription_id'))
                    ->from($db->qn('#__redshop_newsletter_subscription'))
                    ->where($db->qn('user_id') . '=' . $db->q($user->id))
                    ->where($db->qn('email') . '=' . $db->q($user->email));
                
                $db->setQuery($query);
                $subscribe = $db->loadResult();

                //@TODO: TagReplacer instead of str_replace
                if ($subscribe == 0)
                {
                    if ($onChange)
                    {
                        $link = " onchange='window.location.href=\""
                            . JURI::root()
                            . "index.php?option=com_redshop&view=account&task=newsletterSubscribe&tmpl=component&Itemid="
                            . $itemId . "\"";

                    }

                    $newsletterSignUp     = "<input type='checkbox' name='newsletter_signup' value='1' '$link'>";
                    $newsletterSignUpLabel = JText::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
                }
            }

            $templateDesc = str_replace("{newsletter_signup_chk}", $newsletterSignUp, $templateDesc);
            $templateDesc = str_replace("{newsletter_signup_lbl}", $newsletterSignUpLabel, $templateDesc);
            $templateDesc = str_replace("{newsletter_unsubscribe}", "", $templateDesc);
        }

        return $templateDesc;
    }
}