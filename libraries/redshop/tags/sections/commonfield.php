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
class RedshopTagsSectionsCommonField extends RedshopTagsAbstract
{
    public $tags = array(
        '{email}',
        '{email_lbl}',
        '{firstname_lbl}',
        '{firstname}',
        '{lastname_lbl}',
        '{lastname}',
        '{address_lbl}',
        '{address}',
        '{zipcode_lbl}',
        '{zipcode}',
        '{city_lbl}',
        '{city}',
        '{country_lbl}',
        '{country}',
        '{state_lbl}',
        '{state}',
        '{phone_lbl}',
        '{phone}',
        '{phone_optional}',
        '{private_extrafield}',
        '{retype_email_lbl}',
        '{retype_email}',
        '{company_name_lbl}',
        '{company_name}',
        '{country_style}',
        '{country_txtid}'
    );

    public function init()
    {
    }

    /**
     * Execute replace
     *
     * @return  string
     *
     * @since   2.0.0.5
     */
    public function replace()
    {
        $prefix = $this->data['prefix'];
        $data   = $this->data['data'];
        $data   = is_null($data) || !is_array($data) ? array() : $data;

        $countries             = RedshopHelperWorld::getCountryList($data);
        $data['country_code']  = $countries['country_code'];
        $lists['country_code'] = $countries['country_dropdown'];
        $states                = RedshopHelperWorld::getStateList($data);
        $lists['state_code']   = $states['state_dropdown'];
        $countryStyle          = count($countries['countrylist']) == 1 && count(
            $states['statelist']
        ) == 0 ? 'display:none;' : '';
        $stateStyle            = ($states['is_states'] <= 0) ? 'display:none;' : '';
        $options               = RedshopLayoutHelper::$layoutOption;
        $this->template        = $this->replaceRetypeEmail($prefix, $options, $data);

        if ($this->isTagExists('{email}')) {
            $htmlEmail = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'email1',
                    'name'  => 'email1',
                    'value' => (isset($data["email1"]) ? $data["email1"] : ''),
                    'type'  => 'text',
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_(
                            'COM_REDSHOP_PROVIDE_CORRECT_EMAIL_ADDRESS'
                        ) . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{email}', $htmlEmail);
        }

        if ($this->isTagExists('{email_lbl}')) {
            $htmlEmailLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'email1',
                    'class' => 'email1',
                    'text'  => JText::_('COM_REDSHOP_EMAIL')
                ),
                '',
                $options
            );

