<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$redTemplate     = Redtemplate::getInstance();
$model           = $this->getModel('checkout');
$paymentTemplate = \RedshopHelperTemplate::getTemplate("redshop_payment");

if (isset($paymentTemplate[0]->template_desc)) {
    $templateDesc = $paymentTemplate[0]->template_desc;
} else {
    $templateDesc = \RedshopHelperTemplate::getDefaultTemplateContent('payment_method');
}

// Get billing info for check is_company
$billingAddresses = $model->billingaddresses();
$isCompany        = $billingAddresses->is_company;

$eanNumber = (int)$billingAddresses->ean_number;

$templateDesc = \RedshopTagsReplacer::_(
    'paymentmethod',
    $templateDesc,
    array(
        'paymentMethodId' => $this->element,
        'isCompany'       => $isCompany,
        'eanNumber'       => $eanNumber
    )
);

$templateDesc = \RedshopHelperTemplate::parseRedshopPlugin($templateDesc);
echo eval("?>" . $templateDesc . "<?php ");
