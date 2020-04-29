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
class RedshopTagsSectionsShippingAddress extends RedshopTagsAbstract
{
    public $tags = array('{shipping_address}', '{shipping_address_information_lbl}');

    public function init()
    {
    }

    public function replace()
    {
        /**
         * Init $lists to avoid Scrutinizer check.
         */
        $lists = [];

        if ($this->isTagExists('{shipping_address}')) {
            if (Redshop::getConfig()->getBool('SHIPPING_METHOD_ENABLE')) {
                $shippingHtml = '';
                $usersInfoId  = $this->data['usersInfoId'];
                $itemId       = RedshopHelperRouter::getCheckoutItemId();

                if ($usersInfoId) {
                    $shippingAddresses = $this->data['shippingAddresses'];
                    $billingAddresses  = $this->data['billingAddresses'];

                    $shippingHtml = RedshopLayoutHelper::render(
                        'tags.shipping_address.userinfo',
                        array(
                            'shippingAddresses' => $shippingAddresses,
                            'billingAddresses'  => $billingAddresses,
                            'itemId'            => $itemId,
                            'usersInfoId'       => $usersInfoId
                        ),
                        '',
                        RedshopLayoutHelper::$layoutOption
                    );
                } else {
                    $lists['shipping_customer_field'] = Redshop\Fields\SiteHelper::renderFields(
                        RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS
                    );
                    $lists['shipping_company_field']  = Redshop\Fields\SiteHelper::renderFields(
                        RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS
                    );

                    $shippingTable = RedshopTagsReplacer::_(
                        'shippingtable',
                        '',
                        array(
                            'data' => array(),
                            'isCompany' => $this->data['isCompany'],
                            'lists' => $lists
                        )
                    );

                    $shippingHtml = RedshopLayoutHelper::render(
                        'tags.shipping_address.without_userinfo',
                        array(
                            'shippingTable' => $shippingTable
                        ),
                        '',
                        RedshopLayoutHelper::$layoutOption
                    );
                }

                $this->addReplace('{shipping_address}', $shippingHtml);
                $this->addReplace(
                    '{shipping_address_information_lbl}',
                    JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFO_LBL')
                );
            } else {
                $this->addReplace('{shipping_address}', '');
                $this->addReplace('{shipping_address_information_lbl}', '');
            }
        }

        return parent::replace();
    }
}