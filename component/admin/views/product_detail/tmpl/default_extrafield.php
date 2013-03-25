<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

$db = JFactory::getDBO();
//$model = $this->getModel();
$template_id = $this->detail->product_template;
$product_id = $this->detail->product_id;
$section = '1,12,17';

$redTemplate = new Redtemplate();

if ($section == 1 || $section == 12 || $section == 17)
{
	$template_desc = $redTemplate->getTemplate("product", $template_id);
}
else
{
	$template_desc = $redTemplate->getTemplate("category", $template_id);
}
if (count($template_desc) == 0)
{
	return;
}

$template = $template_desc[0]->template_desc;
$str = array();
$sec = explode(',', $section);
for ($t = 0; $t < count($sec); $t++)
{
	$inArr[] = "'" . $sec[$t] . "'";
}
$in = implode(',', $inArr);
$q = "SELECT field_name,field_type,field_section from #__redshop_fields where field_section in (" . $in . ") ";
$db->setQuery($q);
$fields = $db->loadObjectlist();
for ($i = 0; $i < count($fields); $i++)
{
	if (strstr($template, "{" . $fields[$i]->field_name . "}"))
	{
		if ($fields[$i]->field_section == 12)
		{
			if ($fields[$i]->field_type == 15)
				$str[] = $fields[$i]->field_name;
		}
		else
		{
			$str[] = $fields[$i]->field_name;
		}

	}
}

$list_field = array();
if (count($str) > 0)
{
	$dbname = "'" . implode("','", $str) . "'";
	$field = new extra_field();
	for ($t = 0; $t < count($sec); $t++)
	{
		$list_field[] = $field->list_all_field($sec[$t], $product_id, $dbname);
	}
}

if (is_array($list_field))
{
	for ($i = 0; $i < count($list_field); $i++)
	{
		echo $list_field[$i];
	}
}
else
{
	echo $list_field;
}
?>
