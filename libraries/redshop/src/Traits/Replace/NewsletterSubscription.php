<?php
/**
 * @package     Redshop.Libraries
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Traits\Replace;

defined('_JEXEC') || die;

use Joomla\CMS\Language\Text;

/**
 * For classes extends class RedshopTagsAbstract
 *
 * @since  3.0
 */
trait NewsletterSubscription
{
    /**
     * @param   string  $templateDesc
     * @param   int     $onChange
     *
     * @return string|string[]
     * @since 3.0
     */
    public function replaceNewsletterSubscription($templateDesc = "", $onChange = 0)
    {
        $db          = \JFactory::getDbo();
        $replacement = [];

        if (strpos($templateDesc, "{newsletter_signup_chk}") !== false) {
            $itemId                                 = (int) \JFactory::getApplication()->input->get('Itemid');
            $replacement['{newsletter_signup_chk}'] = "";
            $replacement['{newsletter_signup_lbl}'] = "";
            $link                                   = "";

            if (\Redshop::getConfig()->get('DEFAULT_NEWSLETTER') != 0) {
                //@TODO: move query code into function for common use
                $user  = \JFactory::getUser();
                $query = $db->getQuery(true);
                $query->select($db->qn('id'))
                    ->from($db->qn('#__redshop_newsletter_subscription'))
                    ->where($db->qn('user_id') . '=' . $db->q($user->id))
                    ->where($db->qn('email') . '=' . $db->q($user->email));

                $db->setQuery($query);
                $subscribe = $db->loadResult();

                //@TODO: TagReplacer instead of str_replace
                if ($subscribe == 0) {
                    if ($onChange) {
                        $link = " onchange='window.location.href=\""
                            . \JURI::root()
                            . "index.php?option=com_redshop&view=account&task=newsletterSubscribe&tmpl=component&Itemid="
                            . $itemId . "\"";
                    }

                    $replacement['{newsletter_signup_chk}'] = \RedshopLayoutHelper::render(
                        'tags.common.input',
                        array(
                            'class' => 'form-check-input',
                            'type'  => 'checkbox',
                            'name'  => 'newsletter_signup',
                            'value' => 1,
                            'attr'  => $link
                        ),
                        '',
                        \RedshopLayoutHelper::$layoutOption
                    );

                    $replacement['{newsletter_signup_lbl}'] = Text::_('COM_REDSHOP_SIGN_UP_FOR_NEWSLETTER');
                }
            }

            $replacement['{newsletter_unsubscribe}'] = '';

            $templateDesc = $this->strReplace($replacement, $templateDesc);
        }

        return $templateDesc;
    }
}