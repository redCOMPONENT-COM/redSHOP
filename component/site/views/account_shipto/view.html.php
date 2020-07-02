<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Registry;

defined('_JEXEC') or die;

/**
 * Account Shipping To view
 *
 * @package     RedSHOP.Frontend
 * @subpackage  View
 * @since       1.6.0
 */
class RedshopViewAccount_Shipto extends RedshopView
{
    /**
     * @var  array
     */
    public $shippingAddresses;

    /**
     * @var  array
     */
    public $lists;

    /**
     * @var  object
     */
    public $billingAddresses;

    /**
     * @var  Registry
     */
    public $params;

    /**
     * @var  string
     */
    public $request_url;

    /**
     * Execute and display a template script.
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  mixed         A string if successful, otherwise an Error object.
     * @throws  Exception
     *
     * @see     JViewLegacy::loadTemplate()
     * @since   12.2
     */
    public function display($tpl = null)
    {
        /** @var JApplicationSite $app */
        $app            = JFactory::getApplication();
        $input          = $app->input;
        $task           = $input->getCmd('task');
        $user           = JFactory::getUser();
        $session        = JFactory::getSession();
        $auth           = $session->get('auth');
        $lists          = [];
        $layout         = 'default';
        $itemId         = $input->getInt('Itemid', 0);
        $isEdit         = $input->getInt('is_edit', 0);
        $return         = $input->getString('return', "");
        $infoId         = $input->getInt('infoid', 0);
        $billingAddress = new stdClass;
        $twigParams     = [];

        if ($user->id) {
            $billingAddress = RedshopHelperOrder::getBillingAddress($user->id);
        } elseif (isset($auth['users_info_id']) && $auth['users_info_id']) {
            $model          = $this->getModel('account_shipto');
            $billingAddress = $model->_loadData($auth['users_info_id']);
        } else {
            $app->redirect(
                JRoute::_('index.php?option=com_redshop&view=login&Itemid=' . $itemId)
            );
            $app->close();
        }

        if ($task == 'addshipping') {
            JHtml::_('redshopjquery.framework');
            JHtml::_('script', 'com_redshop/jquery.validate.min.js', false, true);
            JHtml::_('script', 'com_redshop/redshop.common.min.js', false, true);
            JHtml::_('script', 'com_redshop/redshop.registration.min.js', false, true);
            JHtml::_('stylesheet', 'com_redshop/redshop.validation.min.css', array(), true);

            $shippingAddresses = $this->get('Data');

            if ($shippingAddresses->users_info_id > 0 && $shippingAddresses->user_id != $billingAddress->user_id) {
                echo JText::_('COM_REDSHOP_ALERTNOTAUTH');

                return;
            }

            $lists['shipping_customer_field'] = Redshop\Fields\SiteHelper::renderFields(
                RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS,
                $shippingAddresses->users_info_id
            );
            $lists['shipping_company_field']  = Redshop\Fields\SiteHelper::renderFields(
                RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS,
                $shippingAddresses->users_info_id
            );

            $post['firstname_ST']    = $shippingAddresses->firstname;
            $post['lastname_ST']     = $shippingAddresses->lastname;
            $post['address_ST']      = $shippingAddresses->address;
            $post['city_ST']         = $shippingAddresses->city;
            $post['zipcode_ST']      = $shippingAddresses->zipcode;
            $post['phone_ST']        = $shippingAddresses->phone;
            $post['country_code_ST'] = $shippingAddresses->country_code;
            $post['state_code_ST']   = $shippingAddresses->state_code;
            $layout                  = 'form';

            $dispatcher = RedshopHelperUtility::getDispatcher();
            JPluginHelper::importPlugin('redshop_shipping');
            $dispatcher->trigger('onRenderCustomField', array($infoId));

            $shippingTable = RedshopTagsReplacer::_(
                'shippingtable',
                '',
                array(
                    'data'      => $post,
                    'isCompany' => $billingAddress->is_company,
                    'lists'     => $lists
                )
            );

            $twigParams = array_merge(
                $twigParams,
                [
                    'post'          => $post,
                    'shippingTable' => $shippingTable
                ]
            );
        } elseif ($user->id) {
            $shippingAddresses = RedshopHelperOrder::getShippingAddress($user->id);
        } else {
            $shippingAddresses = RedshopHelperOrder::getShippingAddress(-$auth['users_info_id']);
        }

        JHtml::_('script', 'system/core.js', false, true);
        JHtml::_('script', 'com_redshop/account/shipto.min.js', false, true);
        $optionShipTo = [
            'isEdit' => $isEdit,
            'link'   => JRoute::_("index.php?option=com_redshop&view=" . $return . "&Itemid" . $itemId)
        ];


        JFactory::getDocument()->addScriptOptions('optionShipTo', $optionShipTo);


        $requestUrl = JUri::getInstance()->toString();
        JFilterOutput::cleanText($requestUrl);

        $twigParams = array_merge(
            $twigParams,
            [
                'lists'             => $lists,
                'shippingAddresses' => $shippingAddresses,
                'billingAddresses'  => $billingAddress,
                'requestUrl'        => $requestUrl,
                'params'            => $app->getParams(),
                'itemId'            => $itemId,
                'isEdit'            => $isEdit,
                'return'            => $return,
                'infoId'            => $infoId
            ]
        );

        print \RedshopLayoutHelper::render(
            $layout,
            $twigParams,
            '',
            array(
                'component'  => 'com_redshop',
                'layoutType' => 'Twig',
                'layoutOf'   => 'component',
                'prefix'     => 'com_redshop/account_shipto'
            )
        );
    }

}
