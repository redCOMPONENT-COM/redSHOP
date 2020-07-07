<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('behavior.modal');

$manufacturerTemplate = RedshopHelperTemplate::getTemplate('manufacturer_detail');

if (count($manufacturerTemplate) > 0 && $manufacturerTemplate[0]->template_desc != "") {
    $templateHtml = $manufacturerTemplate[0]->template_desc;
    $templateId   = $manufacturerTemplate[0]->id;
} else {
    $templateHtml = RedshopHelperTemplate::getDefaultTemplateContent('manufacturer_detail');
    $templateId   = 0;
}

echo RedshopTagsReplacer::_(
    'manufacturerdetail',
    $templateHtml,
    [
        'params'         => $this->params,
        'pageHeadingTag' => $this->pageheadingtag,
        'detail'         => $this->detail
    ]
);
