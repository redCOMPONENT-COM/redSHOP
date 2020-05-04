<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') || die;

/**
 * Tags replacer abstract class
 *
 * @since  3.0.1
 */
class RedshopTagsSectionsQuotationDetail extends RedshopTagsAbstract
{
    /**
     * @var    integer
     *
     * @since   3.0.1
     */
    public $quotationId;

    /**
     * Init
     *
     * @return  mixed
     *
     * @since   3.0.1
     */
    public function init()
    {
        $this->quotationId = $this->input->getInt('quoid');
    }

    /**
     * Execute replace
     *
     * @return  string
     *
     * @since   __DEPLOY__VERSION__
     */
    public function replace()
    {
        $quotationDetail = RedshopHelperQuotation::getQuotationDetail($this->quotationId);

        $fieldList = RedshopHelperExtrafields::getSectionFieldList(17, 0, 0);
        $print     = $this->input->getInt('print');

        if ($print) {
            $onClick = "onclick='window.print();'";
        } else {
            $printUrl = JURI::base(
                ) . "index.php?tmpl=component&option=com_redshop&view=quotation_detail&quoid=" . $this->quotationId . "&print=1";
            $onClick  = 'onclick="window.open(\'' . $printUrl . '\',\'mywindow\',\'scrollbars=1\',\'location=1\')"';
        }

        $this->replacements['{print}'] = RedshopLayoutHelper::render(
            'tags.common.print',
            [
                'onClick' => $onClick
            ],
            '',
            $this->optionLayout
        );

        $this->replacements['{quotation_id}']     = $this->quotationId;
        $this->replacements['{quotation_number}'] = $quotationDetail->quotation_number;
        $this->replacements['{quotation_date}']   = RedshopHelperDatetime::convertDateFormat(
            $quotationDetail->quotation_cdate
        );

        $this->replacements['{quotation_customer_note_lbl}'] = JText::_('COM_REDSHOP_QUOTATION_CUSTOMER_NOTE');
        $this->replacements['{quotation_customer_note}']     = $quotationDetail->quotation_customer_note;

        $statusText = RedshopHelperQuotation::getQuotationStatusName($quotationDetail->quotation_status);

        if ($quotationDetail->quotation_status == '2') {
            $this->template = str_replace('{quotation_customer_note_lbl}', '', $this->template);
            $this->template = str_replace('{quotation_customer_note}', '', $this->template);
        }

        $this->replacements['{quotation_status}'] = $statusText . RedshopLayoutHelper::render(
                'tags.quotation.status',
                [
                    'quotationDetail' => $quotationDetail,
                    'quoId'           => $this->quotationId,
                    'itemId'          => $this->itemId,
                    'encr'            => $this->input->getString('encr')
                ],
                '',
                $this->optionLayout
            );

        $this->replacements['{quotation_note_lbl}'] = JText::_('COM_REDSHOP_QUOTATION_NOTE');
        $this->replacements['{quotation_note}']     = $quotationDetail->quotation_note;

        $billAddress = "";

        if ($quotationDetail->user_id != 0) {
            $billAddress = RedshopLayoutHelper::render(
                'cart.billing',
                array('billingAddresses' => $quotationDetail),
                null,
                array('client' => 0)
            );
        } else {
            if (!isset($quotationDetail->user_info_id)) {
                $quotationDetail->user_info_id = 0;
            }

            if ($quotationDetail->quotation_email != "") {
                $billAddress .= RedshopLayoutHelper::render(
                    'fields.display',
                    array(
                        'extra_field_label' => JText::_("COM_REDSHOP_EMAIL"),
                        'extra_field_value' => $quotationDetail->quotation_email
                    )
                );
            }
        }

        if (strstr($this->template, "{quotation_custom_field_list}")) {
            $billAddress    .= RedshopHelperExtrafields::listAllFieldDisplay(16, $quotationDetail->quotation_id);
            $this->template = str_replace("{quotation_custom_field_list}", "", $this->template);
        } else {
            $this->template = RedshopHelperExtrafields::listAllFieldDisplay(
                16,
                $quotationDetail->quotation_id,
                0,
                "",
                $this->template
            );
        }

        $this->replaceProductLoop($fieldList, $quotationDetail->quotation_status);

        if ($quotationDetail->quotation_status == 1) {
            $quotationTotal    = "";
            $quotationSubTotal = "";
            $quotationTax      = "";
            $quotationDiscount = "";
        } else {
            $quotationTotal    = RedshopHelperProductPrice::formattedPrice($quotationDetail->quotation_total);
            $quotationSubTotal = RedshopHelperProductPrice::formattedPrice($quotationDetail->quotation_subtotal);
            $quotationTax      = RedshopHelperProductPrice::formattedPrice($quotationDetail->quotation_tax);
            $quotationDiscount = RedshopHelperProductPrice::formattedPrice($quotationDetail->quotation_discount);
        }
        $this->replacements['{account_information}']       = $billAddress;
        $this->replacements['{quotation_discount}']        = $quotationDiscount;
        $this->replacements['{quotation_tax}']             = $quotationTax;
        $this->replacements['{quotation_total}']           = $quotationTotal;
        $this->replacements['{quotation_subtotal}']        = $quotationSubTotal;
        $this->replacements['{quotation_discount_lbl}']    = JText::_('COM_REDSHOP_QUOTATION_DISCOUNT_LBL');
        $this->replacements['{quotation_id_lbl}']          = JText::_('COM_REDSHOP_QUOTATION_ID');
        $this->replacements['{quotation_number_lbl}']      = JText::_('COM_REDSHOP_QUOTATION_NUMBER');
        $this->replacements['{quotation_date_lbl}']        = JText::_('COM_REDSHOP_QUOTATION_DATE');
        $this->replacements['{quotation_status_lbl}']      = JText::_('COM_REDSHOP_QUOTATION_STATUS');
        $this->replacements['{quotation_information_lbl}'] = JText::_('COM_REDSHOP_QUOTATION_INFORMATION');
        $this->replacements['{account_information_lbl}']   = JText::_('COM_REDSHOP_ACCOUNT_INFORMATION');
        $this->replacements['{quotation_detail_lbl}']      = JText::_('COM_REDSHOP_QUOTATION_DETAILS');
        $this->replacements['{product_name_lbl}']          = JText::_('COM_REDSHOP_PRODUCT_NAME');
        $this->replacements['{note_lbl}']                  = JText::_('COM_REDSHOP_NOTE_LBL');
        $this->replacements['{price_lbl}']                 = JText::_('COM_REDSHOP_PRICE_LBL');
        $this->replacements['{quantity_lbl}']              = JText::_('COM_REDSHOP_QUANTITY_LBL');
        $this->replacements['{total_price_lbl}']           = JText::_('COM_REDSHOP_TOTAL_PRICE_LBL');
        $this->replacements['{quotation_subtotal_lbl}']    = JText::_('COM_REDSHOP_QUOTATION_SUBTOTAL');
        $this->replacements['{total_lbl}']                 = JText::_('COM_REDSHOP_QUOTATION_TOTAL');
        $this->replacements['{quotation_tax_lbl}']         = JText::_('COM_REDSHOP_QUOTATION_TAX');


        $this->template = $this->strReplace($this->replacements, $this->template);
        $this->template = RedshopHelperTemplate::parseRedshopPlugin($this->template);

        return parent::replace();
    }

