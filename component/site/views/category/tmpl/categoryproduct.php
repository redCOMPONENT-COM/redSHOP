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

$categoryTemplate = RedshopHelperTemplate::getTemplate('categoryproduct');

if (isset($categoryTemplate[0]->template_desc) && !empty(trim($categoryTemplate[0]->template_desc)))
{
	$templateHtml = $categoryTemplate[0]->template_desc;
}
else
{
	$templateHtml = RedshopHelperTemplate::getDefaultTemplateContent('categoryproduct');
}

echo RedshopTagsReplacer::_(
	'categoryproduct',
	$templateHtml,
	[
		'params' => $this->params,
		'catId' =>  $this->catid,
		'detail' => $this->detail,
		'model' => $this->getModel('category'),
		'pageHeadingTag' =>$this->pageheadingtag,
		'orderBySelect' => $this->order_by_select,
		'manufacturerId' => $this->manufacturer_id,
		'lists' => $this->lists
	]
);

