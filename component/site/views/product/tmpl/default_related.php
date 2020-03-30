<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$template         = $this->input->getString('template', '');
$relPTemplate     = RedshopHelperTemplate::getTemplate("related_product", 0, $template);

if (isset($relPTemplate[0]->template_desc) && !empty(trim($relPTemplate[0]->template_desc))) {
	$relatedTemplate = $relPTemplate[0]->template_desc;
} else {
	$relatedTemplate = RedshopHelperTemplate::getDefaultTemplateContent('related_product');
}

echo RedshopTagsReplacer::_(
	'relatedproduct',
	$relatedTemplate,
	[
		'product' => $this->data
	]
);
