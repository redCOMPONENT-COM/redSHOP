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
$treeId = JRequest::getInt('Treeid', 0);

// Get the root label
$rootLabel = $params->get('root_label', '');

$sortType = $params->get('categorysorttype', '');

// A unique name for our tree (to support multiple instances of the menu)
$varname = uniqid("TigraTree_");

$iconPath = JURI::root() . 'media/mod_redshop_categories/tigratree/icons/';

$document = JFactory::getDocument();
JHtml::script('mod_redshop_categories/tree.js', false, true);

$document->addScriptDeclaration("var TREE_TPL = {
'target'  : '_self',    // name of the frame links will be opened in
// other possible values are: _blank, _parent, _search, _self and _top

'icon_e'  : '" . $iconPath . "empty.gif', // empty image
'icon_l'  : '" . $iconPath . "line.gif',  // vertical line

'icon_32' : '" . $iconPath . "base.gif',   // root leaf icon normal
'icon_36' : '" . $iconPath . "base.gif',   // root leaf icon selected

'icon_48' : '" . $iconPath . "base.gif',   // root icon normal
'icon_52' : '" . $iconPath . "base.gif',   // root icon selected
'icon_56' : '" . $iconPath . "base.gif',   // root icon opened
'icon_60' : '" . $iconPath . "base.gif',   // root icon selected

'icon_16' : '" . $iconPath . "folder.gif', // node icon normal
'icon_20' : '" . $iconPath . "folderopen.gif', // node icon selected
'icon_24' : '" . $iconPath . "folderopen.gif', // node icon opened
'icon_28' : '" . $iconPath . "folderopen.gif', // node icon selected opened

'icon_0'  : '" . $iconPath . "page.gif', // leaf icon normal
'icon_4'  : '" . $iconPath . "page.gif', // leaf icon selected

'icon_2'  : '" . $iconPath . "joinbottom.gif', // junction for leaf
'icon_3'  : '" . $iconPath . "join.gif',       // junction for last leaf
'icon_18' : '" . $iconPath . "plusbottom.gif', // junction for closed node
'icon_19' : '" . $iconPath . "plus.gif',       // junctioin for last closed node
'icon_26' : '" . $iconPath . "minusbottom.gif',// junction for opened node
'icon_27' : '" . $iconPath . "minus.gif'       // junctioin for last opended node
};");

// Create the menu output
$menu_htmlcode = "<div class=\"$classMainLevel\" style=\"text-align:left;\">
<script type=\"text/javascript\">
var TREE_ITEMS_$varname = [\n";

// Create the root node
$menu_htmlcode .= "['" . $rootLabel . "', '" . JRoute::_($urlpath . 'index.php') . "',\n";

// Get the actual category items
RedTigraTreeMenuHelper::traverseTreeDown($menu_htmlcode, $categoryId = '0', $level = '0', $shopperGroupId);

$menu_htmlcode .= "]];

var o_tree_$varname = new tree(TREE_ITEMS_$varname, TREE_TPL);
item_expand(o_tree_$varname, $treeId);
o_tree_$varname.select($treeId);
</script>\n";

// Add a linked list in case JavaScript is disabled
$menu_htmlcode .= "<noscript>\n";
$menu_htmlcode .= ModProMenuHelper::getCategoryTree($params, $categoryId, $classMainLevel, $listCssClass = "mm123", $highlightedStyle = "font-style:italic;", $shopperGroupId);
$menu_htmlcode .= "\n</noscript>\n";
$menu_htmlcode .= "</div>";

echo $menu_htmlcode;
