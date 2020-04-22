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

$slide            = $this->input->getInt('ajaxslide', null);
$filterBy         = $this->input->getInt('manufacturer_id', $this->params->get('manufacturer_id'));
$categoryTemplate = $this->input->getInt('category_template', 0);

$loadCategoryTemplate = $this->loadCategorytemplate;

if (!empty($loadCategoryTemplate) && $loadCategoryTemplate[0]->template_desc != "")
{
	$templateDesc = $loadCategoryTemplate[0]->template_desc;
}
else
{
	$templateDesc = RedshopHelperTemplate::getDefaultTemplateContent('category');
}

$categoryItemId = (int) RedshopHelperRouter::getCategoryItemid($this->catid);
$mainItemId     = !$categoryItemId ? $this->itemid : $categoryItemId;
$excludedTags   = array();

// New tags replacement for category template section
$templateDesc = RedshopTagsReplacer::_(
	'category',
	$templateDesc,
	array(
		'category' => $this->maincat,
		'subCategories' => $this->detail,
		'manufacturerId' => $this->manufacturer_id,
		'itemId' => $mainItemId,
		'excludedTags' => $excludedTags
	)
);

$templateDesc = RedshopTagsReplacer::_(
	'categorydetail',
	$templateDesc,
	array(
		'maincat' => $this->maincat,
		'detail' => $this->detail,
		'manufacturer_id' => $this->manufacturer_id,
		'mainItemId' => $mainItemId,
		'excludedTags' => $excludedTags,
		'params' => $this->params,
		'pageheadingtag' => $this->pageheadingtag,
		'print' => $this->print,
		'catid' => $this->catid,
		'itemid' => $this->itemid,
		'state' => $this->state,
		'model' => $this->getModel('category'),
		'order_by_select' => $this->order_by_select,
		'category_template_id' => $this->category_template_id,
		'option' => $this->option,
		'product' => $this->product,
		'productPriceSliderEnable' => $this->productPriceSliderEnable,
		'lists' => $this->lists,
		'category_id' => $this->category_id
	)
);

// Replace redproductfinder filter tag
if (strpos($templateDesc, "{redproductfinderfilter:") !== false)
{
	if (file_exists(JPATH_SITE . '/components/com_redproductfinder/helpers/redproductfinder_helper.php'))
	{
		include_once JPATH_SITE . "/components/com_redproductfinder/helpers/redproductfinder_helper.php";
		$redProductFinderHelper = new redproductfinder_helper;

		$hdnFields = array(
			'texpricemin'       => '0',
			'texpricemax'       => '0',
			'manufacturer_id'   => $filterBy,
			'category_template' => $categoryTemplate
		);

		$hideFilterFlag = false;

		if ($this->catid)
		{
			$productOfCat = RedshopHelperProduct::getProductCategory($this->catid);

			if (empty($productOfCat))
			{
				$hideFilterFlag = true;
			}
		}

		$templateDesc = $redProductFinderHelper->replaceProductfinder_tag($templateDesc, $hdnFields, $hideFilterFlag);
	}
}

$templateDesc = RedshopHelperTemplate::parseRedshopPlugin($templateDesc);
$templateDesc = RedshopHelperText::replaceTexts($templateDesc);
echo eval("?>" . $templateDesc . "<?php ");

if ($slide)
{
	JFactory::getApplication()->close();
}
