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

// Define array to store product detail for ajax cart display
$cartData = $this->data[0]->template_desc;

if ($cartData == "") {
    if (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE')) {
        $cartData = RedshopHelperTemplate::getDefaultTemplateContent('quotation_cart');
    } else {
        $cartData = RedshopHelperTemplate::getDefaultTemplateContent('cart');
    }
}

echo RedshopTagsReplacer::_(
    'cart',
    $cartData,
    array(
        'cart' => $this->cart
    )
);