    /**
     * Replace product loop
     *
     * @param   array    $fieldList
     * @param   integer  $quotationStatus
     *
     * @return  void
     *
     * @since   3.0.1
     */
    private function replaceProductLoop($fieldList, $quotationStatus)
    {
        $quotationProducts = RedshopHelperQuotation::getQuotationProduct($this->quotationId);
        $subTemplate       = $this->getTemplateBetweenLoop('{product_loop_start}', '{product_loop_end}');
        $templateMid       = '';

        if (!empty($subTemplate)) {
            for ($i = 0, $in = count($quotationProducts); $i < $in; $i++) {
                $subReplace  = [];
                $wrapperName = "";

                if ($quotationProducts[$i]->product_wrapperid) {
                    $wrapper = RedshopHelperProduct::getWrapper(
                        $quotationProducts[$i]->product_id,
                        $quotationProducts[$i]->product_wrapperid
                    );

                    if (count($wrapper) > 0) {
                        $wrapperName = JText::_(
                                'COM_REDSHOP_WRAPPER'
                            ) . ":<br/>" . $wrapper[0]->wrapper_name . "(" . RedshopHelperProductPrice::formattedPrice(
                                $quotationProducts[$i]->wrapper_price
                            ) . ")";
                    }
                }

                if ($quotationProducts [$i]->is_giftcard == 1) {
                    $productUserFields = RedshopHelperQuotation::displayQuotationUserField(
                        $quotationProducts[$i]->quotation_item_id,
                        13
                    );
                    $giftcardData      = RedshopEntityGiftcard::getInstance(
                        $quotationProducts[$i]->product_id
                    )->getItem();

                    $productNumber = "";
                    $productImage  = '';
                } else {
                    $productUserFields = RedshopHelperQuotation::displayQuotationUserField(
                        $quotationProducts[$i]->quotation_item_id,
                        12
                    );

                    $product = \Redshop\Product\Product::getProductById($quotationProducts[$i]->product_id);

                    $productNumber = $product->product_number;

                    $productImage = $this->replaceProductImage($product);
                }

                $subReplace['{product_name}']        = $quotationProducts[$i]->product_name;
                $subReplace['{product_wrapper}']     = $wrapperName;
                $subReplace['{product_thumb_image}'] = $productImage;
                $subReplace['{product_s_desc}']      = $product->product_s_desc;
                $subReplace['{product_accessory}']   = $quotationProducts[$i]->product_accessory;
                $subReplace['{product_number}']      = $productNumber;
                $subReplace['{product_number_lbl}']  = JText::_('COM_REDSHOP_PRODUCT_NUMBER_LBL');
                $subReplace['{product_userfields}']  = $productUserFields;
                $subReplace['{product_quantity}']    = $quotationProducts[$i]->product_quantity;
                // ProductFinderDatepicker Extra Field Start

                $templateMid = RedshopHelperProduct::getProductFinderDatepickerValue(
                    $templateMid,
                    $quotationProducts[$i]->product_id,
                    $fieldList
                );

                // ProductFinderDatepicker Extra Field End

                if ($quotationStatus == 1) {
                    $subReplace['{product_price}']                = '';
                    $subReplace['{product_total_price}']          = '';
                    $subReplace['{product_price_excl_vat}']       = '';
                    $subReplace['{product_total_price_excl_vat}'] = '';
                } else {
                    $subReplace['{product_price}'] = RedshopHelperProductPrice::formattedPrice(
                        $quotationProducts[$i]->product_price
                    );

                    $subReplace['{product_total_price}'] = RedshopHelperProductPrice::formattedPrice(
                        $quotationProducts[$i]->product_quantity * $quotationProducts[$i]->product_price
                    );

                    $subReplace['{product_total_price_excl_vat}'] = RedshopHelperProductPrice::formattedPrice(
                        $quotationProducts[$i]->product_quantity * $quotationProducts[$i]->product_excl_price
                    );

                    $subReplace['{product_price_excl_vat}'] = RedshopHelperProductPrice::formattedPrice(
                        $quotationProducts[$i]->product_excl_price
                    );
                }

                $templateMid .= RedshopTagsReplacer::_(
                    'attribute',
                    $this->strReplace($subReplace, $subTemplate['template']),
                    array(
                        'product_attribute' => $quotationProducts[$i]->product_attribute,
                    )
                );
            }
        }

        $this->template = $subTemplate['begin'] . $templateMid . $subTemplate['end'];
    }

