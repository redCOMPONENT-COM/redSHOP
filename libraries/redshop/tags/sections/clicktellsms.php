<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Tags
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Tags replacer abstract class
 *
 * @since 3.0.1
 */
class RedshopTagsSectionsClicktellSms extends RedshopTagsAbstract
{
    /**
     * @var    array
     *
     * @since 3.0.1
     */
    public $orderData = array();

    /**
     * @var    integer
     *
     * @since 3.0.1
     */
    public $paymentName = '';

    /**
     * Init
     *
     * @return  void
     *
     * @since 3.0.1
     */
    public function init()
    {
        $this->orderData   = $this->data['orderData'];
        $this->paymentName = $this->data['paymentName'];
    }


    /**
     * Executing replace
     * @return string
     *
     * @throws Exception
     * @since 3.0.1
     */
    public function replace()
    {
        $shippingMethod = '';
        $details        = Redshop\Shipping\Rate::decrypt($this->orderData->ship_method_id);

        if (count($details) > 1) {
            $text = "";

            if (array_key_exists(2, $details)) {
                $text = " (" . $details[2] . ")";
            }

            $shippingMethod = $details[1] . $text;
        }

        $userData = RedshopHelperUser::getUserInformation($this->orderData->user_id);

        if ($this->isTagExists('{order_id}')) {
            $this->replacements["{order_id}"] = $this->orderData->order_id;
            $this->template                   = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{order_status}')) {
            $this->replacements["{order_status}"] = $this->orderData->order_status;
            $this->template                       = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{customer_name}')) {
            $this->replacements["{customer_name}"] = $userData->firstname;
            $this->template                        = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{payment_status}')) {
            $this->replacements["{payment_status}"] = $this->orderData->order_payment_status;
            $this->template                         = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{order_comment}')) {
            $this->replacements["{order_comment}"] = $this->orderData->customer_note;
            $this->template                        = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{shipping_method}')) {
            $this->replacements["{shipping_method}"] = $shippingMethod ?? '';
            $this->template                          = $this->strReplace($this->replacements, $this->template);
        }

        if ($this->isTagExists('{payment_method}')) {
            $this->replacements["{payment_method}"] = $this->paymentName;
            $this->template                         = $this->strReplace($this->replacements, $this->template);
        }

        return $this->template;
    }
}