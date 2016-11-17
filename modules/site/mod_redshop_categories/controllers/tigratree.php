<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

global $Itemid, $urlpath, $sortparam;

require_once $absoluteModulePath . '/helpers/tigratree.php';

// Decide which node to open (if any)
$Treeid = JRequest::getInt('Treeid');

// Get the root label
$root_label = $params->get('root_label');

if ($params->get('categorysorttype') == "catnameasc")
{
	$sortparam = "category_name ASC";
}
elseif ($params->get('categorysorttype') == "catnamedesc")
{
	$sortparam = "category_name DESC";
}
elseif ($params->get('categorysorttype') == "newest")
{
	$sortparam = "category_id DESC";
}
elseif ($params->get('categorysorttype') == "catorder")
{
	$sortparam = "ordering ASC";
}

// The tree generator
$vmTigraTree = new redTigraTreeMenu;

// A unique name for our tree (to support multiple instances of the menu)
$varname = uniqid("TigraTree_");
$icon_path = JURI::root() . 'modules/mod_redshop_categories/tigratree/icons/';
$document = JFactory::getDocument();
JHTML::script(JURI::root() . 'modules/mod_redshop_categories/tigratree/tree.js');
$document->addScriptDeclaration("var TREE_TPL = {
'target'  : '_self',    // name of the frame links will be opened in
// other possible values are: _blank, _parent, _search, _self and _top

'icon_e'  : '" . $icon_path . "empty.gif', // empty image
'icon_l'  : '" . $icon_path . "line.gif',  // vertical line

'icon_32' : '" . $icon_path . "base.gif',   // root leaf icon normal
'icon_36' : '" . $icon_path . "base.gif',   // root leaf icon selected

'icon_48' : '" . $icon_path . "base.gif',   // root icon normal
'icon_52' : '" . $icon_path . "base.gif',   // root icon selected
'icon_56' : '" . $icon_path . "base.gif',   // root icon opened
'icon_60' : '" . $icon_path . "base.gif',   // root icon selected

'icon_16' : '" . $icon_path . "folder.gif', // node icon normal
'icon_20' : '" . $icon_path . "folderopen.gif', // node icon selected
'icon_24' : '" . $icon_path . "folderopen.gif', // node icon opened
'icon_28' : '" . $icon_path . "folderopen.gif', // node icon selected opened

'icon_0'  : '" . $icon_path . "page.gif', // leaf icon normal
'icon_4'  : '" . $icon_path . "page.gif', // leaf icon selected

'icon_2'  : '" . $icon_path . "joinbottom.gif', // junction for leaf
'icon_3'  : '" . $icon_path . "join.gif',       // junction for last leaf
'icon_18' : '" . $icon_path . "plusbottom.gif', // junction for closed node
'icon_19' : '" . $icon_path . "plus.gif',       // junctioin for last closed node
'icon_26' : '" . $icon_path . "minusbottom.gif',// junction for opened node
'icon_27' : '" . $icon_path . "minus.gif'       // junctioin for last opended node
};");

// Create the menu output
$menu_htmlcode = "<div class=\"$classMainLevel\" style=\"text-align:left;\">
<script type=\"text/javascript\">
var TREE_ITEMS_$varname = [\n";

// Create the root node
$menu_htmlcode .= "['" . $root_label . "', '" . JRoute::_($urlpath . 'index.php') . "',\n";

// Get the actual category items
$vmTigraTree->traverse_tree_down($menu_htmlcode, $categoryId = '0', $level = '0', $shopperGroupId);

$menu_htmlcode .= "]];

var o_tree_$varname = new tree(TREE_ITEMS_$varname, TREE_TPL);
item_expand(o_tree_$varname, $Treeid);
o_tree_$varname.select($Treeid);
</script>\n";

// Add a linked list in case JavaScript is disabled
$menu_htmlcode .= "<noscript>\n";
$menu_htmlcode .= ModProMenuHelper::getCategoryTree($params, $categoryId, $classMainLevel, $listCssClass = "mm123", $highlightedStyle = "font-style:italic;", $shopperGroupId);
$menu_htmlcode .= "\n</noscript>\n";
$menu_htmlcode .= "</div>";

echo $menu_htmlcode; die;
