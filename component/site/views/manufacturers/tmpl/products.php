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

$manufacturer         = $this->detail[0];
$manufacturerTemplate = RedshopHelperTemplate::getTemplate("manufacturer_products", $manufacturer->template_id);

if (count($manufacturerTemplate) > 0 && $manufacturerTemplate[0]->template_desc) {
	$templateDesc = $manufacturerTemplate[0]->template_desc;
	$templateId   = $manufacturerTemplate[0]->id;
} else {
	$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('manufacturer_products');
	$templateId   = 0;
}


/** @var \RedshopModelManufacturers $model */
$model = $this->getModel('manufacturers');

echo RedshopTagsReplacer::_(
	'manufacturerproduct',
	$templateDesc,
	[
		'params'               => $this->params,
		'manufacturerProducts' => $model->getManufacturerProducts($templateDesc),
		'manufacturer'         => $manufacturer,
		'pagination'           => $model->getProductPagination(),
		'lists'                => $this->lists
	]
);
