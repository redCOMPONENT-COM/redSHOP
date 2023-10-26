<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

/**
 * Tags replacer abstract class
 *
 * @since 3.0
 */
class RedshopTagsSectionsWishlistMail extends RedshopTagsAbstract
{
    /**
     * @var    int
     *
     * @since 3.0
     */
    public $itemId;

    /**
     * @var    int
     *
     * @since 3.0
     */
    public $wishlistId;

    /**
     * Init
     *
     * @return  void
     *
     * @since 3.0
     */
    public function init()
    {
        $this->itemId     = $this->data['itemId'];
        $this->wishlistId = $this->data['wishlistId'];
    }

    /**
     * Executing replace
     * @return string
     *
     * @throws Exception
     * @since 3.0
     */
    public function replace()
    {
        $user = $this->data['user'];

        if ($this->isTagExists('{email_to_friend}')) {
            $emailToFriend = RedshopLayoutHelper::render(
                'tags.common.tag',
                array(
                    'text'  => Text::_('COM_REDSHOP_EMAIL_TO_FRIEND'),
                    'id'    => 'emailtofriend',
                    'tag'   => 'div',
                    'class' => 'sendmailtofrind'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{email_to_friend}"] = $emailToFriend;
            $this->template                          = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{emailto_lbl}')) {
            $emailToLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'text' => Text::_('COM_REDSHOP_EMAIL_TO'),
                    'id'   => 'emailto'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{emailto_lbl}"] = $emailToLbl;
            $this->template                      = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{emailto}')) {
            $emailTo = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => 'emailto',
                    'name'  => 'emailto',
                    'type'  => 'text',
                    'value' => ''
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{emailto}"] = $emailTo;
            $this->template                  = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{sender_lbl}')) {
            $senderLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'text' => Text::_('COM_REDSHOP_SENDER'),
                    'id'   => 'sender'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{sender_lbl}"] = $senderLbl;
            $this->template                     = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{sender}')) {
            $sender = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => 'sender',
                    'name'  => 'sender',
                    'type'  => 'text',
                    'value' => $user->name
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{sender}"] = $sender;
            $this->template                 = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{mail_lbl}')) {
            $mailLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'text' => Text::_('COM_REDSHOP_YOUR_EMAIL'),
                    'id'   => 'email'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{mail_lbl}"] = $mailLbl;
            $this->template                   = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{mail}')) {
            $mail = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => 'email',
                    'name'  => 'email',
                    'type'  => 'text',
                    'value' => $user->email
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{mail}"] = $mail;
            $this->template               = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{subject_lbl}')) {
            $subjectLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'text' => Text::_('COM_REDSHOP_SUBJECT'),
                    'id'   => 'subject'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{subject_lbl}"] = $subjectLbl;
            $this->template                      = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{subject}')) {
            $subject = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => 'subject',
                    'name'  => 'subject',
                    'type'  => 'text',
                    'value' => ''
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{subject}"] = $subject;
            $this->template                  = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{cancel_button}')) {
            $cancelButton = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => 'cancel',
                    'name'  => 'cancel',
                    'type'  => 'button',
                    'value' => Text::_('COM_REDSHOP_CANCEL'),
                    'class' => 'button btn',
                    'attr'  => 'onclick="parent.location.reload()"'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{cancel_button}"] = $cancelButton;
            $this->template                        = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{send_button}')) {
            $sendButton = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => 'send',
                    'name'  => 'send',
                    'type'  => 'submit',
                    'value' => Text::_('COM_REDSHOP_SEND'),
                    'class' => 'button btn btn-primary'
                ),
                '',
                RedshopLayoutHelper::$layoutOption
            );

            $this->replacements["{send_button}"] = $sendButton;
            $this->template                      = $this->strReplace($this->replacements, $this->template);
        }

        $template = RedshopLayoutHelper::render(
            'tags.wishlistmail.template',
            array(
                'itemId'     => $this->itemId,
                'wishlistId' => $this->wishlistId,
                'content'    => $this->template
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->template = RedshopHelperTemplate::parseRedshopPlugin($template);

        return parent::replace();
    }
}