            $this->addReplace('{email_lbl}', $htmlEmailLbl);
        }

        if ($this->isTagExists('{firstname_lbl}')) {
            $htmlFirstNameLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'firstname',
                    'class' => 'firstname',
                    'text'  => JText::_('COM_REDSHOP_FIRSTNAME')
                ),
                '',
                $options
            );

            $this->addReplace('{firstname_lbl}', $htmlFirstNameLbl);
        }

        if ($this->isTagExists('{firstname}')) {
            $htmlFirstName = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'firstname',
                    'name'  => 'firstname',
                    'type'  => 'text',
                    'value' => (isset($data["firstname"]) ? $data["firstname"] : ''),
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_(
                            'COM_REDSHOP_PLEASE_ENTER_FIRST_NAME'
                        ) . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{firstname}', $htmlFirstName);
        }

        if ($this->isTagExists('{lastname_lbl}')) {
            $htmlLastNameLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'lastname',
                    'class' => 'lastname',
                    'text'  => JText::_('COM_REDSHOP_LASTNAME')
                ),
                '',
                $options
            );

            $this->addReplace('{lastname_lbl}', $htmlLastNameLbl);
        }

        if ($this->isTagExists('{lastname}')) {
            $htmlLastName = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'lastname',
                    'name'  => 'lastname',
                    'type'  => 'text',
                    'value' => (isset($data["lastname"]) ? $data["lastname"] : ''),
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_(
                            'COM_REDSHOP_PLEASE_ENTER_LAST_NAME'
                        ) . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{lastname}', $htmlLastName);
        }


        if ($this->isTagExists('{address_lbl}')) {
            $htmlAddressLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'address',
                    'class' => 'address',
                    'text'  => JText::_('COM_REDSHOP_ADDRESS')
                ),
                '',
                $options
            );

            $this->addReplace('{address_lbl}', $htmlAddressLbl);
        }

        if ($this->isTagExists('{address}')) {
            $htmlAddress = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'address',
                    'name'  => 'address',
                    'type'  => 'text',
                    'value' => (isset($data["address"]) ? $data["address"] : ''),
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_('COM_REDSHOP_PLEASE_ENTER_ADDRESS') . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{address}', $htmlAddress);
        }

        if ($this->isTagExists('{zipcode_lbl}')) {
            $htmlZipcodeLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'zipcode',
                    'class' => 'zipcode',
                    'text'  => JText::_('COM_REDSHOP_ZIP')
                ),
                '',
                $options
            );

            $this->addReplace('{zipcode_lbl}', $htmlZipcodeLbl);
        }

        if ($this->isTagExists('{zipcode}')) {
            $htmlZipcode = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'zipcode',
                    'name'  => 'zipcode',
                    'type'  => 'text',
                    'value' => (isset($data["zipcode"]) ? $data["zipcode"] : ''),
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" onblur="return autoFillCity(this.value,\'BT\');" title="' . JText::_(
                            'COM_REDSHOP_PLEASE_ENTER_ZIPCODE'
                        ) . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{zipcode}', $htmlZipcode);
        }

        if ($this->isTagExists('{city_lbl}')) {
            $htmlCityLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'city',
                    'class' => 'city',
                    'text'  => JText::_('COM_REDSHOP_CITY')
                ),
                '',
                $options
            );

            $this->addReplace('{city_lbl}', $htmlCityLbl);
        }

        if ($this->isTagExists('{city}')) {
            $htmlCity = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'city',
                    'name'  => 'city',
                    'type'  => 'text',
                    'value' => (isset($data["city"]) ? $data["city"] : ''),
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_('COM_REDSHOP_PLEASE_ENTER_CITY') . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{city}', $htmlCity);
        }

        // Allow phone number to be optional using template tags.
        $phoneIsRequired = $this->isTagExists('{phone_optional}') ? 'required' : '';
        $this->addReplace('{phone_optional}', '');

        if ($this->isTagExists('{phone_lbl}')) {
            $htmlPhoneLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'phone',
                    'class' => 'phone',
                    'text'  => JText::_('COM_REDSHOP_PHONE')
                ),
                '',
                $options
            );

            $this->addReplace('{phone_lbl}', $htmlPhoneLbl);
        }

        if ($this->isTagExists('{phone}')) {
            $htmlPhone = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'phone',
                    'name'  => 'phone',
                    'type'  => 'text',
                    'value' => (isset($data["phone"]) ? $data["phone"] : ''),
                    'class' => 'inputbox phone required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_(
                            'COM_REDSHOP_PLEASE_ENTER_PHONE'
                        ) . '" onblur="return searchByPhone(this.value,\'BT\');" ' . $phoneIsRequired
                ),
                '',
                $options
            );

            $this->addReplace('{phone}', $htmlPhone);
        }

        if ($this->isTagExists('{company_name_lbl}')) {
            $htmlCompanyNameLbl = RedshopLayoutHelper::render(
                'tags.common.label',
                array(
                    'id'    => $prefix . 'company_name',
                    'class' => 'company_name',
                    'text'  => JText::_('COM_REDSHOP_COMPANY_NAME')
                ),
                '',
                $options
            );

            $this->addReplace('{company_name_lbl}', $htmlCompanyNameLbl);
        }

        if ($this->isTagExists('{company_name}')) {
            $htmlCompanyName = RedshopLayoutHelper::render(
                'tags.common.input',
                array(
                    'id'    => $prefix . 'company_name',
                    'name'  => 'company_name',
                    'type'  => 'text',
                    'value' => (isset($data["company_name"]) ? $data["company_name"] : ''),
                    'class' => 'inputbox required',
                    'attr'  => 'size="32" maxlength="250" title="' . JText::_(
                            'COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME'
                        ) . '"'
                ),
                '',
                $options
            );

            $this->addReplace('{company_name}', $htmlCompanyName);
        }

        $this->addReplace('{country_txtid}', 'div_country_txt');
        $this->addReplace('{country_style}', $countryStyle);
        $this->addReplace('{state_txtid}', 'div_state_txt');
        $this->addReplace('{state_style}', $stateStyle);
        $this->addReplace('{country_lbl}', JText::_('COM_REDSHOP_COUNTRY'));
        $this->addReplace('{country}', $lists['country_code']);
        $this->addReplace('{state_lbl}', JText::_('COM_REDSHOP_STATE'));
        $this->addReplace('{state}', $lists['state_code']);

        return parent::replace();
    }

    /**
     * Replace Retype Email
     *
     * @param   string  $prefix
     *
     * @return  string
     *
     * @since   3.0
     */
    public function replaceRetypeEmail($prefix, $options, $data)
    {
        $subTemplate = $this->getTemplateBetweenLoop('{retype_email_start}', '{retype_email_end}');

        $htmlContent = '';

        if (!empty($subTemplate)) {
            if (Redshop::getConfig()->get('SHOW_EMAIL_VERIFICATION')) {
                $htmlContent = $subTemplate['template'];

                $htmlEmailLbl = RedshopLayoutHelper::render(
                    'tags.common.label',
                    array(
                        'id'    => $prefix . 'email2',
                        'class' => 'email2',
                        'text'  => JText::_('COM_REDSHOP_RETYPE_CUSTOMER_EMAIL')
                    ),
                    '',
                    $options
                );

                $htmlEmail = RedshopLayoutHelper::render(
                    'tags.common.input',
                    array(
                        'id'    => $prefix . 'email2',
                        'name'  => 'email2',
                        'type'  => 'text',
                        'value' => $data['email1'],
                        'class' => 'inputbox required',
                        'attr'  => 'size="32" maxlength="250" title="' . JText::_(
                                'COM_REDSHOP_PROVIDE_CORRECT_EMAIL_ADDRESS'
                            ) . '" required'
                    ),
                    '',
                    $options
                );

                $this->replacements['{retype_email_lbl}'] = $htmlEmailLbl;
                $this->replacements['{retype_email}']     = $htmlEmail;

                $htmlContent = $this->strReplace($this->replacements, $htmlContent);
            }

            return $subTemplate['begin'] . $htmlContent . $subTemplate['end'];
        }

        return $this->template;
    }
}
