<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'mail.php');
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'extra_field.php');

/**
 * registrationController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class registrationController extends RedshopCoreController
{
    /**
     * newregistration function
     *
     * @access public
     * @return void
     */
    public function newregistration()
    {
        $item_id = $this->input->get('Itemid');
        $post    = $this->input->getArray($_POST);
        $model   = $this->getModel('registration');
        $success = $model->store($post);

        if ($success)
        {
            if ($post[mywishlist] == 1)
            {
                $wishreturn = JRoute::_('index.php?loginwishlist=1&option=com_redshop&view=wishlist&Itemid=' . $item_id, false);
                $this->setRedirect($wishreturn);
            }
            else
            {
                $msg = WELCOME_MSG;

                if (SHOP_NAME != "")
                {
                    $msg = str_replace("{shopname}", SHOP_NAME, $msg);
                }

                # redirection settings
                $link = JRoute::_('index.php?option=com_redshop&view=redshop&Itemid=' . $item_id);

                $menu        = JSite::getMenu();
                $retMenuItem = $menu->getItem($menu->getParams($item_id)->get('registrationredirect'));

                if (count($retMenuItem) > 0)
                {
                    $link = JRoute::_($retMenuItem->link . '&Itemid=' . $retMenuItem->id);
                }
                # redirection settings End

                $this->setRedirect($link, $msg);
            }
        }
        else
        {
            parent::display();
        }
    }

    public function captcha()
    {
        require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'captcha.php');

        $width       = $this->input->getInt('width', 120);
        $height      = $this->input->getInt('height', 40);
        $characters  = $this->input->getInt('characters', 6);
        $captchaname = $this->input->getCmd('captcha', 'security_code');

        $captcha = new CaptchaSecurityImages($width, $height, $characters, $captchaname);

        return $captcha;
    }

    public function searchUserdetailByPhone()
    {
        ob_clean();
        $get    = $this->input->getArray($_GET);
        $return = "";

        JPluginHelper::importPlugin('telesearch', 'rs_telesearch');
        $this->_dispatcher = JDispatcher::getInstance();
        $tele['phone']     = $get['phone'];
        $accountHandle     = $this->_dispatcher->trigger('findByTelephoneNumber', array($tele));
        if (count($accountHandle) > 0)
        {
            $response = $accountHandle[0];
            if (count($response) > 0)
            {
                $return = implode("`_`", $response);
            }
        }
        echo $return;
        die();
    }

    public function getCompanyOrCustomer()
    {
        $redTemplate                      = new Redtemplate();
        $rsUserhelper                     = new rsUserhelper();
        $extraField                       = new extraField();
        $get                              = $this->input->getArray($_GET);
        $template_id                      = $get['template_id'];
        $is_company                       = $get['is_company'];
        $lists['extra_field_user']        = $extraField->list_all_field(7); // field_section 6 : Customer Registration
        $lists['extra_field_company']     = $extraField->list_all_field(8); // field_section 6 : Company Address
        $lists['shipping_customer_field'] = $extraField->list_all_field(14, 0, 'billingRequired valid');
        $lists['shipping_company_field']  = $extraField->list_all_field(15, 0, 'billingRequired valid');
        $lists['isAjax']                  = 1;
        if ($is_company == 1)
        {
            $template = $redTemplate->getTemplate("company_billing_template", $template_id);
            if (count($template) > 0 && $template[0]->template_desc != "")
            {
                $template_desc = $template[0]->template_desc;
            }
            else
            {
                $template_desc = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{company_name_lbl}</td><td>{company_name}</td><td><span class="required">*</span></td></tr><!-- {vat_number_start} --><tr><td width="100" align="right">{vat_number_lbl}</td><td>{vat_number}</td><td><span class="required">*</span></td></tr><!-- {vat_number_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{ean_number_lbl}</td><td>{ean_number}</td><td></td></tr><tr><td width="100" align="right">{tax_exempt_lbl}</td><td>{tax_exempt}</td></tr><tr><td colspan="3">{company_extrafield}</td></tr></tbody></table>';
            }
            $template_desc = $rsUserhelper->replaceCompanyCustomer($template_desc, $get, $lists);
        }
        else
        {
            $template = $redTemplate->getTemplate("private_billing_template", $template_id);
            if (count($template) > 0 && $template[0]->template_desc != "")
            {
                $template_desc = $template[0]->template_desc;
            }
            else
            {
                $template_desc = '<table class="admintable" style="height: 221px;" border="0" width="183"><tbody><tr><td width="100" align="right">{email_lbl}:</td><td>{email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_start} --><tr><td width="100" align="right">{retype_email_lbl}</td><td>{retype_email}</td><td><span class="required">*</span></td></tr><!-- {retype_email_end} --><tr><td width="100" align="right">{firstname_lbl}</td><td>{firstname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{lastname_lbl}</td><td>{lastname}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{address_lbl}</td><td>{address}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{zipcode_lbl}</td><td>{zipcode}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{city_lbl}</td><td>{city}</td><td><span class="required">*</span></td></tr><tr id="{country_txtid}" style="{country_style}"><td width="100" align="right">{country_lbl}</td><td>{country}</td><td><span class="required">*</span></td></tr><tr id="{state_txtid}" style="{state_style}"><td width="100" align="right">{state_lbl}</td><td>{state}</td><td><span class="required">*</span></td></tr><tr><td width="100" align="right">{phone_lbl}</td><td>{phone}</td><td><span class="required">*</span></td></tr><tr><td colspan="3">{private_extrafield}</td></tr></tbody></table>';
            }
            $template_desc = $rsUserhelper->replacePrivateCustomer($template_desc, $get, $lists);
        }
        echo $return = "<div id='ajaxRegistrationDiv'>" . $template_desc . "</div>";
        die();
    }
}
