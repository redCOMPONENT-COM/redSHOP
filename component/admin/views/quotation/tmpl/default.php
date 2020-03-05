<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$quotationTemplate = RedshopHelperTemplate::getTemplate("quotation_request");

if (count($quotationTemplate) > 0 && $quotationTemplate[0]->template_desc != "") {
    $templateDesc = $quotationTemplate[0]->template_desc;
} else {
    $templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('quotation_request');
}

echo RedshopTagsReplacer::_(
    'quotationrequest',
    $templateDesc,
    [
        'detail'     => $this->detail,
        'requestUrl' => $this->request_url
    ]
);