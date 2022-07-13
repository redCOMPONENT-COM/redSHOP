<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Account Billing To view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewAccount_Billto extends RedshopView
{
    /**
     * Execute and display a template script.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed         A string if successful, otherwise a JError object.
     * @throws  Exception
     */
    public function display($tpl = null)
    {
        /** @var JApplicationSite $app */
        $app    = JFactory::getApplication();
        $params = $app->getParams('com_redshop');
        $input  = JFactory::getApplication()->input;
        $user   = JFactory::getUser();
        $itemId = $input->getInt('Itemid', 0);
        $isEdit = $input->getInt('is_edit', 0);
        $return = $input->getString('return', "");

        $billingAddresses = Redshop\User\Billing\Billing::getGlobal();

        if (empty($billingAddresses) || $billingAddresses == new stdClass) {
            /** @var RedshopModelAccount_Billto $model */
            $model = $this->getModel('account_billto');

            $billingAddresses = $model->_initData();
        }

        $uri     = JUri::getInstance();
        $session = JFactory::getSession();
        $auth    = $session->get('auth');

        if (!is_array($auth)) {
            $auth['users_info_id'] = 0;
            $session->set('auth', $auth);
            $auth = $session->get('auth');
        }

        $link = Redshop\IO\Route::_('index.php?option=com_redshop&view=' . $return . '&Itemid=' . $itemId, false);

        $accountBilltoJs = ['isEdit' => $isEdit, 'link' => $link];
        $document        = JFactory::getDocument();
        $document->addScriptOptions('account_billto', $accountBilltoJs);

        JText::script('COM_REDSHOP_ADDRESS_MIN_CHARACTER_LIMIT');
        JText::script('COM_REDSHOP_ZIPCODE_MIN_CHARACTER_LIMIT');
        JText::script('COM_REDSHOP_CITY_MIN_CHARACTER_LIMIT');
        JHtml::_('behavior.framework');
        JHtml::_('redshopjquery.framework');
        JHtml::_('script', 'com_redshop/jquery.validate.min.js', false, true);
        JHtml::_('script', 'com_redshop/redshop.common.min.js', false, true);
        JHtml::_('script', 'com_redshop/redshop.registration.min.js', false, true);
        JHtml::_('script', 'com_redshop/account/billto.min.js', false, true);
        /** @scrutinizer ignore-deprecated */
        JHtml::stylesheet('com_redshop/redshop.validation.min.css', array(), true);

        // Preform security checks
        if ($user->id == 0 && $auth['users_info_id'] == 0) {
            $app->redirect(Redshop\IO\Route::_('index.php?option=com_redshop&view=login&Itemid=' . JRequest::getInt('Itemid')));
            $app->close();
        }

        $lists = array(
            'requesting_tax_exempt' => JHtml::_(
                'select.booleanlist',
                'requesting_tax_exempt',
                'class="inputbox"',
                $billingAddresses->requesting_tax_exempt
            )
        );

        if ($billingAddresses->is_company) {
            $lists['extra_field_company'] = Redshop\Fields\SiteHelper::renderFields(
                RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS,
                $billingAddresses->users_info_id
            );
        } else {
            $lists['extra_field_user'] = Redshop\Fields\SiteHelper::renderFields(
                RedshopHelperExtrafields::SECTION_PRIVATE_BILLING_ADDRESS,
                $billingAddresses->users_info_id
            );
        }

        $requestUrl = $uri->toString();
        JFilterOutput::cleanText($requestUrl);
        JPluginHelper::importPlugin('redshop_shipping');
        $dispatcher = RedshopHelperUtility::getDispatcher();
        $dispatcher->trigger('onRenderCustomField', array($billingAddresses));
        $post = (array)$billingAddresses;

        $post["email1"] = $post["email"] = $post["user_email"];

        if ($user->username) {
            $post["username"] = $user->username;
        }

        $createAccount = 1;

        if ($post["user_id"] < 0) {
            $createAccount = 0;
        }

        $twigParams = [
            'billingAddresses' => $billingAddresses,
            'params'           => $params,
            'requestUrl'       => $requestUrl,
            'createAccount'    => $createAccount,
            'isEdit'           => $isEdit,
            'post'             => $post,
            'itemId'           => $itemId,
            'dispatcher'       => $dispatcher,
            'lists'            => $lists
        ];

        print \RedshopLayoutHelper::render(
            'default',
            $twigParams,
            '',
            array(
                'component'  => 'com_redshop',
                'layoutType' => 'Twig',
                'layoutOf'   => 'component',
                'prefix'     => 'com_redshop/account_billto'
            )
        );
    }
}
