<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$template = RedshopHelperTemplate::getTemplate("product_sample");

if (count($template) > 0 && $template[0]->template_desc != "") {
	$templateDesc = $template[0]->template_desc;
} else {
	$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('product_sample');
}

echo RedshopTagsReplacer::_(
	'productsample',
	$templateDesc,
	[
		'params' => $this->params
	]
);

