<?php
/**
 * @package     Redshopb.Site
 * @subpackage  mod_redshopb_megamenu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if (isset($item->content) && $item->content)
{
	echo '<div class="shopbMegaMenu_mod">'
		. $item->content
		. '<div class="clearfix"></div></div>';
}
