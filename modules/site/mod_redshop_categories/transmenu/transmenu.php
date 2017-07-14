<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

class TransMenu
{
	var $parent = null;

	function TransMenu(&$parent)
	{
		$this->parent = $parent;
	}

	function beginMenu()
	{
		if ($this->parent->_params->get('menu_style', 'vertical') == 'vertical')
			$cssfile = "transmenuv.css";
		else
			$cssfile = "transmenuh.css";
		echo '
			<link rel="stylesheet" type="text/css" href="', $this->parent->_params->get('LSPath'), '/', $cssfile, '" />
			<script type="text/javascript" src="', $this->parent->_params->get('LSPath'), '/transmenu.js"></script>
		';
		$direction = "TransMenu.direction." . $this->parent->_params->get('menu_direction', "right");
		$position  = "TransMenu.reference." . $this->parent->_params->get('menu_position', "topRight");
		$top       = $this->parent->_params->get('p_t', 0);
		$left      = $this->parent->_params->get('p_l', 0);
		$subpad_x  = $this->parent->_params->get('subpad_x', 1);
		$subpad_y  = $this->parent->_params->get('subpad_y', 0);

		switch ($this->parent->_params->get('menu_style', "vertical"))
		{
			case 'vertical':
				echo '<div id="wrap"><div id="menu">';
				echo '<table cellpadding="0" cellspacing="0" border="0">';

				foreach ($this->parent->children[0] as $v)
				{
					echo "<tr><td>";
					echo $this->getFirstLevelItem($v);
					echo "</td></tr>";
				}

				echo "</table></div></div>";
				break;

			case 'horizontal':
			default:
				echo '<div id="wrap"><div id="menu">';
				echo '<table cellpadding="0" cellspacing="0" border="0"><tr>';

				foreach ($this->parent->children[0] as $v)
				{
					echo "<td>";
					echo $this->getFirstLevelItem($v);
					echo "</td>";
				}

				echo "</tr></table></div></div>";

				break;
		}

		echo '
			<script type="text/javascript">
			if (TransMenu.isSupported()) {
				TransMenu.updateImgPath(\'', $this->parent->_params->get('LSPath'), '/\');
				var ms = new TransMenuSet(', $direction, ', ', $left, ', ', $top, ', ', $position, ');
				TransMenu.subpad_x = ', $subpad_x, ';
				TransMenu.subpad_y = ', $subpad_y, ';

			';
	}

	function endMenu()
	{
		echo '
				TransMenu.renderAll();
			}
			init1=function(){TransMenu.initialize();}
			if (window.attachEvent) {
				window.attachEvent("onload", init1);
			}else{
				TransMenu.initialize();
			}
			</script>
		';
	}

	function genMenuItem(&$row, $level, $pos)
	{
		$app = JFactory::getApplication();

		$objhelper = redhelper::getInstance();
		$Itemid    = JRequest::getInt('Itemid', '1');

		$cItemid = RedshopHelperUtility::getCategoryItemid($row->id);

		if ($cItemid != "")
		{
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $Itemid;
		}

		switch ($row->type)
		{
			case 'separator':
			case 'component_item_link':
				break;
			case 'content_item_link':
				$temp = explode("&task=view&id=", $row->link);
				$row->link .= '&Itemid=' . $app->getItemid($temp[1]);
				break;
			case 'url':
				if (preg_match('/index.php\?/i', $row->link))
				{
					if (!preg_match('/Itemid=/i', $row->link))
					{
						$row->link .= '&Itemid=' . $tmpItemid;
					}
				}
				break;
			case 'content_typed':
			default:
				$row->link .= '&Itemid=' . $tmpItemid;
				break;
		}

		if (strcasecmp(substr($row->link, 0, 4), 'http'))
		{
			$row->link = JRoute::_($row->link);
		}

		if ($level)
		{
			$pmenu = "tmenu$row->parent";

			$active = 0;

			if (in_array($row->id, $this->parent->open))
			{
				$active = 1;
			}

			$row->link = JRoute::_($row->link);
			echo "$pmenu.addItem(\"$row->name\", \"$row->link\", $row->browserNav, $active);\n";
		}
		else
		{
			$pmenu = "ms";
		}

		$cmenu  = "tmenu$row->id";
		$idmenu = "menu$row->id";

		if ($this->parent->hasSubItems($row->id))
		{
			if ($level == 0)
			{
				echo "var $cmenu = " . $pmenu . ".addMenu(document.getElementById(\"$idmenu\"));\n";
			}
			else
			{
				echo "var $cmenu = " . $pmenu . ".addMenu(" . $pmenu . ".items[" . $pos . "]);\n";
			}
		}
	}

	function getFirstLevelItem($mitem)
	{
		global $Itemid, $urlpath;

		$app = JFactory::getApplication();

		$txt = '';

		switch ($mitem->type)
		{
			case 'separator':
			case 'component_item_link':
				break;
			case 'content_item_link':
				$temp = explode("&task=view&id=", $mitem->link);
				$mitem->link .= '&Itemid=' . $app->getItemid($temp[1]);
				break;
			case 'url':
				if (preg_match('/index.php\?/i', $mitem->link))
				{
					if (!preg_match('/Itemid=/i', $mitem->link))
					{
						$mitem->link .= '&Itemid=' . $mitem->id;
					}
				}
				break;
			case 'content_typed':
			default:
				$mitem->link .= '&Itemid=' . $Itemid;
				break;
		}

		$id = 'id="menu' . $mitem->id . '"';

		if (strcasecmp(substr($mitem->link, 0, 4), 'http'))
		{
			$mitem->link = JRoute::_($mitem->link);
		}

		$menuclass = 'mainlevel' . $this->parent->_params->get('class_sfx');

		if (in_array($mitem->id, $this->parent->open))
		{
			$menuclass = 'mainlevel_active' . $this->parent->_params->get('class_sfx');
		}

		switch ($mitem->browserNav)
		{
			// Cases are slightly different
			case 1:
				// Open in a new window
				$txt = '<a href="' . $mitem->link . '" target="_blank" class="' . $menuclass . '" ' . $id . '>' . $mitem->name . '</a>';
				break;

			case 2:
				// Open in a popup window
				$txt = "<a href=\"#\" onclick=\"javascript: window.open('" . $mitem->link . "', '', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550'); return false\" class=\"$menuclass\" " . $id . ">" . $mitem->name . "</a>\n";
				break;

			case 3:
				// Don't link it
				$txt = '<span class="' . $menuclass . '" ' . $id . '>' . $mitem->name . '</span>';
				break;

			default:
				// Formerly case 2
				// Open in parent window
				$txt = '<a href="' . $mitem->link . '" class="' . $menuclass . '" ' . $id . '>' . $mitem->name;
				if ($this->parent->hasSubItems($mitem->id))
					$txt .= '&nbsp;&nbsp;<img border="0" src="' . $this->parent->_params->get('LSPath') . '/img/tabarrow.gif" alt="arrow" />';
				$txt .= '</a>';
				break;
		}

		if ($this->parent->_params->get('menu_images'))
		{
			$menu_params = new stdClass;
			$menu_params = new mosParameters($mitem->params);
			$menu_image  = $menu_params->def('menu_image', -1);

			if (($menu_image <> '-1') && $menu_image)
			{
				$image = '<img src="' . $urlpath . 'images/stories/' . $menu_image . '" border="0" alt="' . $mitem->name . '"/>';

				if ($this->parent->_params->get('menu_images_align'))
				{
					$txt = $txt . ' ' . $image;
				}
				else
				{
					$txt = $image . ' ' . $txt;
				}
			}
		}

		return $txt;
	}
}
