<?php
/**
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Billing Helper
 *
 * @package     Redshop.Libraries
 * @subpackage  Helpers
 * @since       2.0.7
 */
class RedshopHelperBilling
{
    /**
     * Method for render billing layout
     *
     * @param   array    $post            Available data.
     * @param   integer  $isCompany       Is company?
     * @param   array    $lists           Lists
     * @param   integer  $showShipping    Show shipping?
     * @param   integer  $showNewsletter  Show newsletter?
     * @param   integer  $createAccount   Is create account?
     *
     * @return  string                    HTML content layout.
     *
     * @throws  Exception
     * @since   2.1.0
     *
     */
    public static function render(
        $post = array(),
        $isCompany = 0,
        $lists,
        $showShipping = 0,
        $showNewsletter = 0,
        $createAccount = 1
    ) {
        $billingTemplate = RedshopHelperTemplate::getTemplate("billing_template");

        if (!empty($billingTemplate) && !empty($billingTemplate[0]->template_desc)
            && strpos($billingTemplate[0]->template_desc, "private_billing_template:") !== false
            && strpos($billingTemplate[0]->template_desc, "company_billing_template:") !== false) {
            $templateHtml = $billingTemplate[0]->template_desc;
        } else {
            $templateHtml = RedshopHelperTemplate::getDefaultTemplateContent('billing_template');
        }

        $templateHtml = RedshopTagsReplacer::_(
            'billingtemplate',
            $templateHtml,
            array(
                'isCompany'      => $isCompany,
                'data'           => $post,
                'lists'          => $lists,
                'showShipping'   => $showShipping,
                'createAccount'  => $createAccount,
                'showNewsletter' => $showNewsletter
            )
        );

        JPluginHelper::importPlugin('redshop_checkout');
        RedshopHelperUtility::getDispatcher()->trigger('onRenderBillingCheckout', array(&$templateHtml));

        return $templateHtml;
    }
}