    /**
     * Replace product image
     *
     * @param   mixed  $product
     *
     * @return  string
     *
     * @since   3.0.1
     */
    private function replaceProductImage($product)
    {
        $productImagePath = "";

        if ($product->product_full_image) {
            if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $product->product_full_image)) {
                $productImagePath = $product->product_full_image;
            } else {
                if (JFile::exists(
                    REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get(
                        'PRODUCT_DEFAULT_IMAGE'
                    )
                )) {
                    $productImagePath = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
                }
            }
        } else {
            if (JFile::exists(
                REDSHOP_FRONT_IMAGES_RELPATH . "product/" . Redshop::getConfig()->get(
                    'PRODUCT_DEFAULT_IMAGE'
                )
            )) {
                $productImagePath = Redshop::getConfig()->get('PRODUCT_DEFAULT_IMAGE');
            }
        }

        if ($productImagePath) {
            $thumbUrl     = RedshopHelperMedia::getImagePath(
                $productImagePath,
                '',
                'thumb',
                'product',
                Redshop::getConfig()->get('CART_THUMB_WIDTH'),
                Redshop::getConfig()->get('CART_THUMB_HEIGHT'),
                Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
            );
            $productImage = "<div class='product_image'><img src='" . $thumbUrl . "'></div>";
        } else {
            $productImage = "<div class='product_image'></div>";
        }

        return $productImage;
    }
}