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

// Get product helper
$config          = Redconfiguration::getInstance();
$stockroomHelper = rsstockroomhelper::getInstance();

$compare           = new RedshopProductCompare;
$compareCategoryId = $compare->getCategoryId();

if (Redshop::getConfig()->get('PRODUCT_COMPARISON_TYPE') == 'category')
{
	$compareTemplate = $this->redTemplate->getTemplate(
		'compare_product',
		Redshop\Product\Compare::getCategoryCompareTemplate($compareCategoryId)
	);
}
else
{
	$compareTemplate = $this->redTemplate->getTemplate("compare_product", Redshop::getConfig()->get('COMPARE_TEMPLATE_ID'));
}

$template = RedshopHelperTemplate::getDefaultTemplateContent('compare_product');

if (!empty($compareTemplate) && $compareTemplate[0]->template_desc != "")
{
	$template = $compareTemplate[0]->template_desc;
}

$template = RedshopTagsReplacer::_(
	'compareproduct',
	$template,
	array(
		'compare' => $compare,
		'compareCategoryId' => $compareCategoryId,
		'itemId' => $this->itemId,
		'print' => $this->input->getBool('print', false),
		'redTemplate' => $this->redTemplate
	)
);

echo eval("?>" . $template . "<?php ");
