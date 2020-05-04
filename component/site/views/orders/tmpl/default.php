<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$ordersListTemplate = RedshopHelperTemplate::getTemplate("order_list", $this->params->get('template_id'));

if (isset($ordersListTemplate[0]->template_desc) && !empty(trim($ordersListTemplate[0]->template_desc))) {
    $templateDesc = $ordersListTemplate[0]->template_desc;
} else {
    $templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('order_list');
}

echo RedshopTagsReplacer::_(
    'orderlist',
    $templateDesc,
    [
        'pagination' => $this->pagination,
        'detail'     => $this->detail,
        'params'     => $this->params
    ]
);

