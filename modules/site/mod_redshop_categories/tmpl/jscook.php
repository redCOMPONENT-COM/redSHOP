<?php


/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$menuHtml = '<div align="left" class="mainlevel" id="div_' . $varname . '"></div>
<script type="text/javascript">
//<!--
function ' . $varname . '_addEvent( obj, type, fn )
{
   if (obj.addEventListener) {
      obj.addEventListener( type, fn, false );
   } else if (obj.attachEvent) {
      obj["e"+type+fn] = fn;
      obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
      obj.attachEvent( "on"+type, obj[type+fn] );
   }
}

function ' . $varname . '_removeEvent( obj, type, fn )
{
   if (obj.removeEventListener) {
      obj.removeEventListener( type, fn, false );
   } else if (obj.detachEvent) {
      obj.detachEvent( "on"+type, obj[type+fn] );
      obj[type+fn] = null;
      obj["e"+type+fn] = null;
   }
}

var ' . $varname . ' =
[
';
redshopJscookCategoryMenuHelper::traverseTreeDown($menuHtml, '0', '0', $params, $shopperGroupId);
$menuHtml .= "];
";

if ($jscookType == "tree")
{
	$menuHtml .= "var treeindex = ctDraw ('div_$varname', $varname, $jscookTree, '$jscookTreeStyle', 0, 0);";
}
else
{
	$menuHtml .= "cmDrawNow =function() { cmDraw ('div_$varname', $varname, '$menu_orientation', cm$jscookMenuStyle, '$jscookMenuStyle'); };
	" . $varname . "_addEvent( window, \"load\", cmDrawNow, false );";
}

$menuHtml .= "
//-->
</script>\n";

if ($jscookType == "tree")
{
	if ($TreeId)
	{
		$menuHtml .= "<input type=\"hidden\" id=\"TreeId\" name=\"TreeId\" value=\"$TreeId\" />\n";
		$menuHtml .= "<script language=\"JavaScript\" type=\"text/javascript\">ctExposeTreeIndex( treeindex, parseInt(ctGetObject('TreeId').value));</script>\n";
	}
}

$menuHtml .= "<noscript>";
$menuHtml .= $redproduct_menu->get_category_tree($params, $categoryId, $classMainLevel, $listCssClass = "mm123", $highlightedStyle = "font-style:italic;", $shopperGroupId);
$menuHtml .= "\n</noscript>\n";
echo $menuHtml;
