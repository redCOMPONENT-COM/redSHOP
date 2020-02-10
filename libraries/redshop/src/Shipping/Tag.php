<?php
/**
 * @package     RedShop
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Redshop\Shipping;

defined('_JEXEC') or die;

/**
 * Shipping tag
 *
 * @since  2.1.0
 */
class Tag
{
    /**
     * Replace shipping method
     *
     * @param   object  $shipping  Shipping data
     * @param   string  $content   Template content
     *
     * @return  string
     *
     * @since   2.1.0
     */
    public static function replaceShippingMethod($shipping, $content = "")
    {
        $search = array(
            '{shipping_method}',
            '{order_shipping}',
            '{shipping_excl_vat}',
            '{shipping_rate_name}',
            '{shipping}',
            '{vat_shipping}',
            '{order_shipping_shop_location}'
        );

        if (!\Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE') || empty((array)$shipping)) {
            return str_replace($search, array("", "", "", "", '', '', '', ''), $content);
        }

        $shippingDetail = \Redshop\Shipping\Rate::decrypt($shipping->ship_method_id);

        if (count($shippingDetail) <= 1) {
            $shippingDetail = explode("|", $shipping->ship_method_id);
        }

        $shippingMethod   = '';
        $shippingRateName = '';

        if (count($shippingDetail) > 0) {
            $element = str_replace('plgredshop_shipping', '', strtolower($shippingDetail[0]));

            // Load language file of the shipping plugin
            \JFactory::getLanguage()->load('plg_redshop_shipping_' . $element, JPATH_ADMINISTRATOR);

            // Load language file from plugin folder.
            \JFactory::getLanguage()->load(
                'plg_redshop_shipping_' . $element,
                JPATH_ROOT . '/plugins/redshop_shipping/' . $element
            );

            if (array_key_exists(1, $shippingDetail)) {
                $shippingMethod = $shippingDetail[1];
            }

            if (array_key_exists(2, $shippingDetail)) {
                $shippingRateName = $shippingDetail[2];
            }
        }

        $replace = array(
            \JText::_($shippingMethod),
            \RedshopHelperProductPrice::formattedPrice($shipping->order_shipping),
            \RedshopHelperProductPrice::formattedPrice($shipping->order_shipping - $shipping->order_shipping_tax),
            \JText::_($shippingRateName),
            \RedshopHelperProductPrice::formattedPrice($shipping->order_shipping),
            \RedshopHelperProductPrice::formattedPrice($shipping->order_shipping_tax),
            self::getShopLocation($shipping, $shippingDetail)
        );

        return str_replace($search, $replace, $content);
    }

    /**
     * Method for prepare shop location
     *
     * @param   object  $shipping        Shipping
     * @param   array   $shippingDetail  Decrypted shipping detail
     *
     * @return  string
     * @since   2.1.0
     */
    protected static function getShopLocation($shipping, $shippingDetail)
    {
        $shopLocation = $shipping->shop_id;

        // @TODO: Shipping GLS
        if (!empty($shippingDetail) && strtolower($shippingDetail[0]) != 'plgredshop_shippingdefault_shipping_gls') {
            $shopLocation = '';
        }

        $mobiles = array();

        if (!empty($shopLocation)) {
            $mobiles            = explode('###', $shopLocation);
            $arrLocationDetails = explode('|', $shopLocation);
            $countLocDet        = count($arrLocationDetails);
            $shopLocation       = '';

            if ($countLocDet > 1) {
                $shopLocation .= '<b>' . $arrLocationDetails[0] . ' ' . $arrLocationDetails[1] . '</b>';
            }

            if ($countLocDet > 2) {
                $shopLocation .= '<br>' . $arrLocationDetails[2];
            }

            if ($countLocDet > 3) {
                $shopLocation .= '<br>' . $arrLocationDetails[3];
            }

            if ($countLocDet > 4) {
                $shopLocation .= ' ' . $arrLocationDetails[4];
            }

            if ($countLocDet > 5) {
                $shopLocation .= '<br>' . $arrLocationDetails[5];
            }

            if ($countLocDet > 6) {
                $locationsTime = explode('  ', $arrLocationDetails[6]);
                $shopLocation  .= '<br>';

                foreach ($locationsTime as $locationTime) {
                    $shopLocation .= $locationTime . '<br>';
                }
            }
        }

        if (!!empty($mobiles) && isset($mobiles[1])) {
            $shopLocation .= ' ' . $mobiles[1];
        }

        return $shopLocation;
    }

