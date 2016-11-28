<?php
/**
 * @package     Redshopb.Site
 * @subpackage  mod_redshopb_megamenu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

if ($item->menu_image)
{
	$item->params->get('menu_text', 1) ?
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
	$linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
}
else
{
	$linktype = $item->title;
}

$linktype = '<span class="menuLinkTitle">' . $linktype . '</span>';
$linktype .= $item->desc ? '<br /><span class="menuItemDesc">' . $item->desc . '</span>' : '';
?><span class="nav-header <?php echo $item->anchor_css; ?>" <?php echo $title; ?>><?php echo $linktype; ?></span><?php