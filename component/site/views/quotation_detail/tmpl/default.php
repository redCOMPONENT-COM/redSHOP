<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$template = RedshopHelperTemplate::getTemplate("quotation_detail");

if (isset($template[0]->template_desc) && !empty(trim($template[0]->template_desc))) {
    $quotationTemplate = $template[0]->template_desc;
} else {
    $quotationTemplate = RedshopHelperTemplate::getDefaultTemplateContent('quotation_detail');
}

echo RedshopTagsReplacer::_(
    'quotationdetail',
    $quotationTemplate,
    []
);