    /**
     * Replace Shipping Address
     *
     * @param   string   $templateHtml     Template content
     * @param   object   $shippingAddress  Shipping address
     * @param   boolean  $sendMail         Is in send mail
     *
     * @return  string
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    public static function replaceShippingAddress($templateHtml, $shippingAddress, $sendMail = false)
    {
        $shippingEnable = \Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE');

        if (strpos($templateHtml, '{shipping_address_start}') !== false
            && strpos($templateHtml, '{shipping_address_end}') !== false) {
            self::replaceShippingAddressStartEnd($templateHtml, $shippingAddress, $shippingEnable);
        } elseif (strpos($templateHtml, '{shipping_address}') !== false) {
            self::replaceShippingAddressBlock($templateHtml, $shippingAddress, $sendMail, $shippingEnable);
        }

        $shippingText = $shippingEnable === true ? \JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL') : '';
        $templateHtml = str_replace("{shipping_address_information_lbl}", $shippingText, $templateHtml);

        return $templateHtml;
    }

    /**
     * Replace Shipping Address block {shipping_address}
     *
     * @param   string   $templateHtml     Template content
     * @param   object   $shippingAddress  Shipping address
     * @param   boolean  $sendMail         Is in send mail
     * @param   boolean  $shippingEnable   Enable shipping or not
     *
     * @return  void
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    protected static function replaceShippingAddressBlock(&$templateHtml, $shippingAddress, $sendMail, $shippingEnable)
    {
        if (null === $shippingAddress || $shippingAddress === new \stdClass || $shippingEnable === false) {
            $templateHtml = str_replace("{shipping_address}", '', $templateHtml);

            return;
        }

        $shippingLayout = $sendMail === true ? 'mail.shipping' : 'cart.shipping';

        \JPluginHelper::importPlugin('redshop_shipping');
        \RedshopHelperUtility::getDispatcher()->trigger('onBeforeRenderShippingAddress', array(&$shippingAddress));

        $html = \RedshopLayoutHelper::render(
            $shippingLayout,
            array('shippingaddresses' => $shippingAddress),
            null,
            array('client' => 0)
        );

        $section = $shippingAddress->is_company == 1 ? \RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS :
            \RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS;

        // Additional functionality - more flexible way
        $templateHtml = \Redshop\Helper\ExtraFields::displayExtraFields(
            $section,
            $shippingAddress->users_info_id,
            "",
            $templateHtml
        );

        $templateHtml = str_replace("{shipping_address}", $html, $templateHtml);
    }

    /**
     * Replace Shipping Address block {shipping_address_start}...{shipping_address_end}
     *
     * @param   string   $templateHtml     Template content
     * @param   object   $shippingAddress  Shipping address
     * @param   boolean  $shippingEnable   Enable shipping or not
     *
     * @return  void
     * @throws  \Exception
     *
     * @since   2.1.0
     */
    protected static function replaceShippingAddressStartEnd(&$templateHtml, $shippingAddress, $shippingEnable)
    {
        // Remove {shipping_address} tag
        $templateHtml = str_replace("{shipping_address}", "", $templateHtml);

        $templateStart = explode('{shipping_address_start}', $templateHtml);
        $templateEnd   = explode('{shipping_address_end}', $templateStart[1]);
        $shippingData  = $shippingEnable ? $templateEnd[0] : '';

        if (null !== $shippingAddress && $shippingAddress !== new \stdClass && $shippingEnable) {
            $extraSection = $shippingAddress->is_company == 1 ?
                \RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS : \RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS;

            if ($shippingAddress->is_company == 1) {
                self::replaceTag(
                    $shippingData,
                    $shippingAddress->company_name,
                    array('{companyname}', '{companyname_lbl}'),
                    array($shippingAddress->company_name, \JText::_('COM_REDSHOP_COMPANY_NAME'))
                );
            }

            self::replaceTag(
                $shippingData,
                $shippingAddress->firstname,
                array('{firstname}', '{firstname_lbl}'),
                array($shippingAddress->firstname, \JText::_('COM_REDSHOP_FIRSTNAME'))
            );

            self::replaceTag(
                $shippingData,
                $shippingAddress->lastname,
                array('{lastname}', '{lastname_lbl}'),
                array($shippingAddress->lastname, \JText::_('COM_REDSHOP_LASTNAME'))
            );

            self::replaceTag(
                $shippingData,
                $shippingAddress->address,
                array('{address}', '{address_lbl}'),
                array($shippingAddress->address, \JText::_('COM_REDSHOP_ADDRESS'))
            );

            self::replaceTag(
                $shippingData,
                $shippingAddress->zipcode,
                array('{zip}', '{zip_lbl}'),
                array($shippingAddress->zipcode, \JText::_('COM_REDSHOP_ZIP'))
            );

            self::replaceTag(
                $shippingData,
                $shippingAddress->city,
                array('{city}', '{city_lbl}'),
                array($shippingAddress->city, \JText::_('COM_REDSHOP_CITY'))
            );

            $cname = \RedshopHelperOrder::getCountryName($shippingAddress->country_code);
            self::replaceTag(
                $shippingData,
                $cname,
                array('{country}', '{country_lbl}'),
                array(\JText::_($cname), \JText::_('COM_REDSHOP_COUNTRY'))
            );

            $stateName = \RedshopHelperOrder::getStateName(
                $shippingAddress->state_code,
                $shippingAddress->country_code
            );
            self::replaceTag(
                $shippingData,
                $stateName,
                array('{state}', '{state_lbl}'),
                array($stateName, \JText::_('COM_REDSHOP_STATE'))
            );

            self::replaceTag(
                $shippingData,
                $shippingAddress->phone,
                array('{phone}', '{phone_lbl}'),
                array($shippingAddress->phone, \JText::_('COM_REDSHOP_PHONE'))
            );

            $shippingData = str_replace(
                '{shipping_extrafield}',
                \RedshopHelperExtrafields::listAllFieldDisplay($extraSection, $shippingAddress->users_info_id, 1),
                $shippingData
            );

            // Additional functionality - more flexible way
            $shippingData = \Redshop\Helper\ExtraFields::displayExtraFields(
                $extraSection,
                $shippingAddress->users_info_id,
                "",
                $shippingData
            );
        } else {
            $shippingData = str_replace(
                array(
                    '{companyname}',
                    '{companyname_lbl}',
                    '{firstname}',
                    '{firstname_lbl}',
                    '{lastname}',
                    '{lastname_lbl}',
                    '{address}',
                    '{address_lbl}',
                    '{zip}',
                    '{zip_lbl}',
                    '{city}',
                    '{city_lbl}',
                    '{country}',
                    '{country_lbl}',
                    '{state}',
                    '{state_lbl}',
                    '{phone}',
                    '{phone_lbl}',
                    '{shipping_extrafield}'
                ),
                '',
                $shippingData
            );
        }

        $templateHtml = $templateStart[0] . $shippingData . $templateEnd[1];
    }

