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
 * @since  3.0
 */
class RedshopTagsSectionsShippingTable extends RedshopTagsAbstract
{
    public function init()
    {
    }

    public function replace()
    {
        $shippingTemplate = RedshopHelperTemplate::getTemplate("shipping_template");
        $options          = RedshopLayoutHelper::$layoutOption;
        $data             = $this->data['data'];
        $isCompany        = $this->data['isCompany'];
        $lists            = $this->data['lists'];

        if (count($shippingTemplate) > 0 && $shippingTemplate[0]->template_desc != "") {
            $templateHtml = $shippingTemplate[0]->template_desc;
        } else {
            $templateHtml = RedshopHelperTemplate::getDefaultTemplateContent('shipping_template');
        }

        if (!isset($data["phone_ST"]) || $data["phone_ST"] == 0) {
            $data["phone_ST"] = '';
        }

        $allowCustomer = $isCompany == 1 ? 'style="display:none;"' : '';
        $allowCompany  = $isCompany != 1 ? 'style="display:none;"' : '';

        $requiredPostalCode = Redshop::getConfig()->get('REQUIRED_POSTAL_CODE') == 1 ? 'billingRequired' : '';
        $requiredAddress    = Redshop::getConfig()->get('REQUIRED_ADDRESS') == 1 ? 'billingRequired' : '';
        $requiredPhone      = Redshop::getConfig()->get('REQUIRED_PHONE') == 1 ? 'billingRequired' : '';

        $countries = RedshopHelperWorld::getCountryList(
            $data,
            'country_code_ST',
            'ST',
            'inputbox form-control billingRequired valid',
            'state_code_ST'
        );

        $data['country_code_ST']  = $countries['country_code_ST'];
        $lists['country_code_ST'] = $countries['country_dropdown'];

        $states = RedshopHelperWorld::getStateList($data, 'state_code_ST', 'ST');

        $lists['state_code_ST'] = $states['state_dropdown'];

        $countryStyle = count($countries['countrylist']) == 1 && count(
            $states['statelist']
        ) == 0 ? 'display:none;' : '';
        $stateStyle   = $states['is_states'] <= 0 ? 'display:none;' : '';

        $htmlCompanyNameLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'company_name_ST',
                'class' => 'company_name_ST',
                'text'  => JText::_('COM_REDSHOP_COMPANY_NAME_ST')
            ),
            '',
            $options
        );

        $this->replacements['{company_name_st_lbl}'] = $htmlCompanyNameLbl;

        // Tweak by Ronni - Change label view for company_name_ST
        $htmlCompanyName = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="far fa-id-badge"></i>
                </label>
            </span>
        </span>' .
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'company_name_ST',
                'name'  => 'company_name_ST',
                'type'  => 'text',
                'value' => !empty($data["company_name_ST"]) ? $data["company_name_ST"] : '',
                'class' => 'inputbox form-control valid',
                'attr'  => 'size="32" maxlength="250" placeholder="' . JText::_('COM_REDSHOP_COMPANY_NAME') . '" 
                            data-msg="' . JText::_(
                    'COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME'
                    ) . '"'
            ),
            '',
            $options
        );

        $this->replacements['{company_name_st}'] = $htmlCompanyName;

        $htmlFirstNameLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'firstname_ST',
                'class' => 'firstname_ST',
                'text'  => JText::_('COM_REDSHOP_FIRSTNAME')
            ),
            '',
            $options
        );

        $this->replacements['{firstname_st_lbl}'] = $htmlFirstNameLbl;

        // Tweak by Ronni - Change label view for firstname
        $htmlFirstName = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="far fa-id-badge"></i>
                </label>
            </span>
        </span>' . 
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'firstname_ST',
                'name'  => 'firstname_ST',
                'type'  => 'text',
                'value' => !empty($data["firstname_ST"]) ? $data["firstname_ST"] : '',
                'class' => 'inputbox form-control billingRequired valid',
                'attr'  => 'size="32" maxlength="250" placeholder="' . JText::_('COM_REDSHOP_FIRSTNAME') . '" 
                            data-msg="' . JText::_(
                        'COM_REDSHOP_PLEASE_ENTER_FIRST_NAME'
                    ) . '"'
            ),
            '',
            $options
        );

        $this->replacements['{firstname_st}'] = $htmlFirstName;

        $htmlLastNameLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'lastname_ST',
                'class' => 'lastname_ST',
                'text'  => JText::_('COM_REDSHOP_LASTNAME')
            ),
            '',
            $options
        );

        $this->replacements['{lastname_st_lbl}'] = $htmlLastNameLbl;

        // Tweak by Ronni - Change label view for lastname
        $htmlLastName = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="far fa-id-badge"></i>
                </label>
            </span>
        </span>' . 
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'lastname_ST',
                'name'  => 'lastname_ST',
                'type'  => 'text',
                'value' => (!empty($data["lastname_ST"])) ? $data["lastname_ST"] : '',
                'class' => 'inputbox form-control billingRequired valid',
                'attr'  => 'size="32" maxlength="250" placeholder="' . JText::_('COM_REDSHOP_LASTNAME') . '" 
                            data-msg="' . JText::_('COM_REDSHOP_PLEASE_ENTER_LAST_NAME') . '"'
            ),
            '',
            $options
        );

        $this->replacements['{lastname_st}'] = $htmlLastName;

        $htmlAddressLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'address_ST',
                'class' => 'address_ST',
                'text'  => JText::_('COM_REDSHOP_ADDRESS')
            ),
            '',
            $options
        );

        $this->replacements['{address_st_lbl}'] = $htmlAddressLbl;

        // Tweak by Ronni - Change label view for address
        $htmlAddress = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="far fa-address-card"></i>
                </label>
            </span>
        </span>' . 
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'address_ST',
                'name'  => 'address_ST',
                'type'  => 'text',
                'value' => (!empty($data["address_ST"])) ? $data["address_ST"] : '',
                'class' => 'inputbox form-control valid ' . $requiredAddress,
                'attr'  => 'size="32" maxlength="250" placeholder="' . JText::_('COM_REDSHOP_ADDRESS') . '" 
                            data-msg="' . JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS') . '"'
            ),
            '',
            $options
        );

        $this->replacements['{address_st}'] = $htmlAddress;

        $htmlZipcodeLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'zipcode_ST',
                'class' => 'zipcode_ST',
                'text'  => JText::_('COM_REDSHOP_ZIP')
            ),
            '',
            $options
        );

        $this->replacements['{zipcode_st_lbl}'] = $htmlZipcodeLbl;

        // Tweak by Ronni - Change label view for zipcode
        $htmlZipcode = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="far fa-address-card"></i>
                </label>
            </span>
        </span>' . 
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'zipcode_ST',
                'name'  => 'zipcode_ST',
                'type'  => 'text',
                'value' => (!empty($data["zipcode_ST"])) ? $data["zipcode_ST"] : '',
                'class' => 'inputbox form-control valid zipcode ' . $requiredPostalCode,
                'attr'  => 'size="32" maxlength="10" placeholder="' . JText::_('COM_REDSHOP_ZIP') . '" 
                            onblur="return autoFillCity(this.value,\'ST\');" data-msg="' . JText::_(
                        'COM_REDSHOP_PLEASE_ENTER_ZIPCODE'
                    ) . '"'
            ),
            '',
            $options
        );

        $this->replacements['{zipcode_st}'] = $htmlZipcode;

        $htmlCityLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'city_ST',
                'class' => 'city_ST',
                'text'  => JText::_('COM_REDSHOP_CITY')
            ),
            '',
            $options
        );

        $this->replacements['{city_st_lbl}'] = $htmlCityLbl;

        // Tweak by Ronni - Change label view for city
        $htmlCity = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="far fa-address-card"></i>
                </label>
            </span>
        </span>' . 
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'city_ST',
                'name'  => 'city_ST',
                'type'  => 'text',
                'value' => (!empty($data["city_ST"])) ? $data["city_ST"] : '',
                'class' => 'inputbox form-control billingRequired valid',
                'attr'  => 'size="32" maxlength="250" placeholder="' . JText::_('COM_REDSHOP_CITY') . '" 
                            data-msg="' . JText::_('COM_REDSHOP_PLEASE_ENTER_CITY') . '"'
            ),
            '',
            $options
        );

        $this->replacements['{city_st}'] = $htmlCity;

        $htmlPhoneLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'phone_ST',
                'class' => 'phone_ST',
                'text'  => JText::_('COM_REDSHOP_PHONE')
            ),
            '',
            $options
        );

        $this->replacements['{phone_st_lbl}'] = $htmlPhoneLbl;

        // Tweak by Ronni - Change label view for phone
        $htmlPhone = '<span class="input-prepend input-append">
            <span class="add-on">
                <label>
                    <i class="fas fa-mobile-alt"></i>
                </label>
            </span>
        </span>' . 
        RedshopLayoutHelper::render(
            'tags.common.input',
            array(
                'id'    => 'phone_ST',
                'name'  => 'phone_ST',
                'type'  => 'text',
                'value' => (!empty($data["phone_ST"])) ? $data["phone_ST"] : '',
                'class' => 'inputbox form-control valid phone ' . $requiredPhone,
                'attr'  => 'size="32" maxlength="250" placeholder="' . JText::_('COM_REDSHOP_PHONE') . '" 
                            onblur="return searchByPhone(this.value,\'ST\');" data-msg="' . JText::_(
                        'COM_REDSHOP_PLEASE_ENTER_PHONE'
                    ) . '"'
            ),
            '',
            $options
        );

        $this->replacements['{phone_st}'] = $htmlPhone;

        $this->replacements['{country_st_txtid}'] = 'div_country_st_txt';
        $this->replacements['{country_st_style}'] = $countryStyle;
        $this->replacements['{state_st_txtid}']   = 'div_state_st_txt';
        $this->replacements['{state_st_style}']   = $stateStyle;
        $this->replacements['{country_st_lbl}']   = JText::_('COM_REDSHOP_COUNTRY');
        // Tweak by Ronni - Change label view for country
        $this->replacements['{country_st}']       = '<span class="input-prepend input-append">
                    <span class="add-on">
                        <label>
                            <i class="fas fa-globe-europe"></i>
                        </label>
                    </span>
                </span>' . $lists['country_code_ST'];
        $this->replacements['{state_st_lbl}']     = JText::_('COM_REDSHOP_STATE');
        $this->replacements['{state_st}']         = $lists['state_code_ST'];

        // Tweak by Ronni START - Add Billing as sender	dropdown	
        $htmlBillSenderLbl = RedshopLayoutHelper::render(
            'tags.common.label',
            array(
                'id'    => 'billing_as_sender_ST',
                'class' => 'billing_as_sender_ST',
                'text'  => JText::_('COM_REDSHOP_BILLING_AS_SENDER')
            ),
            '',
            $options
        );

        $this->replacements['{billing_as_sender_st_lbl}'] = $htmlBillSenderLbl;

        $chk1 = (!empty($data["billing_as_sender_ST"]) && $data["billing_as_sender_ST"] == 1) ? "selected='selected'" : '';
        $chk0 = (!empty($data["billing_as_sender_ST"]) && $data["billing_as_sender_ST"] == 0) ? "selected='selected'" : '';

        $this->replacements['{billing_as_sender_st}']       = '<span class="input-prepend input-append">
                    <span class="add-on"><i class="fas fa-people-carry"></i><label></label></span>
                </span>
                <select id="billing_as_sender_ST" name="billing_as_sender_ST" size="1" 
                        class="inputbox form-control">
                    <option value="0" ' . $chk0 . '>' . JText::_('COM_REDSHOP_BILLING_AS_SENDER_0') . '</option>
                    <option value="1" ' . $chk1 . '>' . JText::_('COM_REDSHOP_BILLING_AS_SENDER_1') . '</option>
                </select>';
        // Tweak by Ronni END - Add Billing as sender	dropdown

        $this->replaceExtraFied($templateHtml, $lists, $allowCompany, $allowCustomer);

        JPluginHelper::importPlugin('redshop_checkout');
        RedshopHelperUtility::getDispatcher()->trigger('onRenderShippingCheckout', array(&$templateHtml));

        $this->template = $this->strReplace($this->replacements, $templateHtml);

        return parent::replace();
    }


    /**
     * Method for replace extra field
     *
     * @param   string  $templateHtml
     * @param   array   $lists
     * @param   string  $allowCompany
     * @param   string  $allowCustomer
     *
     * @return  string
     *
     * @throws  Exception
     * @since  3.0
     *
     */
    public function replaceExtraFied(&$templateHtml, $lists, $allowCompany, $allowCustomer)
    {
        if (strpos($templateHtml, "{extra_field_st_start}") !== false && strpos(
                $templateHtml,
                "{extra_field_st_end}"
            ) !== false) {
            $htmlStart  = explode('{extra_field_st_start}', $templateHtml);
            $htmlEnd    = explode('{extra_field_st_end}', $htmlStart[1]);
            $htmlMiddle = $htmlEnd[0];

            $companyExtraField = (Redshop::getConfig()->get(
                    'ALLOW_CUSTOMER_REGISTER_TYPE'
                ) != 1 && $lists['shipping_company_field'] != "") ?
                $lists['shipping_company_field'] : "";
            $userExtraField    = (Redshop::getConfig()->get(
                    'ALLOW_CUSTOMER_REGISTER_TYPE'
                ) != 2 && $lists['shipping_customer_field'] != "") ?
                $lists['shipping_customer_field'] : "";

            $htmlCompany = str_replace("{extra_field_st}", $companyExtraField, $htmlMiddle);
            $htmlUser    = str_replace("{extra_field_st}", $userExtraField, $htmlMiddle);

            $htmlCompany = '<div id="exCompanyFieldST" ' . $allowCompany . '>' . $htmlCompany . '</div>';
            $htmlUser    = '<div id="exCustomerFieldST" ' . $allowCustomer . '>' . $htmlUser . '</div>';

            $templateHtml = $htmlStart[0] . $htmlCompany . $htmlUser . $htmlEnd[1];
        }
    }
}