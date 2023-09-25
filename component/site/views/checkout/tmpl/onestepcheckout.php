<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2023 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::script('com_redshop/redshop.creditcard.min.js', ['relative' => true]);
HTMLHelper::script('com_redshop/redshop.onestep.min.js', ['relative' => true]);
/** @var RedshopModelCheckout $model */
$model = $this->getModel('checkout');

$oneStepTemplate = RedshopHelperTemplate::getTemplate("onestep_checkout");

if (count($oneStepTemplate) > 0 && $oneStepTemplate[0]->template_desc) {
    $oneStepTemplateHtml = $oneStepTemplate[0]->template_desc;
} else {
    $oneStepTemplateHtml = Text::_("COM_REDSHOP_TEMPLATE_NOT_EXISTS");
}

echo RedshopTagsReplacer::_(
    'onestepcheckout',
    $oneStepTemplateHtml,
    array(
        'usersInfoId'       => $this->users_info_id,
        'shippingAddresses' => $model->shippingaddresses(),
        'billingAddress'    => $model->billingaddresses()
    )
);