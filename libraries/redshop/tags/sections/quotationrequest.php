<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2020 - 2021 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopTagsSectionsQuotationRequest extends RedshopTagsAbstract
{
    use \Redshop\Traits\Replace\CartItem;

    /**
     * Init
     *
     * @return  mixed
     *
     * @since   __DEPLOY_VERSION__
     */
    public function init()
    {
    }

    /**
     * @var    array
     *
     * @since   __DEPLOY_VERSION__
     */
    public $tags = [
        '{billing_address_information_lbl}',
        '{quotation_custom_field_list}',
        '{billing_address}',
        '{customer_note}',
        '{cancel_btn}',
        '{request_quotation_btn}'
    ];

    /**
     * Execute replace
     *
     * @return  string
     *
     * @since   __DEPLOY_VERSION__
     */
    public function replace()
    {
        $app = JFactory::getApplication();

        $itemId       = $app->input->getInt('Itemid');
        $return       = $app->input->getString('return');
        $session      = JFactory::getSession();
        $cart         = $session->get('cart');
        $detail       = $this->data['detail'];
        $user         = JFactory::getUser();
        $layoutOption = \RedshopLayoutHelper::$layoutOption;

        $subTemplate = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');

        if (!empty($subTemplate)) {
            $templateMiddle = $this->replaceCartItem(
                $subTemplate['template'],
                $cart,
                0,
                Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')
            );
            $this->template = $subTemplate['begin'] . $templateMiddle . $subTemplate['end'];
        }

        $this->template = Redshop\Cart\Render\Label::replace($this->template);

        if ($user->id) {
            $this->template = \RedshopHelperBillingTag::replaceBillingAddress($this->template, $detail);
            $this->template .= \RedshopLayoutHelper::render(
                'tags.common.input',
                [
                    'type'  => 'hidden',
                    'name'  => 'user_email',
                    'id'    => 'user_email',
                    'value' => $detail->user_email
                ],
                '',
                $layoutOption
            );
        } else {
            $billing = '';

            $emailField           = new stdClass;
            $emailField->title    = JText::_('COM_REDSHOP_EMAIL');
            $emailField->desc     = '';
            $emailField->required = '';

            $inputField = \RedshopLayoutHelper::render(
                'tags.common.input',
                [
                    'type' => 'text',
                    'name' => 'user_email',
                    'id'   => 'user_email'
                ],
                '',
                $layoutOption
            );

            $billing .= \RedshopLayoutHelper::render(
                'fields.html',
                [
                    'fieldHandle' => $emailField,
                    'inputField'  => $inputField
                ]
            );

            if ($this->isTagExists('{quotation_custom_field_list}')) {
                $billing .= Redshop\Fields\SiteHelper::renderFields(
                    RedshopHelperExtrafields::SECTION_QUOTATION,
                    $detail->user_info_id,
                    "tbl"
                );
                $this->addReplace('{quotation_custom_field_list}', '');
            } else {
                $this->template = RedshopHelperExtrafields::listAllField(
                    RedshopHelperExtrafields::SECTION_QUOTATION,
                    $detail->user_info_id,
                    "",
                    $this->template
                );
            }

            $billing = \RedshopLayoutHelper::render(
                'tags.common.tag',
                [
                    'tag'   => 'div',
                    'class' => 'redshop-billingaddresses',
                    'text'  => $billing
                ],
                '',
                $layoutOption
            );

            $this->addReplace(
                '{billing_address_information_lbl}',
                JText::_('COM_REDSHOP_BILLING_ADDRESS_INFORMATION_LBL')
            );

            $this->addReplace('{billing_address}', $billing);
        }

        $cancelBtn = \RedshopLayoutHelper::render(
            'tags.quotation_request.cancel_btn',
            [],
            '',
            $layoutOption
        );

        $quotationBtn = \RedshopLayoutHelper::render(
            'tags.quotation_request.request_quotation_btn',
            [
                'itemId' => $itemId,
                'return' => $return
            ],
            '',
            $layoutOption
        );

        $this->addReplace('{cancel_btn}', $cancelBtn);
        $this->addReplace('{request_quotation_btn}', $quotationBtn);
        $this->addReplace('{order_detail_lbl}', JText::_('COM_REDSHOP_ORDER_DETAIL_LBL'));
        $this->addReplace('{customer_note_lbl}', JText::_('COM_REDSHOP_CUSTOMER_NOTE_LBL'));

        $customerNote = \RedshopLayoutHelper::render(
            'tags.common.textarea',
            [
                'name' => 'quotation_note',
                'id'   => 'quotation_note'
            ],
            '',
            $layoutOption
        );

        $this->addReplace('{customer_note}', $customerNote);

        $this->template = \RedshopLayoutHelper::render(
            'tags.common.form',
            [
                'method'  => 'post',
                'name'    => 'adminForm',
                'id'      => 'adminForm',
                'content' => $this->template,
                'action'  => $this->data['requestUrl'],
                'attr'    => 'enctype="multipart/form-data">'
            ],
            '',
            $layoutOption
        );

        return parent::replace();
    }
}