    /**
     * Method for replace with condition
     *
     * @param   string  $html       Template Html
     * @param   string  $condition  Condition for check
     * @param   array   $search     List of tag
     * @param   array   $replace    List of associated data
     *
     * @return  void
     *
     * @since   2.1.0
     */
    protected static function replaceTag(&$html, $condition = '', $search = array(), $replace = array())
    {
        $replace = !empty($condition) ? $replace : array('');
        $html    = str_replace($search, $replace, $html);
    }

    /**
     * @param   string  $boxTemplateDesc
     * @param   int     $shippingBoxPostId
     *
     * @return string|string[]
     * @since __DEPLOY_VERSION__
     */
    public function replaceShippingBoxTemplate($boxTemplateDesc = "", $shippingBoxPostId = 0)
    {
        // Get shipping boxes HTML
        $shippingBoxes = RedshopHelperShipping::getShippingBox();

        $boxTemplateDesc = str_replace(
            "{shipping_box_heading}",
            JText::_('COM_REDSHOP_SHIPPING_BOXES'),
            $boxTemplateDesc
        );

        if (count($shippingBoxes) == 1 || (count($shippingBoxes) > 0 && $shippingBoxPostId == 0)) {
            $shippingBoxPostId = $shippingBoxes[0]->shipping_box_id;
        }

        $shippingBoxList = JText::_('COM_REDSHOP_NO_SHIPPING_BOX');

        if (count($shippingBoxes) > 0) {
            $shippingBoxList = "";

            for ($i = 0, $in = count($shippingBoxes); $i < $in; $i++) {
                $shippingBoxId = $shippingBoxes[$i]->shipping_box_id;

                // Previous priority
                if ($i > 0) {
                    $shippingBoxPriorityPre = $shippingBoxes[$i - 1]->shipping_box_priority;
                }

                // Current priority
                $shipping_box_priority = $shippingBoxes[$i]->shipping_box_priority;
                $checked               = ($shippingBoxPostId == $shippingBoxId) ? "checked='checked'" : "";

                if ($i == 0 || ($shipping_box_priority == $shippingBoxPriorityPre)) {
                    $shippingBoxList .= "<div class='radio'><label class=\"radio\" for='shipping_box_id"
                        . $shippingBoxId . "'><input " . $checked
                        . " type='radio' id='shipping_box_id" . $shippingBoxId
                        . "' name='shipping_box_id'  onclick='javascript:onestepCheckoutProcess(this.name, \'\');' value='"
                        . $shippingBoxId . "' />";
                    $shippingBoxList .= "" . $shippingBoxes[$i]->shipping_box_name . "</label></div>";
                }
            }
        }

        $boxTemplateDesc = str_replace("{shipping_box_list}", $shippingBoxList, $boxTemplateDesc);
        $style             = 'none';

        $shippingMethod = RedshopHelperOrder::getShippingMethodInfo();

        for ($s = 0, $sn = count($shippingMethod); $s < $sn; $s++) {
            if (in_array($shippingMethod[$s]->element, ['bring', 'ups', 'uspsv4'])) {
                $style = 'block';
            }
        }

        if (count($shippingBoxes) <= 1 || count($shippingMethod) <= 1) {
            $style = 'none';
        }

        $boxTemplateDesc = "<div style='display:$style;'>" . $boxTemplateDesc . "</div>";

        return $boxTemplateDesc;
    }

