<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$db            = JFactory::getDBO();
$template_id   = $this->detail->product_template;
$product_id    = $this->detail->product_id;
$redTemplate   = new Redtemplate;
$field         = new extra_field;
$template_desc = $redTemplate->getTemplate("product", $template_id);

if (count($template_desc) == 0)
{
	return;
}

$template   = $template_desc[0]->template_desc;

$fieldModel = JModel::getInstance('fields', 'RedshopModel');

$section = explode(',', '1,12,17');
$fields  = $fieldModel->getFieldInfoBySection($section);

$fieldsInfo = array();

for ($i = 0; $i < count($fields); $i++)
{
	if (strstr($template, "{" . $fields[$i]->field_name . "}"))
	{
		$sectionId = 0;
		$fieldName = '';

		if ($fields[$i]->field_section == 12)
		{
			if ($fields[$i]->field_type == 15)
			{
				$sectionId = $fields[$i]->field_section;
				$fieldName = $fields[$i]->field_name;
			}
		}
		else
		{
			$sectionId = $fields[$i]->field_section;
			$fieldName = $fields[$i]->field_name;
		}

		echo $field->list_all_field($sectionId, $product_id, $fieldName);
	}
}
