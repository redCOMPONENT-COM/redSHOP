<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * registration Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerRegistration extends RedshopController
{
    /**
     * New registration function
     *
     * @access public
     * @return void
     */
    public function newRegistration()
    {
        $input      = JFactory::getApplication()->input;
        $post       = $input->post->getArray();
        $itemId     = $input->getInt('Itemid', 0);
        $dispatcher = RedshopHelperUtility::getDispatcher();

        /** @var RedshopModelRegistration $model */
        $model   = $this->getModel('registration');
        $success = $model->store($post);

        if ($success) {
            $message = JText::sprintf('COM_REDSHOP_ALERT_REGISTRATION_SUCCESSFULLY', $post['username']);
            JPluginHelper::importPlugin('redshop_alert');
            $dispatcher->trigger('storeAlert', array($message));

            if ($post['mywishlist'] == 1) {
                $this->setRedirect(
                    Redshop\IO\Route::_('index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid=' . $itemId, false)
                );
            } else {
                $msg = Redshop::getConfig()->get('WELCOME_MSG');

                if (Redshop::getConfig()->get('SHOP_NAME') != "") {
                    $msg = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $msg);
                }

                // Redirection settings
                $link = Redshop\IO\Route::_('index.php?option=com_redshop&view=redshop&Itemid=' . $itemId);

                $menu        = JFactory::getApplication()->getMenu();
                $retMenuItem = $menu->getItem($menu->getParams($itemId)->get('registrationredirect'));

                if (!empty($retMenuItem)) {
                    $link = Redshop\IO\Route::_($retMenuItem->link . '&Itemid=' . $retMenuItem->id, false);
                }

                // Redirection settings End
                $this->setRedirect($link, $msg);
            }
        } else {
            $this->display();
        }
    }

    /**
     * Method for search user detail by phone
     *
     * @return  void
     */
    public function searchUserdetailByPhone()
    {
        ob_clean();
        $app    = JFactory::getApplication();
        $get    = $app->input->get->getArray();
        $return = "";

        JPluginHelper::importPlugin('telesearch');

        $telephone     = array('phone' => $get['phone']);
        $accountHandle = RedshopHelperUtility::getDispatcher()->trigger('onSearchUserDetails', array($telephone));

        if (count($accountHandle) > 0) {
            $response = $accountHandle[0];

            if (count($response) > 0) {
                $return = implode("`_`", $response);
            }
        }

        echo $return;

        $app->close();
    }

    /**
     * searchUserdetailByCVR
     *
     * @return  void
     */
    public function searchUserdetailByCVR()
    {
        ob_clean();
        $app      = JFactory::getApplication();
        $jInput   = $app->input;
        $vat      = $jInput->getVar('cvr');
        $returnSt = "";

        if (empty($vat)) {
            return false;
        } else {
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, 'https://cvrapi.dk/api?search=' . $vat . '&country=dk');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'CVR Lookup Feature - redShop');

            // Parse result
            $result = curl_exec($ch);

            // Close connection when done
            curl_close($ch);

            // Return our decoded result
            $return = json_decode($result, 1);

            if (count($return) > 0) {
                $name_sp = explode(" ",$return['owners'][0]['name']);

                if (count($name_sp) > 1 && isset($name_sp[1])) {
                    $name_sp1 = $name_sp[1];
                } else {
                    $name_sp1 = '';
                }

                $returnSt = $return['name'] . ":_:" . $return['address'] . ":_:" . $return['zipcode'] . ":_:" 
                    . $return['city'] . ":_:" . $return['phone'] . ":_:" . $return['phone'] . ":_:" 
                    . $name_sp[0] . ":_:" . $name_sp1 . ":_:" . $return['email'] . ":_:" . $return['vat'];
            }
        } 

        echo $returnSt;

        $app->close();
    }

    /**
     * getCompanyOrCustomer
     *
     * @return  void
     * @throws  Exception
     */
    public function getCompanyOrCustomer()
    {
        $app        = JFactory::getApplication();
        $get        = $app->input->get->getArray();
        $templateId = $get['template_id'];
        $isCompany  = $get['is_company'];
        $lists      = array('isAjax' => 1);

        if ($isCompany == 1) {
            $lists['extra_field_company'] = Redshop\Fields\SiteHelper::renderFields(8);
            $template                     = RedshopHelperTemplate::getTemplate("company_billing_template", $templateId);

            if (count($template) > 0 && $template[0]->template_desc != "") {
                $templateHtml = $template[0]->template_desc;
            } else {
                $templateHtml = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{company_name_lbl}</td><td>{company_name}</td><td><span class="required">*</span></td></tr><!-- {vat_number_start} --><tr><td width="100" align="right">{vat_number_lbl}</td><td>{vat_number}</td><td><span class="required">*</span></td></tr><!-- {vat_number_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{ean_number_lbl}</td><td>{ean_number}</td><td></td></tr><tr><td width="100" align="right">{tax_exempt_lbl}</td><td>{tax_exempt}</td></tr><tr><td colspan="3">{company_extrafield}</td></tr></tbody></table>';
            }

            $templateHtml = RedshopTagsReplacer::_(
                'companybillingtemplate',
                $templateHtml,
                array(
                    'data'  => $get,
                    'lists' => $lists
                )
            );
        } else {
            $lists['extra_field_user'] = Redshop\Fields\SiteHelper::renderFields(7);
            $template                  = RedshopHelperTemplate::getTemplate("private_billing_template", $templateId);

            if (count($template) > 0 && $template[0]->template_desc != "") {
                $templateHtml = $template[0]->template_desc;
            } else {
                $templateHtml = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td colspan="3">{private_extrafield}</td></tr></tbody></table>';
            }

            $templateHtml = RedshopTagsReplacer::_(
                'privatebillingtemplate',
                $templateHtml,
                array(
                    'data'  => $get,
                    'lists' => $lists,
                )
            );
        }

        JPluginHelper::importPlugin('redshop_checkout');
        RedshopHelperUtility::getDispatcher()->trigger('onRenderBillingCheckout', array(&$templateHtml));

        echo $return = '<div id="ajaxRegistrationDiv">' . $templateHtml . '</div>';

        $app->close();
    }

    /**
     * Get Billing One Step checkout template
     *
     * @return  void
     * @throws  Exception
     */
    public function getBillingTemplate()
    {
        $app       = JFactory::getApplication();
        $input     = $app->input;
        $isCompany = $input->post->getInt('isCompany');
        $type      = $input->post->getString('type');
        $lists     = array();
        $html      = "";

        if ($isCompany == 1 && $type == 'company') {
            $lists['extra_field_company'] = Redshop\Fields\SiteHelper::renderFields(
                RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS
            );

            $template = RedshopHelperTemplate::getTemplate("company_billing_template");

            if (count($template) > 0 && $template[0]->template_desc != "") {
                $html = $template[0]->template_desc;
            } else {
                $html = RedshopHelperTemplate::getDefaultTemplateContent('company_billing_template');
            }

            $html = RedshopTagsReplacer::_(
                'companybillingtemplate',
                $html,
                array(
                    'data'  => array(),
                    'lists' => $lists
                )
            );
        } elseif ($isCompany == 0 && $type == 'private') {
            $lists['extra_field_user'] = Redshop\Fields\SiteHelper::renderFields(
                RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS
            );

            $template = RedshopHelperTemplate::getTemplate("private_billing_template");

            if (count($template) > 0 && $template[0]->template_desc != "") {
                $html = $template[0]->template_desc;
            } else {
                $html = RedshopHelperTemplate::getDefaultTemplateContent('private_billing_template');
            }

            $html = RedshopTagsReplacer::_(
                'privatebillingtemplate',
                $html,
                array(
                    'data'  => array(),
                    'lists' => $lists
                )
            );
        }

        JPluginHelper::importPlugin('redshop_checkout');
        RedshopHelperUtility::getDispatcher()->trigger('onRenderBillingOneStepCheckout', array(&$html));

        echo $html;

        $app->close();
    }

    public function ajaxValidateNewJoomlaUser()
    {
        $app    = JFactory::getApplication();
        $return = true;

        $username = $app->input->getString('username', '');

        if (!empty($username)) {
            if (!empty(RedshopHelperUser::validateUser($username))) {
                $return = false;
            }
        }

        ob_clean();
        echo json_encode($return);

        $app->close();
    }
}
