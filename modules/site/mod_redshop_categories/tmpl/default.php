<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$append = "";
$class  = "";

if ($allowed)
{
	if ($n == 0)
	{
		$html .= '<ul>';
	}

	if ($style == $highlighted_style)
	{
		$append = '&id=active_menu';
		$class  = "class='parent-active'";
	}

	if ($depth_list[$n] > 0)
	{
		$css_class = "sublevel";

		if ($depth == $depth_list[$n] && $style == $highlighted_style)
		{
			$class  = "class='active'";
		}

		if ($depth_list[$n] > ($sub))
		{
			$html .= '<ul>';
		}
		elseif ($depth_list[$n] < ($sub))
		{
			for ($i = $depth_list[$n]; $i < $sub; $i++)
			{
				$html .= '</ul></li>';
			}
		}

		$sub = $depth_list[$n];
	}
	else
	{
		$css_class = $links_css_class;

		if ($sub > 0)
		{
			$html .= str_repeat("</ul></li>", $sub);
			$sub = 0;
		}

		$html .= "</li>";
	}

	$catname = JText::_($category_tmp[$row_list[$n]]["category_name"]);

	$Itemid = $objhelper->getCategoryItemid($category_tmp[$row_list[$n]]["category_child_id"]);

	if (!$Itemid)
	{
		$Itemid = JRequest::getInt('Itemid');
	}

	$uri = JURI::getInstance();

	$catlink = 'index.php?option=com_redshop&view=category&layout=detail&cid=' . $category_tmp[$row_list[$n]]["category_child_id"] . $append . '&Itemid=' . $Itemid;
	$html .= '<li ' . $class . ' ><a title="' . $catname . '" style="display:block;' . $style . '" class="' . $css_class . '" href=' . JRoute::_($catlink) . '>'
		. str_repeat("", $depth_list[$n]) . $catname
		. $this->products_in_category($category_tmp[$row_list[$n]]["category_child_id"], $params)
		. '</a>';

	if ($n == ($nrows - 1))
	{
		$html .= "</ul>";
	}
}

echo $html;
