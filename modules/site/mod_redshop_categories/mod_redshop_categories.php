<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_categories
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

global $root_label, $jscook_type, $jscookMenu_style, $jscookTree_style, $mm_action_url, $urlpath, $Itemid, $redproduct_menu, $categorysorttype;

$uri = JURI::getInstance();
$urlpath = $uri->root();
$user = JFactory::getUser();
$db = JFactory::getDbo();
//get category id
$category_id = JRequest::getInt('cid');
unset($GLOBALS['category_info']['category_tree']);
//get Item id
$Itemid = JRequest::getInt('Itemid', '1');

$js_src = $urlpath . 'modules/mod_redshop_categories';

require_once dirname(__FILE__) . '/helper.php';

$redproduct_menu = new modProMenuHelper;

/* Get module parameters */
$show_noofproducts = $params->get('show_noofproducts', 'yes');
$menutype = $params->get('menutype', "links");
$class_sfx = $params->get('class_sfx', '');
$pretext = $params->get('pretext', '');
$posttext = $params->get('posttext', '');
$jscookMenu_style = $params->get('jscookMenu_style', 'ThemeOffice');
$jscookTree_style = $params->get('jscookTree_style', 'ThemeXP');
$jscook_type = $params->get('jscook_type', 'menu');
$menu_orientation = $params->get('menu_orientation', 'hbr');
$root_label = $params->get('root_label', 'Shop');
$categorysorttype = $params->get('categorysorttype', 'catname');
$use_shoppergroup = $params->get('use_shoppergroup', 'no');

if ($use_shoppergroup == "yes")
{
	$shopper_group_id = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

	if ($user->id)
	{
		$query = "SELECT shopper_group_id FROM #__redshop_users_info AS ui "
			. "WHERE ui.user_id=" . (int) $user->id;
		$db->setQuery($query);
		$getShopperGroupID = $db->loadResult();

		if ($getShopperGroupID)
		{
			$shopper_group_id = $getShopperGroupID;
		}
	}
}
else
{
	$shopper_group_id = 0;
}

$class_mainlevel = "mainlevel_redshop" . $class_sfx;

echo $pretext;

if ($menutype == 'links')
{
	echo $redproduct_menu->getCategoryTree($params, $category_id, $class_mainlevel, $list_css_class = "mm123", $highlighted_style = "font-style:italic;", $shopper_group_id);
}
elseif ($menutype == "transmenu")
{
	/* TransMenu script to display a DHTML Drop-Down Menu */
	include_once $mod_dir . '/transmenu.php';

}
elseif ($menutype == "dtree")
{
	/* dTree script to display structured categories */
	include_once $mod_dir . '/dtree.php';

}
elseif ($menutype == "jscook")
{
	/* JSCook Script to display structured categories */
	include_once $mod_dir . '/JSCook.php';

}
elseif ($menutype == "tigratree")
{
	/* TigraTree script to display structured categories */
	include_once $mod_dir . '/tigratree.php';
}
elseif ($menutype == "accordion")
{
	/* accordion script to display structured categories */
	include_once $mod_dir . '/accordion.php';
}

echo $posttext;
