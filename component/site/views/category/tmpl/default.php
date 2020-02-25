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

$config        = Redconfiguration::getInstance();
$redTemplate   = Redtemplate::getInstance();

$url = JURI::base();

/** @var RedshopModelCategory $model */
$model                = $this->getModel('category');
$loadCategorytemplate = $this->loadCategorytemplate;

if (!empty($loadCategorytemplate) && $loadCategorytemplate[0]->template_desc != "")
{
	$templateDesc = $loadCategorytemplate[0]->template_desc;
}
else
{
	$templateDesc = "<div class=\"category_front_introtext\">{print}<p>{category_frontpage_introtext}</p></div>";
	$templateDesc .= "\r\n{category_frontpage_loop_start}<div class=\"category_front\">\r\n";
	$templateDesc .= "<div class=\"category_front_image\">{category_thumb_image}</div>\r\n";
	$templateDesc .= "<div class=\"category_front_title\"><h3>{category_name}</h3></div>\r\n</div>{category_frontpage_loop_end}";
}

if ($this->params->get('show_page_heading', 0))
{
	if (!$this->catid)
	{
		echo '<div class="category_title' . $this->escape($this->params->get('pageclass_sfx')) . '">';
	}
	else
	{
		echo '<div class="category' . $this->escape($this->params->get('pageclass_sfx')) . '">';
	}

	if (!$this->catid)
	{
		echo '<h1>';

		if ($this->params->get('page_title') != $this->pageheadingtag)
		{
			echo $this->escape($this->params->get('page_title'));
		}
		else
		{
			echo $this->pageheadingtag;
		}

		echo '</h1>';
	}

	echo '</div>';
}

$categoryTemplateWapper = \RedshopTagsReplacer::_(
	'categoryfrontpage',
	$template_desc,
	array(
		'category'  => $this->detail,
		'print'     => $this->print,
		'model'    => $model
	)
);

echo $categoryTemplateWapper;