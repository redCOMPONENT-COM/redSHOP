<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since  2.1
 */
class RedshopTagsSectionsAskQuestion extends RedshopTagsAbstract
{
    public $tags = array(
        '{user_name_lbl}',
        '{user_email_lbl}',
        '{user_question_lbl}',
        '{user_telephone_lbl}',
        '{user_address_lbl}',
        '{user_name}',
        '{user_email}',
        '{user_question}',
        '{user_address}',
        '{user_telephone}',
        '{send_button}',
        '{captcha}'
    );

    public function init()
    {
    }

    public function replace()
    {
        $this->setTemplateAskQuestion();

        $form       = $this->data['form'];
        $user       = JFactory::getUser();
        $input      = JFactory::getApplication()->input;
        $menuItemId = $input->getInt('Itemid', 0);
        $pid        = $input->getInt('pid', 0);
        $categoryId = $input->getInt('category_id', 0);

        if ($user->id) {
            $form->setValue('your_name', null, $form->getValue('your_name', null, $user->name));
            $form->setValue('your_email', null, $form->getValue('your_email', null, $user->email));
        }

        $this->replacements['{user_name_lbl}']      = $form->getLabel('your_name');
        $this->replacements['{user_email_lbl}']     = $form->getLabel('your_email');
        $this->replacements['{user_question_lbl}']  = $form->getLabel('your_question');
        $this->replacements['{user_telephone_lbl}'] = $form->getLabel('telephone');
        $this->replacements['{user_address_lbl}']   = $form->getLabel('address');
        $this->replacements['{user_name}']          = $form->getInput('your_name');
        $this->replacements['{user_email}']         = $form->getInput('your_email');
        $this->replacements['{user_question}']      = $form->getInput('your_question');
        $this->replacements['{user_address}']       = $form->getInput('address');
        $this->replacements['{user_telephone}']     = $form->getInput('telephone');

        $sendBtn = RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => '',
                'name'  => '',
                'type'  => 'submit',
                'class' => 'btn',
                'value' => JText::_('COM_REDSHOP_SEND'),
                'attr'  => 'onclick="questionSubmitButton(\'ask_question.submit\')"'
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        $this->replacements['{send_button}'] = $sendBtn;

        $captcha = '';

        if ($user->guest) {
            $captcha = RedshopLayoutHelper::render('registration.captcha');
        }

        $this->replacements['{captcha}'] = $captcha;

        $this->template = RedshopLayoutHelper::render(
            'tags.ask_question.ask_question',
            array(
                'contentForm' => $this->strReplace($this->replacements, $this->template),
                'pid'         => $pid,
                'ask'         => $this->data['ask'],
                'categoryId'  => $categoryId,
                'menuItemId'  => $menuItemId,
            ),
            '',
            RedshopLayoutHelper::$layoutOption
        );

        return parent::replace();
    }

    public function setTemplateAskQuestion()
    {
        $template = RedshopHelperTemplate::getTemplate('ask_question_template');

        if (count($template) > 0 && $template[0]->template_desc != "") {
            $templateContent = $template[0]->template_desc;
        } else {
            $templateContent = RedshopHelperTemplate::getDefaultTemplateContent('ask_question_template');
        }

        $this->template = $templateContent;
    }
}