    /**
     * @param   string  $templateDesc
     * @param   int     $shippingRateId
     * @param   int     $shippingBoxPostId
     * @param   int     $userId
     * @param   int     $usersInfoId
     * @param   int     $orderTotal
     * @param   int     $orderSubtotal
     * @param   array   $post
     *
     * @return array
     * @since __DEPLOY_VERSION__
     */
    public static function replaceShippingTemplate(
        $templateDesc = "",
        $shippingRateId = 0,
        $shippingBoxPostId = 0,
        $userId = 0,
        $usersInfoId = 0,
        $orderTotal = 0,
        $orderSubtotal = 0,
        $post = array()
    ) {
        $shippingMethod       = RedshopHelperOrder::getShippingMethodInfo();
        $rateExist            = 0;
        $d                    = array();
        $d['user_id']         = $userId;
        $d['users_info_id']   = $usersInfoId;
        $d['shipping_box_id'] = $shippingBoxPostId;
        $d['ordertotal']      = $orderTotal;
        $d['order_subtotal']  = $orderSubtotal;
        $d['post']            = $post;
        $templateDesc        = str_replace(
            "{shipping_heading}",
            JText::_('COM_REDSHOP_SHIPPING_METHOD'),
            $templateDesc
        );
        
        $extraFieldsTotal     = "";

        if (strpos($templateDesc, "{shipping_method_loop_start}") !== false && strpos(
                $templateDesc,
                "{shipping_method_loop_end}"
            ) !== false) {
            $template1       = explode("{shipping_method_loop_start}", $templateDesc);
            $template1       = explode("{shipping_method_loop_end}", $template1[1]);
            $templateMiddle = $template1[0];

            $templateRateMiddle = "";

            if (strpos($templateMiddle, "{shipping_rate_loop_start}") !== false && strpos(
                    $templateMiddle,
                    "{shipping_rate_loop_end}"
                ) !== false) {
                $template1            = explode("{shipping_rate_loop_start}", $templateMiddle);
                $template1            = explode("{shipping_rate_loop_end}", $template1[1]);
                $templateRateMiddle = $template1[0];
            }

            $rateData = "";

            if ($templateMiddle != "" && count($shippingMethod) > 0) {
                JPluginHelper::importPlugin('redshop_shipping');
                $dispatcher   = RedshopHelperUtility::getDispatcher();
                $shippingRate = $dispatcher->trigger('onListRates', array(&$d));

                if (count($shippingRate) <= 1 && count($shippingRate[0]) <= 1) {
                    $templateDesc = str_replace('{show_when_one_rate}', 'none', $templateDesc);
                }

                for ($s = 0, $sn = count($shippingMethod); $s < $sn; $s++) {
                    if (isset($shippingRate[$s]) === false) {
                        continue;
                    }

                    $rate = $shippingRate[$s];

                    if (!empty($rate)) {
                        if (empty($shippingRateId)) {
                            $shippingRateId = $rate[0]->value;
                        }

                        $rs        = $shippingMethod[$s];
                        $className = $rs->element;
                        $rateData .= $templateMiddle;
                        $rateData = str_replace("{shipping_method_title}", JText::_($rs->name), $rateData);

                        if ($templateRateMiddle != "") {
                            $data         = "";
                            $mainLocation = "";

                            for ($i = 0, $in = count($rate); $i < $in; $i++) {
                                if (isset($rate[$i]->shipping_rate_state) && !empty($rate[$i]->shipping_rate_state)) {
                                    if (Redshop\Cart\Cart::isDiffCountryState($rate[$i], $usersInfoId, $_POST)) {
                                        continue;
                                    }
                                }

                                $checked = '';
                                $data    .= $templateRateMiddle;

                                $displayRate = (trim($rate[$i]->rate) > 0) ?
                                    " (" . RedshopHelperProductPrice::formattedPrice((double)trim($rate[$i]->rate)) . " )"
                                    : "";

                                if ((isset($rate[$i]->checked) && $rate[$i]->checked) || $rateExist == 0) {
                                    $checked = "checked";
                                }

                                if ($checked == "checked") {
                                    $shippingRateId = $rate[$i]->value;
                                }

                                $shippingRateName = '<label class="radio inline" for="shipping_rate_id_'
                                    . $shippingMethod[$s]->extension_id . '_' . $i
                                    . '"><input type="radio" id="shipping_rate_id_'
                                    . $shippingMethod[$s]->extension_id . '_' . $i
                                    . '" name="shipping_rate_id" value="'
                                    . $rate[$i]->value . '" '
                                    . $checked
                                    . ' onclick="javascript:onestepCheckoutProcess(this.name,\'' . $className
                                    . '\');"><span>'
                                    . '' . html_entity_decode($rate[$i]->text) . '</span></label>';

                                $shippingRateShortDesc = '';

                                if (isset($rate[$i]->shortdesc) === true) {
                                    $shippingRateShortDesc = html_entity_decode($rate[$i]->shortdesc);
                                }

                                $shippingRateDesc = '';

                                if (isset($rate[$i]->longdesc) === true) {
                                    $shippingRateDesc = html_entity_decode($rate[$i]->longdesc);
                                }

                                $rateExist++;
                                $data = str_replace("{shipping_rate_name}", $shippingRateName, $data);
                                $data = str_replace("{shipping_rate_short_desc}", $shippingRateShortDesc, $data);
                                $data = str_replace("{shipping_rate_desc}", $shippingRateDesc, $data);
                                $data = str_replace("{shipping_rate}", $displayRate, $data);

                                if (strpos($data, "{shipping_location}") !== false) {
                                    $shippingLocation = RedshopHelperOrder::getShippingLocationInfo($rate[$i]->text);

                                    for ($k = 0, $kn = count($shippingLocation); $k < $kn; $k++) {
                                        if ($shippingLocation[$k] != '') {
                                            $mainLocation = $shippingLocation[$k]->shipping_location_info;
                                        }
                                    }

                                    $data = str_replace("{shipping_location}", $mainLocation, $data);
                                }

                                $dispatcher->trigger(
                                    'onReplaceShippingTemplate',
                                    array($d, &$data, $className, $checked)
                                );

                                $data = str_replace("{gls_shipping_location}", "", $data);
                            }

                            $rateData = str_replace("{shipping_rate_loop_start}", "", $rateData);
                            $rateData = str_replace("{shipping_rate_loop_end}", "", $rateData);
                            $rateData = str_replace($templateRateMiddle, $data, $rateData);
                        }
                    }

                    if (strpos($rateData, "{shipping_extrafields}") !== false) {
                        $paymentParamsNew  = new JRegistry($shippingMethod[$s]->params);
                        $extraFieldPayment = $paymentParamsNew->get('extrafield_shipping');

                        $extraFieldHidden = "";

                        if (!empty($extraFieldPayment)) {
                            $countExtraField = count($extraFieldPayment);

                            for ($ui = 0; $ui < $countExtraField; $ui++) {
                                $productUserFields = Redshop\Fields\SiteHelper::listAllUserFields(
                                    $extraFieldPayment[$ui],
                                    19,
                                    '',
                                    0,
                                    0,
                                    0
                                );
                                $extraFieldsTotal  .= $productUserFields[0] . " " . $productUserFields[1] . "<br>";
                                $extraFieldHidden .= "<input type='hidden' name='extrafields[]' value='"
                                    . $extraFieldPayment[$ui] . "'>";
                            }

                            $rateData = str_replace(
                                "{shipping_extrafields}",
                                "<div id='extrafield_shipping'>" . $extraFieldsTotal . "</div>",
                                $rateData
                            );
                        } else {
                            $rateData = str_replace("{shipping_extrafields}", "", $rateData);
                        }
                    }
                }
            }

            $templateDesc = str_replace("{shipping_method_loop_start}", "", $templateDesc);
            $templateDesc = str_replace("{shipping_method_loop_end}", "", $templateDesc);
            $templateDesc = str_replace('{show_when_one_rate}', 'block', $templateDesc);
            $templateDesc = str_replace($templateMiddle, $rateData, $templateDesc);
        }

        if ($rateExist == 0) {
            $templateDesc = "<div></div>";
        }

        JPluginHelper::importPlugin('redshop_checkout');
        JDispatcher::getInstance()->trigger('onRenderShippingMethod', array(&$templateDesc));

        $return = array("template_desc" => $templateDesc, "shipping_rate_id" => $shippingRateId);

        return $return;
    }
}
